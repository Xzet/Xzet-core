<?php
    /**
     * @class  file
     * @author NHN (developers@xpressengine.com)
     * @brief  file 모듈의 high 클래스
     **/

    class file extends ModuleObject {

        /**
         * @brief 설치시 추가 작업이 필요할시 구현
         **/
        function moduleInstall() {
            // action forward에 등록 (관리자 모드에서 사용하기 위함)
            $oModuleController = &getController('module');

            // 첨부파일의 기본 설정 저장
            $config->allowed_filesize = '2';
            $config->allowed_attach_size = '2';
            $config->allowed_filetypes = '*.*';
            $oModuleController->insertModuleConfig('file', $config);

            // file 모듈에서 사용할 디렉토리 생성
            FileHandler::makeDir('./files/attach/images');
            FileHandler::makeDir('./files/attach/binaries');

            // 2007. 10. 17 글/댓글의 입력/수정/삭제에 대한 trigger 등록
            $oModuleController->insertTrigger('document.insertDocument', 'file', 'controller', 'triggerCheckAttached', 'before');
            $oModuleController->insertTrigger('document.insertDocument', 'file', 'controller', 'triggerAttachFiles', 'after');
            $oModuleController->insertTrigger('document.updateDocument', 'file', 'controller', 'triggerCheckAttached', 'before');
            $oModuleController->insertTrigger('document.updateDocument', 'file', 'controller', 'triggerAttachFiles', 'after');
            $oModuleController->insertTrigger('document.deleteDocument', 'file', 'controller', 'triggerDeleteAttached', 'after');
            $oModuleController->insertTrigger('comment.insertComment', 'file', 'controller', 'triggerCommentCheckAttached', 'before');
            $oModuleController->insertTrigger('comment.insertComment', 'file', 'controller', 'triggerCommentAttachFiles', 'after');
            $oModuleController->insertTrigger('comment.updateComment', 'file', 'controller', 'triggerCommentCheckAttached', 'before');
            $oModuleController->insertTrigger('comment.updateComment', 'file', 'controller', 'triggerCommentAttachFiles', 'after');
            $oModuleController->insertTrigger('comment.deleteComment', 'file', 'controller', 'triggerCommentDeleteAttached', 'after');

            // 2009. 6. 9 자동저장문서 삭제시 첨부 파일도 같이 삭제
            $oModuleController->insertTrigger('editor.deleteSavedDoc', 'file', 'controller', 'triggerDeleteAttached', 'after');

            // 2007. 10. 17 모듈이 삭제될때 등록된 첨부파일도 모두 삭제하는 트리거 추가
            $oModuleController->insertTrigger('module.deleteModule', 'file', 'controller', 'triggerDeleteModuleFiles', 'after');

            // 2007. 10. 19 출력하기 전에 file 권한등을 세팅하는 트리거 호출
            $oModuleController->insertTrigger('module.dispAdditionSetup', 'file', 'view', 'triggerDispFileAdditionSetup', 'before');

            return new Object();
        }

        /**
         * @brief 설치가 이상이 없는지 체크하는 method
         **/
        function checkUpdate() {
            $oDB = &DB::getInstance();
            $oModuleModel = &getModel('module');

            // 2007. 10. 17 글/댓글의 입력/수정/삭제에 대한 trigger 등록
            if(!$oModuleModel->getTrigger('document.insertDocument', 'file', 'controller', 'triggerCheckAttached', 'before')) return true;
            if(!$oModuleModel->getTrigger('document.insertDocument', 'file', 'controller', 'triggerAttachFiles', 'after')) return true;
            if(!$oModuleModel->getTrigger('document.updateDocument', 'file', 'controller', 'triggerCheckAttached', 'before')) return true;
            if(!$oModuleModel->getTrigger('document.updateDocument', 'file', 'controller', 'triggerAttachFiles', 'after')) return true;
            if(!$oModuleModel->getTrigger('document.deleteDocument', 'file', 'controller', 'triggerDeleteAttached', 'after')) return true;
            if(!$oModuleModel->getTrigger('comment.insertComment', 'file', 'controller', 'triggerCommentCheckAttached', 'before')) return true;
            if(!$oModuleModel->getTrigger('comment.insertComment', 'file', 'controller', 'triggerCommentAttachFiles', 'after')) return true;
            if(!$oModuleModel->getTrigger('comment.updateComment', 'file', 'controller', 'triggerCommentCheckAttached', 'before')) return true;
            if(!$oModuleModel->getTrigger('comment.updateComment', 'file', 'controller', 'triggerCommentAttachFiles', 'after')) return true;
            if(!$oModuleModel->getTrigger('comment.deleteComment', 'file', 'controller', 'triggerCommentDeleteAttached', 'after')) return true;

            // 2009. 6. 9 자동저장문서 삭제시 첨부 파일도 같이 삭제
            if(!$oModuleModel->getTrigger('editor.deleteSavedDoc', 'file', 'controller', 'triggerDeleteAttached', 'after')) return true;

            // 2007. 10. 17 모듈이 삭제될때 등록된 첨부파일도 모두 삭제하는 트리거 추가
            if(!$oModuleModel->getTrigger('module.deleteModule', 'file', 'controller', 'triggerDeleteModuleFiles', 'after')) return true;

            // 2007. 10. 19 출력하기 전에 file 권한등을 세팅하는 트리거 호출
            if(!$oModuleModel->getTrigger('module.dispAdditionSetup', 'file', 'view', 'triggerDispFileAdditionSetup', 'before')) return true;

            // 타겟 판별용 필드
            if(!$oDB->isColumnExists('files', 'upload_target_type')) return true;

            return false;
        }

        /**
         * @brief 업데이트 실행
         **/
        function moduleUpdate() {
            $oDB = &DB::getInstance();
            $oModuleModel = &getModel('module');
            $oModuleController = &getController('module');

            // 2007. 10. 17 글/댓글의 입력/수정/삭제에 대한 trigger 등록
            if(!$oModuleModel->getTrigger('document.insertDocument', 'file', 'controller', 'triggerCheckAttached', 'before'))
                $oModuleController->insertTrigger('document.insertDocument', 'file', 'controller', 'triggerCheckAttached', 'before');

            if(!$oModuleModel->getTrigger('document.insertDocument', 'file', 'controller', 'triggerAttachFiles', 'after'))
                $oModuleController->insertTrigger('document.insertDocument', 'file', 'controller', 'triggerAttachFiles', 'after');

            if(!$oModuleModel->getTrigger('document.updateDocument', 'file', 'controller', 'triggerCheckAttached', 'before'))
                $oModuleController->insertTrigger('document.updateDocument', 'file', 'controller', 'triggerCheckAttached', 'before');

            if(!$oModuleModel->getTrigger('document.updateDocument', 'file', 'controller', 'triggerAttachFiles', 'after'))
                $oModuleController->insertTrigger('document.updateDocument', 'file', 'controller', 'triggerAttachFiles', 'after');

            if(!$oModuleModel->getTrigger('document.deleteDocument', 'file', 'controller', 'triggerDeleteAttached', 'after'))
                $oModuleController->insertTrigger('document.deleteDocument', 'file', 'controller', 'triggerDeleteAttached', 'after');

            if(!$oModuleModel->getTrigger('comment.insertComment', 'file', 'controller', 'triggerCommentCheckAttached', 'before'))
                $oModuleController->insertTrigger('comment.insertComment', 'file', 'controller', 'triggerCommentCheckAttached', 'before');

            if(!$oModuleModel->getTrigger('comment.insertComment', 'file', 'controller', 'triggerCommentAttachFiles', 'after'))
                $oModuleController->insertTrigger('comment.insertComment', 'file', 'controller', 'triggerCommentAttachFiles', 'after');

            if(!$oModuleModel->getTrigger('comment.updateComment', 'file', 'controller', 'triggerCommentCheckAttached', 'before'))
                $oModuleController->insertTrigger('comment.updateComment', 'file', 'controller', 'triggerCommentCheckAttached', 'before');

            if(!$oModuleModel->getTrigger('comment.updateComment', 'file', 'controller', 'triggerCommentAttachFiles', 'after'))
                $oModuleController->insertTrigger('comment.updateComment', 'file', 'controller', 'triggerCommentAttachFiles', 'after');

            if(!$oModuleModel->getTrigger('comment.deleteComment', 'file', 'controller', 'triggerCommentDeleteAttached', 'after'))
                $oModuleController->insertTrigger('comment.deleteComment', 'file', 'controller', 'triggerCommentDeleteAttached', 'after');

            // 2009. 6. 9 자동저장문서 삭제시 첨부 파일도 같이 삭제
            if(!$oModuleModel->getTrigger('editor.deleteSavedDoc', 'file', 'controller', 'triggerDeleteAttached', 'after'))
                $oModuleController->insertTrigger('editor.deleteSavedDoc', 'file', 'controller', 'triggerDeleteAttached', 'after');

            // 2007. 10. 17 모듈이 삭제될때 등록된 첨부파일도 모두 삭제하는 트리거 추가
            if(!$oModuleModel->getTrigger('module.deleteModule', 'file', 'controller', 'triggerDeleteModuleFiles', 'after'))
                $oModuleController->insertTrigger('module.deleteModule', 'file', 'controller', 'triggerDeleteModuleFiles', 'after');

            // 2007. 10. 19 출력하기 전에 file 권한등을 세팅하는 트리거 호출
            if(!$oModuleModel->getTrigger('module.dispAdditionSetup', 'file', 'view', 'triggerDispFileAdditionSetup', 'before'))
                $oModuleController->insertTrigger('module.dispAdditionSetup', 'file', 'view', 'triggerDispFileAdditionSetup', 'before');

            // 타겟 판별용 필드
            if(!$oDB->isColumnExists('files', 'upload_target_type')) $oDB->addColumn('files', 'upload_target_type', 'char', '3');

            return new Object(0, 'success_updated');
        }

        /**
         * @brief 캐시 파일 재생성
         **/
        function recompileCache() {
        }

    }
?>
