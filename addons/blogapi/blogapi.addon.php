<?php
    if(!defined("__ZBXE__")) exit();

    /**
     * @file blogapicounter.addon.php
     * @author NHN (developers@xpressengine.com)
     * @brief blogAPI 애드온
     *
     * ms live writer, 파이어폭스의 performancing, zoundry 등의 외부 툴을 이용하여 글을 입력할 수 있게 합니다.
     * 모듈 실행 이전(before_module_proc)에 호출이 되어야 하며 정상동작후에는 강제 종료를 한다.
     **/

    // called_position가 after_module_proc일때 rsd 태그 삽입
    if($called_position == 'after_module_proc') {
        // 현재 모듈의 rsd주소를 만듬
        $site_module_info = Context::get('site_module_info');
        $rsd_url = getFullSiteUrl($site_module_info->domain, '', 'mid',$site_module_info->mid, 'act','api');

        // 헤더에 rsd태그 삽입
        Context::addHtmlHeader("    ".'<link rel="EditURI" type="application/rsd+xml" title="RSD" href="'.$rsd_url.'" />');
    }

    // act가 api가 아니면 그냥 리턴~
    if($_REQUEST['act']!='api') return;

    // 관련 func 파일 읽음
    require_once('./addons/blogapi/blogapi.func.php');

    // xmlprc 파싱
    // 요청된 xmlrpc를 파싱
    $oXmlParser = new XmlParser();
    $xmlDoc = $oXmlParser->parse();

    $method_name = $xmlDoc->methodcall->methodname->body;
    $params = $xmlDoc->methodcall->params->param;
    if($params && !is_array($params)) $params = array($params);

    // 일부 methodname에 대한 호환
    if(in_array($method_name, array('metaWeblog.deletePost', 'metaWeblog.getUsersBlogs', 'metaWeblog.getUserInfo'))) {
        $method_name = str_replace('metaWeblog.', 'blogger.', $method_name);
    }

    // blogger.deletePost일 경우 첫번째 인자 값 삭제
    if($method_name == 'blogger.deletePost') array_shift($params);

    // user_id, password를 구해서 로그인 시도
    $user_id = trim($params[1]->value->string->body);
    $password = trim($params[2]->value->string->body);

    // 모듈 실행전이라면 인증을 처리한다.
    if($called_position == 'before_module_init') {

        // member controller을 이용해서 로그인 시도
        if($user_id && $password) {
            $oMemberController = &getController('member');
            $output = $oMemberController->doLogin($user_id, $password);
            // 로그인 실패시 에러 메시지 출력 
            if(!$output->toBool()) {
                $content = getXmlRpcFailure(1, $output->getMessage());
                printContent($content);
            }
        } else {
            $content = getXmlRpcFailure(1, 'not logged');
            printContent($content);
        }
    }

    // 모듈에서 무언가 작업을 하기 전에 blogapi tool의 요청에 대한 처리를 하고 강제 종료한다.
    if($called_position == 'before_module_proc') {

        // 글쓰기 권한 체크 (권한명의 경우 약속이 필요할듯..)
        if(!$this->grant->write_document) {
            printContent( getXmlRpcFailure(1, 'no permission') );
        }

        // 카테고리의 정보를 구해옴
        $oDocumentModel = &getModel('document');
        $category_list = $oDocumentModel->getCategoryList($this->module_srl);

        // 임시 파일 저장 장소 지정
        $tmp_uploaded_path = sprintf('./files/cache/blogapi/%s/%s/', $this->mid, $user_id);
        $uploaded_target_path = sprintf('/files/cache/blogapi/%s/%s/', $this->mid, $user_id);

        switch($method_name) {
            // 블로그 정보
            case 'blogger.getUsersBlogs' :
                    $obj->url = getFullSiteUrl('');
                    $obj->blogid = $this->mid;
                    $obj->blogName = $this->module_info->browser_title;
                    $blog_list = array($obj);

                    $content = getXmlRpcResponse($blog_list);
                    printContent($content);
                break;

            // 카테고리 목록 return
            case 'metaWeblog.getCategories' :
                    $category_obj_list = array();
                    if($category_list) {
                        foreach($category_list as $category_srl => $category_info) {
                            unset($obj);
                            $obj->description = $category_info->title;
                            //$obj->htmlUrl = Context::getRequestUri().$this->mid.'/1'; 
                            //$obj->rssUrl= Context::getRequestUri().'rss/'.$this->mid.'/1'; 
                            $obj->title = $category_info->title;
                            $obj->categoryid = $category_srl;
                            $category_obj_list[] = $obj;
                        }
                    }

                    $content = getXmlRpcResponse($category_obj_list);
                    printContent($content);
                break;

            // 파일 업로드
            case 'metaWeblog.newMediaObject' :
                    // 파일 업로드 권한 체크
                    $oFileModel = &getModel('file');
                    $file_module_config = $oFileModel->getFileModuleConfig($this->module_srl);
                    if(is_array($file_module_config->download_grant) && count($file_module_config->download_grant)>0) {
                        $logged_info = Context::get('logged_info');
                        if($logged_info->is_admin != 'Y') {
                            $is_permitted = false;
                            for($i=0;$i<count($file_module_config->download_grant);$i++) {
                                $group_srl = $file_module_config->download_grant[$i];
                                if($logged_info->group_list[$group_srl]) {
                                    $is_permitted = true;
                                    break;
                                }
                            }
                            if(!$is_permitted) printContent( getXmlRpcFailure(1, 'no permission') );
                        }
                    }

                    $fileinfo = $params[3]->value->struct->member;
                    foreach($fileinfo as $key => $val) {
                        $nodename = $val->name->body;
                        if($nodename == 'bits') $filedata = base64_decode($val->value->base64->body);
                        elseif($nodename == 'name') $filename = $val->value->string->body;
                    }

                    $tmp_arr = explode('/',$filename);
                    $filename = array_pop($tmp_arr);

                    if(!is_dir($tmp_uploaded_path)) FileHandler::makeDir($tmp_uploaded_path);

                    $target_filename = sprintf('%s%s', $tmp_uploaded_path, $filename);
                    FileHandler::writeFile($target_filename, $filedata);
                    $obj->url = Context::getRequestUri().$target_filename;

                    $content = getXmlRpcResponse($obj);
                    printContent($content);
                break;

            // 글 가져오기
            case 'metaWeblog.getPost' :
                    $document_srl = $params[0]->value->string->body;
                    if(!$document_srl) {
                        printContent( getXmlRpcFailure(1, 'no permission') );
                    } else {
                        $oDocumentModel = &getModel('document');
                        $oDocument = $oDocumentModel->getDocument($document_srl);
                        if(!$oDocument->isExists() || !$oDocument->isGranted()) {
                            printContent( getXmlRpcFailure(1, 'no permission') );
                        } else {
                            // 카테고리를 사용하는지 확인후 사용시 카테고리 목록을 구해와서 Context에 세팅
                            $category = "";
                            if($oDocument->get('category_srl')) {
                                $oDocumentModel = &getModel('document');
                                $category_list = $oDocumentModel->getCategoryList($oDocument->get('module_srl'));
                                if($category_list[$oDocument->get('category_srl')]) {
                                    $category = $category_list[$oDocument->get('category_srl')]->title;
                                }
                            }
                            
                            $content = sprintf(
                                    '<?xml version="1.0" encoding="utf-8"?>'.
                                    '<methodResponse>'.
                                    '<params>'.
                                        '<param>'.
                                            '<value>'.
                                                '<struct>'.
                                                    '<member><name>categories</name><value><array><data><value><![CDATA[%s]]></value></data></array></value></member>'.
                                                    '<member><name>dateCreated</name><value><dateTime.iso8601>%s</dateTime.iso8601></value></member>'.
                                                    '<member><name>description</name><value><![CDATA[%s]]></value></member>'.
                                                    '<member><name>link</name><value>%s</value></member>'.
                                                    '<member><name>postid</name><value><string>%s</string></value></member>'.
                                                    '<member><name>title</name><value><![CDATA[%s]]></value></member>'.
                                                    '<member><name>publish</name><value><boolean>1</boolean></value></member>'.
                                                '</struct>'.
                                            '</value>'.
                                        '</param>'.
                                    '</params>'.
                                    '</methodResponse>',
                                    $category,
                                    date("Ymd", $oDocument->getRegdateTime()).'T'.date("H:i:s", $oDocument->getRegdateTime()),
                                    $oDocument->getContent(false, false, true,false),
                                    getFullUrl('','document_srl', $oDocument->document_srl),
                                    $oDocument->document_srl,
                                    $oDocument->getTitleText()
                                );
                            printContent($content);
                        }
                    }
                break;

            // 글작성
            case 'metaWeblog.newPost' :
                    unset($obj);
                    $info = $params[3];
                    // 글, 제목, 카테고리 정보 구함
                    for($i=0;$i<count($info->value->struct->member);$i++) {
                        $val = $info->value->struct->member[$i];
                        switch($val->name->body) {
                            case 'title' :
                                    $obj->title = $val->value->string->body;
                                break;
                            case 'description' :
                                    $obj->content = $val->value->string->body;
                                break;
                            case 'categories' :
                                    $categories = $val->value->array->data->value;
                                    if(!is_array($categories)) $categories = array($categories);
                                    $category = $categories[0]->string->body;
                                    if($category && $category_list) {
                                        foreach($category_list as $category_srl => $category_info) {
                                            if($category_info->title == $category) $obj->category_srl = $category_srl;
                                        }
                                    }
                                break;
                            case 'tagwords' :
                                    $tags = $val->value->array->data->value;
                                    if(!is_array($tags)) $tags = array($tags);
                                    for($j=0;$j<count($tags);$j++) {
                                        $tag_list[] = $tags[$j]->string->body;
                                    }
                                    if(count($tag_list)) $obj->tags = implode(',',$tag_list);
                                break;
                        }

                    }

                    // 문서 번호 설정
                    $document_srl = getNextSequence();
                    $obj->document_srl = $document_srl;
                    $obj->module_srl = $this->module_srl;

                    // 첨부파일 정리
                    if(is_dir($tmp_uploaded_path)) {
                        $file_list = FileHandler::readDir($tmp_uploaded_path);
                        $file_count = count($file_list);
                        if($file_count) {
                            $oFileController = &getController('file');
                            for($i=0;$i<$file_count;$i++) {
                                $file_info['tmp_name'] = sprintf('%s%s', $tmp_uploaded_path, $file_list[$i]);
                                $file_info['name'] = $file_list[$i];
                                $oFileController->insertFile($file_info, $this->module_srl, $document_srl, 0, true);
                            }
                            $obj->uploaded_count = $file_count;
                        }
                    }

                    $obj->content = str_replace($uploaded_target_path,sprintf('/files/attach/images/%s/%s%s', $this->module_srl, getNumberingPath($document_srl,3), $filename), $obj->content);

                    $oDocumentController = &getController('document');
                    $obj->allow_comment = 'Y';
                    $obj->allow_trackback = 'Y';
                    $output = $oDocumentController->insertDocument($obj);

                    if(!$output->toBool()) {
                        $content = getXmlRpcFailure(1, $output->getMessage());
                    } else {
                        $content = getXmlRpcResponse(strval($document_srl));
                    }
                    FileHandler::removeDir($tmp_uploaded_path);

                    printContent($content);
                break;

            // 글 수정
            case 'metaWeblog.editPost' :
                    $tmp_val = $params[0]->value->string->body;
                    if(!$tmp_val) $tmp_val = $params[0]->value->i4->body;
                    if(!$tmp_val) {
                        $content = getXmlRpcFailure(1, 'no permission');
                        break;
                    }
                    $tmp_arr = explode('/', $tmp_val);
                    $document_srl = array_pop($tmp_arr);
                    if(!$document_srl) {
                        $content = getXmlRpcFailure(1, 'no permission');
                        break;
                    }

                    $oDocumentModel = &getModel('document');
                    $oDocument = $oDocumentModel->getDocument($document_srl);

                    // 글 수정 권한 체크
                    if(!$oDocument->isGranted()) {
                        $content = getXmlRpcFailure(1, 'no permission');
                        break;
                    }

                    $obj = $oDocument->getObjectVars();

                    $info = $params[3];

                    // 글, 제목, 카테고리 정보 구함
                    for($i=0;$i<count($info->value->struct->member);$i++) {
                        $val = $info->value->struct->member[$i];
                        switch($val->name->body) {
                            case 'title' :
                                    $obj->title = $val->value->string->body;
                                break;
                            case 'description' :
                                    $obj->content = $val->value->string->body;
                                break;
                            case 'categories' :
                                    $categories = $val->value->array->data->value;
                                    if(!is_array($categories)) $categories = array($categories);
                                    $category = $categories[0]->string->body;
                                    if($category && $category_list) {
                                        foreach($category_list as $category_srl => $category_info) {
                                            if($category_info->title == $category) $obj->category_srl = $category_srl;
                                        }
                                    }
                                break;
                            case 'tagwords' :
                                    $tags = $val->value->array->data->value;
                                    if(!is_array($tags)) $tags = array($tags);
                                    for($j=0;$j<count($tags);$j++) {
                                        $tag_list[] = $tags[$j]->string->body;
                                    }
                                    if(count($tag_list)) $obj->tags = implode(',',$tag_list);
                                break;
                        }

                    }

                    // 문서 번호 설정
                    $obj->document_srl = $document_srl;
                    $obj->module_srl = $this->module_srl;

                    // 첨부파일 정리
                    if(is_dir($tmp_uploaded_path)) {
                        $file_list = FileHandler::readDir($tmp_uploaded_path);
                        $file_count = count($file_list);
                        if($file_count) {
                            $oFileController = &getController('file');
                            for($i=0;$i<$file_count;$i++) {
                                $file_info['tmp_name'] = sprintf('%s%s', $tmp_uploaded_path, $file_list[$i]);
                                $file_info['name'] = $file_list[$i];

                                $moved_filename = sprintf('./files/attach/images/%s/%s/%s', $this->module_srl, $document_srl, $file_info['name']);
                                if(file_exists($moved_filename)) continue;

                                $oFileController->insertFile($file_info, $this->module_srl, $document_srl, 0, true);
                            }
                            $obj->uploaded_count += $file_count;
                        }
                    }

                    $obj->content = str_replace($uploaded_target_path,sprintf('/files/attach/images/%s/%s%s', $this->module_srl, getNumberingPath($document_srl,3), $filename), $obj->content);

                    $oDocumentController = &getController('document');
                    $output = $oDocumentController->updateDocument($oDocument,$obj);

                    if(!$output->toBool()) {
                        $content = getXmlRpcFailure(1, $output->getMessage());
                    } else {
                        $content = getXmlRpcResponse(true);
                        FileHandler::removeDir($tmp_uploaded_path);
                    }

                    printContent($content);
                break;

            // 글삭제
            case 'blogger.deletePost' :
                    $tmp_val = $params[0]->value->string->body;
                    $tmp_arr = explode('/', $tmp_val);
                    $document_srl = array_pop($tmp_arr);

                    // 글 받아오기 
                    $oDocumentModel = &getModel('document');
                    $oDocument = $oDocumentModel->getDocument($document_srl);

                    // 글 존재
                    if(!$oDocument->isExists()) {
                        $content = getXmlRpcFailure(1, 'not exists');

                    // 글 삭제 권한 체크
                    } elseif(!$oDocument->isGranted()) {
                        $content = getXmlRpcFailure(1, 'no permission');
                        break;

                    // 삭제
                    } else {
                        $oDocumentController = &getController('document');
                        $output = $oDocumentController->deleteDocument($document_srl);
                        if(!$output->toBool()) $content = getXmlRpcFailure(1, $output->getMessage());
                        else $content = getXmlRpcResponse(true);
                    }

                    printContent($content);
                break;

            // 최신글 받기
            case 'metaWeblog.getRecentPosts' :
                    // 목록을 구하기 위한 옵션
                    $args->module_srl = $this->module_srl; ///< 현재 모듈의 module_srl
                    $args->page = 1;
                    $args->list_count = 20;
                    $args->sort_index = 'list_order'; ///< 소팅 값
                    $logged_info = Context::get('logged_info');
                    $args->search_target = 'member_srl';
                    $args->search_keyword = $logged_info->member_srl;
                    $output = $oDocumentModel->getDocumentList($args);
                    if(!$output->toBool() || !$output->data) {
                        $content = getXmlRpcFailure(1, 'post not founded');
                        printContent($content);
                    } else {
                        $oEditorController = &getController('editor');

                        $posts = array();
                        foreach($output->data as $key => $oDocument) {
                            $post = null;
                            $post->categories = array();
                            $post->dateCreated = date("Ymd", $oDocument->getRegdateTime()).'T'.date("H:i:s", $oDocument->getRegdateTime());
                            $post->description = htmlspecialchars($oEditorController->transComponent($oDocument->getContent(false,false,true,false)));
                            $post->link = $post->permaLink = getFullUrl('','document_srl',$oDocument->document_srl);
                            $post->postid = $oDocument->document_srl;
                            $post->title = htmlspecialchars($oDocument->get('title'));
                            $post->publish = 1;
                            $post->userid = $oDocument->get('user_id');
                            $post->mt_allow_pings = 0;
                            $post->mt_allow_comments = $oDocument->allowComment()=='Y'?1:0;
                            $posts[] = $post;
                        }
                        $content = getXmlRpcResponse($posts);
                        printContent($content);
                    }
                break;

            // 아무런 요청이 없을 경우 RSD 출력
            default :

                    $homepagelink = getUrl('','mid',$this->mid);
                    $site_module_info = Context::get('site_module_info');
                    $api_url = getFullSiteUrl($site_module_info->domain, '', 'mid',$site_module_info->mid, 'act','api');
                    $content = <<<RSDContent
<?xml version="1.0" ?>
<rsd version="1.0" xmlns="http://archipelago.phrasewise.com/rsd" >
<service>
    <engineName>XpressEngine</engineName>
    <engineLink>http://www.xpressengine.com/ </engineLink>
    <homePageLink>{$homepagelink}</homePageLink>
    <apis>
        <api name="MetaWeblog" preferred="true" apiLink="{$api_url}" blogID="" />
    </apis>
</service>
</rsd>
RSDContent;
                    printContent($content);
                break;
        }
    }
?>
