<?php 
    /**
     * @file  index.php
     * @author NHN (developers@xpressengine.com)
     * @brief 시작 페이지
     *
     * Request Argument에서 mid, act로 module 객체를 찾아서 생성하고 
     * 모듈 정보를 세팅함
     *
     * @mainpage Xzet 
     * @section intro 소개
     * Xzet 는 오픈 프로젝트로 개발되는 오픈 소스인 XpressEngine 기반의 CMS입니다.
     * 자세한 내용은 아래 링크를 참조하세요.
     * - 공식홈페이지        : http://xzet.yjsoft.pe.kr
     * - SVN Repository      : https://github.com/Xzet/xe-core-14
     * 
     * "XpressEngine (XE)" is free software; you can redistribute it and/or 
     * modify it under the terms of the GNU Lesser General Public 
     * License as published by the Free Software Foundation; either 
     * version 2.1 of the License, or (at your option) any later version. 
     * 
     * This library is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
     * Lesser General Public License for more details.
     * 
     * You should have received a copy of the GNU Lesser General Public
     * License along with this library; if not, write to the Free Software
     * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
     *
     **/

    /**
     * @brief 기본적인 상수 선언,  웹에서 직접 호출되는 것을 막기 위해 체크하는 상수 선언
     **/
    define('__ZBXE__', true);

    // XE 1.5.x와의 호환을 위해 __XE__ 상수도 선언
    define('__XE__', true);

    /**
     * @brief 필요한 설정 파일들을 include 
     **/
    require('./config/config.inc.php');

    /**
     * @brief Context 객체를 생성하여 초기화
     * 모든 Request Argument/ 환경변수등을 세팅
     **/
    $oContext = &Context::getInstance();
    $oContext->init();

    /**
     * @brief default_url 이 설정되어 있고 현재 url이 default_url과 다르면 SSO인증을 위한 rediret 시도 후 모듈 동작
     **/
    if($oContext->checkSSO()) {
        $oModuleHandler = new ModuleHandler();
        if($oModuleHandler->init()) {
            $oModule = &$oModuleHandler->procModule();
            $oModuleHandler->displayContent($oModule);
        }
    }
    $oContext->close();
?>
