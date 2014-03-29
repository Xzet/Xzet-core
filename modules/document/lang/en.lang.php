<?php
    /**
     * @file   modules/document/lang/en.lang.php
     * @author NHN (developers@xpressengine.com)
     * @brief  Document module's basic language pack
     **/

    $lang->document_list = 'Documents List';
    $lang->thumbnail_type = 'Thumbnail Type';
    $lang->thumbnail_crop = 'Crop';
    $lang->thumbnail_ratio = 'Ratio';
    $lang->cmd_delete_all_thumbnail = 'Delete all thumbnails';
    $lang->move_target_module = "Target module ";
    $lang->title_bold = 'Bold';
    $lang->title_color = 'Color';
    $lang->new_document_count = 'New documents';

    $lang->parent_category_title = 'Parent Category';
    $lang->category_title = 'Category';
    $lang->category_color = 'Category Font Color';
    $lang->expand = 'Expand';
    $lang->category_group_srls = 'Accessable Group';

    $lang->cmd_make_child = 'Add Child Category';
    $lang->cmd_enable_move_category = "Change Category Position (Drag the top menu after selection)";

    $lang->about_category_title = 'Please input category name';
    $lang->about_expand = 'By selecting this option, it will be always expanded';
    $lang->about_category_group_srls = 'Only selected group will be able to use current category';
    $lang->about_category_color = 'You can set font color of category.';

    $lang->cmd_search_next = 'Search Next';

    $lang->cmd_temp_save = 'Temporary Save';

	$lang->cmd_toggle_checked_document = 'Reverse selected items';
    $lang->cmd_delete_checked_document = 'Delete selected';
    $lang->cmd_document_do = 'I want to';

    $lang->msg_cart_is_null = 'Please select the articles to delete';
    $lang->msg_category_not_moved = 'Could not be moved';
    $lang->msg_is_secret = 'This is a secret article';
    $lang->msg_checked_document_is_deleted = '%d article(s) was(were) deleted';

    // Search targets in admin page
        $lang->search_target_list = array(
        'title' => 'Subject',
        'content' => 'Content',
        'user_id' => 'User ID',
        'member_srl' => 'Member Serial Number',
        'user_name' => 'User Name',
        'nick_name' => 'Nickname',
        'email_address' => 'Email',
        'homepage' => 'Homepage',
        'is_notice' => 'Notice',
        'is_secret' => 'Secret',
        'tags' => 'Tag',
        'readed_count' => 'Number of Views (over)',
        'voted_count' => 'Number of Votes (over)',
        'comment_count ' => 'Number of Comments (over)',
        'trackback_count ' => 'Number of Trackbacks (over)',
        'uploaded_count ' => 'Number of Attachments (over)',
        'regdate' => 'Date',
        'last_update' => 'Last Modified Date',
        'ipaddress' => 'IP Address',
    );

    $lang->alias = "Alias";
    $lang->history = "History";
    $lang->about_use_history = "History can restore documents to previous revisions";
    $lang->trace_only = "Trace only";

    $lang->cmd_trash = 'Trashcan';
    $lang->cmd_restore = 'Restore';
    $lang->cmd_restore_all = 'Restore All';

    $lang->in_trash = 'Trashcan';
    $lang->trash_nick_name = 'Deleter';
    $lang->trash_date = 'Deleted date';
    $lang->trash_description = 'Description';

	$lang->search_target_trash_list = array(
        'title' => 'Title',
        'content' => 'Content',
        'user_id' => 'User ID',
        'member_srl' => 'Member srl',
        'user_name' => 'User name',
        'nick_name' => 'Nickname',
        'trash_member_srl' => 'Deleter srl',
        'trash_user_name' => 'Deleter name',
        'trash_nick_name' => 'Deleter nickname',
        'trash_date' => 'Deleted date',
        'trash_ipaddress' => 'Deleter IP address',
    );

    $lang->success_trashed = "Successfully moved to trashcan";
    $lang->msg_not_selected_document = 'There is no selected article.';
	$lang->show_voted_member = 'Show Voters';
?>
