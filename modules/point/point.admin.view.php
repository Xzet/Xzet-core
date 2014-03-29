<?php
    /**
     * @class  pointAdminView
     * @author NHN (developers@xpressengine.com)
     * @brief  point모듈의 admin view class
     **/

    class pointAdminView extends point {

        /**
         * @brief 초기화
         **/
        function init() {
            // 설정 정보 가져오기
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('point');

            // 설정 변수 지정
            Context::set('config', $config);				

			//Security
			$security = new Security();			
			$security->encodeHTML('config.point_name','config.level_icon');
			$security->encodeHTML('module_info..');			
			
            // template path지정
            $this->setTemplatePath($this->module_path.'tpl');
        }

        /**
         * @brief 기본 설정
         **/
        function dispPointAdminConfig() {
            // 레벨 아이콘 목록 구함
            $level_icon_list = FileHandler::readDir("./modules/point/icons");
            Context::set('level_icon_list', $level_icon_list);

            // 그룹 목록 가져오기
            $oMemberModel = &getModel('member');
            $group_list = $oMemberModel->getGroups();
            $selected_group_list = array();
            if(count($group_list)) {
                foreach($group_list as $key => $val) {
                    $selected_group_list[$key] = $val;
                }
            }			
            Context::set('group_list', $selected_group_list);

			//Security
			$security = new Security();			
			$security->encodeHTML('group_list..title','group_list..description');			
			
            // 템플릿 지정
            $this->setTemplateFile('config');
        }

        /**
         * @brief 모듈별 점수 지정
         **/
        function dispPointAdminModuleConfig() {
            // mid 목록 가져오기
            $oModuleModel = &getModel('module');
            $mid_list = $oModuleModel->getMidList();
            Context::set('mid_list', $mid_list);

            Context::set('module_config', $oModuleModel->getModulePartConfigs('point'));

			//Security
			$security = new Security();			
			$security->encodeHTML('mid_list..browser_title','mid_list..mid');			
			
            // 템플릿 지정
            $this->setTemplateFile('module_config');
        }

        /**
         * @brief 회원 포인트순 목록 가져오기
         **/
        function dispPointAdminPointList() {
            $oPointModel = &getModel('point');

            $args->list_count = 20;
            $args->page = Context::get('page');

            $output = $oPointModel->getMemberList($args);

            // 템플릿에 쓰기 위해서 context::set
            Context::set('total_count', $output->total_count);
            Context::set('total_page', $output->total_page);
            Context::set('page', $output->page);
            Context::set('member_list', $output->data);
            Context::set('page_navigation', $output->page_navigation);

            // 멤버모델 객체 생성
            $oMemberModel = &getModel('member');

            // group 목록 가져오기
            $this->group_list = $oMemberModel->getGroups();
            Context::set('group_list', $this->group_list);
			
			//Security
			$security = new Security();			
			$security->encodeHTML('group_list..title','group_list..description');
			$security->encodeHTML('member_list..');			

            // 템플릿 지정
            $this->setTemplateFile('member_list');
        }
    }
?>
