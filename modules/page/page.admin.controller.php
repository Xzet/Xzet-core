<?php
    /**
     * @class  pageAdminController
     * @author NHN (developers@xpressengine.com)
     * @brief  page 모듈의 admin controller class
     **/

    class pageAdminController extends page {

        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief 페이지 추가
         **/
        function procPageAdminInsert() {
            // module 모듈의 model/controller 객체 생성
            $oModuleController = &getController('module');
            $oModuleModel = &getModel('module');

            // 게시판 모듈의 정보 설정
            $args = Context::getRequestVars();
            $args->module = 'page';
            $args->mid = $args->page_name;
            unset($args->page_name);

			if($args->use_mobile != 'Y') $args->use_mobile = '';

            // module_srl이 넘어오면 원 모듈이 있는지 확인
            if($args->module_srl) {
                $module_info = $oModuleModel->getModuleInfoByModuleSrl($args->module_srl);
                if($module_info->module_srl != $args->module_srl) {
					unset($args->module_srl);
				}
				else
				{
					foreach($args as $key=>$val)
					{
						$module_info->{$key} = $val;
					}
					$args = $module_info;
				}
            }

            // module_srl의 값에 따라 insert/update
            if(!$args->module_srl) {
                $output = $oModuleController->insertModule($args);
                $msg_code = 'success_registed';
            } else {
                $output = $oModuleController->updateModule($args);
                $msg_code = 'success_updated';
            }

            if(!$output->toBool()) return $output;

            $this->add("page", Context::get('page'));
            $this->add('module_srl',$output->get('module_srl'));
            $this->setMessage($msg_code);
        }

		function putDocumentsInPageToArray($target, &$array)
		{
			if(!$target) return;
			preg_match_all('!<img src="./common/tpl/images/widget_bg.jpg" ([^>]+)!is', $target, $matches);
			$pattern = '!document_srl="(\d+)"!';
			foreach($matches[1] as $match)
			{
				$match2 = null;
				preg_match($pattern, $match, $match2);
				if(count($match2))
				{
					$array[(int)$match2[1]] = 1;
				}
			}
		}

        /**
         * @brief 페이지 수정 내용 저장
         **/
        function procPageAdminInsertContent() {
            $module_srl = Context::get('module_srl');
            $content = Context::get('content');
            if(!$module_srl) return new Object(-1,'msg_invalid_request');
			$mcontent = Context::get('mcontent');
			$type = Context::get('type');

            // 페이지의 원 정보를 구해옴
            $oModuleModel = &getModel('module');
            $module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
			if($type == "mobile") {
                if(!$mcontent) $mcontent = '';
				$module_info->mcontent = $mcontent;
			}
			else {
				if(!isset($content)) $content ='';
				$module_info->content = $content;
			}

			$document_srls = array();
			$this->putDocumentsInPageToArray($module_info->content, $document_srls);
			$this->putDocumentsInPageToArray($module_info->mcontent, $document_srls);

            $oDocumentModel = &getModel('document');
            $oDocumentController = &getController('document');
            $obj->module_srl = $module_srl;
            $obj->list_count = 99999999;
            $output = $oDocumentModel->getDocumentList($obj);
			if(count($output->data)) {
				foreach($output->data as $document)
				{
					if($document_srls[$document->document_srl]) continue;
					$oDocumentController->deleteDocument($document->document_srl, true);
				}
			}
            // module 모듈의 controller 객체 생성
            $oModuleController = &getController('module');

            // 저장
            $output = $oModuleController->updateModule($module_info);
            if(!$output->toBool()) return $output;

            // 해당 페이지에 첨부된 파일의 상태를 유효로 변경
            $oFileController = &getController('file');
            $oFileController->setFilesValid($module_info->module_srl);

            // 캐시파일 재생성
            //$this->procPageAdminRemoveWidgetCache();

            $this->add("module_srl", $module_info->module_srl);
            $this->add("page", Context::get('page'));
            $this->add("mid", $module_info->mid);
            $this->setMessage($msg_code);
        }

        /**
         * @brief 페이지 삭제
         **/
        function procPageAdminDelete() {
            $module_srl = Context::get('module_srl');

            // 원본을 구해온다
            $oModuleController = &getController('module');
            $output = $oModuleController->deleteModule($module_srl);
            if(!$output->toBool()) return $output;

            $this->add('module','page');
            $this->add('page',Context::get('page'));
            $this->setMessage('success_deleted');
        }

        /**
         * @brief 페이지 기본 정보의 추가
         **/
        function procPageAdminInsertConfig() {
            // 기본 정보를 받음
            $args = Context::getRequestVars();

            // module Controller 객체 생성하여 입력
            $oModuleController = &getController('module');
            $output = $oModuleController->insertModuleConfig('page',$args);
            return $output;
        }

        /**
         * @brief 첨부파일 업로드
         **/
        function procUploadFile() {
            // 기본적으로 필요한 변수 설정
            $upload_target_srl = Context::get('upload_target_srl');
            $module_srl = Context::get('module_srl');

            // file class의 controller 객체 생성
            $oFileController = &getController('file');
            $output = $oFileController->insertFile($module_srl, $upload_target_srl);

            // 첨부파일의 목록을 java script로 출력
            $oFileController->printUploadedFileList($upload_target_srl);
        }

        /**
         * @brief 첨부파일 삭제
         * 에디터에서 개별 파일 삭제시 사용
         **/
        function procDeleteFile() {
            // 기본적으로 필요한 변수인 upload_target_srl, module_srl을 설정
            $upload_target_srl = Context::get('upload_target_srl');
            $module_srl = Context::get('module_srl');
            $file_srl = Context::get('file_srl');

            // file class의 controller 객체 생성
            $oFileController = &getController('file');
            if($file_srl) $output = $oFileController->deleteFile($file_srl, $this->grant->manager);

            // 첨부파일의 목록을 java script로 출력
            $oFileController->printUploadedFileList($upload_target_srl);
        }

        /**
         * @brief 지정된 페이지의 위젯 캐시 파일 지우기
         **/
        function procPageAdminRemoveWidgetCache() {
            $module_srl = Context::get('module_srl');

            $oModuleModel = &getModel('module');
            $module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);

            $content = $module_info->content;

            $cache_file = sprintf("%sfiles/cache/page/%d.%s.cache.php", _XE_PATH_, $module_info->module_srl, Context::getLangType());
            if(file_exists($cache_file)) FileHandler::removeFile($cache_file);

            // widget controller 의 캐시파일 재생성 실행
            $oWidgetController = &getController('widget');
            $oWidgetController->recompileWidget($content);
        }

    }
?>
