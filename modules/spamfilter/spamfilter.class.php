<?php
    /**
     * @class  spamfilter
     * @author NHN (developers@xpressengine.com)
     * @brief  spamfilter 모듈의 high class
     **/

    class spamfilter extends ModuleObject {

        /**
         * @brief 설치시 추가 작업이 필요할시 구현
         **/
        function moduleInstall() {
            // action forward에 등록 (관리자 모드에서 사용하기 위함)
            $oModuleController = &getController('module');

            // 2007. 12. 7 글/ 댓글/ 엮인글이 등록될때 스팸필터링을 시도하는 트리거
            $oModuleController->insertTrigger('document.insertDocument', 'spamfilter', 'controller', 'triggerInsertDocument', 'before');
            $oModuleController->insertTrigger('comment.insertComment', 'spamfilter', 'controller', 'triggerInsertComment', 'before');
            $oModuleController->insertTrigger('trackback.insertTrackback', 'spamfilter', 'controller', 'triggerInsertTrackback', 'before');


            //2008-12-17 글 수정시 스펨필터 추가
            $oModuleController->insertTrigger('comment.updateComment', 'spamfilter', 'controller', 'triggerInsertComment', 'before');
            $oModuleController->insertTrigger('document.updateDocument', 'spamfilter', 'controller', 'triggerInsertDocument', 'before');


            return new Object();
        }

        /**
         * @brief 설치가 이상이 없는지 체크하는 method
         **/
        function checkUpdate() {
            $oDB = &DB::getInstance();
            $oModuleModel = &getModel('module');

            // 2007. 12. 7 글/ 댓글/ 엮인글이 등록될때 스팸필터링을 시도하는 트리거
            if(!$oModuleModel->getTrigger('document.insertDocument', 'spamfilter', 'controller', 'triggerInsertDocument', 'before')) return true;
            if(!$oModuleModel->getTrigger('comment.insertComment', 'spamfilter', 'controller', 'triggerInsertComment', 'before')) return true;
            if(!$oModuleModel->getTrigger('trackback.insertTrackback', 'spamfilter', 'controller', 'triggerInsertTrackback', 'before')) return true;

            //2008-12-17 글 수정시 스펨필터 추가
            if(!$oModuleModel->getTrigger('comment.updateComment', 'spamfilter', 'controller', 'triggerInsertComment', 'before')) return true;
            if(!$oModuleModel->getTrigger('document.updateDocument', 'spamfilter', 'controller', 'triggerInsertDocument', 'before')) return true;

            /**
             * 히트 카운트 필드(hit) 추가
             **/
            if(!$oDB->isColumnExists('spamfilter_denied_word', 'hit')) return true;
            if(!$oDB->isColumnExists('spamfilter_denied_word', 'latest_hit')) return true;

            if(!$oDB->isColumnExists('spamfilter_denied_ip', 'description')) return true;

            return false;
        }

        /**
         * @brief 업데이트 실행
         **/
        function moduleUpdate() {
            $oDB = &DB::getInstance();
            $oModuleModel = &getModel('module');
            $oModuleController = &getController('module');

            // 2007. 12. 7 글/ 댓글/ 엮인글이 등록될때 스팸필터링을 시도하는 트리거
            if(!$oModuleModel->getTrigger('document.insertDocument', 'spamfilter', 'controller', 'triggerInsertDocument', 'before'))
                $oModuleController->insertTrigger('document.insertDocument', 'spamfilter', 'controller', 'triggerInsertDocument', 'before');
            if(!$oModuleModel->getTrigger('comment.insertComment', 'spamfilter', 'controller', 'triggerInsertComment', 'before'))
                $oModuleController->insertTrigger('comment.insertComment', 'spamfilter', 'controller', 'triggerInsertComment', 'before');
            if(!$oModuleModel->getTrigger('trackback.insertTrackback', 'spamfilter', 'controller', 'triggerInsertTrackback', 'before'))
                $oModuleController->insertTrigger('trackback.insertTrackback', 'spamfilter', 'controller', 'triggerInsertTrackback', 'before');

            //2008-12-17 글 수정시 스펨필터 추가
            if(!$oModuleModel->getTrigger('comment.updateComment', 'spamfilter', 'controller', 'triggerInsertComment', 'before')){
                $oModuleController->insertTrigger('comment.updateComment', 'spamfilter', 'controller', 'triggerInsertComment', 'before');
            }
            //2008-12-17 글 수정시 스펨필터 추가
            if(!$oModuleModel->getTrigger('document.updateDocument', 'spamfilter', 'controller', 'triggerInsertDocument', 'before')){
                $oModuleController->insertTrigger('document.updateDocument', 'spamfilter', 'controller', 'triggerInsertDocument', 'before');
            }

            /**
             * 히트 카운트 필드(hit) 추가
             **/
            if(!$oDB->isColumnExists('spamfilter_denied_word', 'hit')) {
                $oDB->addColumn('spamfilter_denied_word','hit','number',12,0,true);
                $oDB->addIndex('spamfilter_denied_word','idx_hit', 'hit');
            }
            if(!$oDB->isColumnExists('spamfilter_denied_word', 'latest_hit')) {
                $oDB->addColumn('spamfilter_denied_word','latest_hit','date');
                $oDB->addIndex('spamfilter_denied_word','idx_latest_hit', 'latest_hit');
            }

            if(!$oDB->isColumnExists('spamfilter_denied_ip', 'description')) {
                $oDB->addColumn('spamfilter_denied_ip','description','varchar', 250);
            }

            return new Object(0,'success_updated');
        }

        /**
         * @brief 캐시 파일 재생성
         **/
        function recompileCache() {
        }
    }
?>