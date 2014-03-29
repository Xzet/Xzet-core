<?php
    /**
     * @class  pageWap
     * @author NHN (developers@xpressengine.com)
     * @brief  page 모듈의 wap class
     **/

    class pageWap extends page {

        /**
         * @brief wap procedure method
         *
         * 페이지 모듈은 형식이 정해져 있지 않기에 전체 컨텐츠를 mobile class에서 제어해서 출력함
         **/
        function procWAP(&$oMobile) {
            // 권한 체크
            if(!$this->grant->access) return $oMobile->setContent(Context::getLang('msg_not_permitted'));

            // 위젯의 내용을 추출함
            $oWidgetController = &getController('widget');
            $content = $oWidgetController->transWidgetCode($this->module_info->content);
            $oMobile->setContent($content);
        }

    }
?>
