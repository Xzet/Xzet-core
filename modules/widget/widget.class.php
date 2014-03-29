<?php
    /**
     * @class  widget
     * @author NHN (developers@xpressengine.com)
     * @brief  widget 모듈의 high class
     **/

    class widget extends ModuleObject {

        /**
         * @brief 설치시 추가 작업이 필요할시 구현
         **/
        function moduleInstall() {
            // widget 에서 사용할 cache디렉토리 생성
            FileHandler::makeDir('./files/cache/widget');
			FileHandler::makeDir('./files/cache/widget_cache');

            // widget compile을 위한 display.after 트리거 추가
            $oModuleController = &getController('module');
            $oModuleController->insertTrigger('display', 'widget', 'controller', 'triggerWidgetCompile', 'before');

            return new Object();
        }

        /**
         * @brief 설치가 이상이 없는지 체크하는 method
         **/
        function checkUpdate() {
            $oModuleModel = &getModel('module');

            // widget compile을 위한 display.after 트리거 추가 (2009. 04. 14)
            if(!$oModuleModel->getTrigger('display', 'widget', 'controller', 'triggerWidgetCompile', 'before')) return true;

            return false;
        }

        /**
         * @brief 업데이트 실행
         **/
        function moduleUpdate() {
            $oModuleModel = &getModel('module');
            $oModuleController = &getController('module');

            // widget compile을 위한 display.after 트리거 추가 (2009. 04. 14)
            if(!$oModuleModel->getTrigger('display', 'widget', 'controller', 'triggerWidgetCompile', 'before')) {
                $oModuleController->insertTrigger('display', 'widget', 'controller', 'triggerWidgetCompile', 'before');
            }

            return new Object(0, 'success_updated');
        }

        /**
         * @brief 캐시 파일 재생성
         **/
        function recompileCache() {
            // widget 정보를 담은 캐시 파일 삭제
            FileHandler::removeFilesInDir("./files/cache/widget");

            // widget 생성 캐시 파일 삭제
            FileHandler::removeFilesInDir("./files/cache/widget_cache");
        }

    }
?>
