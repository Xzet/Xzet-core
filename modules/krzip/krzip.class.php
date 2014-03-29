<?php
    /**
     * @class  krzip
     * @author NHN (developers@xpressengine.com)
     * @brief  우편번호 검색 모듈인 krzip의 상위 클래스
     **/

    class krzip extends ModuleObject {

        var $hostname = 'kr.zip.zeroboard.com';
        var $port = 80;
        var $query = '/server.php?addr3=';

        /**
         * @brief 설치시 추가 작업이 필요할시 구현
         **/
        function moduleInstall() {
            return new Object();
        }

        /**
         * @brief 설치가 이상이 없는지 체크하는 method
         **/
        function checkUpdate() {
            return false;
        }

        /**
         * @brief 업데이트 실행
         **/
        function moduleUpdate() {
            return new Object();
        }

        /**
         * @brief 캐시 파일 재생성
         **/
        function recompileCache() {
        }
    }
?>
