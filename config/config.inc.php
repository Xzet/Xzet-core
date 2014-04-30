<?php
    /**
     * @file   config/config.inc.php
     * @author NHN (developers@xpressengine.com)
     * @brief  기본적으로 사용하는 class파일의 include 및 환경 설정을 함
     **/

if(version_compare(PHP_VERSION, '5.4.0', '<'))
{
	@error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);
}
else
{
	@error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING ^ E_STRICT);
	//@error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
}

    if(!defined('__ZBXE__')) exit();

	define('__XE__',__ZBXE__);


    /**
     * @brief XE의 전체 버전 표기
     * 이 파일의 수정이 없더라도 공식 릴리즈시에 수정되어 함께 배포되어야 함
     **/
    define('__ZBXE_VERSION__', '1.4.5.23');

    /**
     * @brief zbXE가 설치된 장소의 base path를 구함
     **/
    define('_XE_PATH_', str_replace('config/config.inc.php', '', str_replace('\\', '/', __FILE__)));

	/**
	 * @brief 쿠키 이외의 값에서도 세션을 인식할 수 있도록 함(파일업로드 등에서의 문제 수정)
	 **/
	ini_set('session.use_only_cookies', 0);

    /**
     * @brief 기본 설정에 우선하는 사용자 설정 파일
     * config/config.user.inc.php 파일에 아래 내용을 저장하면 됨
     * <?php 
     * define('__DEBUG__', 0);
     * define('__DEBUG_OUTPUT__', 0);
     * define('__DEBUG_PROTECT__', 1);
     * define('__DEBUG_PROTECT_IP__', '127.0.0.1');
     * define('__DEBUG_DB_OUTPUT__', 0);
     * define('__LOG_SLOW_QUERY__', 0);
     * define('__OB_GZHANDLER_ENABLE__', 1);
     * define('__ENABLE_PHPUNIT_TEST__', 0);
     * define('__PROXY_SERVER__', 'http://domain:port/path');
     * ?>
     */
    if(file_exists(_XE_PATH_.'config/config.user.inc.php')) {
        require _XE_PATH_.'config/config.user.inc.php';
    }

    /**
     * @brief 디버깅 메시지 출력 (비트 값)
     * 0 : 디버그 메시지를 생성/ 출력하지 않음
     * 1 : debugPrint() 함수를 통한 메시지 출력
     * 2 : 소요시간, Request/Response info 출력
     * 4 : DB 쿼리 내역 출력
     **/
    if(!defined('__DEBUG__')) define('__DEBUG__', 0);

    /**
     * @brief 디버그 메세지의 출력 장소
     * 0 : files/_debug_message.php 에 연결하여 출력
     * 1 : HTML 최하단에 주석으로 출력 (Response Method가 HTML 일 때)
     * 2 : Firebug 콘솔에 출력 (PHP 4 & 5. Firebug/FirePHP 플러그인 필요)
     **/
    if(!defined('__DEBUG_OUTPUT__')) define('__DEBUG_OUTPUT__', 0);

    /**
     * @brief FirePHP 콘솔 및 브라우저 주석 출력 보안
     * 0 : 제한 없음 (권장하지 않음)
     * 1 : 지정한 IP 주소에만 허용
     **/
    if(!defined('__DEBUG_PROTECT__')) define('__DEBUG_PROTECT__', 1);
    if(!defined('__DEBUG_PROTECT_IP__')) define('__DEBUG_PROTECT_IP__', '127.0.0.1');

    /**
     * @brief DB 오류 메세지 출력 정의
     * 0 : 출력하지 않음
     * 1 : files/_debug_db_query.php 에 연결하여 출력
     **/
    if(!defined('__DEBUG_DB_OUTPUT__')) define('__DEBUG_DB_OUTPUT__', 0);

    /**
     * @brief DB 쿼리중 정해진 시간을 넘기는 쿼리의 로그 남김
     * 0 : 로그를 남기지 않음
     * 0 이상 : 단위를 초로 하여 지정된 초 이상의 실행시간이 걸린 쿼리를 로그로 남김
     * 로그파일은 ./files/_db_slow_query.php 파일로 저장됨
     **/
    if(!defined('__LOG_SLOW_QUERY__')) define('__LOG_SLOW_QUERY__', 0);

    /**
     * @brief DB 쿼리 정보를 남김
     * 0 : 쿼리에 정보를 추가하지 않음
     * 1 : XML Query ID를 쿼리 주석으로 남김
     **/
    if(!defined('__DEBUG_QUERY__')) define('__DEBUG_QUERY__', 0);

    /**
     * @brief ob_gzhandler를 이용한 압축 기능을 강제로 사용하거나 끄는 옵션
     * 0 : 사용하지 않음
     * 1 : 사용함
     * 대부분의 서버에서는 문제가 없는데 특정 서버군에서 압축전송시 IE에서 오동작을 일으키는경우가 있음
     **/
    if(!defined('__OB_GZHANDLER_ENABLE__')) define('__OB_GZHANDLER_ENABLE__', 1);

    /**
     * @brief php unit test (경로/tests/index.php) 의 실행 유무 지정
     * 0 : 사용하지 않음
     * 1 : 사용함
     **/
    if(!defined('__ENABLE_PHPUNIT_TEST__')) define('__ENABLE_PHPUNIT_TEST__', 0);

    /**
     * @brief __PROXY_SERVER__ 는 대상 서버를 거쳐서 외부 요청을 하도록 하는 서버의 정보를 가지고 있음
     * FileHandler::getRemoteResource 에서 이 상수를 사용함
     **/
    if(!defined('__PROXY_SERVER__')) define('__PROXY_SERVER__', null);

    /**
     * @brief Firebug 콘솔 출력 사용시 관련 파일 require
     **/
    if((__DEBUG_OUTPUT__ == 2) && version_compare(PHP_VERSION, '6.0.0') === -1) {
        require _XE_PATH_.'libs/FirePHPCore/FirePHP.class.php';
    }

	/**
	 * @brief Set Timezone as server time 
	 **/
	if(version_compare(PHP_VERSION, '5.3.0') >= 0)
	{
		date_default_timezone_set(@date_default_timezone_get());		
	}

	if(!defined('__XE_LOADED_CLASS__')){
		/**
		 * @brief 간단하게 사용하기 위한 함수 정의한 파일 require
		 **/
		require(_XE_PATH_.'config/func.inc.php');

		if(__DEBUG__) define('__StartTime__', getMicroTime());

		/**
		 * @brief 기본적인 class 파일 include
		 * @TODO : PHP5 기반으로 바꾸게 되면 _autoload()를 이용할 수 있기에 제거 대상
		 **/
		if(__DEBUG__) define('__ClassLoadStartTime__', getMicroTime());
		require(_XE_PATH_.'classes/object/Object.class.php');
		require(_XE_PATH_.'classes/extravar/Extravar.class.php');
		require(_XE_PATH_.'classes/handler/Handler.class.php');
		require(_XE_PATH_.'classes/xml/XmlParser.class.php');
		require(_XE_PATH_.'classes/xml/XmlJsFilter.class.php');
		require(_XE_PATH_.'classes/cache/CacheHandler.class.php');
		require(_XE_PATH_.'classes/context/Context.class.php');
		require(_XE_PATH_.'classes/db/DB.class.php');
		require(_XE_PATH_.'classes/file/FileHandler.class.php');
		require(_XE_PATH_.'classes/widget/WidgetHandler.class.php');
		require(_XE_PATH_.'classes/editor/EditorHandler.class.php');
		require(_XE_PATH_.'classes/module/ModuleObject.class.php');
		require(_XE_PATH_.'classes/module/ModuleHandler.class.php');
		require(_XE_PATH_.'classes/display/DisplayHandler.class.php');
		require(_XE_PATH_.'classes/template/TemplateHandler.class.php');
		require(_XE_PATH_.'classes/mail/Mail.class.php');
		require(_XE_PATH_.'classes/page/PageHandler.class.php');
		require(_XE_PATH_.'classes/mobile/Mobile.class.php');
		require(_XE_PATH_.'classes/security/Security.class.php');				
		if(__DEBUG__) $GLOBALS['__elapsed_class_load__'] = getMicroTime() - __ClassLoadStartTime__;
	}
?>
