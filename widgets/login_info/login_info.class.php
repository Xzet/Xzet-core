<?php
    /**
     * @class login_info
     * @author NHN (developers@xpressengine.com)
     * @version 0.1
     * @brief 로그인 폼을 출력하는 위젯
     *
     * $logged_info를 이용하며 이는 미리 설정되어 있음
     **/

    class login_info extends WidgetHandler {

        /**
         * @brief 위젯의 실행 부분
         * ./widgets/위젯/conf/info.xml에 선언한 extra_vars를 args로 받는다
         * 결과를 만든후 print가 아니라 return 해주어야 한다
         **/
        function proc($args) {
            // 템플릿의 스킨 경로를 지정 (skin, colorset에 따른 값을 설정)
            $tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
            Context::set('colorset', $args->colorset);

            // 템플릿 파일을 지정
            if(Context::get('is_logged')) $tpl_file = 'login_info';
            else $tpl_file = 'login_form';

            // 회원 관리 정보를 받음
            $oModuleModel = &getModel('module');
            $this->member_config = $oModuleModel->getModuleConfig('member');
            Context::set('member_config', $this->member_config);

            // ssl 사용시 현재 https접속상태인지에 대한 flag및 https url 생성
            $ssl_mode = false;
            if($this->member_config->enable_ssl == 'Y') {
                if(preg_match('/^https:\/\//i',Context::getRequestUri())) $ssl_mode = true;
            }
            Context::set('ssl_mode',$ssl_mode);

            // 템플릿 컴파일
            $oTemplate = &TemplateHandler::getInstance();
            return $oTemplate->compile($tpl_path, $tpl_file);
        }


    }
?>
