<?php
    /**
     * @class  pageAdminView
     * @author NHN (developers@xpressengine.com)
     * @brief  page 모듈의 admin view 클래스
     **/

    class pageAdminView extends page {

        var $module_srl = 0;
        var $list_count = 20;
        var $page_count = 10;

        /**
         * @brief 초기화
         **/
        function init() {
            // module_srl이 있으면 미리 체크하여 존재하는 모듈이면 module_info 세팅
            $module_srl = Context::get('module_srl');

            // module model 객체 생성
            $oModuleModel = &getModel('module');

            // module_srl이 넘어오면 해당 모듈의 정보를 미리 구해 놓음
            if($module_srl) {
                $module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
                if(!$module_info) {
                    Context::set('module_srl','');
                    $this->act = 'list';
                } else {
                    ModuleModel::syncModuleToSite($module_info);
                    $this->module_info = $module_info;
                    Context::set('module_info',$module_info);
                }
            }

            // 모듈 카테고리 목록을 구함
            $module_category = $oModuleModel->getModuleCategories();
            Context::set('module_category', $module_category);

			//Security
			$security = new Security();
			$security->encodeHTML('module_category..title');

            // 템플릿 경로 구함 (page의 경우 tpl에 관리자용 템플릿 모아놓음)
            $this->setTemplatePath($this->module_path.'tpl');

        }

        /**
         * @brief 페이지 관리 목록 보여줌
         **/
        function dispPageAdminContent() {
            $args->sort_index = "module_srl";
            $args->page = Context::get('page');
            $args->list_count = 40;
            $args->page_count = 10;
            $args->s_module_category_srl = Context::get('module_category_srl');

			$s_mid = Context::get('s_mid');
			if($s_mid) $args->s_mid = $s_mid;

			$s_browser_title = Context::get('s_browser_title');
			if($s_browser_title) $args->s_browser_title = $s_browser_title;

            $output = executeQuery('page.getPageList', $args);
            moduleModel::syncModuleToSite($output->data);

            // 템플릿에 쓰기 위해서 context::set
            Context::set('total_count', $output->total_count);
            Context::set('total_page', $output->total_page);
            Context::set('page', $output->page);
            Context::set('page_list', $output->data);
            Context::set('page_navigation', $output->page_navigation);

			//Security
			$security = new Security();
			$security->encodeHTML('page_list..browser_title');
			$security->encodeHTML('page_list..mid');
			$security->encodeHTML('module_info.');

            // 템플릿 파일 지정
            $this->setTemplateFile('index');
        }

        /**
         * @brief 선택된 페이지의 정보 출력
         **/
        function dispPageAdminInfo() {
            // GET parameter에서 module_srl을 가져옴
            $module_srl = Context::get('module_srl');
            $module_info = Context::get('module_info');

            // module_srl 값이 없다면 그냥 index 페이지를 보여줌
            if(!$module_srl) return $this->dispPageAdminContent();

            // 레이아웃이 정해져 있다면 레이아웃 정보를 추가해줌(layout_title, layout)
            if($module_info->layout_srl) {
                $oLayoutModel = &getModel('layout');
                $layout_info = $oLayoutModel->getLayout($module_info->layout_srl);
                $module_info->layout = $layout_info->layout;
                $module_info->layout_title = $layout_info->layout_title;
            }

            // 레이아웃 목록을 구해옴
            $oLayoutModel = &getModel('layout');
            $layout_list = $oLayoutModel->getLayoutList();
            Context::set('layout_list', $layout_list);

			$mobile_layout_list = $oLayoutModel->getLayoutList(0,"M");
			Context::set('mlayout_list', $mobile_layout_list);

			//Security
			$security = new Security();
			$security->encodeHTML('layout_list..layout');
			$security->encodeHTML('layout_list..title');
			$security->encodeHTML('mlayout_list..layout');
			$security->encodeHTML('mlayout_list..title');
			$security->encodeHTML('module_info.');

            // 템플릿 파일 지정
            $this->setTemplateFile('page_info');
        }

        /**
         * @brief 페이지 추가 설정 보여줌
         * 추가설정은 서비스형 모듈들에서 다른 모듈과의 연계를 위해서 설정하는 페이지임
         **/
        function dispPageAdminPageAdditionSetup() {
            // content는 다른 모듈에서 call by reference로 받아오기에 미리 변수 선언만 해 놓음
            $content = '';

            $oEditorView = &getView('editor');
            $oEditorView->triggerDispEditorAdditionSetup($content);
            Context::set('setup_content', $content);

            // 템플릿 파일 지정
            $this->setTemplateFile('addition_setup');

			$security = new Security();
			$security->encodeHTML('module_info.');
        }

        /**
         * @brief 페이지 추가 폼 출력
         **/
        function dispPageAdminInsert() {

            // GET parameter에서 module_srl을 가져옴
            $module_srl = Context::get('module_srl');

            // module_srl이 있으면 해당 모듈의 정보를 구해서 세팅
            if($module_srl) {
                $oModuleModel = &getModel('module');
                $module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
                if($module_info->module_srl == $module_srl) Context::set('module_info',$module_info);
                else {
                    unset($module_info);
                    unset($module_srl);
                }
            }

            // 레이아웃 목록을 구해옴
            $oLayoutModel = &getModel('layout');
            $layout_list = $oLayoutModel->getLayoutList();
            Context::set('layout_list', $layout_list);

			$mobile_layout_list = $oLayoutModel->getLayoutList(0,"M");
			Context::set('mlayout_list', $mobile_layout_list);

			//Security
			$security = new Security();
			$security->encodeHTML('layout_list..layout');
			$security->encodeHTML('layout_list..title');
			$security->encodeHTML('mlayout_list..layout');
			$security->encodeHTML('mlayout_list..title');

            // 템플릿 파일 지정
            $this->setTemplateFile('page_insert');
        }

		function dispPageAdminMobileContent() {
            if($this->module_srl) Context::set('module_srl',$this->module_srl);

            // 캐시 파일 지정
            $cache_file = sprintf("%sfiles/cache/page/%d.%s.m.cache.php", _XE_PATH_, $this->module_info->module_srl, Context::getLangType());
            $interval = (int)($this->module_info->page_caching_interval);
            if($interval>0) {
                if(!file_exists($cache_file)) $mtime = 0;
                else $mtime = filemtime($cache_file);

                if($mtime + $interval*60 > time()) {
                    $page_content = FileHandler::readFile($cache_file);
                } else {
                    $oWidgetController = &getController('widget');
                    $page_content = $oWidgetController->transWidgetCode($this->module_info->mcontent);
                    FileHandler::writeFile($cache_file, $page_content);
                }
            } else {
                if(file_exists($cache_file)) FileHandler::removeFile($cache_file);
                $page_content = $this->module_info->mcontent;
            }

            Context::set('module_info', $this->module_info);
            Context::set('page_content', $page_content);

            $this->setTemplateFile('mcontent');
		}

		function dispPageAdminMobileContentModify() {
            Context::set('module_info', $this->module_info);

            // 내용을 세팅
            $content = Context::get('mcontent');
            if(!$content) $content = $this->module_info->mcontent;
            Context::set('content', $content);

            // 내용중 위젯들을 변환
            $oWidgetController = &getController('widget');
            $content = $oWidgetController->transWidgetCode($content, true);
            Context::set('page_content', $content);

            // 위젯 목록을 세팅
            $oWidgetModel = &getModel('widget');
            $widget_list = $oWidgetModel->getDownloadedWidgetList();
            Context::set('widget_list', $widget_list);

            //Security
			$security = new Security();
			$security->encodeHTML('widget_list..title','module_info.mid');

			// 템플릿 파일 지정
            $this->setTemplateFile('page_mobile_content_modify');
		}

        /**
         * @brief 페이지 내용 수정
         **/
        function dispPageAdminContentModify() {
            // 모듈 정보를 세팅
            Context::set('module_info', $this->module_info);

            // 내용을 세팅
            $content = Context::get('content');
            if(!$content) $content = $this->module_info->content;
            Context::set('content', $content);

            // 내용중 위젯들을 변환
            $oWidgetController = &getController('widget');
            $content = $oWidgetController->transWidgetCode($content, true);
            Context::set('page_content', $content);

            // 위젯 목록을 세팅
            $oWidgetModel = &getModel('widget');
            $widget_list = $oWidgetModel->getDownloadedWidgetList();
            Context::set('widget_list', $widget_list);

			//Security
			$security = new Security();
			$security->encodeHTML('widget_list..title','module_info.mid');

			// 템플릿 파일 지정
            $this->setTemplateFile('page_content_modify');
        }

        /**
         * @brief 페이지 삭제 화면 출력
         **/
        function dispPageAdminDelete() {
            $module_srl = Context::get('module_srl');
            if(!$module_srl) return $this->dispContent();

            $oModuleModel = &getModel('module');
            $module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
            Context::set('module_info',$module_info);

			//Security
			$security = new Security();
			$security->encodeHTML('module_info.module','module_info.mid');

            // 템플릿 파일 지정
            $this->setTemplateFile('page_delete');

			$security = new Security();
			$security->encodeHTML('module_info.');
        }

        /**
         * @brief 권한 목록 출력
         **/
        function dispPageAdminGrantInfo() {
            // 공통 모듈 권한 설정 페이지 호출
            $oModuleAdminModel = &getAdminModel('module');
            $grant_content = $oModuleAdminModel->getModuleGrantHTML($this->module_info->module_srl, $this->xml_info->grant);
            Context::set('grant_content', $grant_content);

            $this->setTemplateFile('grant_list');

			$security = new Security();
			$security->encodeHTML('module_info.');
        }
    }
?>
