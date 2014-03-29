<?php
    /**
     * @file   modules/file/lang/zh-CN.lang.php
     * @author NHN (developers@xpressengine.com)
     * @brief  附件(file) 模块语言包
     **/

    $lang->file = '附件';
    $lang->file_name = '文件名';
    $lang->file_size = '文件大小';
    $lang->download_count = '下载次数';
    $lang->status = '状态';
    $lang->is_valid = '有效';
    $lang->is_stand_by = '等待';
    $lang->file_list = '附件列表';
    $lang->allow_outlink = '防盗链';
    $lang->allow_outlink_site = '允许外链站点';
    $lang->allowed_filesize = '文件大小限制';
    $lang->allowed_attach_size = '上传限制';
    $lang->allowed_filetypes = '可用扩展名';
    $lang->enable_download_group = '允许下载的用户组';

    $lang->about_allow_outlink = '根据反向链接防止盗链。(*.wmv, *.mp3等媒体文件除外)';
    $lang->about_allow_outlink_site = '可以设置允许外链的站点。多个站点以换行来区分，即一行一个。<br />ex)http://xpressengine.com/';
    $lang->about_allowed_filesize = '最大单个上传文件大小(管理员不受此限制)。';
    $lang->about_allowed_attach_size = '每个主题最大上传文件大小(管理员不受此限制)。';
    $lang->about_allowed_filetypes = '只允许上传指定的扩展名。 可以用"*.扩展名"来指定或用 ";"来 区分多个扩展名<br />例) *.* or *.jpg;*.gif;<br />(管理员不受此限制)';

    $lang->cmd_delete_checked_file = '删除所选项目';
    $lang->cmd_move_to_document = '查看源主题';
    $lang->cmd_download = '下载';

    $lang->msg_not_permitted_download = '您不具备下载的权限。';
    $lang->msg_cart_is_null = ' 请选择要删除的文件。';
    $lang->msg_checked_file_is_deleted = '已删除%d个文件！';
    $lang->msg_exceeds_limit_size = '已超过系统指定的上传文件大小！';
    $lang->msg_file_not_found = '无法找到该文件。';

    $lang->file_search_target_list = array(
        'filename' => '文件名',
        'filesize_more' => '文件大小 (byte, 以上)',
        'filesize_mega_more' => '文件大小 (Mb, 以上)',
	'filesize_less' => '文件大小 (byte, 以下)',
	'filesize_mega_less' => '文件大小 (Mb, 以下)',
        'download_count' => '下载次数 (以上)',
        'user_id' => '用户名',
        'user_name' => '姓名',
        'nick_name' => '昵称',
        'regdate' => '登录日期',
        'ipaddress' => 'IP地址',
    );
    $lang->msg_not_allowed_outlink = '此站不允许外链下载。'; 
    $lang->msg_not_permitted_create = '无法生成文件或文件夹。';
    $lang->msg_file_upload_error = '上传文件发生出错。';

?>
