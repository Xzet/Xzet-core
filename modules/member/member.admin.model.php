<?php
    /**
     * @class  memberAdminModel
     * @author NHN (developers@xpressengine.com)
     * @brief  member module의 admin model class
     **/

    class memberAdminModel extends member {

        /**
         * @brief 자주 호출될거라 예상되는 데이터는 내부적으로 가지고 있자...
         **/
        var $member_info = NULL;
        var $member_groups = NULL;
        var $join_form_list = NULL;

        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief 회원 목록을 구함
         **/
        function getMemberList() {
            // 검색 옵션 정리
            $args->is_admin = Context::get('is_admin')=='Y'?'Y':'';
            $args->is_denied = Context::get('is_denied')=='Y'?'Y':'';
            $args->selected_group_srl = Context::get('selected_group_srl');

            $search_target = trim(Context::get('search_target'));
            $search_keyword = trim(Context::get('search_keyword'));

            if($search_target && $search_keyword) {
                switch($search_target) {
                    case 'user_id' :
                            if($search_keyword) $search_keyword = str_replace(' ','%',$search_keyword);
                            $args->s_user_id = $search_keyword;
                        break;
                    case 'user_name' :
                            if($search_keyword) $search_keyword = str_replace(' ','%',$search_keyword);
                            $args->s_user_name = $search_keyword;
                        break;
                    case 'nick_name' :
                            if($search_keyword) $search_keyword = str_replace(' ','%',$search_keyword);
                            $args->s_nick_name = $search_keyword;
							$args->html_nick_name = htmlspecialchars($search_keyword);
                        break;
                    case 'email_address' :
                            if($search_keyword) $search_keyword = str_replace(' ','%',$search_keyword);
                            $args->s_email_address = $search_keyword;
                        break;
                    case 'regdate' :
                            $args->s_regdate = preg_replace("/[^0-9]/","",$search_keyword);
                        break;
                    case 'regdate_more' :
                            $args->s_regdate_more = substr(preg_replace("/[^0-9]/","",$search_keyword) . '00000000000000',0,14);
                        break;
                    case 'regdate_less' :
                            $args->s_regdate_less = substr(preg_replace("/[^0-9]/","",$search_keyword) . '00000000000000',0,14);
                        break;
                    case 'last_login' :
                            $args->s_last_login = $search_keyword;
                        break;
                    case 'last_login_more' :
                            $args->s_last_login_more = substr(preg_replace("/[^0-9]/","",$search_keyword) . '00000000000000',0,14);
                        break;
                    case 'last_login_less' :
                            $args->s_last_login_less = substr(preg_replace("/[^0-9]/","",$search_keyword) . '00000000000000',0,14);
                        break;
                    case 'extra_vars' :
                            $args->s_extra_vars = ereg_replace("[^0-9]","",$search_keyword);
                        break;
                }
            }

            // selected_group_srl이 있으면 query id를 변경 (table join때문에)
            $sort_order = Context::get('sort_order');
            $sort_index = Context::get('sort_index');
            if($sort_index != 'last_login') {
                $sort_index = "list_order";
            }else{
                $sort_order = 'desc';
            }
            if($args->selected_group_srl) {
                $query_id = 'member.getMemberListWithinGroup';
                $args->sort_index = "member.".$sort_index;
            } else {
                $query_id = 'member.getMemberList';
                $args->sort_index = $sort_index; 
            }
            if($sort_order != "desc") $sort_order = "asc";
            $args->sort_order = $sort_order;
            Context::set('sort_order', $sort_order);

            // 기타 변수들 정리
            $args->page = Context::get('page');
            $args->list_count = 40;
            $args->page_count = 10;
            $output = executeQuery($query_id, $args);
            return $output;
        }

        /**
         * @brief 사이트별 회원 목록을 구함
         **/
        function getSiteMemberList($site_srl, $page = 1) {
            $args->site_srl = $site_srl;
            $args->page = $page;
            $args->list_count = 40;
            $args->page_count = 10;
            $query_id = 'member.getSiteMemberList';
            $output = executeQueryArray($query_id, $args);
            return $output;
        }

        /**
         * @brief 회원 모듈의 특정 스킨에 속한 컬러셋 목록을 return
         **/
        function getMemberAdminColorset() {
            $skin = Context::get('skin');
            if(!$skin) $tpl = "";
            else {
                $oModuleModel = &getModel('module');
                $skin_info = $oModuleModel->loadSkinInfo($this->module_path, $skin);
                Context::set('skin_info', $skin_info);

                $oModuleModel = &getModel('module');
                $config = $oModuleModel->getModuleConfig('member');
                if(!$config->colorset) $config->colorset = "white";
                Context::set('config', $config);

                $oTemplate = &TemplateHandler::getInstance();
                $tpl = $oTemplate->compile($this->module_path.'tpl', 'colorset_list');
            }

            $this->add('tpl', $tpl);
        }

    }
?>
