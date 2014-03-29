<?php
    /**
     * @class  poll_maker
     * @author NHN (developers@xpressengine.com)
     * @brief  에디터에서 url링크하는 기능 제공. 
     **/

    class poll_maker extends EditorHandler { 

        // editor_sequence 는 에디터에서 필수로 달고 다녀야 함....
        var $editor_sequence = 0;
        var $component_path = '';

        /**
         * @brief editor_sequence과 컴포넌트의 경로를 받음
         **/
        function poll_maker($editor_sequence, $component_path) {
            $this->editor_sequence = $editor_sequence;
            $this->component_path = $component_path;
        }

        /**
         * @brief popup window요청시 popup window에 출력할 내용을 추가하면 된다
         **/
        function getPopupContent() {
            // 설문조사 스킨을 구함
            $oModuleModel = &getModel('module');
            $skin_list = $oModuleModel->getSkins("./modules/poll/");
            Context::set('skin_list', $skin_list);

            // 템플릿을 미리 컴파일해서 컴파일된 소스를 return
            $tpl_path = $this->component_path.'tpl';
            $tpl_file = 'popup.html';

            $oTemplate = &TemplateHandler::getInstance();
            return $oTemplate->compile($tpl_path, $tpl_file);
        }

        /**
         * @brief 에디터 컴포넌트가 별도의 고유 코드를 이용한다면 그 코드를 html로 변경하여 주는 method
         *
         * 이미지나 멀티미디어, 설문등 고유 코드가 필요한 에디터 컴포넌트는 고유코드를 내용에 추가하고 나서
         * DocumentModule::transContent() 에서 해당 컴포넌트의 transHtml() method를 호출하여 고유코드를 html로 변경
         **/
        function transHTML($xml_obj) {
            $poll_srl = $xml_obj->attrs->poll_srl;
            $skin = $xml_obj->attrs->skin;
            if(!$skin) $skin = 'default';

            preg_match('/width([^[:digit:]]+)([0-9]+)/i',$xml_obj->attrs->style,$matches);
            $width = $matches[2];
            if(!$width) $width = 400;
            $style = sprintf('width:%dpx', $width);

            // poll model 객체 생성해서 html 얻어와서 return
            $oPollModel = &getModel('poll');
            return $oPollModel->getPollHtml($poll_srl, $style, $skin);
        }
    }
?>
