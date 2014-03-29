<?php
    /**
     * @class  layoutModel
     * @author NHN (developers@xpressengine.com)
     * @version 0.1
     * @brief  layout 모듈의 Model class
     **/

    class layoutModel extends layout {

        var $useUserLayoutTemp = null;
        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief DB 에 생성된 레이아웃의 목록을 구함
         * 생성되었다는 것은 DB에 등록이 되었다는 것을 의미
         **/
        function getLayoutList($site_srl = 0, $layout_type="P") {
            if(!$site_srl) {
                $site_module_info = Context::get('site_module_info');
                $site_srl = (int)$site_module_info->site_srl;
            }
            $args->site_srl = $site_srl;
			$args->layout_type = $layout_type;
            $output = executeQuery('layout.getLayoutList', $args);
            if(!$output->data) return;
            if(is_array($output->data)) return $output->data;
            return array($output->data);
        }

        /**
         * @brief DB 에 생성된 한개의 레이아웃 정보를 구함
         * 생성된 레이아웃의 DB정보+XML정보를 return
         **/
        function getLayout($layout_srl) {
            // 일단 DB에서 정보를 가져옴
            $args->layout_srl = $layout_srl;
            $output = executeQuery('layout.getLayout', $args);
            if(!$output->data) return;

            // layout, extra_vars를 정리한 후 xml 파일 정보를 정리해서 return
            $layout_info = $this->getLayoutInfo($layout, $output->data, $output->data->layout_type);
            return $layout_info;
        }

        /**
         * @brief 레이아웃의 경로를 구함
         **/
        function getLayoutPath($layout_name, $layout_type = "P") {
            if($layout_name == 'faceoff'){
                $class_path = './modules/layout/faceoff/';
            }else if($layout_type == "M") {
				$class_path = sprintf("./m.layouts/%s/", $layout_name);
			}
			else
			{
                $class_path = sprintf('./layouts/%s/', $layout_name);
            }
            if(is_dir($class_path)) return $class_path;
            return "";
        }

        /**
         * @brief 레이아웃의 종류와 정보를 구함
         * 다운로드되어 있는 레이아웃의 종류 (생성과 다른 의미)
         **/
        function getDownloadedLayoutList($layout_type = "P") {
            // 다운받은 레이아웃과 설치된 레이아웃의 목록을 구함
			if($layout_type == "M")
			{
				$directory = "./m.layouts";
			}
			else
			{
				$directory = "./layouts";
			}

            $searched_list = FileHandler::readDir($directory);
            $searched_count = count($searched_list);
            if(!$searched_count) return;

            natcasesort($searched_list);

            // 찾아진 레이아웃 목록을 loop돌면서 필요한 정보를 간추려 return
            for($i=0;$i<$searched_count;$i++) {
                // 레이아웃의 이름
                $layout = $searched_list[$i];

                // 해당 레이아웃의 정보를 구함
                $layout_info = $this->getLayoutInfo($layout, null, $layout_type);

                $list[] = $layout_info;
            }
            return $list;
        }

        /**
         * @brief 모듈의 conf/info.xml 을 읽어서 정보를 구함
         * 이것 역시 캐싱을 통해서 xml parsing 시간을 줄인다..
         **/
        function getLayoutInfo($layout, $info = null, $layout_type = "P") {
            if($info) {
                $layout_title = $info->title;
                $layout = $info->layout;
                $layout_srl = $info->layout_srl;
                $site_srl = $info->site_srl;
                $vars = unserialize($info->extra_vars);

                if($info->module_srl) {
                    $layout_path = preg_replace('/([a-zA-Z0-9\_\.]+)(\.html)$/','',$info->layout_path);
                    $xml_file = sprintf('%sskin.xml', $layout_path);
                }
            }

            // 요청된 모듈의 경로를 구한다. 없으면 return
            if(!$layout_path) $layout_path = $this->getLayoutPath($layout, $layout_type);
            if(!is_dir($layout_path)) return;

            // 현재 선택된 모듈의 스킨의 정보 xml 파일을 읽음
            if(!$xml_file) $xml_file = sprintf("%sconf/info.xml", $layout_path);
            if(!file_exists($xml_file)) {
                $layout_info->layout = $layout;
                $layout_info->path = $layout_path;
                $layout_info->layout_title = $layout_title;
				if(!$layout_info->layout_type)
					$layout_info->layout_type =  $layout_type;
                return $layout_info;
            }

            // cache 파일을 비교하여 문제 없으면 include하고 $layout_info 변수를 return
            if(!$layout_srl){
                $cache_file = $this->getLayoutCache($layout, Context::getLangType());
            }else{
                $cache_file = $this->getUserLayoutCache($layout_srl, Context::getLangType());
            }
            if(file_exists($cache_file)&&filemtime($cache_file)>filemtime($xml_file)) {
                @include($cache_file);

                if($layout_info->extra_var && $vars) {
                    foreach($vars as $key => $value) {
                        if(!$layout_info->extra_var->{$key} && !$layout_info->{$key}) {
                            $layout_info->{$key} = $value;
                        }
                    }
                }
                return $layout_info;
            }

            // cache 파일이 없으면 xml parsing하고 변수화 한 후에 캐시 파일에 쓰고 변수 바로 return
            $oXmlParser = new XmlParser();
            $tmp_xml_obj = $oXmlParser->loadXmlFile($xml_file);
            if($tmp_xml_obj->layout) $xml_obj = $tmp_xml_obj->layout;
            elseif($tmp_xml_obj->skin) $xml_obj = $tmp_xml_obj->skin;

            if(!$xml_obj) return;

            $buff = '';
            $buff .= sprintf('$layout_info->site_srl = "%s";', $site_srl);

            if($xml_obj->version && $xml_obj->attrs->version == '0.2') {
                // 레이아웃의 제목, 버전
                sscanf($xml_obj->date->body, '%d-%d-%d', $date_obj->y, $date_obj->m, $date_obj->d);
                $date = sprintf('%04d%02d%02d', $date_obj->y, $date_obj->m, $date_obj->d);
                $buff .= sprintf('$layout_info->layout = "%s";', $layout);
                $buff .= sprintf('$layout_info->type = "%s";', $xml_obj->attrs->type);
                $buff .= sprintf('$layout_info->path = "%s";', $layout_path);
                $buff .= sprintf('$layout_info->title = "%s";', $xml_obj->title->body);
                $buff .= sprintf('$layout_info->description = "%s";', $xml_obj->description->body);
                $buff .= sprintf('$layout_info->version = "%s";', $xml_obj->version->body);
                $buff .= sprintf('$layout_info->date = "%s";', $date);
                $buff .= sprintf('$layout_info->homepage = "%s";', $xml_obj->link->body);
                $buff .= sprintf('$layout_info->layout_srl = $layout_srl;');
                $buff .= sprintf('$layout_info->layout_title = $layout_title;');
                $buff .= sprintf('$layout_info->license = "%s";', $xml_obj->license->body);
                $buff .= sprintf('$layout_info->license_link = "%s";', $xml_obj->license->attrs->link);
				$buff .= sprintf('$layout_info->layout_type = "%s";', $layout_type);

                // 작성자 정보
                if(!is_array($xml_obj->author)) $author_list[] = $xml_obj->author;
                else $author_list = $xml_obj->author;

                for($i=0; $i < count($author_list); $i++) {
                    $buff .= sprintf('$layout_info->author['.$i.']->name = "%s";', $author_list[$i]->name->body);
                    $buff .= sprintf('$layout_info->author['.$i.']->email_address = "%s";', $author_list[$i]->attrs->email_address);
                    $buff .= sprintf('$layout_info->author['.$i.']->homepage = "%s";', $author_list[$i]->attrs->link);
                }



                // 추가 변수 (템플릿에서 사용할 제작자 정의 변수)
                $extra_var_groups = $xml_obj->extra_vars->group;
                if(!$extra_var_groups) $extra_var_groups = $xml_obj->extra_vars;
                if(!is_array($extra_var_groups)) $extra_var_groups = array($extra_var_groups);
                foreach($extra_var_groups as $group){
                    $extra_vars = $group->var;
                    if($extra_vars) {
                        if(!is_array($extra_vars)) $extra_vars = array($extra_vars);

                        $extra_var_count = count($extra_vars);

                        $buff .= sprintf('$layout_info->extra_var_count = "%s";', $extra_var_count);
                        for($i=0;$i<$extra_var_count;$i++) {
                            unset($var);
                            unset($options);
                            $var = $extra_vars[$i];
                            $name = $var->attrs->name;

                            $buff .= sprintf('$layout_info->extra_var->%s->group = "%s";', $name, $group->title->body);
                            $buff .= sprintf('$layout_info->extra_var->%s->title = "%s";', $name, $var->title->body);
                            $buff .= sprintf('$layout_info->extra_var->%s->type = "%s";', $name, $var->attrs->type);
                            $buff .= sprintf('$layout_info->extra_var->%s->value = $vars->%s;', $name, $name);
                            $buff .= sprintf('$layout_info->extra_var->%s->description = "%s";', $name, str_replace('"','\"',$var->description->body));

                            $options = $var->options;
                            if(!$options) continue;

                            if(!is_array($options)) $options = array($options);
                            $options_count = count($options);
                            $thumbnail_exist = false;
                            for($j=0; $j < $options_count; $j++) {
                                $thumbnail = $options[$j]->attrs->src;
                                if($thumbnail) {
                                    $thumbnail = $layout_path.$thumbnail;
                                    if(file_exists($thumbnail)) {
                                        $buff .= sprintf('$layout_info->extra_var->%s->options["%s"]->thumbnail = "%s";', $var->attrs->name, $options[$j]->attrs->value, $thumbnail);
                                        if(!$thumbnail_exist) {
                                            $buff .= sprintf('$layout_info->extra_var->%s->thumbnail_exist = true;', $var->attrs->name);
                                            $thumbnail_exist = true;
                                        }
                                    }
                                }
                                $buff .= sprintf('$layout_info->extra_var->%s->options["%s"]->val = "%s";', $var->attrs->name, $options[$j]->attrs->value, $options[$j]->title->body);

                            }
                        }
                    }
                }

                // 메뉴
                if($xml_obj->menus->menu) {
                    $menus = $xml_obj->menus->menu;
                    if(!is_array($menus)) $menus = array($menus);

                    $menu_count = count($menus);
                    $buff .= sprintf('$layout_info->menu_count = "%s";', $menu_count);
                    for($i=0;$i<$menu_count;$i++) {
                        $name = $menus[$i]->attrs->name;
                        if($menus[$i]->attrs->default == "true") $buff .= sprintf('$layout_info->default_menu = "%s";', $name);
                        $buff .= sprintf('$layout_info->menu->%s->name = "%s";',$name, $menus[$i]->attrs->name);
                        $buff .= sprintf('$layout_info->menu->%s->title = "%s";',$name, $menus[$i]->title->body);
                        $buff .= sprintf('$layout_info->menu->%s->maxdepth = "%s";',$name, $menus[$i]->attrs->maxdepth);

                        $buff .= sprintf('$layout_info->menu->%s->menu_srl = $vars->%s;', $name, $name);
                        $buff .= sprintf('$layout_info->menu->%s->xml_file = "./files/cache/menu/".$vars->%s.".xml.php";',$name, $name);
                        $buff .= sprintf('$layout_info->menu->%s->php_file = "./files/cache/menu/".$vars->%s.".php";',$name, $name);
                    }
                }


                // history
                if($xml_obj->history) {
                    if(!is_array($xml_obj->history)) $history_list[] = $xml_obj->history;
                    else $history_list = $xml_obj->history;

                    for($i=0; $i < count($history_list); $i++) {
                        sscanf($history_list[$i]->attrs->date, '%d-%d-%d', $date_obj->y, $date_obj->m, $date_obj->d);
                        $date = sprintf('%04d%02d%02d', $date_obj->y, $date_obj->m, $date_obj->d);
                        $buff .= sprintf('$layout_info->history['.$i.']->description = "%s";', $history_list[$i]->description->body);
                        $buff .= sprintf('$layout_info->history['.$i.']->version = "%s";', $history_list[$i]->attrs->version);
                        $buff .= sprintf('$layout_info->history['.$i.']->date = "%s";', $date);

                        if($history_list[$i]->author) {
                            (!is_array($history_list[$i]->author)) ? $obj->author_list[] = $history_list[$i]->author : $obj->author_list = $history_list[$i]->author;

                            for($j=0; $j < count($obj->author_list); $j++) {
                                $buff .= sprintf('$layout_info->history['.$i.']->author['.$j.']->name = "%s";', $obj->author_list[$j]->name->body);
                                $buff .= sprintf('$layout_info->history['.$i.']->author['.$j.']->email_address = "%s";', $obj->author_list[$j]->attrs->email_address);
                                $buff .= sprintf('$layout_info->history['.$i.']->author['.$j.']->homepage = "%s";', $obj->author_list[$j]->attrs->link);
                            }
                        }

                        if($history_list[$i]->log) {
                            (!is_array($history_list[$i]->log)) ? $obj->log_list[] = $history_list[$i]->log : $obj->log_list = $history_list[$i]->log;

                            for($j=0; $j < count($obj->log_list); $j++) {
                                $buff .= sprintf('$layout_info->history['.$i.']->logs['.$j.']->text = "%s";', $obj->log_list[$j]->body);
                                $buff .= sprintf('$layout_info->history['.$i.']->logs['.$j.']->link = "%s";', $obj->log_list[$j]->attrs->link);
                            }
                        }
                    }
                }



            } else {

                // 레이아웃의 제목, 버전
                sscanf($xml_obj->author->attrs->date, '%d. %d. %d', $date_obj->y, $date_obj->m, $date_obj->d);
                $date = sprintf('%04d%02d%02d', $date_obj->y, $date_obj->m, $date_obj->d);
                $buff .= sprintf('$layout_info->layout = "%s";', $layout);
                $buff .= sprintf('$layout_info->path = "%s";', $layout_path);
                $buff .= sprintf('$layout_info->title = "%s";', $xml_obj->title->body);
                $buff .= sprintf('$layout_info->description = "%s";', $xml_obj->author->description->body);
                $buff .= sprintf('$layout_info->version = "%s";', $xml_obj->attrs->version);
                $buff .= sprintf('$layout_info->date = "%s";', $date);
                $buff .= sprintf('$layout_info->layout_srl = $layout_srl;');
                $buff .= sprintf('$layout_info->layout_title = $layout_title;');

                // 작성자 정보
                $buff .= sprintf('$layout_info->author[0]->name = "%s";', $xml_obj->author->name->body);
                $buff .= sprintf('$layout_info->author[0]->email_address = "%s";', $xml_obj->author->attrs->email_address);
                $buff .= sprintf('$layout_info->author[0]->homepage = "%s";', $xml_obj->author->attrs->link);

                // 추가 변수 (템플릿에서 사용할 제작자 정의 변수)
                $extra_var_groups = $xml_obj->extra_vars->group;
                if(!$extra_var_groups) $extra_var_groups = $xml_obj->extra_vars;
                if(!is_array($extra_var_groups)) $extra_var_groups = array($extra_var_groups);
                foreach($extra_var_groups as $group){
                    $extra_vars = $group->var;
                    if($extra_vars) {
                        if(!is_array($extra_vars)) $extra_vars = array($extra_vars);

                        $extra_var_count = count($extra_vars);

                        $buff .= sprintf('$layout_info->extra_var_count = "%s";', $extra_var_count);
                        for($i=0;$i<$extra_var_count;$i++) {
                            unset($var);
                            unset($options);
                            $var = $extra_vars[$i];
                            $name = $var->attrs->name;

                            $buff .= sprintf('$layout_info->extra_var->%s->group = "%s";', $name, $group->title->body);
                            $buff .= sprintf('$layout_info->extra_var->%s->title = "%s";', $name, $var->title->body);
                            $buff .= sprintf('$layout_info->extra_var->%s->type = "%s";', $name, $var->attrs->type);
                            $buff .= sprintf('$layout_info->extra_var->%s->value = $vars->%s;', $name, $name);
                            $buff .= sprintf('$layout_info->extra_var->%s->description = "%s";', $name, str_replace('"','\"',$var->description->body));

                            $options = $var->options;
                            if(!$options) continue;

                            if(!is_array($options)) $options = array($options);
                            $options_count = count($options);
                            for($j=0;$j<$options_count;$j++) {
                                $buff .= sprintf('$layout_info->extra_var->%s->options["%s"] = "%s";', $var->attrs->name, $options[$j]->value->body, $options[$j]->title->body);
                            }
                        }
                    }
                }

                // 메뉴
                if($xml_obj->menus->menu) {
                    $menus = $xml_obj->menus->menu;
                    if(!is_array($menus)) $menus = array($menus);

                    $menu_count = count($menus);
                    $buff .= sprintf('$layout_info->menu_count = "%s";', $menu_count);
                    for($i=0;$i<$menu_count;$i++) {
                        $name = $menus[$i]->attrs->name;
                        if($menus[$i]->attrs->default == "true") $buff .= sprintf('$layout_info->default_menu = "%s";', $name);
                        $buff .= sprintf('$layout_info->menu->%s->name = "%s";',$name, $menus[$i]->attrs->name);
                        $buff .= sprintf('$layout_info->menu->%s->title = "%s";',$name, $menus[$i]->title->body);
                        $buff .= sprintf('$layout_info->menu->%s->maxdepth = "%s";',$name, $menus[$i]->maxdepth->body);

                        $buff .= sprintf('$layout_info->menu->%s->menu_srl = $vars->%s;', $name, $name);
                        $buff .= sprintf('$layout_info->menu->%s->xml_file = "./files/cache/menu/".$vars->%s.".xml.php";',$name, $name);
                        $buff .= sprintf('$layout_info->menu->%s->php_file = "./files/cache/menu/".$vars->%s.".php";',$name, $name);
                    }
                }

            }


            // header_script
            $oModuleModel = &getModel('module');
            $layout_config = $oModuleModel->getModulePartConfig('layout', $layout_srl);
            $header_script = trim($layout_config->header_script);

            if($header_script) $buff .= sprintf(' $layout_info->header_script = "%s"; ', str_replace('"','\\"',$header_script));

            $buff = '<?php if(!defined("__ZBXE__")) exit(); '.$buff.' ?>';
            FileHandler::writeFile($cache_file, $buff);
            if(file_exists($cache_file)) @include($cache_file);
            return $layout_info;
        }


        /**
         * @brief layout설정화면에서의 업로드한 이미지목록을 반환한다
         **/
        function getUserLayoutImageList($layout_srl){
            $path = $this->getUserLayoutImagePath($layout_srl);
            $list = FileHandler::readDir($path);
            return $list;
        }


        /**
         * @brief ini config들을 가져온다 array다.
         **/
        function getUserLayoutIniConfig($layout_srl, $layout_name=null){
            $file = $this->getUserLayoutIni($layout_srl);
            if($layout_name && !file_exists(FileHandler::getRealPath($file))){
                FileHandler::copyFile($this->getDefaultLayoutIni($layout_name),$this->getUserLayoutIni($layout_srl));
            }

            $output = FileHandler::readIniFile($file);
            return $output;
        }

        /**
         * @brief user layout path
         **/
        function getUserLayoutPath($layout_srl){
            return sprintf("./files/faceOff/%s",getNumberingPath($layout_srl,3));
        }

        /**
         * @brief user layout image path
         **/
        function getUserLayoutImagePath($layout_srl){
            return $this->getUserLayoutPath($layout_srl). 'images/';
        }

        /**
         * @brief user layout css 관리자가 설정화면에서 저장한 css
         **/
        function getUserLayoutCss($layout_srl){
            return $this->getUserLayoutPath($layout_srl). 'layout.css';
        }


        /**
         * @brief faceoff용 css module handler에서 import 한다
         **/
        function getUserLayoutFaceOffCss($layout_srl){
            $src = $this->_getUserLayoutFaceOffCss($layout_srl);
            if($this->useUserLayoutTemp == 'temp') return;
            return $src;
        }


        /**
         * @brief faceoff용 css module handler에서 import 한다
         **/
        function _getUserLayoutFaceOffCss($layout_srl){
            return $this->getUserLayoutPath($layout_srl). 'faceoff.css';
        }

        /**
         * @brief user layout tmp html
         **/
        function getUserLayoutTempFaceOffCss($layout_srl){
            return $this->getUserLayoutPath($layout_srl). 'tmp.faceoff.css';
        }



        /**
         * @brief user layout html
         **/
        function getUserLayoutHtml($layout_srl){
            $src = $this->getUserLayoutPath($layout_srl). 'layout.html';
            $temp = $this->getUserLayoutTempHtml($layout_srl);
            if($this->useUserLayoutTemp == 'temp'){
                if(!file_exists(FileHandler::getRealPath($temp))) FileHandler::copyFile($src,$temp);
                return $temp;
            }else{
                return $src;
            }
        }

        /**
         * @brief user layout tmp html
         **/
        function getUserLayoutTempHtml($layout_srl){
            return $this->getUserLayoutPath($layout_srl). 'tmp.layout.html';
        }


        /**
         * @brief user layout ini
         **/
        function getUserLayoutIni($layout_srl){
            $src = $this->getUserLayoutPath($layout_srl). 'layout.ini';
            $temp = $this->getUserLayoutTempIni($layout_srl);
            if($this->useUserLayoutTemp == 'temp'){
                if(!file_exists(FileHandler::getRealPath($temp))) FileHandler::copyFile($src,$temp);
                return $temp;
            }else{
                return $src;
            }
        }

        /**
         * @brief user layout tmp ini
         **/
        function getUserLayoutTempIni($layout_srl){
            return $this->getUserLayoutPath($layout_srl). 'tmp.layout.ini';
        }

        /**
         * @brief user layout cache 
         * todo 파일 자체를 삭제 필요가 있다
         **/
        function getUserLayoutCache($layout_srl,$lang_type){
            return $this->getUserLayoutPath($layout_srl). "{$lang_type}.cache.php";
        }

        /**
         * @brief layout cache 
         **/
        function getLayoutCache($layout_name,$lang_type){
            return sprintf("./files/cache/layout/%s.%s.cache.php",$layout_name,$lang_type);
        }

        /**
         * @brief default layout ini  사용자의 임의 수정을 막기 위해 
         **/
        function getDefaultLayoutIni($layout_name){
            return $this->getDefaultLayoutPath($layout_name). 'layout.ini';
        }

        /**
         * @brief default layout html 사용자의 임의 수정을 막기 위해 
         **/
        function getDefaultLayoutHtml($layout_name){
            return $this->getDefaultLayoutPath($layout_name). 'layout.html';
        }

        /**
         * @brief default layout css 사용자의 임의 수정을 막기 위해 
         **/
        function getDefaultLayoutCss($layout_name){
            return $this->getDefaultLayoutPath($layout_name). 'css/layout.css';
        }

        /**
         * @brief default layout path 사용자의 임의 수정을 막기 위해 
         **/
        function getDefaultLayoutPath() {
            return "./modules/layout/faceoff/";
        }


        /**
         * @brief faceoff 인지 
         **/
        function useDefaultLayout($layout_name){
            $info = $this->getLayoutInfo($layout_name);
            if($info->type == 'faceoff') return true;
            else return false;
        }


        /**
         * @brief User Layout 을 임시 저장 모드로
         **/
        function setUseUserLayoutTemp($flag='temp'){
            $this->useUserLayoutTemp = $flag;
        }


        /**
         * @brief User Layout 임시 저장 파일 목록.
         **/
        function getUserLayoutTempFileList($layout_srl){
            $file_list = array(
                $this->getUserLayoutTempHtml($layout_srl)
                ,$this->getUserLayoutTempFaceOffCss($layout_srl)
                ,$this->getUserLayoutTempIni($layout_srl)
            );
            return $file_list;
        }


        /**
         * @brief User Layout 저장 파일 목록.
         **/
        function getUserLayoutFileList($layout_srl){
            $file_list = array(
                basename($this->getUserLayoutHtml($layout_srl))
                ,basename($this->getUserLayoutFaceOffCss($layout_srl))
                ,basename($this->getUserLayoutIni($layout_srl))
                ,basename($this->getUserLayoutCss($layout_srl))
            );

            $image_path = $this->getUserLayoutImagePath($layout_srl);
            $image_list = FileHandler::readDir($image_path,'/(.*(?:swf|jpg|jpeg|gif|bmp|png)$)/i');

            for($i=0,$c=count($image_list);$i<$c;$i++) $file_list[] = 'images/' . $image_list[$i];
            return $file_list;
        }

        /**
         * @brief faceOff관련 서비스 출력을 위한 동작 실행
         **/
        function doActivateFaceOff(&$layout_info) {
            $layout_info->faceoff_ini_config = $this->getUserLayoutIniConfig($layout_info->layout_srl, $layout_info->layout);

            // 기본 faceoff layout CSS
            Context::addCSSFile($this->getDefaultLayoutCss($layout_info->layout));

            // 레이아웃 매니져에서 생성된 CSS
            $faceoff_layout_css = $this->getUserLayoutFaceOffCss($layout_info->layout_srl);
            if($faceoff_layout_css) Context::addCSSFile($faceoff_layout_css);

            // 레이아웃의 위젯을 위한 css출력
            Context::addCSSFile($this->module_path.'/tpl/css/widget.css');
            if($layout_info->extra_var->colorset->value == 'black') Context::addCSSFile($this->module_path.'/tpl/css/widget@black.css');
            else Context::addCSSFile($this->module_path.'/tpl/css/widget@white.css');

            // 권한에 따른 다른 내용 출력
            $logged_info = Context::get('logged_info');

            // faceOff 레이아웃 편집 버튼 노출
            if(Context::get('module')!='admin' && strpos(Context::get('act'),'Admin')===false && ($logged_info->is_admin == 'Y' || $logged_info->is_site_admin)) {
                Context::addHtmlFooter("<div class=\"faceOffManager\"><a href=\"".getUrl('','mid',Context::get('mid'),'act','dispLayoutAdminLayoutModify','delete_tmp','Y')."\" class=\"buttonSet buttonLayoutEditor\"><span>".Context::getLang('cmd_layout_edit')."</span></a></div>");
            }

            // faceOff페이지 수정시에 메뉴 출력
            if(Context::get('act')=='dispLayoutAdminLayoutModify' && ($logged_info->is_admin == 'Y' || $logged_info->is_site_admin)) {
                $oTemplate = &TemplateHandler::getInstance();
                Context::addBodyHeader($oTemplate->compile($this->module_path.'/tpl', 'faceoff_layout_menu'));
            }
        }
    }
?>
