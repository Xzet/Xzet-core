<?php
    /**
     * @file   modules/integration_search/lang/zh-TW.lang.php
     * @author NHN (developers@xpressengine.com) 翻譯：royallin
     * @brief  綜合搜尋(integration_search)模組正體中文語言
     **/

    $lang->integration_search = "搜尋";

    $lang->sample_code = "原始碼";
    $lang->about_target_module = "只將所選擇的模組當作搜尋對象。請注意權限設置。";
    $lang->about_sample_code = "將上述原始碼插入到相對應版面當中，即可實現搜尋功能。";
    $lang->msg_no_keyword = "請輸入關鍵字";
    $lang->msg_document_more_search  = "계속 검색 버튼을 선택하시면 아직 검색하지 않은 부분까지 계속 검색 하실 수 있습니다";

    $lang->is_result_text = "符合<strong>'%s'</strong>的搜尋結果，約有<strong>%d</strong>項";
    $lang->multimedia = "圖片/影片";
    
    $lang->include_search_target = '只有在選定的目標';
    $lang->exclude_search_target = '搜索選定目的地從';

    $lang->is_search_option = array(
        'document' => array(
            'title_content' => '標題+內容',
            'title' => '標題',
            'content' => '內容',
            'tag' => '標籤',
        ),
        'trackback' => array(
            'url' => '目標網址',
            'blog_name' => '目標網站名稱',
            'title' => '標題',
            'excerpt' => '內容',
        ),
    );

    $lang->is_sort_option = array(
        'regdate' => '日期',
        'comment_count' => '評論',
        'readed_count' => '檢視',
        'voted_count' => '推薦',
    );
?>
