<?php
    /**
     * @class  editorModel
     * @author NHN (developers@xpressengine.com)
     * @brief  editor 모듈의 model 클래스
     **/

    class editorModel extends editor {

        var $loaded_component_list = array();

        /**
         * @brief 에디터를 return
         *
         * 에디터의 경우 내부적으로 1~30까지의 임시 editor_seuqnece를 생성한다.
         * 즉 한페이지에 30개 이상의 에디터를 출력하지는 못하도록 제한되어 있다.
         *
         * 단, 수정하는 경우 또는 파일업로드를 한 자동저장본의 경우는 getNextSequence() 값으로 저장된 editor_seqnece가
         * 설정된다.
         **/

        /**
         * @brief 모듈별 에디터 설정을 return
         **/
        function getEditorConfig($module_srl) {
            if(!$GLOBALS['__editor_module_config__'][$module_srl]) {
                // 선택된 모듈의 trackback설정을 가져옴
                $oModuleModel = &getModel('module');
                $GLOBALS['__editor_module_config__'][$module_srl] = $oModuleModel->getModulePartConfig('editor', $module_srl);
            }

            $editor_config = $GLOBALS['__editor_module_config__'][$module_srl];

            if(!is_object($editor_config)) $editor_config = null;

            if(!is_array($editor_config->enable_html_grant)) $editor_config->enable_html_grant = array();
            if(!is_array($editor_config->enable_comment_html_grant)) $editor_config->enable_comment_html_grant = array();
            if(!is_array($editor_config->upload_file_grant)) $editor_config->upload_file_grant = array();
            if(!is_array($editor_config->comment_upload_file_grant)) $editor_config->comment_upload_file_grant = array();
            if(!is_array($editor_config->enable_default_component_grant)) $editor_config->enable_default_component_grant = array();
            if(!is_array($editor_config->enable_comment_default_component_grant)) $editor_config->enable_comment_default_component_grant = array();
            if(!is_array($editor_config->enable_component_grant)) $editor_config->enable_component_grant = array();
            if(!is_array($editor_config->enable_comment_component_grant)) $editor_config->enable_comment_component_grant= array();

            if(!$editor_config->editor_height) $editor_config->editor_height = 500;
            if(!$editor_config->comment_editor_height) $editor_config->comment_editor_height = 120;
            if($editor_config->enable_autosave!='N') $editor_config->enable_autosave = "Y";

            if(!$editor_config->editor_skin) $editor_config->editor_skin = 'xpresseditor';
            if(!$editor_config->comment_editor_skin) $editor_config->comment_editor_skin = 'xpresseditor';
            if(!$editor_config->content_style) $editor_config->content_style = 'default';

            return $editor_config;
        }

		function loadDrComponents(){
			$drComponentPath = './modules/editor/skins/dreditor/drcomponents/';
            $drComponentList = FileHandler::readDir($drComponentPath);

			$oTemplate = &TemplateHandler::getInstance();

			$drComponentInfo = array();
			if($drComponentList){
				foreach($drComponentList as $i => $drComponent){
					unset($obj);
					$obj = $this->getDrComponentXmlInfo($drComponent);
					Context::loadLang(sprintf('%s%s/lang/',$drComponentPath,$drComponent));					
					$path = sprintf('%s%s/tpl/',$drComponentPath,$drComponent);
					$obj->html = $oTemplate->compile($path,$drComponent);
					$drComponentInfo[$drComponent] = $obj;
				}
			}
			Context::set('drComponentList',$drComponentInfo);
		}

		function getDrComponentXmlInfo($drComponentName){
			$lang_type = Context::getLangType();

            // 요청된 컴포넌트의 xml파일 위치를 구함
            $component_path = sprintf('%s/skins/dreditor/drcomponents/%s/', $this->module_path, $drComponentName);

            $xml_file = sprintf('%sinfo.xml', $component_path);
            $cache_file = sprintf('./files/cache/editor/dr_%s.%s.php', $drComponentName, $lang_type);

            // 캐시된 xml파일이 있으면 include 후 정보 return
            if(file_exists($cache_file) && file_exists($xml_file) && filemtime($cache_file) > filemtime($xml_file)) {
                include($cache_file);
                return $xml_info;
            }

            // 캐시된 파일이 없으면 파싱후 캐싱 후 return
            $oParser = new XmlParser();
            $xml_doc = $oParser->loadXmlFile($xml_file);

			$component_info->component_name = $drComponentName;
			$component_info->title = $xml_doc->component->title->body;
			$component_info->description = str_replace('\n', "\n", $xml_doc->component->description->body);
			$component_info->version = $xml_doc->component->version->body;
			$component_info->date = $xml_doc->component->date->body;
			$component_info->homepage = $xml_doc->component->link->body;
			$component_info->license = $xml_doc->component->license->body;
			$component_info->license_link = $xml_doc->component->license->attrs->link;

			$buff = '<?php if(!defined("__ZBXE__")) exit(); ';
			$buff .= sprintf('$xml_info->component_name = "%s";', $component_info->component_name);
			$buff .= sprintf('$xml_info->title = "%s";', $component_info->title);
			$buff .= sprintf('$xml_info->description = "%s";', $component_info->description);
			$buff .= sprintf('$xml_info->version = "%s";', $component_info->version);
			$buff .= sprintf('$xml_info->date = "%s";', $component_info->date);
			$buff .= sprintf('$xml_info->homepage = "%s";', $component_info->homepage);
			$buff .= sprintf('$xml_info->license = "%s";', $component_info->license);
			$buff .= sprintf('$xml_info->license_link = "%s";', $component_info->license_link);

			// 작성자 정보
			if(!is_array($xml_doc->component->author)) $author_list[] = $xml_doc->component->author;
			else $author_list = $xml_doc->component->author;

			for($i=0; $i < count($author_list); $i++) {
				$buff .= sprintf('$xml_info->author['.$i.']->name = "%s";', $author_list[$i]->name->body);
				$buff .= sprintf('$xml_info->author['.$i.']->email_address = "%s";', $author_list[$i]->attrs->email_address);
				$buff .= sprintf('$xml_info->author['.$i.']->homepage = "%s";', $author_list[$i]->attrs->link);
			}

			// history
			if($xml_doc->component->history) {
				if(!is_array($xml_doc->component->history)) $history_list[] = $xml_doc->component->history;
				else $history_list = $xml_doc->component->history;

				for($i=0; $i < count($history_list); $i++) {
					unset($obj);
					sscanf($history_list[$i]->attrs->date, '%d-%d-%d', $date_obj->y, $date_obj->m, $date_obj->d);
					$date = sprintf('%04d%02d%02d', $date_obj->y, $date_obj->m, $date_obj->d);
					$buff .= sprintf('$xml_info->history['.$i.']->description = "%s";', $history_list[$i]->description->body);
					$buff .= sprintf('$xml_info->history['.$i.']->version = "%s";', $history_list[$i]->attrs->version);
					$buff .= sprintf('$xml_info->history['.$i.']->date = "%s";', $date);

					if($history_list[$i]->author) {
						(!is_array($history_list[$i]->author)) ? $obj->author_list[] = $history_list[$i]->author : $obj->author_list = $history_list[$i]->author;

						for($j=0; $j < count($obj->author_list); $j++) {
							$buff .= sprintf('$xml_info->history['.$i.']->author['.$j.']->name = "%s";', $obj->author_list[$j]->name->body);
							$buff .= sprintf('$xml_info->history['.$i.']->author['.$j.']->email_address = "%s";', $obj->author_list[$j]->attrs->email_address);
							$buff .= sprintf('$xml_info->history['.$i.']->author['.$j.']->homepage = "%s";', $obj->author_list[$j]->attrs->link);
						}
					}

					if($history_list[$i]->log) {
						(!is_array($history_list[$i]->log)) ? $obj->log_list[] = $history_list[$i]->log : $obj->log_list = $history_list[$i]->log;

						for($j=0; $j < count($obj->log_list); $j++) {
							$buff .= sprintf('$xml_info->history['.$i.']->logs['.$j.']->text = "%s";', $obj->log_list[$j]->body);
							$buff .= sprintf('$xml_info->history['.$i.']->logs['.$j.']->link = "%s";', $obj->log_list[$j]->attrs->link);
						}
					}
				}
			}

           // 추가 변수 정리 (에디터 컴포넌트에서는 text형만 가능)
            $extra_vars = $xml_doc->component->extra_vars->var;
            if($extra_vars) {
                if(!is_array($extra_vars)) $extra_vars = array($extra_vars);
                foreach($extra_vars as $key => $val) {
                    unset($obj);
                    $key = $val->attrs->name;
                    $title = $val->title->body;
                    $description = $val->description->body;
                    $xml_info->extra_vars->{$key}->title = $title;
                    $xml_info->extra_vars->{$key}->description = $description;

                    $buff .= sprintf('$xml_info->extra_vars->%s->%s = "%s";', $key, 'title', $title);
                    $buff .= sprintf('$xml_info->extra_vars->%s->%s = "%s";', $key, 'description', $description);
                }
            }

            $buff .= ' ?>';

            FileHandler::writeFile($cache_file, $buff, "w");

			unset($xml_info);
            include($cache_file);
            return $xml_info;
		}

        /**
         * @brief 에디터 template을 return
         * upload_target_srl은 글의 수정시 호출하면 됨.
         * 이 upload_target_srl은 첨부파일의 유무를 체크하기 위한 루틴을 구현하는데 사용됨.
         **/
        function getEditor($upload_target_srl = 0, $option = null) {
            /**
             * 기본적인 에디터의 옵션을 정리
             **/
            // 파일 업로드 유무 옵션 설정
            if(!$option->allow_fileupload) $allow_fileupload = false;
            else $allow_fileupload = true;

            // content_style 세팅
            if(!$option->content_style) $option->content_style = 'default';
            Context::set('content_style', $option->content_style);

            // 기본 글꼴 지정
            Context::set('content_font', $option->content_font);
            Context::set('content_font_size', $option->content_font_size);

            // 자동 저장 유무 옵션 설정 글 수정시는 사용 안함
            if(!$option->enable_autosave) $enable_autosave = false;
            elseif(Context::get($option->primary_key_name)) $enable_autosave = false;
            else $enable_autosave = true;

            // 기본 에디터 컴포넌트 사용 설정
            if(!$option->enable_default_component) $enable_default_component = false;
            else $enable_default_component = true;

            // 확장 컴포넌트 사용 설정
            if(!$option->enable_component) $enable_component = false;
            else $enable_component = true;

            // html 모드 조절
            if($option->disable_html) $html_mode = false;
            else $html_mode = true;

            // 높이 설정
            if(!$option->height) $editor_height = 400;
            else $editor_height = $option->height;

            // 스킨 설정
            $skin = $option->skin;
            if(!$skin) $skin = 'xpresseditor';

            $colorset = $option->colorset;
            Context::set('colorset', $colorset);
            Context::set('skin', $skin);

			if($skin=='dreditor'){
				$this->loadDrComponents();	
			}

            /**
             * 자동백업 기능 체크 (글 수정일 경우는 사용하지 않음)
             **/
            if($enable_autosave) {
                // 자동 저장된 데이터를 추출
                $saved_doc = $this->getSavedDoc($upload_target_srl);

                // 자동 저장 데이터를 context setting
                Context::set('saved_doc', $saved_doc);
            }
            Context::set('enable_autosave', $enable_autosave);

            /**
             * 에디터의 고유 번호 추출 (한 페이지에 여러개의 에디터를 출력하는 경우를 대비)
             **/
            if($option->editor_sequence) $editor_sequence = $option->editor_sequence;
            else {
                if(!$GLOBALS['_editor_sequence_']) $GLOBALS['_editor_sequence_'] = 1;
                $editor_sequence = $GLOBALS['_editor_sequence_'] ++;
            }

            /**
             * 업로드 활성화시 내부적으로 file 모듈의 환경설정을 이용하여 설정
             **/
            $files_count = 0;
            if($allow_fileupload) {
                $oFileModel = &getModel('file');

                // SWFUploader에 세팅할 업로드 설정 구함
                $file_config = $oFileModel->getUploadConfig();
                $file_config->allowed_attach_size = $file_config->allowed_attach_size*1024*1024;
                $file_config->allowed_filesize = $file_config->allowed_filesize*1024*1024;

                Context::set('file_config',$file_config);

                // 업로드 가능 용량등에 대한 정보를 세팅
                $upload_status = $oFileModel->getUploadStatus();
                Context::set('upload_status', $upload_status);

                // upload가능하다고 설정 (내부적으로 캐싱하여 처리)
                $oFileController = &getController('file');
                $oFileController->setUploadInfo($editor_sequence, $upload_target_srl);

                // 이미 등록된 파일이 있는지 검사
                if($upload_target_srl) $files_count = $oFileModel->getFilesCount($upload_target_srl);
            }
            Context::set('files_count', (int)$files_count);

            Context::set('allow_fileupload', $allow_fileupload);

            // 에디터 동작을 위한 editor_sequence값 설정
            Context::set('editor_sequence', $editor_sequence);

            // 파일 첨부 관련 행동을 하기 위해 문서 번호를 upload_target_srl로 설정
            // 신규문서일 경우 upload_target_srl=0 이고 첨부파일 관련 동작이 요청될때 이 값이 변경됨
            Context::set('upload_target_srl', $upload_target_srl);

            // 문서 혹은 댓글의 primary key값을 세팅한다.
            Context::set('editor_primary_key_name', $option->primary_key_name);

            // 내용을 sync 맞추기 위한 content column name을 세팅한다
            Context::set('editor_content_key_name', $option->content_key_name);


            /**
             * 에디터 컴포넌트 체크
             **/
            $site_module_info = Context::get('site_module_info');
            $site_srl = (int)$site_module_info->site_srl;
            if($enable_component) {
                if(!Context::get('component_list')) {
                    $component_list = $this->getComponentList(true, $site_srl);
                    Context::set('component_list', $component_list);
                }
            }
            Context::set('enable_component', $enable_component);
            Context::set('enable_default_component', $enable_default_component);

            /**
             * html_mode 가능한지 변수 설정
             **/
            Context::set('html_mode', $html_mode);

            /**
             * 에디터 세로 크기 설정
             **/
            Context::set('editor_height', $editor_height);

            // 에디터의 초기화를 수동으로하는 것에 대한 값 체크
            Context::set('editor_manual_start', $option->manual_start);

            /**
             * 템플릿을 미리 컴파일해서 컴파일된 소스를 하기 위해 스킨의 경로를 설정
             ?**/
            $tpl_path = sprintf('%sskins/%s/', $this->module_path, $skin);
            $tpl_file = 'editor.html';

            if(!file_exists($tpl_path.$tpl_file)) {
                $skin = 'xpresseditor';
                $tpl_path = sprintf('%sskins/%s/', $this->module_path, $skin);
            }
            Context::set('editor_path', $tpl_path);

			// load editor skin lang
			Context::loadLang($tpl_path.'lang');

            // tpl 파일을 compile한 결과를 return
            $oTemplate = new TemplateHandler();
            return $oTemplate->compile($tpl_path, $tpl_file);
        }

        /**
         * @brief 모듈별 설정이 반영된 에디터 template을 return
         * getEditor() 와 동일한 결과물을 return하지만 getModuleEditor()는 각 모듈별 추가 설정을 통해 직접 제어되는 설정을 이용하여 에디터를 생성함
         *
         * document/ comment 2가지 종류를 이용함.
         * 굳이 나눈 이유는 하나의 모듈에서 2개 종류의 에디터 사용을 위해서인데 게시판이나 블로그등 원글과 그에 연관된 글(댓글)을 위한 용도임.
         **/
        function getModuleEditor($type = 'document', $module_srl, $upload_target_srl, $primary_key_name, $content_key_name) {
            // 지정된 모듈의 에디터 설정을 구해옴
            $editor_config = $this->getEditorConfig($module_srl);

            // type에 따른 설정 정리
            if($type == 'document') {
                $config->editor_skin = $editor_config->editor_skin;
                $config->content_style = $editor_config->content_style;
                $config->content_font = $editor_config->content_font;
                $config->content_font_size = $editor_config->content_font_size;
                $config->sel_editor_colorset = $editor_config->sel_editor_colorset;
                $config->upload_file_grant = $editor_config->upload_file_grant;
                $config->enable_default_component_grant = $editor_config->enable_default_component_grant;
                $config->enable_component_grant = $editor_config->enable_component_grant;
                $config->enable_html_grant = $editor_config->enable_html_grant;
                $config->editor_height = $editor_config->editor_height;
                $config->enable_autosave = $editor_config->enable_autosave;
            } else {
                $config->editor_skin = $editor_config->comment_editor_skin;
                $config->content_style = $editor_config->comment_content_style;
                $config->content_font = $editor_config->content_font;
                $config->content_font_size = $editor_config->content_font_size;
                $config->sel_editor_colorset = $editor_config->sel_comment_editor_colorset;
                $config->upload_file_grant = $editor_config->comment_upload_file_grant;
                $config->enable_default_component_grant = $editor_config->enable_comment_default_component_grant;
                $config->enable_component_grant = $editor_config->enable_comment_component_grant;
                $config->enable_html_grant = $editor_config->enable_comment_html_grant;
                $config->editor_height = $editor_config->comment_editor_height;
                $config->enable_autosave = 'N';
            }

            // 권한 체크를 위한 현재 로그인 사용자의 그룹 설정 체크
            if(Context::get('is_logged')) {
                $logged_info = Context::get('logged_info');
                $group_list = $logged_info->group_list;
            } else {
                $group_list = array();
            }

            // 에디터 옵션 변수를 미리 설정
            $option->skin = $config->editor_skin;
            $option->content_style = $config->content_style;
            $option->content_font = $config->content_font;
            $option->content_font_size = $config->content_font_size;
            $option->colorset = $config->sel_editor_colorset;

            // 파일 업로드 권한 체크
            $option->allow_fileupload = false;
            if(count($config->upload_file_grant)) {
                foreach($group_list as $group_srl => $group_info) {
                    if(in_array($group_srl, $config->upload_file_grant)) {
                        $option->allow_fileupload = true;
                        break;
                    }
                }
            } else $option->allow_fileupload = true;

            // 기본 컴포넌트 사용 권한
            $option->enable_default_component = false;
            if(count($config->enable_default_component_grant)) {
                foreach($group_list as $group_srl => $group_info) {
                    if(in_array($group_srl, $config->enable_default_component_grant)) {
                        $option->enable_default_component = true;
                        break;
                    }
                }
            } else $option->enable_default_component = true;

            // 확장 컴포넌트 사용 권한
            $option->enable_component = false;
            if(count($config->enable_component_grant)) {
                foreach($group_list as $group_srl => $group_info) {
                    if(in_array($group_srl, $config->enable_component_grant)) {
                        $option->enable_component = true;
                        break;
                    }
                }
            } else $option->enable_component = true;

            // HTML 편집 권한
            $enable_html = false;
            if(count($config->enable_html_grant)) {
                foreach($group_list as $group_srl => $group_info) {
                    if(in_array($group_srl, $config->enable_html_grant)) {
                        $enable_html = true;
                        break;
                    }
                }
            } else $enable_html = true;

            if($enable_html) $option->disable_html = false;
            else $option->disable_html = true;

            // 높이 설정
            $option->height = $config->editor_height;

            // 자동 저장 유무 옵션 설정
            $option->enable_autosave = $config->enable_autosave=='Y'?true:false;

            // 기타 설정
            $option->primary_key_name = $primary_key_name;
            $option->content_key_name = $content_key_name;

            return $this->getEditor($upload_target_srl, $option);
        }

        /**
         * @brief 자동저장되어 있는 정보를 가져옴
         **/
        function getSavedDoc($upload_target_srl) {
            // 로그인 회원이면 member_srl, 아니면 ipaddress로 저장되어 있는 문서를 찾음
            if(Context::get('is_logged')) {
                $logged_info = Context::get('logged_info');
                $auto_save_args->member_srl = $logged_info->member_srl;
            } else {
                $auto_save_args->ipaddress = $_SERVER['REMOTE_ADDR'];
            }
            $auto_save_args->module_srl = Context::get('module_srl');
            // module_srl이 없으면 현재 모듈
            if(!$auto_save_args->module_srl) {
                $current_module_info = Context::get('current_module_info');
                $auto_save_args->module_srl = $current_module_info->module_srl;
            }

            // DB에서 자동저장 데이터 추출
            $output = executeQuery('editor.getSavedDocument', $auto_save_args);
            $saved_doc = $output->data;

            // 자동저장한 결과가 없으면 null값 return
            if(!$saved_doc) return;

            // 자동저장된 값이 혹시 이미 등록된 글인지 확인
            $oDocumentModel = &getModel('document');
            $oSaved = $oDocumentModel->getDocument($saved_doc->document_srl);
            if($oSaved->isExists()) return;

            // 자동저장 데이터에 문서번호가 있고 이 번호에 파일이 있다면 파일을 모두 이동하고
            // 해당 문서 번호를 editor_sequence로 세팅함
            if($saved_doc->document_srl && $upload_target_srl && !Context::get('document_srl')) {
                $saved_doc->module_srl = $auto_save_args->module_srl;
                $oFileController = &getController('file');
                $oFileController->moveFile($saved_doc->document_srl, $saved_doc->module_srl, $upload_target_srl);
            }
            else if($upload_target_srl) $saved_doc->document_srl = $upload_target_srl;

            // 자동 저장 데이터 변경
            $oEditorController = &getController('editor');
            $oEditorController->deleteSavedDoc(false);
            $oEditorController->doSaveDoc($saved_doc);

            return $saved_doc;
        }

        /**
         * @brief component의 객체 생성
         **/
        function getComponentObject($component, $editor_sequence = 0, $site_srl = 0) {
			if(!preg_match('/^[a-zA-Z0-9_-]+$/',$component) || !preg_match('/^[0-9]+$/', $editor_sequence . $site_srl)) return;

            if(!$this->loaded_component_list[$component][$editor_sequence]) {
                // 해당 컴포넌트의 객체를 생성해서 실행
                $class_path = sprintf('%scomponents/%s/', $this->module_path, $component);
                $class_file = sprintf('%s%s.class.php', $class_path, $component);
                if(!file_exists($class_file)) return new Object(-1, sprintf(Context::getLang('msg_component_is_not_founded'), $component));

                // 클래스 파일을 읽은 후 객체 생성
                require_once($class_file);
                $tmp_fn = create_function('$seq,$path', "return new {$component}(\$seq,\$path);");
				$oComponent = $tmp_fn($editor_sequence, $class_path);
                if(!$oComponent) return new Object(-1, sprintf(Context::getLang('msg_component_is_not_founded'), $component));

                // 설정 정보를 추가
                $component_info = $this->getComponent($component, $site_srl);
                $oComponent->setInfo($component_info);
                $this->loaded_component_list[$component][$editor_sequence] = $oComponent;
            }

            return $this->loaded_component_list[$component][$editor_sequence];
        }

        /**
         * @brief editor skin 목록을 return
         **/
        function getEditorSkinList() {
            return FileHandler::readDir('./modules/editor/skins');
        }

        /**
         * @brief 에디터 컴포넌트 목록 캐시 파일 이름 return
         **/
        function getCacheFile($filter_enabled= true, $site_srl = 0) {
            $lang = Context::getLangType();
            $cache_path = _XE_PATH_.'files/cache/editor/cache/';
            if(!is_dir($cache_path)) FileHandler::makeDir($cache_path);
            $cache_file = $cache_path.'component_list.' . $lang .'.';
            if($filter_enabled) $cache_file .= 'filter.';
            if($site_srl) $cache_file .= $site_srl.'.';
            $cache_file .= 'php';
            return $cache_file;
        }

        /**
         * @brief component 목록을 return (DB정보 보함)
         **/
        function getComponentList($filter_enabled = true, $site_srl=0, $from_db=false) {
            $cache_file = $this->getCacheFile(false, $site_srl);
            if($from_db || !file_exists($cache_file)) {
                $oEditorController = &getController('editor');
                $oEditorController->makeCache(false, $site_srl);
            }

            if(!file_exists($cache_file)) return;
            @include($cache_file);
			$logged_info = Context::get('logged_info');
			if($logged_info && is_array($logged_info->group_list)) 
			{
				$group_list = array_keys($logged_info->group_list);
			}
			else
			{
				$group_list = array();
			}

            if(count($component_list)) {
                foreach($component_list as $key => $val) {
                    if(!trim($key)) continue;
                    if(!is_dir(_XE_PATH_.'modules/editor/components/'.$key)) {
                        FileHandler::removeFile($cache_file);
                        return $this->getComponentList($filter_enabled, $site_srl);
                    }
					if(!$filter_enabled) continue;
					if($val->enabled == "N") {
						unset($component_list->{$key});
						continue;
					}
					if($logged_info->is_admin == "Y" || $logged_info->is_site_admin == "Y") continue;
					if($val->target_group)
					{
						if(!$logged_info) {
							$val->enabled = "N";	
						}
						else {
							$is_granted = false;
							foreach($group_list as $group_srl)
							{
								if(in_array($group_srl, $val->target_group)) $is_granted = true;	
							}
							if(!$is_granted) $val->enabled = "N"; 
						}
					}
					if($val->enabled != "N" && $val->mid_list)
					{
						$mid = Context::get('mid');
						if(!in_array($mid, $val->mid_list)) $val->enabled = "N";
					}
					if($val->enabled == "N") {
						unset($component_list->{$key});
						continue;
					}
                }

            }
            return $component_list;
        }

        /**
         * @brief compnent의 xml+db정보를 구함
         **/
        function getComponent($component_name, $site_srl = 0) {
            $args->component_name = $component_name;

            if($site_srl) {
                $args->site_srl = $site_srl;
                $output = executeQuery('editor.getSiteComponent', $args);
            } else {
                $output = executeQuery('editor.getComponent', $args);
            }
            $component = $output->data;

            $component_name = $component->component_name;

            unset($xml_info);
            $xml_info = $this->getComponentXmlInfo($component_name);
            $xml_info->enabled = $component->enabled;

            $xml_info->target_group = array();

            $xml_info->mid_list = array();

            if($component->extra_vars) {
                $extra_vars = unserialize($component->extra_vars);

                if($extra_vars->target_group) {
                    $xml_info->target_group = $extra_vars->target_group;
                    unset($extra_vars->target_group);
                }

                if($extra_vars->mid_list) {
                    $xml_info->mid_list = $extra_vars->mid_list;
                    unset($extra_vars->mid_list);
                }


                if($xml_info->extra_vars) {
                    foreach($xml_info->extra_vars as $key => $val) {
                        $xml_info->extra_vars->{$key}->value = $extra_vars->{$key};
                    }
                }
            }

            return $xml_info;
        }

        /**
         * @brief component의 xml정보를 읽음
         **/
        function getComponentXmlInfo($component) {
            $lang_type = Context::getLangType();

            // 요청된 컴포넌트의 xml파일 위치를 구함
            $component_path = sprintf('%s/components/%s/', $this->module_path, $component);

            $xml_file = sprintf('%sinfo.xml', $component_path);
            $cache_file = sprintf('./files/cache/editor/%s.%s.php', $component, $lang_type);

            // 캐시된 xml파일이 있으면 include 후 정보 return
            if(file_exists($cache_file) && file_exists($xml_file) && filemtime($cache_file) > filemtime($xml_file)) {
                include($cache_file);
                return $xml_info;
            }

            // 캐시된 파일이 없으면 파싱후 캐싱 후 return
            $oParser = new XmlParser();
            $xml_doc = $oParser->loadXmlFile($xml_file);

            // 정보 정리
            if($xml_doc->component->version && $xml_doc->component->attrs->version == '0.2') {
                $component_info->component_name = $component;
                $component_info->title = $xml_doc->component->title->body;
                $component_info->description = str_replace('\n', "\n", $xml_doc->component->description->body);
                $component_info->version = $xml_doc->component->version->body;
                $component_info->date = $xml_doc->component->date->body;
                $component_info->homepage = $xml_doc->component->link->body;
                $component_info->license = $xml_doc->component->license->body;
                $component_info->license_link = $xml_doc->component->license->attrs->link;

                $buff = '<?php if(!defined("__ZBXE__")) exit(); ';
                $buff .= sprintf('$xml_info->component_name = "%s";', $component_info->component_name);
                $buff .= sprintf('$xml_info->title = "%s";', $component_info->title);
                $buff .= sprintf('$xml_info->description = "%s";', $component_info->description);
                $buff .= sprintf('$xml_info->version = "%s";', $component_info->version);
                $buff .= sprintf('$xml_info->date = "%s";', $component_info->date);
                $buff .= sprintf('$xml_info->homepage = "%s";', $component_info->homepage);
                $buff .= sprintf('$xml_info->license = "%s";', $component_info->license);
                $buff .= sprintf('$xml_info->license_link = "%s";', $component_info->license_link);

                // 작성자 정보
                if(!is_array($xml_doc->component->author)) $author_list[] = $xml_doc->component->author;
                else $author_list = $xml_doc->component->author;

                for($i=0; $i < count($author_list); $i++) {
                    $buff .= sprintf('$xml_info->author['.$i.']->name = "%s";', $author_list[$i]->name->body);
                    $buff .= sprintf('$xml_info->author['.$i.']->email_address = "%s";', $author_list[$i]->attrs->email_address);
                    $buff .= sprintf('$xml_info->author['.$i.']->homepage = "%s";', $author_list[$i]->attrs->link);
                }

                // history
                if($xml_doc->component->history) {
                    if(!is_array($xml_doc->component->history)) $history_list[] = $xml_doc->component->history;
                    else $history_list = $xml_doc->component->history;

                    for($i=0; $i < count($history_list); $i++) {
                        unset($obj);
                        sscanf($history_list[$i]->attrs->date, '%d-%d-%d', $date_obj->y, $date_obj->m, $date_obj->d);
                        $date = sprintf('%04d%02d%02d', $date_obj->y, $date_obj->m, $date_obj->d);
                        $buff .= sprintf('$xml_info->history['.$i.']->description = "%s";', $history_list[$i]->description->body);
                        $buff .= sprintf('$xml_info->history['.$i.']->version = "%s";', $history_list[$i]->attrs->version);
                        $buff .= sprintf('$xml_info->history['.$i.']->date = "%s";', $date);

                        if($history_list[$i]->author) {
                            (!is_array($history_list[$i]->author)) ? $obj->author_list[] = $history_list[$i]->author : $obj->author_list = $history_list[$i]->author;

                            for($j=0; $j < count($obj->author_list); $j++) {
                                $buff .= sprintf('$xml_info->history['.$i.']->author['.$j.']->name = "%s";', $obj->author_list[$j]->name->body);
                                $buff .= sprintf('$xml_info->history['.$i.']->author['.$j.']->email_address = "%s";', $obj->author_list[$j]->attrs->email_address);
                                $buff .= sprintf('$xml_info->history['.$i.']->author['.$j.']->homepage = "%s";', $obj->author_list[$j]->attrs->link);
                            }
                        }

                        if($history_list[$i]->log) {
                            (!is_array($history_list[$i]->log)) ? $obj->log_list[] = $history_list[$i]->log : $obj->log_list = $history_list[$i]->log;

                            for($j=0; $j < count($obj->log_list); $j++) {
                                $buff .= sprintf('$xml_info->history['.$i.']->logs['.$j.']->text = "%s";', $obj->log_list[$j]->body);
                                $buff .= sprintf('$xml_info->history['.$i.']->logs['.$j.']->link = "%s";', $obj->log_list[$j]->attrs->link);
                            }
                        }
                    }
                }


            } else {
                sscanf($xml_doc->component->author->attrs->date, '%d. %d. %d', $date_obj->y, $date_obj->m, $date_obj->d);
                $date = sprintf('%04d%02d%02d', $date_obj->y, $date_obj->m, $date_obj->d);
                $xml_info->component_name = $component;
                $xml_info->title = $xml_doc->component->title->body;
                $xml_info->description = str_replace('\n', "\n", $xml_doc->component->author->description->body);
                $xml_info->version = $xml_doc->component->attrs->version;
                $xml_info->date = $date;
                $xml_info->author->name = $xml_doc->component->author->name->body;
                $xml_info->author->email_address = $xml_doc->component->author->attrs->email_address;
                $xml_info->author->homepage = $xml_doc->component->author->attrs->link;

                $buff = '<?php if(!defined("__ZBXE__")) exit(); ';
                $buff .= sprintf('$xml_info->component_name = "%s";', $xml_info->component_name);
                $buff .= sprintf('$xml_info->title = "%s";', $xml_info->title);
                $buff .= sprintf('$xml_info->description = "%s";', $xml_info->description);
                $buff .= sprintf('$xml_info->version = "%s";', $xml_info->version);
                $buff .= sprintf('$xml_info->date = "%s";', $xml_info->date);
                $buff .= sprintf('$xml_info->author[0]->name = "%s";', $xml_info->author->name);
                $buff .= sprintf('$xml_info->author[0]->email_address = "%s";', $xml_info->author->email_address);
                $buff .= sprintf('$xml_info->author[0]->homepage = "%s";', $xml_info->author->homepage);
            }

            // 추가 변수 정리 (에디터 컴포넌트에서는 text형만 가능)
            $extra_vars = $xml_doc->component->extra_vars->var;
            if($extra_vars) {
                if(!is_array($extra_vars)) $extra_vars = array($extra_vars);
                foreach($extra_vars as $key => $val) {
                    unset($obj);
                    $key = $val->attrs->name;
                    $title = $val->title->body;
                    $description = $val->description->body;
                    $xml_info->extra_vars->{$key}->title = $title;
                    $xml_info->extra_vars->{$key}->description = $description;

                    $buff .= sprintf('$xml_info->extra_vars->%s->%s = "%s";', $key, 'title', $title);
                    $buff .= sprintf('$xml_info->extra_vars->%s->%s = "%s";', $key, 'description', $description);
                }
            }

            $buff .= ' ?>';

            FileHandler::writeFile($cache_file, $buff, "w");

			unset($xml_info);
            include($cache_file);
            return $xml_info;
        }
    }
?>
