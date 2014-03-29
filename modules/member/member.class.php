<?php
    /**
     * @class  member
     * @author NHN (developers@xpressengine.com)
     * @brief  member module의 high class
     **/
    class member extends ModuleObject {

        /**
         * @brief constructor
         **/
        function member() {
            if(!Context::isInstalled()) return;

            $oModuleModel = &getModel('module');
            $member_config = $oModuleModel->getModuleConfig('member');

            // SSL 사용시 회원가입/정보/비밀번호등과 관련된 action에 대해 SSL 전송하도록 지정
            if(Context::get('_use_ssl') == 'optional') {
                Context::addSSLAction('dispMemberModifyPassword');
                Context::addSSLAction('dispMemberSignUpForm');
                Context::addSSLAction('dispMemberModifyInfo');
                Context::addSSLAction('dispMemberResendAuthMail');
                Context::addSSLAction('dispMemberLoginForm');
                Context::addSSLAction('dispMemberFindAccount');
                Context::addSSLAction('dispMemberLeave');
                Context::addSSLAction('procMemberLogin');
                Context::addSSLAction('procMemberModifyPassword');
                Context::addSSLAction('procMemberInsert');
                Context::addSSLAction('procMemberModifyInfo');
                Context::addSSLAction('procMemberFindAccount');
                Context::addSSLAction('procMemberResendAuthMail');
                Context::addSSLAction('procMemberLeave');
            }
        }

        /**
         * @brief 설치시 추가 작업이 필요할시 구현
         **/
        function moduleInstall() {
            // action forward에 등록 (관리자 모드에서 사용하기 위함)
            $oModuleController = &getController('module');

            $oDB = &DB::getInstance();
            $oDB->addIndex("member_group","idx_site_title", array("site_srl","title"),true);

            $oModuleModel = &getModel('module');
            $args = $oModuleModel->getModuleConfig('member');

            // 기본 정보를 세팅
            $args->enable_join = 'Y';
            if(!$args->enable_openid) $args->enable_openid = 'N';
            if(!$args->enable_auth_mail) $args->enable_auth_mail = 'N';
            if(!$args->image_name) $args->image_name = 'Y';
            if(!$args->image_mark) $args->image_mark = 'Y';
            if(!$args->profile_image) $args->profile_image = 'Y';
            if(!$args->image_name_max_width) $args->image_name_max_width = '90';
            if(!$args->image_name_max_height) $args->image_name_max_height = '20';
            if(!$args->image_mark_max_width) $args->image_mark_max_width = '20';
            if(!$args->image_mark_max_height) $args->image_mark_max_height = '20';
            if(!$args->profile_image_max_width) $args->profile_image_max_width = '80';
            if(!$args->profile_image_max_height) $args->profile_image_max_height = '80';
            if($args->group_image_mark!='Y') $args->group_image_mark = 'N';

            $oModuleController->insertModuleConfig('member',$args);

            // 멤버 컨트롤러 객체 생성
            $oMemberModel = &getModel('member');
            $oMemberController = &getController('member');
            $oMemberAdminController = &getAdminController('member');

            $groups = $oMemberModel->getGroups();
            if(!count($groups)) {
                // 관리자, 정회원, 준회원 그룹을 입력
                $group_args->title = Context::getLang('admin_group');
                $group_args->is_default = 'N';
                $group_args->is_admin = 'Y';
                $output = $oMemberAdminController->insertGroup($group_args);

                unset($group_args);
                $group_args->title = Context::getLang('default_group_1');
                $group_args->is_default = 'Y';
                $group_args->is_admin = 'N';
                $output = $oMemberAdminController->insertGroup($group_args);

                unset($group_args);
                $group_args->title = Context::getLang('default_group_2');
                $group_args->is_default = 'N';
                $group_args->is_admin = 'N';
                $oMemberAdminController->insertGroup($group_args);
            }

            // 관리자 정보 세팅
            $admin_args->is_admin = 'Y';
            $output = executeQuery('member.getMemberList', $admin_args);
            if(!$output->data) {
                $admin_info = Context::gets('user_id','password','nick_name','user_name', 'email_address');
                if($admin_info->user_id) {
                    // 관리자 정보 입력
                    $oMemberAdminController->insertAdmin($admin_info);

                    // 로그인 처리시킴
                    $output = $oMemberController->doLogin($admin_info->user_id);
                }
            }

            // 금지 아이디 등록 (기본 + 모듈명)
            $oModuleModel = &getModel('module');
            $module_list = $oModuleModel->getModuleList();
            foreach($module_list as $key => $val) {
                $oMemberAdminController->insertDeniedID($val->module,'');
            }
            $oMemberAdminController->insertDeniedID('www','');
            $oMemberAdminController->insertDeniedID('root','');
            $oMemberAdminController->insertDeniedID('administrator','');
            $oMemberAdminController->insertDeniedID('telnet','');
            $oMemberAdminController->insertDeniedID('ftp','');
            $oMemberAdminController->insertDeniedID('http','');

            // member 에서 사용할 cache디렉토리 생성
            FileHandler::makeDir('./files/member_extra_info/image_name');
            FileHandler::makeDir('./files/member_extra_info/image_mark');
            FileHandler::makeDir('./files/member_extra_info/profile_image');
            FileHandler::makeDir('./files/member_extra_info/signature');

            $oDB->addIndex("member_openid_association","idx_assoc", array("server_url(255)","handle"), false);
            return new Object();
        }

        /**
         * @brief 설치가 이상이 없는지 체크하는 method
         **/
        function checkUpdate() {
            $oDB = &DB::getInstance();
            $oModuleModel = &getModel('module');

            // member 디렉토리 체크 (2007. 8. 11 추가)
            if(!is_dir("./files/member_extra_info")) return true;

            // member 디렉토리 체크 (2007. 10. 22 추가)
            if(!is_dir("./files/member_extra_info/profile_image")) return true;

            // member_auth_mail 테이블에 is_register 필드 추가 (2008. 04. 22)
            $act = $oDB->isColumnExists("member_auth_mail", "is_register");
            if(!$act) return true;

            // member_group_member 테이블에 site_srl 추가 (2008. 11. 15)
            if(!$oDB->isColumnExists("member_group_member", "site_srl")) return true;
            if(!$oDB->isColumnExists("member_group", "site_srl")) return true;
            if($oDB->isIndexExists("member_group","uni_member_group_title")) return true;

			// Add a column for list_order (05/18/2011)
            if(!$oDB->isColumnExists("member_group", "list_order")) return true;

            // image_mark 추가 (2009. 02. 14)
            if(!$oDB->isColumnExists("member_group", "image_mark")) return true;

            // password 유효기간을 위한 추가
            if(!$oDB->isColumnExists("member", "change_password_date")) return true;

            // 비밀번호 찾기 질문/답변을 위한 추가
            if(!$oDB->isColumnExists("member", "find_account_question")) return true;
            if(!$oDB->isColumnExists("member", "find_account_answer")) return true;

            if(!$oDB->isColumnExists("member", "list_order")) return true;
            if(!$oDB->isIndexExists("member","idx_list_order")) return true;

            return false;
        }

        /**
         * @brief 업데이트 실행
         **/
        function moduleUpdate() {
            $oDB = &DB::getInstance();
            $oModuleController = &getController('module');

            // member 디렉토리 체크
            FileHandler::makeDir('./files/member_extra_info/image_name');
            FileHandler::makeDir('./files/member_extra_info/image_mark');
            FileHandler::makeDir('./files/member_extra_info/signature');
            FileHandler::makeDir('./files/member_extra_info/profile_image');

            // DB 필드 추가
            if (!$oDB->isColumnExists("member_auth_mail", "is_register")) {
                $oDB->addColumn("member_auth_mail", "is_register", "char", 1, "N", true);
            }

            // member_group_member 테이블에 site_srl 추가 (2008. 11. 15)
            if (!$oDB->isColumnExists("member_group_member", "site_srl")) {
                $oDB->addColumn("member_group_member", "site_srl", "number", 11, 0, true);
                $oDB->addIndex("member_group_member", "idx_site_srl", "site_srl", false);
            }
            if (!$oDB->isColumnExists("member_group", "site_srl")) {
                $oDB->addColumn("member_group", "site_srl", "number", 11, 0, true);
                $oDB->addIndex("member_group","idx_site_title", array("site_srl","title"),true);
            }
            if($oDB->isIndexExists("member_group","uni_member_group_title")) {
                $oDB->dropIndex("member_group","uni_member_group_title",true);
            }

            // Add a column(list_order) to "member_group" table (05/18/2011)
            if (!$oDB->isColumnExists("member_group", "list_order")) {
                $oDB->addColumn("member_group", "list_order", "number", 11, '', true);
                $oDB->addIndex("member_group","idx_list_order", "list_order",false);
                $output = executeQuery('member.updateAllMemberGroupListOrder');
            }

            // image_mark 추가 (2009. 02. 14)
            if(!$oDB->isColumnExists("member_group", "image_mark")) {
                $oDB->addColumn("member_group", "image_mark", "text");
            }

            // password 유효기간을 위한 추가
            if(!$oDB->isColumnExists("member", "change_password_date")) {
                $oDB->addColumn("member", "change_password_date", "date");
                executeQuery('member.updateAllChangePasswordDate');
            }

            // 비밀번호 찾기 질문/답변을 위한 추가
            if(!$oDB->isColumnExists("member", "find_account_question")) {
                $oDB->addColumn("member", "find_account_question", "number", 11);
            }
            if(!$oDB->isColumnExists("member", "find_account_answer")) {
                $oDB->addColumn("member", "find_account_answer", "varchar", 250);
            }

            if(!$oDB->isColumnExists("member", "list_order")) {
                $oDB->addColumn("member", "list_order", "number", 11);
                set_time_limit(0);
                $args->list_order = 'member_srl';
                executeQuery('member.updateMemberListOrderAll',$args);
                executeQuery('member.updateMemberListOrderAll');
            }
            if(!$oDB->isIndexExists("member","idx_list_order")) {
                $oDB->addIndex("member","idx_list_order", array("list_order"));
            }

            return new Object(0, 'success_updated');
        }

        /**
         * @brief 캐시 파일 재생성
         **/
        function recompileCache() {
            set_include_path(_XE_PATH_."modules/member/php-openid-1.2.3");
            require_once('Auth/OpenID/XEStore.php');
            $store = new Auth_OpenID_XEStore();
            $store->reset();
        }
    }
?>
