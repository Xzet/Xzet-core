<?php
    /**
     * @file   modules/module/lang/ko.lang.php
     * @author NHN (developers@xpressengine.com)
     * @brief  한국어 언어팩
     **/

    $lang->virtual_site = '가상 사이트';
    $lang->module_list = '모듈 목록';
    $lang->module_index = '모듈 목록';
    $lang->module_category = '모듈 분류';
    $lang->module_info = '모듈 정보';
    $lang->add_shortcut = '관리자 메뉴에 추가';
    $lang->module_action = '동작';
    $lang->module_maker = '모듈 제작자';
    $lang->module_license = '라이선스';
    $lang->module_history = '변경 이력 ';
    $lang->category_title = '분류 이름';
    $lang->header_text = '상단 내용';
    $lang->footer_text = '하단 내용';
    $lang->use_category = '분류 사용';
    $lang->category_title = '분류명';
    $lang->checked_count = '선택된 글 수';
    $lang->skin_default_info = '스킨 기본정보';
    $lang->skin_author = '스킨 제작자';
    $lang->skin_license = '라이선스';
    $lang->skin_history = '변경 이력';
    $lang->module_copy = '모듈 복사';
    $lang->module_selector = '모듈 선택기';
    $lang->do_selected = '선택된 것들을...';
    $lang->bundle_setup = '일괄 기본 설정';
    $lang->bundle_addition_setup = '일괄 추가 설정';
    $lang->bundle_grant_setup = '일괄 권한 설정';
    $lang->lang_code = '언어 코드';
    $lang->filebox = '파일박스';

    $lang->access_type = '접속 방법';
    $lang->access_domain = 'Domain 접속';
    $lang->access_vid = 'Site ID 접속';
    $lang->about_domain = '1개 이상의 사이트를 만들기 위해서는 전용 도메인이 있어야 합니다.<br/>독립 도메인이나 서브 도메인이 있으면 되고 XE가 설치된 경로까지 같이 넣어주세요.<br />예) www.xpressengine.com/xe';
    $lang->about_vid = '별도의 도메인이 아닌 http://XE주소/ID 로 접속할 수 있습니다. 모듈명(mid)과 중복될 수 없습니다.<br/>첫 글자는 영문으로 시작해야 하고 영문과 숫자 그리고 _ 만 사용할 수 있습니다';
    $lang->msg_already_registed_vid = '이미 등록된 사이트 ID 입니다. 게시판 등의 mid와도 중복이 되지 않습니다. 다른 ID를 입력해주세요.';
    $lang->msg_already_registed_domain = '이미 등록된 도메인입니다. 다른 도메인을 사용해주세요';

    $lang->header_script = '헤더 스크립트';
    $lang->about_header_script = 'HTML의 &lt;head&gt;와 &lt;/head&gt; 사이에 들어가는 코드를 직접 입력할 수 있습니다.<br />&lt;script, &lt;style 또는 &lt;meta 태그 등을 이용하실 수 있습니다';

    $lang->grant_access = '접근 권한';
    $lang->grant_manager = '관리 권한';

    $lang->grant_to_all = '모든 사용자';
    $lang->grant_to_login_user = '로그인 사용자';
    $lang->grant_to_site_user = '가입한 사용자';
    $lang->grant_to_group = '특정 그룹 사용자';

    $lang->cmd_add_shortcut = '바로가기 추가';
    $lang->cmd_install = '설치';
    $lang->cmd_update = '업데이트';
    $lang->cmd_manage_category = '분류 관리';
    $lang->cmd_manage_grant = '권한 관리';
    $lang->cmd_manage_skin = '스킨 관리';
    $lang->cmd_manage_document = '게시글 관리';
    $lang->cmd_find_module = '모듈 찾기';
    $lang->cmd_find_langcode = '언어 코드 찾기';

    $lang->msg_new_module = '모듈 생성';
    $lang->msg_update_module = '모듈 수정';
    $lang->msg_module_name_exists = '이미 존재하는 모듈 이름입니다. 다른 이름을 입력해주세요.';
    $lang->msg_category_is_null = '등록된 분류가 없습니다.';
    $lang->msg_grant_is_null = '등록된 권한 대상이 없습니다.';
    $lang->msg_no_checked_document = '선택된 게시물이 없습니다.';
    $lang->msg_move_failed = '이동 실패하였습니다.';
    $lang->msg_cannot_delete_for_child = '하부 분류가 있는 분류는 삭제하실 수 없습니다.';
    $lang->msg_limit_mid ='모듈 이름은 영문+[영문+숫자+_] 만 가능합니다.';
    $lang->msg_extra_name_exists = '이미 존재하는 확장 변수 이름입니다. 다른 이름을 입력해주세요.';

    $lang->about_browser_title = '브라우저 제목에 나타나는 값입니다. RSS/Trackback에서도 사용됩니다.';
    $lang->about_mid = '모듈 이름은 http://주소/?mid=모듈이름 처럼 직접 호출할 수 있는 값입니다. (영문+[영문+숫자+_] 만 가능. 최대 40 글자)';
    $lang->about_default = '선택하시면 사이트에 mid값 없이 접속하였을 경우 기본으로 보여줍니다.';
    $lang->about_module_category = "분류를 통한 관리를 할 수 있도록 합니다. 모듈 분류 관리는 <a href=\"./?module=admin&amp;act=dispModuleAdminCategory\">모듈관리 > 모듈분류</a>에서 하실 수 있습니다.";
    $lang->about_description= '관리용으로 사용되는 설명입니다.';
    $lang->about_header_text = '모듈 상단에 표시되는 내용입니다. (HTML 태그 사용 가능)';
    $lang->about_footer_text = '모듈 하단에 표시되는 내용입니다. (HTML 태그 사용 가능)';
    $lang->about_skin = '모듈 스킨을 선택하실 수 있습니다.';
    $lang->about_use_category = '선택하시면 분류 기능을 사용할 수 있습니다.';
    $lang->about_list_count = '한 페이지에 표시될 글 수를 지정하실 수 있습니다. (기본 20개)';
    $lang->about_search_list_count = '검색, 카테고리 선택 등을 할 경우 표시될 글 수를 지정하실 수 있습니다. (기본 20개)';
    $lang->about_page_count = '목록 하단, 페이지를 이동하는 링크 수를 지정하실 수 있습니다. (기본 10개)';
    $lang->about_admin_id = '해당 모듈에 대해 최고 권한을 가지는 관리자를 지정할 수 있습니다.';
    $lang->about_grant = '특정 권한의 대상을 모두 해제하면 로그인하지 않은 회원까지 권한을 가질 수 있습니다.';
    $lang->about_grant_deatil = '가입한 사용자는 cafeXE 등 분양형 가상 사이트에 가입을 한 로그인 사용자를 의미합니다.';
    $lang->about_module = "XE는 기본 라이브러리를 제외한 나머지는 모두 모듈로 구성되어 있습니다.\n모듈 관리 모듈은 설치된 모든 모듈을 보여주고 관리를 돕습니다.";
    $lang->about_extra_vars_default_value = '다중/단일 선택 등 기본 값이 여러 개가 필요한 경우 , (콤마)로 연결하시면 됩니다.';
    $lang->about_search_virtual_site = '가상 사이트(예:cafeXE) 도메인을 입력하신 후 검색하세요.<br/>가상 사이트 이외의 모듈은 내용을 비우고 검색하시면 됩니다. (http:// 는 제외)';
    $lang->about_extra_vars_eid_value = '확장 변수의 이름을 적어주세요. (영문+[영문+숫자+_] 만 가능)';
    $lang->about_langcode = '언어별로 다르게 설정하고 싶으시면 언어 코드 찾기를 이용해주세요.';
    $lang->about_file_extension= "%s 파일만 가능합니다.";
?>
