<?php
    /**
     * @class  fileView
     * @author NHN (developers@xpressengine.com)
     * @brief  file module의 view class
     *
     *
     *
     **/

    class fileView extends file {

        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief 서비스형 모듈의 추가 설정을 위한 부분
         * file의 사용 형태에 대한 설정만 받음
         **/
        function triggerDispFileAdditionSetup(&$obj) {
            $current_module_srl = Context::get('module_srl');
            $current_module_srls = Context::get('module_srls');

            if(!$current_module_srl && !$current_module_srls) {
                // 선택된 모듈의 정보를 가져옴
                $current_module_info = Context::get('current_module_info');
                $current_module_srl = $current_module_info->module_srl;
                if(!$current_module_srl) return new Object();
            }

            // 선택된 모듈의 file설정을 가져옴
            $oFileModel = &getModel('file');
            $file_config = $oFileModel->getFileModuleConfig($current_module_srl);
            Context::set('file_config', $file_config);

            // 그룹의 설정을 위한 권한 가져오기
            $oMemberModel = &getModel('member');
            $site_module_info = Context::get('site_module_info');
            $group_list = $oMemberModel->getGroups($site_module_info->site_srl);
            Context::set('group_list', $group_list);

            // 템플릿 파일 지정
            $oTemplate = &TemplateHandler::getInstance();
            $tpl = $oTemplate->compile($this->module_path.'tpl', 'file_module_config');
            $obj .= $tpl;

            return new Object();
        }
    }
?>
