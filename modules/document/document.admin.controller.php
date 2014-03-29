<?php
    /**
     * @class  documentAdminController
     * @author NHN (developers@xpressengine.com)
     * @brief  document 모듈의 admin controller 클래스
     **/

    class documentAdminController extends document {

        /**
         * @brief 초기화
         **/
        function init() {
        }

        /**
         * @brief 관리자 페이지에서 선택된 문서들 삭제
         **/
        function procDocumentAdminDeleteChecked() {
            // 선택된 글이 없으면 오류 표시
            $cart = Context::get('cart');
            if(!$cart) return $this->stop('msg_cart_is_null');
            $document_srl_list= explode('|@|', $cart);
            $document_count = count($document_srl_list);
            if(!$document_count) return $this->stop('msg_cart_is_null');

            // 글삭제
            $oDocumentController = &getController('document');
            for($i=0;$i<$document_count;$i++) {
                $document_srl = trim($document_srl_list[$i]);
                if(!$document_srl) continue;

                $oDocumentController->deleteDocument($document_srl, true);
            }

            $this->setMessage( sprintf(Context::getLang('msg_checked_document_is_deleted'), $document_count) );
        }

        /** 
         * @brief 특정 게시물들의 소속 모듈 변경 (게시글 이동시에 사용)
         **/
        function moveDocumentModule($document_srl_list, $module_srl, $category_srl) {
            if(!count($document_srl_list)) return;

            $oDocumentModel = &getModel('document');
            $oDocumentController = &getController('document');

            $oDB = &DB::getInstance();
            $oDB->begin();

            $triggerObj->document_srls = implode(',',$document_srl_list);
            $triggerObj->module_srl = $module_srl;
            $triggerObj->category_srl = $category_srl;

            // Call trigger (before)
            $output = ModuleHandler::triggerCall('document.moveDocumentModule', 'before', $triggerObj);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            for($i=count($document_srl_list)-1;$i>=0;$i--) {
                $document_srl = $document_srl_list[$i];
                $oDocument = $oDocumentModel->getDocument($document_srl);
                if(!$oDocument->isExists()) continue;

                $source_category_srl = $oDocument->get('category_srl');

                unset($obj);
                $obj = $oDocument->getObjectVars();

                // 대상 모듈이 다를 경우 첨부파일 이동
                if($module_srl != $obj->module_srl && $oDocument->hasUploadedFiles()) {
                    $oFileController = &getController('file');

                    $files = $oDocument->getUploadedFiles();
                    foreach($files as $key => $val) {
                        $file_info = array();
                        $file_info['tmp_name'] = $val->uploaded_filename;
                        $file_info['name'] = $val->source_filename;
                        $inserted_file = $oFileController->insertFile($file_info, $module_srl, $obj->document_srl, $val->download_count, true);
                        if($inserted_file && $inserted_file->toBool()) {
                            // 이미지/동영상등일 경우
                            if($val->direct_download == 'Y') {
                                $source_filename = substr($val->uploaded_filename,2);
                                $target_filename = substr($inserted_file->get('uploaded_filename'),2);
                                $obj->content = str_replace($source_filename, $target_filename, $obj->content);

                            // binary 파일일 경우
                            } else {
                                $obj->content = str_replace('file_srl='.$val->file_srl, 'file_srl='.$inserted_file->get('file_srl'), $obj->content);
                                $obj->content = str_replace('sid='.$val->sid, 'sid='.$inserted_file->get('sid'), $obj->content);
                            }
                        }

                        // 기존 파일 삭제
                        $oFileController->deleteFile($val->file_srl);
                    }

                    // 등록된 모든 파일을 유효로 변경
                    $oFileController->setFilesValid($obj->document_srl);
                }

                if($module_srl != $obj->module_srl)
                {
                    $oDocumentController->deleteDocumentAliasByDocument($obj->document_srl);
                }

                // 게시물의 모듈 이동
                $obj->module_srl = $module_srl;
                $obj->category_srl = $category_srl;
                $output = executeQuery('document.updateDocumentModule', $obj);
                if(!$output->toBool()) {
                    $oDB->rollback();
                    return $output;
                }

                // 카테고리가 변경되었으면 검사후 없는 카테고리면 0으로 세팅
                if($source_category_srl != $category_srl) {
                    if($source_category_srl) $oDocumentController->updateCategoryCount($oDocument->get('module_srl'), $source_category_srl);
                    if($category_srl) $oDocumentController->updateCategoryCount($module_srl, $category_srl);
                }

            }

            $args->document_srls = implode(',',$document_srl_list);
            $args->module_srl = $module_srl;

            // 댓글의 이동
            $output = executeQuery('comment.updateCommentModule', $args);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            $output = executeQuery('comment.updateCommentListModule', $args);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            // 엮인글의 이동
            $output = executeQuery('trackback.updateTrackbackModule', $args);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            // 태그
            $output = executeQuery('tag.updateTagModule', $args);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            // Call trigger (before)
            $output = ModuleHandler::triggerCall('document.moveDocumentModule', 'after', $triggerObj);
            if(!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }
            
            $oDB->commit();
            return new Object();
        }

        /** 
         * @brief 게시글의 복사
         **/
        function copyDocumentModule($document_srl_list, $module_srl, $category_srl) {
            if(!count($document_srl_list)) return;

            $oDocumentModel = &getModel('document');
            $oDocumentController = &getController('document');

            $oFileModel = &getModel('file');

            $oDB = &DB::getInstance();
            $oDB->begin();

            for($i=count($document_srl_list)-1;$i>=0;$i--) {
                $document_srl = $document_srl_list[$i];
                $oDocument = $oDocumentModel->getDocument($document_srl);
                if(!$oDocument->isExists()) continue;

                $obj = null;
                $obj = $oDocument->getObjectVars();
                $obj->module_srl = $module_srl;
                $obj->document_srl = getNextSequence();
                $obj->category_srl = $category_srl;
                $obj->password_is_hashed = true;
                $obj->comment_count = 0;
                $obj->trackback_count = 0;

                // 첨부파일 미리 등록
                if($oDocument->hasUploadedFiles()) {
                    $files = $oDocument->getUploadedFiles();
                    foreach($files as $key => $val) {
                        $file_info = array();
                        $file_info['tmp_name'] = $val->uploaded_filename;
                        $file_info['name'] = $val->source_filename;
                        $oFileController = &getController('file');
                        $inserted_file = $oFileController->insertFile($file_info, $module_srl, $obj->document_srl, 0, true);

                        // 이미지/동영상등일 경우
                        if($val->direct_download == 'Y') {
                            $source_filename = substr($val->uploaded_filename,2);
                            $target_filename = substr($inserted_file->get('uploaded_filename'),2);
                            $obj->content = str_replace($source_filename, $target_filename, $obj->content);

                        // binary 파일일 경우
                        } else {
                            $obj->content = str_replace('file_srl='.$val->file_srl, 'file_srl='.$inserted_file->get('file_srl'), $obj->content);
                            $obj->content = str_replace('sid='.$val->sid, 'sid='.$inserted_file->get('sid'), $obj->content);
                        }
                    }
                }
                
                // 글의 등록
                $output = $oDocumentController->insertDocument($obj, true);
                if(!$output->toBool()) {
                    $oDB->rollback();
                    return $output;
                }

                // 댓글 이전
                if($oDocument->getCommentCount()) {
                    $oCommentModel = &getModel('comment');
                    $comment_output = $oCommentModel->getCommentList($document_srl, 0, true, 99999999);
                    $comments = $comment_output->data;
                    if(count($comments)) {
                        $oCommentController = &getController('comment');
                        $success_count = 0;
                        $p_comment_srl = array();
                        foreach($comments as $comment_obj) {
                            $comment_srl = getNextSequence();
                            $p_comment_srl[$comment_obj->comment_srl] = $comment_srl;

                            $comment_obj->module_srl = $obj->module_srl;
                            $comment_obj->document_srl = $obj->document_srl;
                            $comment_obj->comment_srl = $comment_srl;

                            if($comment_obj->parent_srl) $comment_obj->parent_srl = $p_comment_srl[$comment_obj->parent_srl];

                            $output = $oCommentController->insertComment($comment_obj, true);
                            if($output->toBool()) $success_count ++;
                        }
                        $oDocumentController->updateCommentCount($obj->document_srl, $success_count, $comment_obj->nick_name, true);

                    }

                }

                // 엮인글 이전
                if($oDocument->getTrackbackCount()) {
                    $oTrackbackModel = &getModel('trackback');
                    $trackbacks = $oTrackbackModel->getTrackbackList($oDocument->document_srl);
                    if(count($trackbacks)) {
                        $success_count = 0;
                        foreach($trackbacks as $trackback_obj) {
                            $trackback_obj->trackback_srl = getNextSequence();
                            $trackback_obj->module_srl = $obj->module_srl;
                            $trackback_obj->document_srl = $obj->document_srl;
                            $output = executeQuery('trackback.insertTrackback', $trackback_obj);
                            if($output->toBool()) $success_count++;
                        }

                        // 엮인글 수 업데이트
                        $oDocumentController->updateTrackbackCount($obj->document_srl, $success_count);
                    }
                }

                $copied_srls[$document_srl] = $obj->document_srl;
            }
            $oDB->commit();

            $output = new Object();
            $output->add('copied_srls', $copied_srls);
            return $output;
        }

        /**
         * @brief 특정 모듈의 전체 문서 삭제
         **/
        function deleteModuleDocument($module_srl) {
            $args->module_srl = $module_srl;
            $output = executeQuery('document.deleteModuleDocument', $args);
            return $output;
        }

        /**
         * @brief 문서 모듈의 기본설정 저장
         **/
        function procDocumentAdminInsertConfig() {
            // 기본 정보를 받음
            $config = Context::gets('thumbnail_type');

            // module Controller 객체 생성하여 입력
            $oModuleController = &getController('module');
            $output = $oModuleController->insertModuleConfig('document',$config);
            return $output;
        }

        /**
         * @brief 선택된 글들에 대해 신고 취소
         **/
        function procDocumentAdminCancelDeclare() {
            $document_srl = trim(Context::get('document_srl'));

            if($document_srl) {
                $args->document_srl = $document_srl;
                $output = executeQuery('document.deleteDeclaredDocuments', $args);
                if(!$output->toBool()) return $output;
            }
        }

        /**
         * @brief 모든 생성된 섬네일 삭제
         **/
        function procDocumentAdminDeleteAllThumbnail() {

            // files/attaches/images/ 디렉토리를 순환하면서 thumbnail_*.jpg 파일을 모두 삭제 (1.0.4 이전까지)
            $this->deleteThumbnailFile('./files/attach/images');

            // files/cache/thumbnails 디렉토리 자체를 삭제 (1.0.5 이후 변경된 섬네일 정책)
            FileHandler::removeFilesInDir('./files/cache/thumbnails');

            $this->setMessage('success_deleted');
        }

        function deleteThumbnailFile($path) {
            $directory = dir($path);
            while($entry = $directory->read()) {
                if ($entry != "." && $entry != "..") {
                    if (is_dir($path."/".$entry)) {
                        $this->deleteThumbnailFile($path."/".$entry);
                    } else {
                        if(!preg_match('/^thumbnail_([^\.]*)\.jpg$/i',$entry)) continue;
                        FileHandler::removeFile($path.'/'.$entry);
                    }
                }
            }
            $directory->close();
        }

        /**
         * @brief 모듈의 확장 변수 추가 또는 수정
         **/
        function procDocumentAdminInsertExtraVar() {
            $module_srl = Context::get('module_srl');
            $var_idx = Context::get('var_idx');
            $name = Context::get('name');
            $type = Context::get('type');
            $is_required = Context::get('is_required');
            $default = Context::get('default');
            $desc = Context::get('desc');
            $search = Context::get('search');
			$eid = Context::get('eid');

            if(!$module_srl || !$name || !$eid) return new Object(-1,'msg_invalid_request');

            // idx가 지정되어 있지 않으면 최고 값을 지정
            if(!$var_idx) {
                $obj->module_srl = $module_srl;
                $output = executeQuery('document.getDocumentMaxExtraKeyIdx', $obj);
                $var_idx = $output->data->var_idx+1;
            }

			// 이미 존재하는 모듈 이름인지 체크
			$obj->module_srl = $module_srl;
			$obj->var_idx = $var_idx;
			$obj->eid = $eid;
            $output = executeQuery('document.isExistsExtraKey', $obj);
            if(!$output->toBool() || $output->data->count) {
                return new Object(-1, 'msg_extra_name_exists');
            }

            // insert or update
            $oDocumentController = &getController('document');
            $output = $oDocumentController->insertDocumentExtraKey($module_srl, $var_idx, $name, $type, $is_required, $search, $default, $desc, $eid);
            if(!$output->toBool()) return $output;

            $this->setMessage('success_registed');
        }

        /**
         * @brief 모듈의 확장 변수 삭제
         **/
        function procDocumentAdminDeleteExtraVar() {
            $module_srl = Context::get('module_srl');
            $var_idx = Context::get('var_idx');
            if(!$module_srl || !$var_idx) return new Object(-1,'msg_invalid_request');

            $oDocumentController = &getController('document');
            $output = $oDocumentController->deleteDocumentExtraKeys($module_srl, $var_idx);
            if(!$output->toBool()) return $output;

            $this->setMessage('success_deleted');
        }
 
        /**
         * @brief 확장변수 순서 조절
         **/
        function procDocumentAdminMoveExtraVar() {
            $type = Context::get('type');
            $module_srl = Context::get('module_srl');
            $var_idx = Context::get('var_idx');

            if(!$type || !$module_srl || !$var_idx) return new Object(-1,'msg_invalid_request');

            $oModuleModel = &getModel('module');
            $module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
            if(!$module_info->module_srl) return new Object(-1,'msg_invalid_request');

            $oDocumentModel = &getModel('document');
            $extra_keys = $oDocumentModel->getExtraKeys($module_srl);
            if(!$extra_keys[$var_idx]) return new Object(-1,'msg_invalid_request');

            if($type == 'up') $new_idx = $var_idx-1;
            else $new_idx = $var_idx+1;
            if($new_idx<1) return new Object(-1,'msg_invalid_request');

            // 바꿀 idx가 없으면 바로 업데이트
            if(!$extra_keys[$new_idx]) {
                $args->module_srl = $module_srl;
                $args->var_idx = $var_idx;
                $args->new_idx = $new_idx;
                $output = executeQuery('document.updateDocumentExtraKeyIdx', $args);
                if(!$output->toBool()) return $output;
                $output = executeQuery('document.updateDocumentExtraVarIdx', $args);
                if(!$output->toBool()) return $output;
            // 있으면 기존의 꺼랑 교체
            } else {
                $args->module_srl = $module_srl;
                $args->var_idx = $new_idx;
                $args->new_idx = -10000;
                $output = executeQuery('document.updateDocumentExtraKeyIdx', $args);
                if(!$output->toBool()) return $output;
                $output = executeQuery('document.updateDocumentExtraVarIdx', $args);
                if(!$output->toBool()) return $output;

                $args->var_idx = $var_idx;
                $args->new_idx = $new_idx;
                $output = executeQuery('document.updateDocumentExtraKeyIdx', $args);
                if(!$output->toBool()) return $output;
                $output = executeQuery('document.updateDocumentExtraVarIdx', $args);
                if(!$output->toBool()) return $output;

                $args->var_idx = -10000;
                $args->new_idx = $var_idx;
                $output = executeQuery('document.updateDocumentExtraKeyIdx', $args);
                if(!$output->toBool()) return $output;
                $output = executeQuery('document.updateDocumentExtraVarIdx', $args);
                if(!$output->toBool()) return $output;
            }
        }

        function procDocumentAdminInsertAlias() {
            $args = Context::gets('module_srl','document_srl', 'alias_title');
            $alias_srl = Context::get('alias_srl');
            if(!$alias_srl) 
            {
                $args->alias_srl = getNextSequence();
                $query = "document.insertAlias";
            }
            else 
            {
                $args->alias_srl = $alias_srl;
                $query = "document.updateAlias";
            }
            $output = executeQuery($query, $args);
            if(!$output->toBool())
            {
                return $output;
            }
        }

        function procDocumentAdminDeleteAlias() {
            $alias_srl = Context::get('alias_srl');
            $args->alias_srl = $alias_srl;
            $output = executeQuery("document.deleteAlias", $args);
            if (!$output->toBool())
            {
                return $output;
            }
        }

        function procDocumentAdminRestoreTrash() {
            $trash_srl = Context::get('trash_srl');
			$this->restoreTrash($trash_srl);
        }

		function restoreTrash($trash_srl){
            $oDB = &DB::getInstance();
            $oDocumentModel = &getModel('document');

            $trash_args->trash_srl = $trash_srl;

            $output = executeQuery('document.getTrash', $trash_args);
            if (!$output->toBool()) {
                return $output;
            }

            $document_args->document_srl = $output->data->document_srl;
            $document_args->module_srl = $output->data->module_srl;
            $document_args->member_srl = $output->data->member_srl;
            $document_args->ipaddress = $output->data->ipaddress;
            $document_args->update_order = $output->data->update_order;

            $oDocument = $oDocumentModel->getDocument($document_args->document_srl);

            // begin transaction
            $oDB->begin();

            $output = executeQuery('document.updateDocument', $document_args);
            if (!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            $output = executeQuery('document.deleteTrash', $trash_args);
            if (!$output->toBool()) {
                $oDB->rollback();
                return $output;
            }

            // 임시 저장되었던 글이 아닌 경우, 등록된 첨부파일의 상태를 유효로 지정
            if($oDocument->hasUploadedFiles() && $document_args->member_srl != $document_args->module_srl) {
                $args->upload_target_srl = $oDocument->document_srl;
                $args->isvalid = 'Y';
                executeQuery('file.updateFileValid', $args);
            }

            // trigger 호출 (after)
            if($output->toBool()) {
                $trigger_output = ModuleHandler::triggerCall('document.restoreTrash', 'after', $document_args);
                if(!$trigger_output->toBool()) {
                    $oDB->rollback();
                    return $trigger_output;
                }
            }

            // commit
            $oDB->commit();
			return $output;
		}

    }
?>
