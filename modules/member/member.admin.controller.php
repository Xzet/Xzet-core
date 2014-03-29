<?php
    /**
     * @class  memberAdminController
     * @author NHN (developers@xpressengine.com)
     * @brief  member module의 admin controller class
     **/

    class memberAdminController extends member {

        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief 사용자 추가 (관리자용)
         **/
        function procMemberAdminInsert() {
            if(Context::getRequestMethod() == "GET") return new Object(-1, "msg_invalid_request");
            // 필수 정보들을 미리 추출
            $args = Context::gets('member_srl','user_id','user_name','nick_name','homepage','blog','birthday','email_address','password','allow_mailing','allow_message','denied','is_admin','description','group_srl_list','limit_date');

            // 넘어온 모든 변수중에서 몇가지 불필요한 것들 삭제
            $all_args = Context::getRequestVars();
            unset($all_args->module);
            unset($all_args->act);
            if(!isset($args->limit_date)) $args->limit_date = "";

            // 모든 request argument에서 필수 정보만 제외 한 후 추가 데이터로 입력
            $extra_vars = delObjectVars($all_args, $args);
            $args->extra_vars = serialize($extra_vars);

            // member_srl이 넘어오면 원 회원이 있는지 확인
            if($args->member_srl) {
                // 멤버 모델 객체 생성
                $oMemberModel = &getModel('member');

                // 회원 정보 구하기
                $member_info = $oMemberModel->getMemberInfoByMemberSrl($args->member_srl);

                // 만약 원래 회원이 없으면 새로 입력하기 위한 처리
                if($member_info->member_srl != $args->member_srl) unset($args->member_srl);
            }

            $oMemberController = &getController('member');

            // member_srl의 값에 따라 insert/update
            if(!$args->member_srl) {
                $output = $oMemberController->insertMember($args);
                $msg_code = 'success_registed';
            } else {
                $output = $oMemberController->updateMember($args);
                $msg_code = 'success_updated';
            }

            if(!$output->toBool()) return $output;

            // 서명 저장
            $signature = Context::get('signature');
            $oMemberController->putSignature($args->member_srl, $signature);

            // 결과 리턴
            $this->add('member_srl', $args->member_srl);
            $this->setMessage($msg_code);
        }

        /**
         * @brief 사용자 삭제 (관리자용)
         **/
        function procMemberAdminDelete() {
            // 일단 입력된 값들을 모두 받아서 db 입력항목과 그외 것으로 분리
            $member_srl = Context::get('member_srl');

            $oMemberController = &getController('member');
            $output = $oMemberController->deleteMember($member_srl);
            if(!$output->toBool()) return $output;

            $this->add('page',Context::get('page'));
            $this->setMessage("success_deleted");
        }

        /**
         * @brief 회원 관리용 기본 정보의 추가
         **/
        function procMemberAdminInsertConfig() {
            // 기본 정보를 받음
            $args = Context::gets(
                'webmaster_name', 'webmaster_email',
                'skin', 'colorset',
                'editor_skin', 'editor_colorset',
                'enable_openid', 'enable_join', 'enable_confirm', 'limit_day',
                'after_login_url', 'after_logout_url', 'redirect_url', 'agreement',
                'profile_image', 'profile_image_max_width', 'profile_image_max_height',
                'image_name', 'image_name_max_width', 'image_name_max_height',
                'image_mark', 'image_mark_max_width', 'image_mark_max_height',
                'group_image_mark', 'group_image_mark_max_width', 'group_image_mark_max_height',
                'signature','signature_max_height','change_password_date'
            );

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
            if($args->signature!='Y') $args->signature = 'N';
            if(!trim(strip_tags($args->agreement))) $args->agreement = null;
            $args->limit_day = (int)$args->limit_day;
            if(!$args->change_password_date) $args->change_password_date = 0; 

            $oMemberController = &getController('member');
            $output = $oMemberController->setMemberConfig($args);
            return $output;
        }

        /**
         * @brief 사용자 그룹 추가
         **/
        function procMemberAdminInsertGroup() {
            $args = Context::gets('title','description','is_default','image_mark');
            $output = $this->insertGroup($args);
            if(!$output->toBool()) return $output;

            $this->add('group_srl','');
            $this->add('page',Context::get('page'));
            $this->setMessage('success_registed');
        }

        /**
         * @brief 사용자 그룹 정보 수정
         **/
        function procMemberAdminUpdateGroup() {
            $group_srl = Context::get('group_srl');
            $mode = Context::get('mode');

            switch($mode) {
                case 'delete' :
                        $output = $this->deleteGroup($group_srl);
                        if(!$output->toBool()) return $output;
                        $msg_code = 'success_deleted';
                    break;
                case 'update' :
                        $args = Context::gets('group_srl','title','description','is_default','image_mark');
                        $args->site_srl = 0;
                        $output = $this->updateGroup($args);
                        if(!$output->toBool()) return $output;
                        $msg_code = 'success_updated';
                    break;
            }

            $this->add('group_srl','');
            $this->add('page',Context::get('page'));
            $this->setMessage($msg_code);
        }

        /**
         * @brief 가입 항목 추가
         **/
        function procMemberAdminInsertJoinForm() {
            $args->member_join_form_srl = Context::get('member_join_form_srl');

            $args->column_type = Context::get('column_type');
            $args->column_name = strtolower(Context::get('column_name'));
            $args->column_title = Context::get('column_title');
            $args->default_value = explode('|@|', Context::get('default_value'));
            $args->is_active = Context::get('is_active');
            if(!in_array(strtoupper($args->is_active), array('Y','N'))) $args->is_active = 'N';
            $args->required = Context::get('required');
            if(!in_array(strtoupper($args->required), array('Y','N'))) $args->required = 'N';
            $args->description = Context::get('description');

            // 기본값의 정리
            if(in_array($args->column_type, array('checkbox','select','radio')) && count($args->default_value) ) {
                $args->default_value = serialize($args->default_value);
            } else {
                $args->default_value = '';
            }

            // member_join_form_srl이 있으면 수정, 없으면 추가
            if(!$args->member_join_form_srl){
                $args->list_order = getNextSequence();
                $output = executeQuery('member.insertJoinForm', $args);
            }else{
                $output = executeQuery('member.updateJoinForm', $args);
            }

            if(!$output->toBool()) return $output;

            $this->add('act','dispJoinForm');
            $this->setMessage('success_registed');
        }

        /**
         * @brief 가입 항목의 상/하 이동 및 내용 수정
         **/
        function procMemberAdminUpdateJoinForm() {
            $member_join_form_srl = Context::get('member_join_form_srl');
            $mode = Context::get('mode');

            switch($mode) {
                case 'up' :
                        $output = $this->moveJoinFormUp($member_join_form_srl);
                        $msg_code = 'success_moved';
                    break;
                case 'down' :
                        $output = $this->moveJoinFormDown($member_join_form_srl);
                        $msg_code = 'success_moved';
                    break;
                case 'delete' :
                        $output = $this->deleteJoinForm($member_join_form_srl);
                        $msg_code = 'success_deleted';
                    break;
                case 'update' :
                    break;
            }
            if(!$output->toBool()) return $output;

            $this->setMessage($msg_code);
        }

        /**
         * @brief 선택된 회원들을 일괄 삭제
         */
        function procMemberAdminDeleteMembers() {
            $target_member_srls = Context::get('target_member_srls');
            if(!$target_member_srls) return new Object(-1, 'msg_invalid_request');
            $member_srls = explode(',', $target_member_srls);
            $oMemberController = &getController('member');

            foreach($member_srls as $member) {
                $output = $oMemberController->deleteMember($member);
                if(!$output->toBool()) {
                    $this->setMessage('failed_deleted');
                    return $output;
                }
            }

            $this->setMessage('success_deleted');
        }

        /**
         * @brief 선택된 회원들의 그룹을 일괄 변경
         **/
        function procMemberAdminUpdateMembersGroup() {
            $member_srl = Context::get('member_srl');
            if(!$member_srl) return new Object(-1,'msg_invalid_request');
            $member_srls = explode(',',$member_srl);

            $group_srl = Context::get('group_srls');
            $group_srls = explode('|@|', $group_srl);
            if(!$group_srl) return new Object(-1,'msg_check_group');

            $oDB = &DB::getInstance();
            $oDB->begin();

            // 선택된 회원들의 그룹을 삭제
            $args->member_srl = $member_srl;
            $output = executeQuery('member.deleteMembersGroup', $args);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            // 선택된 그룹으로 추가
            $group_count = count($group_srls);
            $member_count = count($member_srls);
            for($j=0;$j<$group_count;$j++) {
                $group_srl = (int)trim($group_srls[$j]);
                if(!$group_srl) continue;
                for($i=0;$i<$member_count;$i++) {
                    $member_srl = (int)trim($member_srls[$i]);
                    if(!$member_srl) continue;

                    $args = null;
                    $args->member_srl = $member_srl;
                    $args->group_srl = $group_srl;

                    $output = executeQuery('member.addMemberToGroup', $args);
                    if(!$output->toBool()) {
                        $oDB->rollback();
                        return $output;
                    }
                }
            }
            $oDB->commit();

            $this->setMessage('success_updated');
        }

        /**
         * @brief 금지 아이디 추가
         **/
        function procMemberAdminInsertDeniedID() {
            $user_id = Context::get('user_id');
            $description = Context::get('description');

            $output = $this->insertDeniedID($user_id, $description);
            if(!$output->toBool()) return $output;

            $this->add('group_srl','');
            $this->add('page',Context::get('page'));
            $this->setMessage('success_registed');
        }

        /**
         * @brief 금지 아이디 업데이트
         **/
        function procMemberAdminUpdateDeniedID() {
            $user_id = Context::get('user_id');
            $mode = Context::get('mode');

            switch($mode) {
                case 'delete' :
                        $output = $this->deleteDeniedID($user_id);
                        if(!$output->toBool()) return $output;
                        $msg_code = 'success_deleted';
                    break;
            }

            $this->add('page',Context::get('page'));
            $this->setMessage($msg_code);
        }

        /**
         * @brief 관리자를 추가한다
         **/
        function insertAdmin($args) {
            // 관리자임을 설정
            $args->is_admin = 'Y';

            // 관리자 그룹을 구해와서 설정
            $oMemberModel = &getModel('member');
            $admin_group = $oMemberModel->getAdminGroup();
            $args->group_srl_list = $admin_group->group_srl;

            $oMemberController = &getController('member');
            return $oMemberController->insertMember($args);
        }

        /**
         * @brief 회원의 그룹값을 변경
         **/
        function changeGroup($source_group_srl, $target_group_srl) {
            $args->source_group_srl = $source_group_srl;
            $args->target_group_srl = $target_group_srl;

            return executeQuery('member.changeGroup', $args);
        }

        /**
         * @brief 그룹 등록
         **/
        function insertGroup($args) {
            if(!$args->site_srl) $args->site_srl = 0;
            // is_default값을 체크, Y일 경우 일단 모든 is_default에 대해서 N 처리
            if($args->is_default!='Y') {
                $args->is_default = 'N';
            } else {
                $output = executeQuery('member.updateGroupDefaultClear', $args);
                if(!$output->toBool()) return $output;
            }

			if (!$args->group_srl) $args->group_srl = getNextSequence();
            return executeQuery('member.insertGroup', $args);
        }

        /**
         * @brief 그룹 정보 수정
         **/
        function updateGroup($args) {
            // is_default값을 체크, Y일 경우 일단 모든 is_default에 대해서 N 처리
            if($args->is_default!='Y') $args->is_default = 'N';
            else {
                $output = executeQuery('member.updateGroupDefaultClear', $args);
                if(!$output->toBool()) return $output;
            }

            return executeQuery('member.updateGroup', $args);
        }

        /**
         * 그룹 삭제
         **/
        function deleteGroup($group_srl, $site_srl = null) {
            // 멤버모델 객체 생성
            $oMemberModel = &getModel('member');

            // 삭제 대상 그룹을 가져와서 체크 (is_default == 'Y'일 경우 삭제 불가)
            $group_info = $oMemberModel->getGroup($group_srl);

            if(!$group_info) return new Object(-1, 'lang->msg_not_founded');
            if($group_info->is_default == 'Y') return new Object(-1, 'msg_not_delete_default');

            // is_default == 'Y'인 그룹을 가져옴
            $default_group = $oMemberModel->getDefaultGroup($site_srl);
            $default_group_srl = $default_group->group_srl;

            // default_group_srl로 변경
            $this->changeGroup($group_srl, $default_group_srl);

            $args->group_srl = $group_srl;
            return executeQuery('member.deleteGroup', $args);
        }


        function procMemberAdminUpdateGroupOrder() {
			$vars = Context::getRequestVars();
			
			foreach($vars->group_srls as $key => $val){
				$args->group_srl = $val;
				$args->list_order = $key + 1;
				executeQuery('member.updateMemberGroupListOrder', $args);
			}

			header(sprintf('Location:%s', getNotEncodedUrl('', 'module', 'admin', 'act', 'dispMemberAdminGroupList')));
        }

        /**
         * @brief 금지아이디 등록
         **/
        function insertDeniedID($user_id, $description = '') {
            $args->user_id = $user_id;
            $args->description = $description;
            $args->list_order = -1*getNextSequence();

            return executeQuery('member.insertDeniedID', $args);
        }

        /**
         * @brief 금지아이디 삭제
         **/
        function deleteDeniedID($user_id) {
            $args->user_id = $user_id;
            return executeQuery('member.deleteDeniedID', $args);
        }

        /**
         * @brief 가입폼 항목을 삭제
         **/
        function deleteJoinForm($member_join_form_srl) {
            $args->member_join_form_srl = $member_join_form_srl;
            $output = executeQuery('member.deleteJoinForm', $args);
            return $output;
        }

        /**
         * @brief 가입항목을 상단으로 이동
         **/
        function moveJoinFormUp($member_join_form_srl) {
            $oMemberModel = &getModel('member');

            // 선택된 가입항목의 정보를 구한다
            $args->member_join_form_srl = $member_join_form_srl;
            $output = executeQuery('member.getJoinForm', $args);

            $join_form = $output->data;
            $list_order = $join_form->list_order;

            // 전체 가입항목 목록을 구한다
            $join_form_list = $oMemberModel->getJoinFormList();
            $join_form_srl_list = array_keys($join_form_list);
            if(count($join_form_srl_list)<2) return new Object();

            $prev_member_join_form = NULL;
            foreach($join_form_list as $key => $val) {
                if($val->member_join_form_srl == $member_join_form_srl) break;
                $prev_member_join_form = $val;
            }

            // 이전 가입항목가 없으면 그냥 return
            if(!$prev_member_join_form) return new Object();

            // 선택한 가입항목의 정보
            $cur_args->member_join_form_srl = $member_join_form_srl;
            $cur_args->list_order = $prev_member_join_form->list_order;

            // 대상 가입항목의 정보
            $prev_args->member_join_form_srl = $prev_member_join_form->member_join_form_srl;
            $prev_args->list_order = $list_order;

            // DB 처리
            $output = executeQuery('member.updateMemberJoinFormListorder', $cur_args);
            if(!$output->toBool()) return $output;

            executeQuery('member.updateMemberJoinFormListorder', $prev_args);
            if(!$output->toBool()) return $output;

            return new Object();
        }

        /**
         * @brief 가입항목을 하단으로 이동
         **/
        function moveJoinFormDown($member_join_form_srl) {
            $oMemberModel = &getModel('member');

            // 선택된 가입항목의 정보를 구한다
            $args->member_join_form_srl = $member_join_form_srl;
            $output = executeQuery('member.getJoinForm', $args);

            $join_form = $output->data;
            $list_order = $join_form->list_order;

            // 전체 가입항목 목록을 구한다
            $join_form_list = $oMemberModel->getJoinFormList();
            $join_form_srl_list = array_keys($join_form_list);
            if(count($join_form_srl_list)<2) return new Object();

            for($i=0;$i<count($join_form_srl_list);$i++) {
                if($join_form_srl_list[$i]==$member_join_form_srl) break;
            }

            $next_member_join_form_srl = $join_form_srl_list[$i+1];

            // 이전 가입항목가 없으면 그냥 return
            if(!$next_member_join_form_srl) return new Object();
            $next_member_join_form = $join_form_list[$next_member_join_form_srl];

            // 선택한 가입항목의 정보
            $cur_args->member_join_form_srl = $member_join_form_srl;
            $cur_args->list_order = $next_member_join_form->list_order;

            // 대상 가입항목의 정보
            $next_args->member_join_form_srl = $next_member_join_form->member_join_form_srl;
            $next_args->list_order = $list_order;

            // DB 처리
            $output = executeQuery('member.updateMemberJoinFormListorder', $cur_args);
            if(!$output->toBool()) return $output;

            $output = executeQuery('member.updateMemberJoinFormListorder', $next_args);
            if(!$output->toBool()) return $output;

            return new Object();
        }
    }
?>
