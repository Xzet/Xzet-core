<?php
    /**
     * @file   modules/importer/lang/ko.lang.php
     * @author NHN (developers@xpressengine.com)
     * @brief  Importer(importer) 모듈의 기본 언어팩
     **/

    // 버튼에 사용되는 언어
    $lang->cmd_sync_member = '동기화';
    $lang->cmd_continue = '계속 진행';
    $lang->preprocessing = '데이터 이전을 위한 사전 준비 중입니다.';

    // 항목
    $lang->importer = 'XE 데이터 이전';
    $lang->source_type = '이전 대상';
    $lang->type_member = '회원 정보';
    $lang->type_message = '쪽지(메시지) 정보';
    $lang->type_ttxml = 'TTXML';
    $lang->type_module = '게시물 정보';
    $lang->type_syncmember = '회원정보 동기화';
    $lang->target_module = '대상 모듈';
    $lang->xml_file = 'XML 파일';

    $lang->import_step_title = array(
        1 => 'Step 1. 이전 대상 선택',
        12 => 'Step 1-2. 대상 모듈 선택',
        13 => 'Step 1-3. 대상 분류 선택',
        2 => 'Step 2. XML파일 지정',
        3 => 'Step 2. 회원정보와 게시물의 정보 동기화',
        99 => '데이터 이전',
    );

    $lang->import_step_desc = array(
        1 => '이전하려는 XML파일의 종류를 선택해주세요.',
        12 => '데이터를 이전 받을 대상 모듈을 선택해주세요.',
        121 => '글:',
        122 => '방명록:',
        13 => '데이터 이전을 할 대상 분류를 선택해주세요.',
        2 => "이전할 데이터 XML파일의 경로를 입력해주세요.\n상대 또는 절대 경로를 입력하시면 됩니다.",
        3 => '회원정보와 게시물 정보가 이전 후에 맞지 않을 수 있습니다. 이때 동기화를 하시면 user_id를 기반으로 올바르게 동작하도록 합니다.',
        99 => '데이터 이전중입니다.',
    );

    // 안내/경고
    $lang->msg_sync_member = '동기화 버튼을 클릭하시면 회원정보와 게시물 정보의 동기화를 시작합니다.';
    $lang->msg_no_xml_file = 'XML파일을 찾을 수 없습니다. 경로를 다시 확인해주세요.';
    $lang->msg_invalid_xml_file = '잘못된 형식의 XML파일입니다.';
    $lang->msg_importing = '%d개의 데이터 중 %d개를 입력중입니다. (계속 멈추어 있으면 "계속진행" 버튼을 클릭해주세요.)';
    $lang->msg_import_finished = '%d/%d 개의 데이터 입력이 완료되었습니다. 상황에 따라 입력되지 못한 데이터가 있을 수 있습니다.';
    $lang->msg_sync_completed = '회원과 게시물, 댓글의 동기화가 완료되었습니다.';

    // 주절주절
    $lang->about_type_member = '데이터 이전 대상이 회원정보일 경우 선택해주세요.';
    $lang->about_type_message = '데이터 이전 대상이 쪽지(메시지)일 경우 선택해주세요.';
    $lang->about_type_ttxml = '데이터 이전 대상이 TTXML(textcube계열)일 경우 선택해주세요.';
    $lang->about_ttxml_user_id = 'TTXML 이전 시에 글쓴이로 지정할 사용자 아이디를 입력해주세요. (이미 가입된 아이디여야 합니다.)';
    $lang->about_type_module = '데이터 이전 대상이 게시판 등의 게시물 정보일 경우 선택해주세요.';
    $lang->about_type_syncmember = '회원정보와 게시물정보 등을 이전 후, 회원정보를 동기화해야 할 때 선택해주세요.';
    $lang->about_importer = "제로보드4, zb5beta 또는 다른 프로그램의 데이터를 XE 데이터로 이전할 수 있습니다.\n이전을 위해서는 <a href=\"http://www.xpressengine.com/index.php?mid=download&category_srl=18324038/\" onclick=\"winopen(this.href);return false;\">XML Exporter</a>를 이용해서 원하는 데이터를 XML파일로 생성 후 업로드해주셔야 합니다.";
    $lang->about_target_path = "첨부 파일을 받기 위해 제로보드4가 설치된 위치를 입력해주세요.\n같은 서버에 있을 경우 /home/아이디/public_html/bbs 등과 같이 제로보드4의 위치를 입력하시고\n다른 서버일 경우 http://도메인/bbs 처럼 제로보드4가 설치된 곳의 url을 입력해주세요.";
?>
