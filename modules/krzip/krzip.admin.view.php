<?php
    /**
     * @class  krzipAdminView
     * @author NHN (developers@xpressengine.com)
     * @brief  krzip 모듈의 admin view class
     **/

    class krzipAdminView extends krzip {

        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief 설정
         **/
        function dispKrzipAdminConfig() {
            // 설정 정보를 받아옴 (module model 객체를 이용)
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('krzip');
            Context::set('config',$config);

			//Security
			$security = new Security();
			$security->encodeHTML('config..');
			
            // 템플릿 파일 지정
            $this->setTemplatePath($this->module_path.'tpl');
            $this->setTemplateFile('index');
        }


    }
?>
