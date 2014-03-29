<?php
    /**
     * @file   modules/document/lang/zh-TW.lang.php
     * @author NHN (developers@xpressengine.com) 翻譯：royallin
     * @brief  主題(document)模組正體中文語言
     **/

    $lang->document_list = '主題列表';
    $lang->thumbnail_type = '縮圖建立方式';
    $lang->thumbnail_crop = '裁減';
    $lang->thumbnail_ratio = '比例';
    $lang->cmd_delete_all_thumbnail = '刪除全部縮圖';
    $lang->title_bold = '粗體';
    $lang->title_color = '標題顏色';
    $lang->new_document_count = 'N';

    $lang->parent_category_title = '主分類';
    $lang->category_title = '分類名稱';
    $lang->category_color = '分類顏色';
    $lang->expand = '展開';
    $lang->category_group_srls = '群組';

    $lang->cmd_make_child = '新增子分類';
    $lang->cmd_enable_move_category = '分類順序(勾選後用滑鼠拖曳分類項目)';

    $lang->about_category_title = '請輸入分類名稱。';
    $lang->about_expand = '選擇此項將維持展開狀態。';
    $lang->about_category_group_srls = '被選擇的群組才可以檢視此分類。';
    $lang->about_category_color = '設定分類顏色。例)紅色為 #ff0000';

    $lang->cmd_search_next = '繼續搜尋';

    $lang->cmd_temp_save = '臨時儲存';

    $lang->cmd_toggle_checked_document = '反選';
    $lang->cmd_delete_checked_document = '刪除所選';
    $lang->cmd_document_do = '將此主題..';

    $lang->msg_cart_is_null = '請選擇要刪除的文章。';
    $lang->msg_category_not_moved = '無法移動！';
    $lang->msg_is_secret = '秘密！';
    $lang->msg_checked_document_is_deleted = '刪除%d個文章。';

    $lang->move_target_module = '移到';

    // 管理者頁面中搜尋的內容
    $lang->search_target_list = array(
        'title' => '標題',
        'content' => '內容',
        'user_id' => '帳號',
        'member_srl' => '會員編號',
        'user_name' => '姓名',
        'nick_name' => '暱稱',
        'email_address' => '電子郵件',
        'homepage' => '主頁',
        'is_notice' => '公告',
        'is_secret' => '秘密',
        'tags' => '標籤',
        'readed_count' => '點閱數(以上)',
        'voted_count' => '推薦數(以上)',
        'comment_count ' => '評論數(以上)',
        'trackback_count ' => '引用數(以上)',
        'uploaded_count ' => '上傳檔案數(以上)',
        'regdate' => '登錄日期',
        'last_update' => '最近更新',
        'ipaddress' => 'IP位址',
    );

    $lang->alias = "Alias";
    $lang->history = "歷史紀錄";
    $lang->about_use_history = "選擇是否使用歷史記錄功能。選擇使用，將能夠編輯歷史紀錄並還原。";
    $lang->trace_only = "追蹤";

    $lang->cmd_trash = "垃圾桶";
    $lang->cmd_restore = "還原";
    $lang->cmd_restore_all = "全部還原";

    $lang->in_trash = "垃圾桶";
    $lang->trash_nick_name = "暱稱";
    $lang->trash_date = "刪除日期";
    $lang->trash_description = "原因";

    // 管理者頁面中垃圾桶的搜尋目標
    $lang->search_target_trash_list = array(
        'title' => '標題',
        'content' => '內容',
        'user_id' => '帳號',
        'member_srl' => '會員編號',
        'user_name' => '姓名',
        'nick_name' => '暱稱',
        'trash_member_srl' => '移除會員編號',
        'trash_user_name' => '移除姓名',
        'trash_nick_name' => '移除暱稱',
        'trash_date' => '刪除日期',
        'trash_ipaddress' => '移除 IP 位址',
    );

    $lang->success_trashed = "成功地移到垃圾桶";
    $lang->msg_not_selected_document = '尚未選擇主題';
	$lang->show_voted_member = '投票會員';
?>
