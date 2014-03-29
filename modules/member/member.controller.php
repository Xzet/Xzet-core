<?php
    /**
     * @class  memberController
     * @author NHN (developers@xpressengine.com)
     * @brief  member module의 Controller class
     **/

    class memberController extends member {

        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief user_id, password를 체크하여 로그인 시킴
         **/
        function procMemberLogin($user_id = null, $password = null, $keep_signed = null) {
            // 변수 정리
            if(!$user_id) $user_id = Context::get('user_id');
            $user_id = trim($user_id);

            if(!$password) $password = Context::get('password');
            $password = trim($password);

            if(!$keep_signed) $keep_signed = Context::get('keep_signed');

            // 아이디나 비밀번호가 없을때 오류 return
            if(!$user_id) return new Object(-1,'null_user_id');
            if(!$password) return new Object(-1,'null_password');

            $output = $this->doLogin($user_id, $password, $keep_signed=='Y'?true:false);

            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('member');
            if($config->after_login_url) $this->setRedirectUrl($config->after_login_url);

            // 설정된 change_password_date 확인
            $limit_date = $config->change_password_date;

            // change_password_date가 설정되어 있으면 확인
            if ($limit_date > 0) {
                $oMemberModel = &getModel('member');
                $member_info = $oMemberModel->getMemberInfoByUserID($user_id);
                if ($member_info->change_password_date < date ('YmdHis', strtotime ('-' . $limit_date . ' day'))) {
                    $this->setRedirectUrl(getNotEncodedUrl('','vid',Context::get('vid'),'mid',Context::get('mid'),'act','dispMemberModifyPassword'));
                }
            }

            $redirect_url = Context::get('redirect_url'); 
            if ($output->toBool () && Context::getRequestMethod () == "POST" && $redirect_url) {
                header ("location:" . $redirect_url);
            }

            return $output;
        }

        /**
         * @brief openid로그인
         **/
        function procMemberOpenIDLogin($validator = "procMemberOpenIDValidate") {
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('member');
            if($config->enable_openid != 'Y') $this->stop('msg_invalid_request');

            if(!defined('Auth_OpenID_RAND_SOURCE') && !file_exists("/dev/urandom"))
            {
                define('Auth_OpenID_RAND_SOURCE', null);
            }

            set_include_path(_XE_PATH_."modules/member/php-openid-1.2.3");
            require_once('Auth/OpenID.php');
            require_once('Auth/OpenID/Consumer.php');
            require_once('Auth/OpenID/XEStore.php');
            $store = new Auth_OpenID_XEStore();
            $consumer = new Auth_OpenID_Consumer($store);

            $user_id = Context::get('user_id');
            if (!$user_id) $user_id = Context::get('openid');
            $auth_request = $consumer->begin($user_id); 
            $auth_request->addExtensionArg('sreg', 'required', 'email');
            $auth_request->addExtensionArg('sreg', 'optional', 'dob');
            if(!$auth_request)
            {
                return new Object(-1, "association failed");
            }

            $trust_root = 'http://'.$_SERVER["HTTP_HOST"];
            $referer_url = Context::get('referer_url');
            if (!$referer_url) $referer_url = $_SERVER['HTTP_REFERER'];
            if (!$referer_url)
                $referer_url = htmlspecialchars_decode(getRequestUri(RELEASE_SSL));
            $goto = urlencode($referer_url);
            $ApprovedURL = Context::getRequestUri(RELEASE_SSL) . "?module=member&act=" . $validator. "&goto=" . $goto;
            $redirect_url = $auth_request->redirectURL($trust_root, $ApprovedURL);
            $this->add("redirect_url", $redirect_url);
            if (Context::getRequestMethod() == 'POST')
                header("location:" . $redirect_url);
        }

        function getLegacyUserIDsFromOpenID($openid_identity) {
            //  Issue 17515512: workaround
            $result = array();
            $uri_matches = array();
            preg_match(Auth_OpenID_getURIPattern(), $openid_identity, $uri_matches);

            if (count($uri_matches) < 9) {
                for ($i = count($uri_matches); $i <= 9; $i++) {
                    $uri_matches[] = '';
                }
            }

            $scheme = $uri_matches[2];
            $authority = $uri_matches[4];
            $path = $uri_matches[5];
            $query = $uri_matches[6];
            $fragment = $uri_matches[8];

            if ($scheme === null) $scheme = '';
            if ($authority === null) $authority = '';
            if ($path === null) $path = '';
            if ($query === null) $query = '';
            if ($fragment === null) $fragment = '';

            if ($scheme == 'http' or $scheme == '')
                $scheme_part = '';
            else
                $scheme_part = $scheme."://";


            if ($path == '' || $path == '/') {
                $result[] = $scheme_part.$authority.''.$query.$fragment;
                $result[] = $scheme_part.$authority.'/'.$query.$fragment;
            }
            else {
                $result[] = $scheme_part.$authority.$path.$query.$fragment;
            }

            return $result;
        }

        /**
         * @brief openid 인증 체크
         **/
        function procMemberOpenIDValidate() {
            set_include_path(_XE_PATH_."modules/member/php-openid-1.2.3");
            require_once('Auth/OpenID.php');
            require_once('Auth/OpenID/Consumer.php');
            require_once('Auth/OpenID/XEStore.php');
            require_once('Auth/OpenID/URINorm.php');

            $store = new Auth_OpenID_XEStore();
            $consumer = new Auth_OpenID_Consumer($store);
            $response = $consumer->complete($_GET);
            switch($response->status) {
                case Auth_OpenID_CANCEL :
                // 사용자가 인증을 취소했을 때의 처리
                return $this->stop('authorization_canceled');
            case Auth_OpenID_FAILURE :
                // 무언가의 문제로 인해 인증이 실패했을 때의 처리(인증을 요구한 openid가 없다든가..)
                return $this->stop('invalid_authorization');
            case Auth_OpenID_SUCCESS :
                // 인증성공!!
                break;
            default:
                return $this->stop('invalid_authorization');
            }

            // 인증 성공
            $oMemberModel = &getModel('member');

            //  이 오픈아이디와 연결된 (또는 연결되어 있을 가능성이 있는) 제로보드 아이디들을 받아온다.
            $login_success = false;
            $assoc_member_info = null;
            $openid_identity = $response->signed_args["openid.identity"];
            $args->openid = $openid_identity;
            $output = executeQuery('member.getMemberSrlByOpenID', $args);

            if ($output->toBool() && $output->data && !is_array($output->data)) {
                $member_srl = $output->data->member_srl;
                $member_info = $oMemberModel->getMemberInfoByMemberSrl($member_srl);
                if ($member_info) {
                    $assoc_member_info = $member_info;
                }
            }
            
            $user_id_candidates = $this->getLegacyUserIDsFromOpenID($openid_identity);
            $default_user_id = $user_id_candidates[0];

            if ($assoc_member_info != null) {
                $user_id_candidates = array_merge(array($assoc_member_info->user_id), $user_id_candidates);
            }
            $sreg = $response->extensionResponse('sreg');

            foreach($user_id_candidates as $user_id) {
                $args->user_id = $args->nick_name = $user_id;
                // 기본 정보들을 받음
                $args->email_address = $sreg['email']; 
                $args->user_name = $sreg['fullname'];
                if(!$args->user_name) list($args->user_name) = explode('@', $args->email_address);
                $args->birthday = str_replace('-','',$sreg['dob']);

                // 자체 인증 시도
                $output = $this->doLogin($args->user_id);

                if ($output->toBool()) {
                    if ($assoc_member_info == null) {
                        $logged_info = Context::get('logged_info');
                        $args->member_srl = $logged_info->member_srl;
                        $args->openid = $openid_identity;
                        executeQuery('member.addOpenIDToMember', $args);
                    }
                    $login_success = true;
                    break;
                }
            }

            // 자체 인증 실패시 회원 가입시킴
            if(!$login_success) {
                $args->user_id = $args->nick_name = $default_user_id;
                $args->password = md5(getmicrotime());

                $output = $this->insertMember($args);
                if(!$output->toBool()) return $this->stop($output->getMessage());
                $output = $this->doLogin($args->user_id);
                if(!$output->toBool()) return $this->stop($output->getMessage());

                $logged_info = Context::get('logged_info');
                $args->member_srl = $logged_info->member_srl;
                $args->openid = $openid_identity;
                executeQuery('member.addOpenIDToMember', $args);
            }

            Context::close();

            // 페이지 이동
            if(Context::get('goto')) {
                $goto = Context::get('goto');
                header("location:" . $goto);
            } else {
                header("location:./");
            }

            exit();
        }

        /**
         * @brief 오픈아이디 연결 요청
         **/
        function procMemberAddOpenIDToMember() {
            return $this->procMemberOpenIDLogin("procMemberValidateAddOpenIDToMember");
        }

        /**
         * @brief 오픈아이디 연결 요청 마무리
         **/
        function procMemberValidateAddOpenIDToMember() {
            set_include_path(_XE_PATH_."modules/member/php-openid-1.2.3");
            require_once('Auth/OpenID.php');
            require_once('Auth/OpenID/Consumer.php');
            require_once('Auth/OpenID/XEStore.php');
            require_once('Auth/OpenID/URINorm.php');

            $store = new Auth_OpenID_XEStore();
            $consumer = new Auth_OpenID_Consumer($store);
            $response = $consumer->complete($_GET);

            switch($response->status) {
                case Auth_OpenID_CANCEL :
                // 사용자가 인증을 취소했을 때의 처리
                return $this->stop('authorization_canceled');
            case Auth_OpenID_FAILURE :
                // 무언가의 문제로 인해 인증이 실패했을 때의 처리(인증을 요구한 openid가 없다든가..)
                return $this->stop('invalid_authorization');
            case Auth_OpenID_SUCCESS :
                {
                    $logged_info = Context::get('logged_info');
                    if (!Context::get('is_logged')) return $this->stop('msg_not_logged');

                    $member_srl = $logged_info->member_srl;

                    $args->member_srl = $member_srl;
                    $openid_identity = $response->signed_args["openid.identity"];
                    $args->openid = $openid_identity;

                    $output = executeQuery('member.addOpenIDToMember', $args);
                    if (!$output->toBool()) return $output;

                    Context::close();

                    if(Context::get('goto')){
                        $goto = Context::get('goto');
                        header("location:" . $goto);
                    }else{
                        header("location:./");
                    }
                    exit();
                }
                // 인증성공!!
                break;
            default:
                return $this->stop('invalid_authorization');
            }
        }

        /**
         * @brief 오픈아이디 연결 해제
         **/
        function procMemberDeleteOpenIDFromMember() {
            $logged_info = Context::get('logged_info');
            $openid_identity = Context::get('openid_to_delete');
            $arg->openid = $openid_identity;
            $result = executeQuery('member.getMemberSrlByOpenID', $arg);

            if (!Context::get('is_logged')) {
                $this->setError(-1);
                $this->setMessage('msg_not_logged');
                return;
            } else if (!$result->data || is_array($result->data)) {
                $this->setError(-1);
                $this->setMessage('msg_not_founded');
                return;
            } else if ($result->data->member_srl != $logged_info->member_srl) {
                $this->setError(-1);
                $this->setMessage('msg_not_permitted');
                return;
            }

            $arg->openid = $openid_identity;

            $output = executeQuery('member.deleteMemberOpenID', $arg);
            if(!$output->toBool()) return $output;

            $this->setMessage('success_updated');
        }


        /**
         * @brief 로그아웃
         **/
        function procMemberLogout() {
            // 로그아웃 이전에 trigger 호출 (before)
            $logged_info = Context::get('logged_info');
            $trigger_output = ModuleHandler::triggerCall('member.doLogout', 'before', $logged_info);
            if(!$trigger_output->toBool()) return $trigger_output;

            // 세션 정보 파기
            $this->destroySessionInfo();

            // 로그아웃 이후 trigger 호출 (after)
            $trigger_output = ModuleHandler::triggerCall('member.doLogout', 'after', $logged_info);
            if(!$trigger_output->toBool()) return $trigger_output;

            $output = new Object();

            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('member');
            if($config->after_logout_url) Context::set('redirect_url', $config->after_logout_url);

            return $output;
        }

        /**
         * @brief 스크랩 기능
         **/
        function procMemberScrapDocument() {
            // 로그인 정보 체크
            if(!Context::get('is_logged')) return new Object(-1, 'msg_not_logged');
            $logged_info = Context::get('logged_info');

            $document_srl = (int)Context::get('document_srl');
            if(!$document_srl) $document_srl = (int)Context::get('target_srl');
            if(!$document_srl) return new Object(-1,'msg_invalid_request');

            // 문서 가져오기
            $oDocumentModel = &getModel('document');
            $oDocument = $oDocumentModel->getDocument($document_srl);

            // 변수 정리
            $args->document_srl = $document_srl;
            $args->member_srl = $logged_info->member_srl;
            $args->user_id = $oDocument->get('user_id');
            $args->user_name = $oDocument->get('user_name');
            $args->nick_name = $oDocument->get('nick_name');
            $args->target_member_srl = $oDocument->get('member_srl');
            $args->title = $oDocument->get('title');

            // 있는지 조사
            $output = executeQuery('member.getScrapDocument', $args);
            if($output->data->count) return new Object(-1, 'msg_alreay_scrapped');

            // 입력
            $output = executeQuery('member.addScrapDocument', $args);
            if(!$output->toBool()) return $output;

            $this->setError(-1);
            $this->setMessage('success_registed');
        }

        /**
         * @brief 스크랩 삭제
         **/
        function procMemberDeleteScrap() {
            // 로그인 정보 체크
            if(!Context::get('is_logged')) return new Object(-1, 'msg_not_logged');
            $logged_info = Context::get('logged_info');

            $document_srl = (int)Context::get('document_srl');
            if(!$document_srl) return new Object(-1,'msg_invalid_request');

            // 변수 정리
            $args->member_srl = $logged_info->member_srl;
            $args->document_srl = $document_srl;
            return executeQuery('member.deleteScrapDocument', $args);
        }

        /**
         * @brief 게시글 저장
         **/
        function procMemberSaveDocument() {
            // 로그인 정보 체크
            if(!Context::get('is_logged')) return new Object(-1, 'msg_not_logged');

            $logged_info = Context::get('logged_info');
			
            // 해당 mid, vid에 쓰기 권한이 있는지 체크 (2012-06-30 by CMD)
            $oModuleModel = &getModel('module');
            $module_info = $oModuleModel->getModuleInfoByMid(Context::get('mid'));
			$module_grant = $oModuleModel->getGrant($module_info, $logged_info);
            if(!$module_grant->write_document) return new Object(-1, 'msg_not_permitted');

            // form 정보를 모두 받음
            $obj = Context::getRequestVars();

            // 글의 대상 모듈을 회원 정보로 변경
            $obj->module_srl = $logged_info->member_srl;
			unset($obj->is_notice);

            // 제목을 사용하지 않는 방명록 등에서 내용 앞 부분을 제목 가져오기
            if(!$obj->title) {
                $obj->title = cut_str(strip_tags($obj->content), 20, '...');
            }

            $oDocumentModel = &getModel('document');
            $oDocumentController = &getController('document');

            // 이미 존재하는 글인지 체크
            $oDocument = $oDocumentModel->getDocument($obj->document_srl, $this->grant->manager);

            // 이미 존재하는 경우 수정
            if($oDocument->isExists() && $oDocument->document_srl == $obj->document_srl) {
                // 해당 문서에 대한 권한이 있는지 체크 (2012-06-30 by CMD)
                if(!$oDocument->isGranted()) return new Object(-1,'msg_not_permitted');
                $output = $oDocumentController->updateDocument($oDocument, $obj);
                $msg_code = 'success_updated';

            // 그렇지 않으면 신규 등록
            } else {
                $output = $oDocumentController->insertDocument($obj);
                $msg_code = 'success_registed';
                $obj->document_srl = $output->get('document_srl');
                $oDocument = $oDocumentModel->getDocument($obj->document_srl, $this->grant->manager);
            }

            // 등록된 첨부파일의 상태를 무효로 지정
            if($oDocument->hasUploadedFiles()) {
                $args->upload_target_srl = $oDocument->document_srl;
                $args->isvalid = 'N';
                executeQuery('file.updateFileValid', $args);
            }

            $this->setMessage('success_saved');
            $this->add('document_srl', $obj->document_srl);
        }

        /**
         * @brief 저장된 글 삭제
         **/
        function procMemberDeleteSavedDocument() {
            // 로그인 정보 체크
            if(!Context::get('is_logged')) return new Object(-1, 'msg_not_logged');
            $logged_info = Context::get('logged_info');

            $document_srl = (int)Context::get('document_srl');
            if(!$document_srl) return new Object(-1,'msg_invalid_request');

            // 변수 정리
            $oDocumentController = &getController('document');
            $oDocumentController->deleteDocument($document_srl, true);
        }

        /**
         * @brief 회원 가입시 특정 항목들에 대한 값 체크
         **/
        function procMemberCheckValue() {
            $name = Context::get('name');
            $value = Context::get('value');
            if(!$value) return;

            $oMemberModel = &getModel('member');

            // 로그인 여부 체크
            $logged_info = Context::get('logged_info');


            switch($name) {
                case 'user_id' :
                        // 금지 아이디 검사
                        if($oMemberModel->isDeniedID($value)) return new Object(0,'denied_user_id');

                        // 중복 검사
                        $member_srl = $oMemberModel->getMemberSrlByUserID($value);
                        if($member_srl && $logged_info->member_srl != $member_srl ) return new Object(0,'msg_exists_user_id');
                    break;
                case 'nick_name' :
                        // 중복 검사
                        $member_srl = $oMemberModel->getMemberSrlByNickName($value);
                        if($member_srl && $logged_info->member_srl != $member_srl ) return new Object(0,'msg_exists_nick_name');

                    break;
                case 'email_address' :
                        // 중복 검사
                        $member_srl = $oMemberModel->getMemberSrlByEmailAddress($value);
                        if($member_srl && $logged_info->member_srl != $member_srl ) return new Object(0,'msg_exists_email_address');
                    break;
            }
        }

        /**
         * @brief 회원 가입
         **/
        function procMemberInsert() {
            if (Context::getRequestMethod () == "GET") return new Object (-1, "msg_invalid_request");
            $oMemberModel = &getModel ('member');
            $config = $oMemberModel->getMemberConfig ();

            // before 트리거 호출
            $trigger_output = ModuleHandler::triggerCall ('member.procMemberInsert', 'before', $config);
            if (!$trigger_output->toBool ()) return $trigger_output;

            // 관리자가 회원가입을 허락하였는지 검사
            if ($config->enable_join != 'Y') return $this->stop ('msg_signup_disabled');

            // 약관에 동의하였는지 검사 (약관이 있을 경우만)
            if ($config->agreement && Context::get('accept_agreement')!='Y') return $this->stop('msg_accept_agreement');

            // 필수 정보들을 미리 추출
            $args = Context::gets('user_id','user_name','nick_name','homepage','blog','birthday','email_address','password','allow_mailing','find_account_question','find_account_answer');
            $args->member_srl = getNextSequence();
            $args->list_order = -1 * $args->member_srl;

            // 넘어온 모든 변수중에서 몇가지 불필요한 것들 삭제
            $all_args = Context::getRequestVars();
            unset($all_args->module);
            unset($all_args->act);
            unset($all_args->is_admin);
            unset($all_args->description);
            unset($all_args->group_srl_list);
            unset($all_args->body);
            unset($all_args->accept_agreement);
            unset($all_args->signature);
            unset($all_args->password2);

            // 메일 인증 기능 사용시 회원 상태를 denied로 설정
            if ($config->enable_confirm == 'Y') $args->denied = 'Y';

            // 모든 request argument에서 필수 정보만 제외 한 후 추가 데이터로 입력
            $extra_vars = delObjectVars($all_args, $args);
            $args->extra_vars = serialize($extra_vars);

            // member_srl의 값에 따라 insert/update
            $output = $this->insertMember($args);
            if(!$output->toBool()) return $output;

            // 가상사이트일 경우 사이트 가입
            $site_module_info = Context::get('site_module_info');
            if($site_module_info->site_srl > 0) {
                $default_group = $oMemberModel->getDefaultGroup($site_module_info->site_srl);
                if($default_group->group_srl) {
                    $this->addMemberToGroup($args->member_srl, $default_group->group_srl, $site_module_info->site_srl);
                }

            }

            // 로그인 시킴
            if ($config->enable_confirm != 'Y') $this->doLogin($args->user_id);

            // 결과 정리
            $this->add('member_srl', $args->member_srl);
            if($config->redirect_url) $this->add('redirect_url', $config->redirect_url);
            if ($config->enable_confirm == 'Y') {
                $msg = sprintf(Context::getLang('msg_confirm_mail_sent'), $args->email_address);
                $this->setMessage($msg);
            }
            else $this->setMessage('success_registed');

            // after 트리거 호출
            $trigger_output = ModuleHandler::triggerCall('member.procMemberInsert', 'after', $config);
            if(!$trigger_output->toBool()) return $trigger_output;
        }

		/**
		 * @brief 회원 정보 수정 전 비밀번호 재확인
		 **/
		function procMemberModifyInfoBefore() {
			if($_SESSION['rechecked_password_step'] != 'INPUT_PASSWORD') return $this->stop('msg_invalid_request');

			if(!Context::get('is_logged')) return $this->stop('msg_not_logged');

			$password = Context::get('password');

			if(!$password) return $this->stop('msg_invalid_request');
			$logged_info = Context::get('logged_info');
			$oMemberModel = &getModel('member');

			if(!$this->memberInfo->password) {
				$memberInfo = $oMemberModel->getMemberInfoByMemberSrl($logged_info->member_srl);
				$this->memberInfo->password = $memberInfo->password;
			}
			
			// Verify the cuttent password
			if(!$oMemberModel->isValidPassword($this->memberInfo->password, $password)) return new Object(-1, 'invalid_password');

			$_SESSION['rechecked_password_step'] = 'VALIDATE_PASSWORD';
		}

        /**
         * @brief 회원 정보 수정
         **/
        function procMemberModifyInfo() {
            if(!Context::get('is_logged')) return $this->stop('msg_not_logged');

            if($_SESSION['rechecked_password_step'] != 'INPUT_DATA') return $this->stop('msg_invalid_request');
            unset($_SESSION['rechecked_password_step']);

            // 필수 정보들을 미리 추출
            $args = Context::gets('user_name','nick_name','homepage','blog','birthday','email_address','allow_mailing','find_account_question','find_account_answer');

            // 로그인 정보
            $logged_info = Context::get('logged_info');
            $args->member_srl = $logged_info->member_srl;

            // 넘어온 모든 변수중에서 몇가지 불필요한 것들 삭제
            $all_args = Context::getRequestVars();
            unset($all_args->module);
            unset($all_args->act);
            unset($all_args->is_admin);
            unset($all_args->description);
            unset($all_args->group_srl_list);
            unset($all_args->body);
            unset($all_args->accept_agreement);
            unset($all_args->signature);
            unset($all_args->_filter);

            // 모든 request argument에서 필수 정보만 제외 한 후 추가 데이터로 입력
            $extra_vars = delObjectVars($all_args, $args);
            $args->extra_vars = serialize($extra_vars);

            // 멤버 모델 객체 생성
            $oMemberModel = &getModel('member');

            // member_srl의 값에 따라 insert/update
            $output = $this->updateMember($args);
            if(!$output->toBool()) return $output;

            // 서명 저장
            $signature = Context::get('signature');
            $this->putSignature($args->member_srl, $signature);

            // user_id 에 따른 정보 가져옴
            $member_info = $oMemberModel->getMemberInfoByMemberSrl($args->member_srl);

            // 로그인 성공후 trigger 호출 (after)
            $trigger_output = ModuleHandler::triggerCall('member.doLogin', 'after', $member_info);
            if(!$trigger_output->toBool()) return $trigger_output;

            $this->setSessionInfo($member_info);

            // 결과 리턴
            $this->add('member_srl', $args->member_srl);
            $this->setMessage('success_updated');
        }

        /**
         * @brief 회원 비밀번호 수정
         **/
        function procMemberModifyPassword() {
            if(!Context::get('is_logged')) return $this->stop('msg_not_logged');

            // 필수 정보들을 미리 추출
            $current_password = trim(Context::get('current_password'));
            $password = trim(Context::get('password'));

            // 로그인한 유저의 정보를 가져옴
            $logged_info = Context::get('logged_info');
            $member_srl = $logged_info->member_srl;

            // member model 객체 생성
            $oMemberModel = &getModel('member');

            // member_srl 에 따른 정보 가져옴
            $member_info = $oMemberModel->getMemberInfoByMemberSrl($member_srl);

            // 현재 비밀번호가 맞는지 확인
            if(!$oMemberModel->isValidPassword($member_info->password, $current_password)) return new Object(-1, 'invalid_password');

            // 이전 비밀번호와 같은지 확인
            if ($current_password == $password) return new Object(-1, 'invalid_new_password');

            // member_srl의 값에 따라 insert/update
            $args->member_srl = $member_srl;
            $args->password = $password;
            $output = $this->updateMemberPassword($args);
            if(!$output->toBool()) return $output;

            $this->add('member_srl', $args->member_srl);
            $this->setMessage('success_updated');
        }

        /**
         * @brief 탈퇴
         **/
        function procMemberLeave() {
            if(!Context::get('is_logged')) return $this->stop('msg_not_logged');

            // 필수 정보들을 미리 추출
            $password = trim(Context::get('password'));

            // 로그인한 유저의 정보를 가져옴
            $logged_info = Context::get('logged_info');
            $member_srl = $logged_info->member_srl;

            // member model 객체 생성
            $oMemberModel = &getModel('member');

            // member_srl 에 따른 정보 가져옴
            $member_info = $oMemberModel->getMemberInfoByMemberSrl($member_srl);

            // 현재 비밀번호가 맞는지 확인
            if(!$oMemberModel->isValidPassword($member_info->password, $password)) return new Object(-1, 'invalid_password');

            $output = $this->deleteMember($member_srl);
            if(!$output->toBool()) return $output;

            // 모든 세션 정보 파기
            $this->destroySessionInfo();

            // 성공 메세지 리턴
            $this->setMessage('success_leaved');
        }

        /**
         * @brief 오픈아이디 탈퇴
         **/
        function procMemberOpenIDLeave() {
            // 비로그인 상태이면 에러
            if(!Context::get('is_logged')) return $this->stop('msg_not_logged');

            // 현재 ip와 세션 아이피 비교
            if($_SESSION['ipaddress']!=$_SERVER['REMOTE_ADDR']) return $this->stop('msg_not_permitted');

            // 로그인한 유저의 정보를 가져옴
            $logged_info = Context::get('logged_info');
            $member_srl = $logged_info->member_srl;

            $output = $this->deleteMember($member_srl);
            if(!$output->toBool()) return $output;

            // 모든 세션 정보 파기
            $this->destroySessionInfo();

            // 성공 메세지 리턴
            $this->setMessage('success_leaved');
        }

        /**
         * @brief 프로필 이미지 추가
         **/
        function procMemberInsertProfileImage() {
            // 정상적으로 업로드 된 파일인지 검사
            $file = $_FILES['profile_image'];
            if(!is_uploaded_file($file['tmp_name'])) return $this->stop('msg_not_uploaded_profile_image');

            // 회원 정보를 검사해서 회원번호가 없거나 관리자가 아니고 회원번호가 틀리면 무시
            $member_srl = Context::get('member_srl');
            if(!$member_srl) return $this->stop('msg_not_uploaded_profile_image');

            $logged_info = Context::get('logged_info');
            if($logged_info->is_admin != 'Y' && $logged_info->member_srl != $member_srl) return $this->stop('msg_not_uploaded_profile_image');

            // 회원 모듈 설정에서 이미지 이름 사용 금지를 하였을 경우 관리자가 아니면 return;
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('member');
            if($logged_info->is_admin != 'Y' && $config->profile_image != 'Y') return $this->stop('msg_not_uploaded_profile_image');

            $this->insertProfileImage($member_srl, $file['tmp_name']);

            // 페이지 리프레쉬
            $this->setRefreshPage();
        }

        function insertProfileImage($member_srl, $target_file) {
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('member');

            // 정해진 사이즈를 구함
            $max_width = $config->profile_image_max_width;
            if(!$max_width) $max_width = "90";
            $max_height = $config->profile_image_max_height;
            if(!$max_height) $max_height = "20";

            // 저장할 위치 구함
            $target_path = sprintf('files/member_extra_info/profile_image/%s', getNumberingPath($member_srl));
            FileHandler::makeDir($target_path);

            // 파일 정보 구함
            list($width, $height, $type, $attrs) = @getimagesize($target_file);
            if($type == 3) $ext = 'png';
            elseif($type == 2) $ext = 'jpg';
            else $ext = 'gif';

            $target_filename = sprintf('%s%d.%s', $target_path, $member_srl, $ext);

            // 지정된 사이즈보다 크거나 gif가 아니면 변환
            if($width > $max_width || $height > $max_height || $type!=1) FileHandler::createImageFile($target_file, $target_filename, $max_width, $max_height, $ext);
            else @copy($target_file, $target_filename);
        }

        /**
         * @brief 이미지 이름을 추가
         **/
        function procMemberInsertImageName() {
            // 정상적으로 업로드 된 파일인지 검사
            $file = $_FILES['image_name'];
            if(!is_uploaded_file($file['tmp_name'])) return $this->stop('msg_not_uploaded_image_name');

            // 회원 정보를 검사해서 회원번호가 없거나 관리자가 아니고 회원번호가 틀리면 무시
            $member_srl = Context::get('member_srl');
            if(!$member_srl) return $this->stop('msg_not_uploaded_image_name');

            $logged_info = Context::get('logged_info');
            if($logged_info->is_admin != 'Y' && $logged_info->member_srl != $member_srl) return $this->stop('msg_not_uploaded_image_name');

            // 회원 모듈 설정에서 이미지 이름 사용 금지를 하였을 경우 관리자가 아니면 return;
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('member');
            if($logged_info->is_admin != 'Y' && $config->image_name != 'Y') return $this->stop('msg_not_uploaded_image_name');

            $this->insertImageName($member_srl, $file['tmp_name']);

            // 페이지 리프레쉬
            $this->setRefreshPage();
        }

        function insertImageName($member_srl, $target_file) {
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('member');

            // 정해진 사이즈를 구함
            $max_width = $config->image_name_max_width;
            if(!$max_width) $max_width = "90";
            $max_height = $config->image_name_max_height;
            if(!$max_height) $max_height = "20";

            // 저장할 위치 구함
            $target_path = sprintf('files/member_extra_info/image_name/%s/', getNumberingPath($member_srl));
            FileHandler::makeDir($target_path);

            $target_filename = sprintf('%s%d.gif', $target_path, $member_srl);

            // 파일 정보 구함
            list($width, $height, $type, $attrs) = @getimagesize($target_file);

            // 지정된 사이즈보다 크거나 gif가 아니면 변환
            if($width > $max_width || $height > $max_height || $type!=1) FileHandler::createImageFile($target_file, $target_filename, $max_width, $max_height, 'gif');
            else @copy($target_file, $target_filename);
        }

        /**
         * @brief 프로필 이미지를 삭제
         **/
        function procMemberDeleteProfileImage() {
            $member_srl = Context::get('member_srl');
            if(!$member_srl) return new Object(0,'success');

            $logged_info = Context::get('logged_info');

            if($logged_info->is_admin != 'Y') {
                $oModuleModel = &getModel('module');
                $config = $oModuleModel->getModuleConfig('member');
                if($config->profile_image == 'N') return new Object(0,'success');
            }

            if($logged_info->is_admin == 'Y' || $logged_info->member_srl == $member_srl) {
                $oMemberModel = &getModel('member');
                $profile_image = $oMemberModel->getProfileImage($member_srl);
                FileHandler::removeFile($profile_image->file);
            }
            return new Object(0,'success');
        }

        /**
         * @brief 이미지 이름을 삭제
         **/
        function procMemberDeleteImageName() {
            $member_srl = Context::get('member_srl');
            if(!$member_srl) return new Object(0,'success');

            $logged_info = Context::get('logged_info');

            if($logged_info->is_admin != 'Y') {
                $oModuleModel = &getModel('module');
                $config = $oModuleModel->getModuleConfig('member');
                if($config->image_name == 'N') return new Object(0,'success');
            }

            if($logged_info->is_admin == 'Y' || $logged_info->member_srl == $member_srl) {
                $oMemberModel = &getModel('member');
                $image_name = $oMemberModel->getImageName($member_srl);
                FileHandler::removeFile($image_name->file);
            }
            return new Object(0,'success');
        }

        /**
         * @brief 이미지 마크를 추가
         **/
        function procMemberInsertImageMark() {
            // 정상적으로 업로드 된 파일인지 검사
            $file = $_FILES['image_mark'];
            if(!is_uploaded_file($file['tmp_name'])) return $this->stop('msg_not_uploaded_image_mark');

            // 회원 정보를 검사해서 회원번호가 없거나 관리자가 아니고 회원번호가 틀리면 무시
            $member_srl = Context::get('member_srl');
            if(!$member_srl) return $this->stop('msg_not_uploaded_image_mark');

            $logged_info = Context::get('logged_info');
            if($logged_info->is_admin != 'Y' && $logged_info->member_srl != $member_srl) return $this->stop('msg_not_uploaded_image_mark');

            // 회원 모듈 설정에서 이미지 마크 사용 금지를 하였을 경우 관리자가 아니면 return;
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('member');
            if($logged_info->is_admin != 'Y' && $config->image_mark != 'Y') return $this->stop('msg_not_uploaded_image_mark');

            $this->insertImageMark($member_srl, $file['tmp_name']);

            // 페이지 리프레쉬
            $this->setRefreshPage();
        }

        function insertImageMark($member_srl, $target_file) {
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('member');

            // 정해진 사이즈를 구함
            $max_width = $config->image_mark_max_width;
            if(!$max_width) $max_width = "20";
            $max_height = $config->image_mark_max_height;
            if(!$max_height) $max_height = "20";

            $target_path = sprintf('files/member_extra_info/image_mark/%s/', getNumberingPath($member_srl));
            FileHandler::makeDir($target_path);

            $target_filename = sprintf('%s%d.gif', $target_path, $member_srl);

            // 파일 정보 구함
            list($width, $height, $type, $attrs) = @getimagesize($target_file);

            if($width > $max_width || $height > $max_height || $type!=1) FileHandler::createImageFile($target_file, $target_filename, $max_width, $max_height, 'gif');
            else @copy($target_file, $target_filename);

        }

        /**
         * @brief 이미지 마크를  삭제
         **/
        function procMemberDeleteImageMark() {
            $member_srl = Context::get('member_srl');
            if(!$member_srl) return new Object(0,'success');

            $logged_info = Context::get('logged_info');
            if($logged_info->is_admin == 'Y' || $logged_info->member_srl == $member_srl) {
                $oMemberModel = &getModel('member');
                $image_mark = $oMemberModel->getImageMark($member_srl);
                FileHandler::removeFile($image_mark->file);
            }
            return new Object(0,'success');
        }

        /**
         * @brief 아이디/ 비밀번호 찾기
         **/
        function procMemberFindAccount() {
            $email_address = Context::get('email_address');
            if(!$email_address) return new Object(-1, 'msg_invalid_request');

            $oMemberModel = &getModel('member');
            $oModuleModel = &getModel('module');

            // 메일 주소에 해당하는 회원이 있는지 검사
            $member_srl = $oMemberModel->getMemberSrlByEmailAddress($email_address);
            if(!$member_srl) return new Object(-1, 'msg_email_not_exists');

            // 회원의 정보를 가져옴
            $member_info = $oMemberModel->getMemberInfoByMemberSrl($member_srl);

            // 아이디/비밀번호 찾기가 가능한 상태의 회원인지 검사
            if ($member_info->denied == 'Y') {
                $chk_args->member_srl = $member_info->member_srl;
                $output = executeQuery('member.chkAuthMail', $chk_args);
                if ($output->toBool() && $output->data->count != '0') return new Object(-1, 'msg_user_not_confirmed');
            }

            // 인증 DB에 데이터를 넣음
            $args->user_id = $member_info->user_id;
            $args->member_srl = $member_info->member_srl;
            $args->new_password = rand(111111,999999);
            $args->auth_key = md5( rand(0,999999 ) );
            $args->is_register = 'N';

            $output = executeQuery('member.insertAuthMail', $args);
            if(!$output->toBool()) return $output;

            // 메일 내용을 구함
            Context::set('auth_args', $args);
            Context::set('member_info', $member_info);

            $member_config = $oModuleModel->getModuleConfig('member');
            if(!$member_config->skin) $member_config->skin = "default";
            if(!$member_config->colorset) $member_config->colorset = "white";

            Context::set('member_config', $member_config);

            $tpl_path = sprintf('%sskins/%s', $this->module_path, $member_config->skin);
            if(!is_dir($tpl_path)) $tpl_path = sprintf('%sskins/%s', $this->module_path, 'default');

            $find_url = getFullUrl ('', 'module', 'member', 'act', 'procMemberAuthAccount', 'member_srl', $member_info->member_srl, 'auth_key', $args->auth_key);
            Context::set('find_url', $find_url);

            $oTemplate = &TemplateHandler::getInstance();
            $content = $oTemplate->compile($tpl_path, 'find_member_account_mail');

            // 사이트 웹마스터 정보를 구함
            $oModuleModel = &getModel('module');
            $member_config = $oModuleModel->getModuleConfig('member');

            // 메일 발송
            $oMail = new Mail();
            $oMail->setTitle( Context::getLang('msg_find_account_title') );
            $oMail->setContent($content);
            $oMail->setSender( $member_config->webmaster_name?$member_config->webmaster_name:'webmaster', $member_config->webmaster_email);
            $oMail->setReceiptor( $member_info->user_name, $member_info->email_address );
            $oMail->send();

            // 메세지 return
            $msg = sprintf(Context::getLang('msg_auth_mail_sent'), $member_info->email_address);
            return new Object(0,$msg);
        }


        /**
         * @brief 질문/답변을 통한 임시 비밀번호 생성
         **/
        function procMemberFindAccountByQuestion() {
            $email_address = Context::get('email_address');
            $user_id = Context::get('user_id');
            $find_account_question = trim(Context::get('find_account_question'));
            $find_account_answer = trim(Context::get('find_account_answer'));

            if(!$user_id || !$email_address || !$find_account_question || !$find_account_answer) return new Object(-1, 'msg_invalid_request');

            $oMemberModel = &getModel('member');
            $oModuleModel = &getModel('module');

            // 메일 주소에 해당하는 회원이 있는지 검사
            $member_srl = $oMemberModel->getMemberSrlByEmailAddress($email_address);
            if(!$member_srl) return new Object(-1, 'msg_email_not_exists');

            // 회원의 정보를 가져옴
            $member_info = $oMemberModel->getMemberInfoByMemberSrl($member_srl);

            // 질문 응답이 없으면 
            if (!$member_info->find_account_question || !$member_info->find_account_answer) return new Object(-1, 'msg_question_not_exists');

            if(trim($member_info->find_account_question) != $find_account_question || trim($member_info->find_account_answer) != $find_account_answer) return new Object(-1, 'msg_answer_not_matches');

            // 임시비밀번호로 변경 및 비밀번호 변경시간을 1로 설정
            $args->member_srl = $member_srl;
            list($usec, $sec) = explode(" ", microtime()); 
            $temp_password = substr(md5($user_id . $member_info->find_account_answer. $usec . $sec),0,15);

            $args->password = $temp_password;
            $args->change_password_date = '1';
            $output = $this->updateMemberPassword($args);
            if(!$output->toBool()) return $output;

            $_SESSION['xe_temp_password_'.$user_id] = $temp_password;

            $this->add('user_id',$user_id);
        }

        /**
         * @brief 아이디/비밀번호 찾기 기능 실행
         * 메일에 등록된 링크를 선택시 호출되는 method로 비밀번호를 바꾸고 인증을 시켜버림
         **/
        function procMemberAuthAccount() {
            // user_id, authkey 검사
            $member_srl = Context::get('member_srl');
            $auth_key = Context::get('auth_key');
            if(!$member_srl || !$auth_key) return $this->stop('msg_invalid_request');

            // user_id, authkey로 비밀번호 찾기 로그 검사
            $args->member_srl = $member_srl;
            $args->auth_key = $auth_key;
            $output = executeQuery('member.getAuthMail', $args);
            if(!$output->toBool() || $output->data->auth_key != $auth_key) return $this->stop('msg_invalid_auth_key');

            // 인증 정보가 맞다면 새비밀번호로 비밀번호를 바꿈
            if ($output->data->is_register == 'Y') {
                $args->password = $output->data->new_password;
                $args->denied = 'N';
            } else {
                $args->password = md5($output->data->new_password);
                unset($args->denied);
            }

            // $output->data->is_register 값을 백업해 둔다.
            $is_register = $output->data->is_register;

            $output = executeQuery('member.updateMemberPassword', $args);
            if(!$output->toBool()) return $this->stop($output->getMessage());

            // 인증 테이블에서 member_srl에 해당하는 모든 값을 지움
            executeQuery('member.deleteAuthMail',$args);

            // 결과를 통보
            Context::set('is_register', $is_register);
            $this->setTemplatePath($this->module_path.'tpl');
            $this->setTemplateFile('msg_success_authed');
        }

        /**
         * @brief 아이디/비밀번호 찾기 기능 실행
         * 메일에 등록된 링크를 선택시 호출되는 method로 비밀번호를 바꾸고 인증을 시켜버림
         **/
        function procMemberUpdateAuthMail() {
            $member_srl = Context::get('member_srl');
            if(!$member_srl) return new Object(-1, 'msg_invalid_request');

            $oMemberModel = &getModel('member');

            // 회원의 정보를 가져옴
            $member_info = $oMemberModel->getMemberInfoByMemberSrl($member_srl);

            // 인증메일 재발송 요청이 가능한 상태의 회원인지 검사
            if ($member_info->denied != 'Y')
                return new Object(-1, 'msg_invalid_request');

            $chk_args->member_srl = $member_srl;
            $output = executeQuery('member.chkAuthMail', $chk_args);
            if ($output->toBool() && $output->data->count == '0') return new Object(-1, 'msg_invalid_request');

            // 인증 DB에 데이터를 넣음
            $auth_args->member_srl = $member_srl;
            $auth_args->auth_key = md5(rand(0, 999999));

            $output = executeQuery('member.updateAuthMail', $auth_args);
            if (!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            // 메일 내용을 구함
            Context::set('auth_args', $auth_args);
            Context::set('member_info', $member_info);

            $oModuleModel = &getModel('module');
            $member_config = $oModuleModel->getModuleConfig('member');
            if(!$member_config->skin) $member_config->skin = "default";
            if(!$member_config->colorset) $member_config->colorset = "white";

            Context::set('member_config', $member_config);

            $tpl_path = sprintf('%sskins/%s', $this->module_path, $member_config->skin);
            if(!is_dir($tpl_path)) $tpl_path = sprintf('%sskins/%s', $this->module_path, 'default');

            $auth_url = getFullUrl('','module','member','act','procMemberAuthAccount','member_srl',$member_info->member_srl, 'auth_key',$auth_args->auth_key);
            Context::set('auth_url', $auth_url);

            $oTemplate = &TemplateHandler::getInstance();
            $content = $oTemplate->compile($tpl_path, 'confirm_member_account_mail');

            // 사이트 웹마스터 정보를 구함
            $oModuleModel = &getModel('module');
            $member_config = $oModuleModel->getModuleConfig('member');

            // 메일 발송
            $oMail = new Mail();
            $oMail->setTitle( Context::getLang('msg_confirm_account_title') );
            $oMail->setContent($content);
            $oMail->setSender( $member_config->webmaster_name?$member_config->webmaster_name:'webmaster', $member_config->webmaster_email);
            $oMail->setReceiptor( $member_info->user_name, $member_info->email_address );
            $oMail->send();

            // 메세지 return
            $msg = sprintf(Context::getLang('msg_auth_mail_sent'), $member_info->email_address);
            return new Object(-1, $msg);
        }

        /**
         * @brief 인증 메일 재발송
         **/
        function procMemberResendAuthMail() {
            // email_address 검사
            $email_address = Context::get('email_address');
            if(!$email_address) return $this->stop('msg_invalid_request');

            // email_address로 비밀번호 찾기 로그 검사
            $oMemberModel = &getModel('member');

            $args->email_address = $email_address;
            $member_info = $oMemberModel->getMemberSrlByEmailAddress($email_address);
            if(!$member_info) return $this->stop('msg_not_exists_member');

            $member_info = $oMemberModel->getMemberInfoByMemberSrl($member_info);

            // 이전에 인증 메일을 보냈는지 확인
            $chk_args->member_srl = $member_info->member_srl;
            $output = executeQuery('member.chkAuthMail', $chk_args);
            if($output->toBool() && $output->data->count == '0') return new Object(-1, 'msg_invalid_request');

            $auth_args->member_srl = $member_info->member_srl;
            $output = executeQueryArray('member.getAuthMailInfo', $auth_args);
            if(!$output->data || !$output->data[0]->auth_key)  return new Object(-1, 'msg_invalid_request');
            $auth_info = $output->data[0];

            // 메일 내용을 구함
            Context::set('member_info', $member_info);
            $oModuleModel = &getModel('module');
            $member_config = $oModuleModel->getModuleConfig('member');
            if(!$member_config->skin) $member_config->skin = "default";
            if(!$member_config->colorset) $member_config->colorset = "white";

            Context::set('member_config', $member_config);

            $tpl_path = sprintf('%sskins/%s', $this->module_path, $member_config->skin);
            if(!is_dir($tpl_path)) $tpl_path = sprintf('%sskins/%s', $this->module_path, 'default');

            $auth_url = getFullUrl('','module','member','act','procMemberAuthAccount','member_srl',$member_info->member_srl, 'auth_key',$auth_info->auth_key);
            Context::set('auth_url', $auth_url);

            $oTemplate = &TemplateHandler::getInstance();
            $content = $oTemplate->compile($tpl_path, 'confirm_member_account_mail');

            // 사이트 웹마스터 정보를 구함
            $oModuleModel = &getModel('module');
            $member_config = $oModuleModel->getModuleConfig('member');

            // 메일 발송
            $oMail = new Mail();
            $oMail->setTitle( Context::getLang('msg_confirm_account_title') );
            $oMail->setContent($content);
            $oMail->setSender( $member_config->webmaster_name?$member_config->webmaster_name:'webmaster', $member_config->webmaster_email);
            $oMail->setReceiptor( $args->user_name, $args->email_address );
            $oMail->send();

            $msg = sprintf(Context::getLang('msg_confirm_mail_sent'), $args->email_address);
            $this->setMessage($msg);
        }

        /**
         * @brief 가상 사이트 가입
         **/
        function procModuleSiteSignUp() {
            $site_module_info = Context::get('site_module_info');
            $logged_info = Context::get('logged_info');
            if(!$site_module_info->site_srl || !Context::get('is_logged') || count($logged_info->group_srl_list) ) return new Object(-1,'msg_invalid_request');

            $oMemberModel = &getModel('member');
            $default_group = $oMemberModel->getDefaultGroup($site_module_info->site_srl);
            $this->addMemberToGroup($logged_info->member_srl, $default_group->group_srl, $site_module_info->site_srl);
            $groups[$default_group->group_srl] = $default_group->title;
            $logged_info->group_list = $groups;
        }

        /**
         * @brief 가상 사이트 탈퇴
         **/
        function procModuleSiteLeave() {
            $site_module_info = Context::get('site_module_info');
            $logged_info = Context::get('logged_info');
            if(!$site_module_info->site_srl || !Context::get('is_logged') || count($logged_info->group_srl_list) ) return new Object(-1,'msg_invalid_request');

            $args->site_srl= $site_module_info->site_srl;
            $args->member_srl = $logged_info->member_srl;
            $output = executeQuery('member.deleteMembersGroup', $args);
            if(!$output->toBool()) return $output;
            $this->setMessage('success_deleted');
        }

        /**
         * @brief 회원 설정 정보를 저장
         **/
        function setMemberConfig($args) {
            if(!$args->skin) $args->skin = "default";
            if(!$args->colorset) $args->colorset = "white";
            if(!$args->editor_skin) $args->editor_skin= "xpresseditor";
            if(!$args->editor_colorset) $args->editor_colorset = "white";
            if($args->enable_join!='Y') $args->enable_join = 'N';
            if($args->enable_openid!='Y') $args->enable_openid= 'N';
            if($args->profile_image !='Y') $args->profile_image = 'N';
            if($args->image_name!='Y') $args->image_name = 'N';
            if($args->image_mark!='Y') $args->image_mark = 'N';
            if($args->group_image_mark!='Y') $args->group_image_mark = 'N';
            if(!trim(strip_tags($args->agreement))) $args->agreement = null;
            $args->limit_day = (int)$args->limit_day;

            $agreement = trim($args->agreement);
            unset($args->agreement);

            $oModuleController = &getController('module');
            $output = $oModuleController->insertModuleConfig('member',$args);
            if(!$output->toBool()) return $output;

            $agreement_file = _XE_PATH_.'files/member_extra_info/agreement.txt';
            FileHandler::writeFile($agreement_file, $agreement);

            return new Object();
        }

        /**
         * @brief 서명을 파일로 저장
         **/
        function putSignature($member_srl, $signature) {
            $signature = trim(removeHackTag($signature));
            $signature = preg_replace('/<(\/?)(embed|object|param)/is', '&lt;$1$2', $signature);

            $check_signature = trim(str_replace(array('&nbsp;',"\n","\r"),'',strip_tags($signature,'<img><object>')));
            $path = sprintf('files/member_extra_info/signature/%s/', getNumberingPath($member_srl));
            $filename = sprintf('%s%d.signature.php', $path, $member_srl);

            if(!$check_signature) return FileHandler::removeFile($filename);

            $buff = sprintf('<?php if(!defined("__ZBXE__")) exit();?>%s', $signature);
            FileHandler::makeDir($path);
            FileHandler::writeFile($filename, $buff);
        }

        /**
         * @brief 서명 파일 삭제
         **/
        function delSignature($member_srl) {
            $filename = sprintf('files/member_extra_info/signature/%s%d.gif', getNumberingPath($member_srl), $member_srl);
            FileHandler::removeFile($filename);
        }

        /**
         * @brief member_srl에 group_srl을 추가
         **/
        function addMemberToGroup($member_srl,$group_srl,$site_srl=0) {
            $args->member_srl = $member_srl;
            $args->group_srl = $group_srl;
            if($site_srl) $args->site_srl = $site_srl;

            $oModel =& getModel('member');
            $groups = $oModel->getMemberGroups($member_srl, $site_srl, true);
            if($groups[$group_srl]) return new Object();

            // 추가
            $output = executeQuery('member.addMemberToGroup',$args);
            $output2 = ModuleHandler::triggerCall('member.addMemberToGroup', 'after', $args);

            return $output;
        }

        /**
         * @brief 특정 회원들의 그룹을 일괄 변경
         * 가상 사이트와 같이 한 회원이 하나의 그룹만 가질 경우 사용할 수 있음
         **/
        function replaceMemberGroup($args) {
            $obj->site_srl = $args->site_srl;
            $obj->member_srl = implode(',',$args->member_srl);

            $output = executeQueryArray('member.getMembersGroup', $obj);
            if($output->data) foreach($output->data as $key => $val) $date[$val->member_srl] = $val->regdate;

            $output = executeQuery('member.deleteMembersGroup', $obj);
            if(!$output->toBool()) return $output;

            $inserted_members = array();
            foreach($args->member_srl as $key => $val) {
                if($inserted_members[$val]) continue;
                $inserted_members[$val] = true;

                unset($obj);
                $obj->member_srl = $val;
                $obj->group_srl = $args->group_srl;
                $obj->site_srl = $args->site_srl;
                $obj->regdate = $date[$obj->member_srl];
                $output = executeQuery('member.addMemberToGroup', $obj);
                if(!$output->toBool()) return $output;
            }
            return new Object();
        }


        /**
         * @brief 자동 로그인 시킴
         **/
        function doAutologin() {
            // 자동 로그인 키 값을 구함
            $args->autologin_key = $_COOKIE['xeak'];

            // 키값에 해당하는 정보 구함
            $output = executeQuery('member.getAutologin', $args);

            // 정보가 없으면 쿠키 삭제
            if(!$output->toBool() || !$output->data) {
                setCookie('xeak',null,time()+60*60*24*365, '/');
                return;
            }

            $user_id = $output->data->user_id;
            $password = $output->data->password;
            if(!$user_id || !$password) {
                setCookie('xeak',null,time()+60*60*24*365, '/');
                return;
            }

            $do_auto_login = false;

            // 정보를 바탕으로 키값 비교
            $key = md5($user_id.$password.$_SERVER['REMOTE_ADDR']);

            if($key == $args->autologin_key) {

                // 설정된 change_password_date 확인
                $oModuleModel = &getModel('module');
                $member_config = $oModuleModel->getModuleConfig('member');
                $limit_date = $member_config->change_password_date;

                // change_password_date가 설정되어 있으면 확인
                if($limit_date > 0) {
                    $oMemberModel = &getModel('member');
                    $member_info = $oMemberModel->getMemberInfoByUserID($user_id);

                    if($member_info->change_password_date >= date('YmdHis', strtotime('-'.$limit_date.' day')) ){
                        $do_auto_login = true;
                    }

                } else {
                    $do_auto_login = true;
                }
            }
            
            
            if($do_auto_login) {
                $output = $this->doLogin($user_id);
            } else {
                executeQuery('member.deleteAutologin', $args);
                setCookie('xeak',null,time()+60*60*24*365, '/');
            }
        }

        /**
         * @brief 로그인 시킴
         **/
        function doLogin($user_id, $password = '', $keep_signed = false) {
            $user_id = strtolower($user_id);

            // 로그인 이전에 trigger 호출 (before)
            $trigger_obj->user_id = $user_id;
            $trigger_obj->password = $password;
            $trigger_output = ModuleHandler::triggerCall('member.doLogin', 'before', $trigger_obj);
            if(!$trigger_output->toBool()) return $trigger_output;

            // member model 객체 생성
            $oMemberModel = &getModel('member');

            // user_id 에 따른 정보 가져옴
            $member_info = $oMemberModel->getMemberInfoByUserID($user_id);

            // return 값이 없으면 존재하지 않는 사용자로 지정
            if(!$user_id || strtolower($member_info->user_id) != strtolower($user_id)) return new Object(-1, 'invalid_user_id');

            // 비밀번호 검사
            if($password && !$oMemberModel->isValidPassword($member_info->password, $password)) return new Object(-1, 'invalid_password');

            // denied == 'Y' 이면 알림
            if($member_info->denied == 'Y') {
                $args->member_srl = $member_info->member_srl;
                $output = executeQuery('member.chkAuthMail', $args);
                if ($output->toBool() && $output->data->count != '0') return new Object(-1,'msg_user_not_confirmed');
                return new Object(-1,'msg_user_denied');
            }

            // denied_date가 현 시간보다 적으면 알림
            if($member_info->limit_date && substr($member_info->limit_date,0,8) >= date("Ymd")) return new Object(-1,sprintf(Context::getLang('msg_user_limited'),zdate($member_info->limit_date,"Y-m-d")));

            // 사용자 정보의 최근 로그인 시간을 기록
            $args->member_srl = $member_info->member_srl;
            $output = executeQuery('member.updateLastLogin', $args);

            // 로그인 성공후 trigger 호출 (after)
            $trigger_output = ModuleHandler::triggerCall('member.doLogin', 'after', $member_info);
            if(!$trigger_output->toBool()) return $trigger_output;

            // 자동 로그인 사용시 정보 처리
            if($keep_signed) {
                // 자동 로그인 키 생성
                $autologin_args->autologin_key = md5(strtolower($user_id).$member_info->password.$_SERVER['REMOTE_ADDR']);
                $autologin_args->member_srl = $member_info->member_srl;
                executeQuery('member.deleteAutologin', $autologin_args);
                $autologin_output = executeQuery('member.insertAutologin', $autologin_args);
                if($autologin_output->toBool()) setCookie('xeak',$autologin_args->autologin_key, time()+60*60*24*365, '/');
            }

            $this->setSessionInfo($member_info);

            return $output;
        }

        /**
         * @brief 세션 정보 갱싱 또는 생성
         **/
        function setSessionInfo($member_info = null) {
            $oMemberModel = &getModel('member');

            // 사용자 정보가 넘어오지 않았다면 현재 세션 정보에서 사용자 정보를 추출
            if(!$member_info && $_SESSION['member_srl'] && $oMemberModel->isLogged() ) {
                $member_info = $oMemberModel->getMemberInfoByMemberSrl($_SESSION['member_srl']);

                // 회원정보가 없다면 세션 파기
                if($member_info->member_srl != $_SESSION['member_srl']) {
                    $this->destroySessionInfo();
                    return;
                }
            }

            // 사용중지 아이디이면 세션 파기
            if($member_info->denied=='Y') {
                $this->destroySessionInfo();
                return;
            }

            // 오픈아이디인지 체크 (일단 아이디 형식으로만 결정)
            if(preg_match("/^([_0-9a-zA-Z]+)$/is", $member_info->user_id)) $member_info->is_openid = false;
            else $member_info->is_openid = true;

            // 로그인 처리를 위한 세션 설정
            $_SESSION['is_logged'] = true;
            $_SESSION['ipaddress'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['member_srl'] = $member_info->member_srl;
            $_SESSION['is_admin'] = '';

            // 비밀번호는 세션에 저장되지 않도록 지워줌;;
            //unset($member_info->password);

            // 사용자 그룹 설정
            /*
            if($member_info->group_list) {
                $group_srl_list = array_keys($member_info->group_list);
                $_SESSION['group_srls'] = $group_srl_list;

                // 관리자 그룹일 경우 관리자로 지정
                $oMemberModel = &getModel('member');
                $admin_group = $oMemberModel->getAdminGroup();
                if($admin_group->group_srl && in_array($admin_group->group_srl, $group_srl_list)) $_SESSION['is_admin'] = 'Y';
            }
            */

            // 세션에 로그인 사용자 정보 저장
            $_SESSION['logged_info'] = $member_info;
            Context::set('is_logged', true);
            Context::set('logged_info', $member_info);

            // 사용자의 전용 메뉴 구성 (이 메뉴는 애드온등으로 변경될 수 있음)
            $this->addMemberMenu( 'dispMemberInfo', 'cmd_view_member_info');
            $this->addMemberMenu( 'dispMemberScrappedDocument', 'cmd_view_scrapped_document');
            $this->addMemberMenu( 'dispMemberSavedDocument', 'cmd_view_saved_document');
            $this->addMemberMenu( 'dispMemberOwnDocument', 'cmd_view_own_document');
			$this->addMemberMenu( 'dispMemberOwnComment', 'cmd_view_own_comment');
        }

        /**
         * @brief 로그인한 사용자의 개인화된 메뉴 제공을 위한 method
         * 로그인 정보 출력 위젯 또는 개인화 페이지에서 사용됨
         **/
        function addMemberMenu($act, $str) {
            $logged_info = Context::get('logged_info');

            $logged_info->menu_list[$act] = Context::getLang($str);

            Context::set('logged_info', $logged_info);
            $_SESSION['logged_info'] = $logged_info;
        }

        /**
         * @brief 로그인 회원의 닉네임등을 클릭할때 나타나는 팝업 메뉴를 추가하는 method
         **/
        function addMemberPopupMenu($url, $str, $icon = '', $target = 'self') {
            $member_popup_menu_list = Context::get('member_popup_menu_list');
            if(!is_array($member_popup_menu_list)) $member_popup_menu_list = array();

            $obj->url = $url;
            $obj->str = $str;
            $obj->icon = $icon;
            $obj->target = $target;
            $member_popup_menu_list[] = $obj;

            Context::set('member_popup_menu_list', $member_popup_menu_list);
        }

        /**
         * @brief member 테이블에 사용자 추가
         **/
        function insertMember(&$args, $password_is_hashed = false) {
            // trigger 호출 (before)
            $output = ModuleHandler::triggerCall('member.insertMember', 'before', $args);
            if(!$output->toBool()) return $output;

            // 멤버 설정 정보에서 가입약관 부분을 재확인
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('member');

            $logged_info = Context::get('logged_info');

            // 임시 제한 일자가 있을 경우 제한 일자에 내용 추가
            if($config->limit_day) $args->limit_date = date("YmdHis", time()+$config->limit_day*60*60*24);

            // 입력할 사용자의 아이디를 소문자로 변경
            $args->user_id = strtolower($args->user_id);

            // 필수 변수들의 조절
            if($args->allow_mailing!='Y') $args->allow_mailing = 'N';
            if($args->denied!='Y') $args->denied = 'N';
            $args->allow_message= 'Y';

            if($logged_info->is_admin == 'Y') {
                if($args->is_admin!='Y') $args->is_admin = 'N';
            } else {
                unset($args->is_admin);
            }

            list($args->email_id, $args->email_host) = explode('@', $args->email_address);

            // 홈페이지, 블로그의 주소 검사
            if($args->homepage && !preg_match("/^[a-z]+:\/\//i",$args->homepage)) $args->homepage = 'http://'.$args->homepage;
            if($args->blog && !preg_match("/^[a-z]+:\/\//i",$args->blog)) $args->blog = 'http://'.$args->blog;

            // 모델 객체 생성
            $oMemberModel = &getModel('member');
            // 금지 아이디인지 체크
            if($oMemberModel->isDeniedID($args->user_id)) return new Object(-1,'denied_user_id');

            // 아이디, 닉네임, email address 의 중복 체크
            $member_srl = $oMemberModel->getMemberSrlByUserID($args->user_id);
            if($member_srl) return new Object(-1,'msg_exists_user_id');

            $member_srl = $oMemberModel->getMemberSrlByNickName($args->nick_name);
            if($member_srl) return new Object(-1,'msg_exists_nick_name');

            $member_srl = $oMemberModel->getMemberSrlByEmailAddress($args->email_address);
            if($member_srl) return new Object(-1,'msg_exists_email_address');

            $oDB = &DB::getInstance();
            $oDB->begin();

            // DB에 입력
            $args->member_srl = getNextSequence();
            $args->list_order = -1 * $args->member_srl;
			$args->nick_name = htmlspecialchars($args->nick_name);
			$args->homepage = htmlspecialchars($args->homepage);
			$args->blog = htmlspecialchars($args->blog);
            if($args->password && !$password_is_hashed) $args->password = md5($args->password);
            elseif(!$args->password) unset($args->password);

            $output = executeQuery('member.insertMember', $args);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            // 입력된 그룹 값이 없으면 기본 그룹의 값을 등록
            if(!$args->group_srl_list) {
                $default_group = $oMemberModel->getDefaultGroup(0);

                // 기본 그룹에 추가
                $output = $this->addMemberToGroup($args->member_srl,$default_group->group_srl);
                if(!$output->toBool()) {
                    $oDB->rollback();
                    return $output;
                }

            // 입력된 그룹 값이 있으면 해당 그룹의 값을 등록
            } else {
                $group_srl_list = explode('|@|', $args->group_srl_list);
                for($i=0;$i<count($group_srl_list);$i++) {
                    $output = $this->addMemberToGroup($args->member_srl,$group_srl_list[$i]);

                    if(!$output->toBool()) {
                        $oDB->rollback();
                        return $output;
                    }
                }
            }

            // 메일 인증 모드 사용시(가입된 회원이 denied일 때) 인증 메일 발송
            if ($args->denied == 'Y') {
                // 인증 DB에 데이터를 넣음
                $auth_args->user_id = $args->user_id;
                $auth_args->member_srl = $args->member_srl;
                $auth_args->new_password = $args->password;
                $auth_args->auth_key = md5(rand(0, 999999));
                $auth_args->is_register = 'Y';

                $output = executeQuery('member.insertAuthMail', $auth_args);
                if (!$output->toBool()) {
                    $oDB->rollback();
                    return $output;
                }

                // 메일 내용을 구함
                Context::set('auth_args', $auth_args);
                Context::set('member_info', $args);

                $member_config = $oModuleModel->getModuleConfig('member');
                if(!$member_config->skin) $member_config->skin = "default";
                if(!$member_config->colorset) $member_config->colorset = "white";

                Context::set('member_config', $member_config);

                $tpl_path = sprintf('%sskins/%s', $this->module_path, $member_config->skin);
                if(!is_dir($tpl_path)) $tpl_path = sprintf('%sskins/%s', $this->module_path, 'default');

                $auth_url = getFullUrl('','module','member','act','procMemberAuthAccount','member_srl',$args->member_srl, 'auth_key',$auth_args->auth_key);
                Context::set('auth_url', $auth_url);

                $oTemplate = &TemplateHandler::getInstance();
                $content = $oTemplate->compile($tpl_path, 'confirm_member_account_mail');

                // 사이트 웹마스터 정보를 구함
                $oModuleModel = &getModel('module');
                $member_config = $oModuleModel->getModuleConfig('member');

                // 메일 발송
                $oMail = new Mail();
                $oMail->setTitle( Context::getLang('msg_confirm_account_title') );
                $oMail->setContent($content);
                $oMail->setSender( $member_config->webmaster_name?$member_config->webmaster_name:'webmaster', $member_config->webmaster_email);
                $oMail->setReceiptor( $args->user_name, $args->email_address );
                $oMail->send();
            }

            // trigger 호출 (after)
            if($output->toBool()) {
                $trigger_output = ModuleHandler::triggerCall('member.insertMember', 'after', $args);
                if(!$trigger_output->toBool()) {
                    $oDB->rollback();
                    return $trigger_output;
                }
            }

            $oDB->commit(true);

            $output->add('member_srl', $args->member_srl);
            return $output;
        }

        /**
         * @brief member 정보 수정
         **/
        function updateMember($args) {
            // trigger 호출 (before)
            $output = ModuleHandler::triggerCall('member.updateMember', 'before', $args);
            if(!$output->toBool()) return $output;

            // 모델 객체 생성
            $oMemberModel = &getModel('member');

            $logged_info = Context::get('logged_info');

            // 수정하려는 대상의 원래 정보 가져오기
            $member_info = $oMemberModel->getMemberInfoByMemberSrl($args->member_srl);
            if(!$args->user_id) $args->user_id = $member_info->user_id;

            // 필수 변수들의 조절
            if($args->allow_mailing!='Y') $args->allow_mailing = 'N';
            if($args->allow_message && !in_array($args->allow_message, array('Y','N','F'))) $args->allow_message = 'Y';

            if($logged_info->is_admin == 'Y') {
                if($args->denied!='Y') $args->denied = 'N';
                if($args->is_admin!='Y' && $logged_info->member_srl != $args->member_srl) $args->is_admin = 'N';
            } else {
                unset($args->is_admin);
                unset($args->denied);
            }

            list($args->email_id, $args->email_host) = explode('@', $args->email_address);

            // 홈페이지, 블로그의 주소 검사
            if($args->homepage && !preg_match("/^[a-z]+:\/\//is",$args->homepage)) $args->homepage = 'http://'.$args->homepage;
            if($args->blog && !preg_match("/^[a-z]+:\/\//is",$args->blog)) $args->blog = 'http://'.$args->blog;

            // 아이디, 닉네임, email address 의 중복 체크
            $member_srl = $oMemberModel->getMemberSrlByUserID($args->user_id);
            if($member_srl&&$args->member_srl!=$member_srl) return new Object(-1,'msg_exists_user_id');

            $member_srl = $oMemberModel->getMemberSrlByNickName($args->nick_name);
            if($member_srl&&$args->member_srl!=$member_srl) return new Object(-1,'msg_exists_nick_name');

            $member_srl = $oMemberModel->getMemberSrlByEmailAddress($args->email_address);
            if($member_srl&&$args->member_srl!=$member_srl) return new Object(-1,'msg_exists_email_address');

            $oDB = &DB::getInstance();
            $oDB->begin();

            // DB에 update
            if($args->password) $args->password = md5($args->password);
            else $args->password = $member_info->password;
            if(!$args->user_name) $args->user_name = $member_info->user_name;
			$args->nick_name = htmlspecialchars($args->nick_name);
			$args->homepage = htmlspecialchars($args->homepage);
			$args->blog = htmlspecialchars($args->blog);

			if(!$args->description) $args->description = '';
            $output = executeQuery('member.updateMember', $args);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            // 그룹 정보가 있으면 그룹 정보를 변경
            if($args->group_srl_list) {
                $group_srl_list = explode('|@|', $args->group_srl_list);
                $args->site_srl = 0;

                // 일단 해당 회원의 모든 그룹 정보를 삭제
                $output = executeQuery('member.deleteMemberGroupMember', $args);
                if(!$output->toBool()) {
                    $oDB->rollback();
                    return $output;
                }

                // 하나 하나 루프를 돌면서 입력
                for($i=0;$i<count($group_srl_list);$i++) {
                    $output = $this->addMemberToGroup($args->member_srl,$group_srl_list[$i]);
                    if(!$output->toBool()) {
                        $oDB->rollback();
                        return $output;
                    }
                }
            }

            // trigger 호출 (after)
            if($output->toBool()) {
                $trigger_output = ModuleHandler::triggerCall('member.updateMember', 'after', $args);
                if(!$trigger_output->toBool()) {
                    $oDB->rollback();
                    return $trigger_output;
                }
            }

            $oDB->commit();

            // 세션에 저장
            $member_info = $oMemberModel->getMemberInfoByMemberSrl($args->member_srl);

            $logged_info = Context::get('logged_info');
            if($logged_info->member_srl == $member_srl) {
                $_SESSION['logged_info'] = $member_info;
            }

            $output->add('member_srl', $args->member_srl);
            return $output;
        }

        /**
         * @brief member 비밀번호 수정
         **/
        function updateMemberPassword($args) {
            $output = executeQuery('member.updateChangePasswordDate', $args);
            $args->password = md5($args->password);
            return executeQuery('member.updateMemberPassword', $args);
        }

        /**
         * @brief 사용자 삭제
         **/
        function deleteMember($member_srl) {
            // trigger 호출 (before)
            $trigger_obj->member_srl = $member_srl;
            $output = ModuleHandler::triggerCall('member.deleteMember', 'before', $trigger_obj);
            if(!$output->toBool()) return $output;

            // 모델 객체 생성
            $oMemberModel = &getModel('member');

            // 해당 사용자의 정보를 가져옴
            $member_info = $oMemberModel->getMemberInfoByMemberSrl($member_srl);
            if(!$member_info) return new Object(-1, 'msg_not_exists_member');

            // 관리자의 경우 삭제 불가능
            if($member_info->is_admin == 'Y') return new Object(-1, 'msg_cannot_delete_admin');

            $oDB = &DB::getInstance();
            $oDB->begin();

            $args->member_srl = $member_srl;
            // member_auth_mail에서 해당 항목들 삭제
            $output = executeQuery('member.deleteAuthMail', $args);
            if (!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            //  member_openid에서 해당 항목들 삭제
            $output = executeQuery('member.deleteMemberOpenIDByMemberSrl', $ags);

            //  TODO: 테이블 업그레이드를 하지 않은 경우에 실패할 수 있다.
            /*
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }
            */

            // member_group_member에서 해당 항목들 삭제
            $output = executeQuery('member.deleteMemberGroupMember', $args);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            // member 테이블에서 삭제
            $output = executeQuery('member.deleteMember', $args);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            // trigger 호출 (after)
            if($output->toBool()) {
                $trigger_output = ModuleHandler::triggerCall('member.deleteMember', 'after', $trigger_obj);
                if(!$trigger_output->toBool()) {
                    $oDB->rollback();
                    return $trigger_output;
                }
            }

            $oDB->commit();

            // 이름이미지, 이미지마크, 서명 삭제
            $this->procMemberDeleteImageName();
            $this->procMemberDeleteImageMark();
            $this->delSignature($member_srl);

            return $output;
        }

        /**
         * @brief 모든 세션 정보 파기
         **/
        function destroySessionInfo() {
            if(!$_SESSION || !is_array($_SESSION)) return;
            foreach($_SESSION as $key => $val) {
                $_SESSION[$key] = '';
            }
            session_destroy();
            setcookie(session_name(), '', time()-42000, '/');
            setcookie('sso','',time()-42000, '/');

            if($_COOKIE['xeak']) {
                $args->autologin_key = $_COOKIE['xeak'];
                executeQuery('member.deleteAutologin', $args);
            }
        }
    }
?>
