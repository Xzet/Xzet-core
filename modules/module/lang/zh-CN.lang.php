<?php
    /**
     * @file   zh-CN.lang.php
     * @author NHN (developers@xpressengine.com)
     * @brief  简体中文语言包
     **/

    $lang->virtual_site = "站点";
    $lang->module_list = "模块目录";
    $lang->module_index = "模块列表";
    $lang->module_category = "模块分类";
    $lang->module_info = "模块信息";
    $lang->add_shortcut = "添加到快捷菜单";
    $lang->module_action = "动作";
    $lang->module_maker = "模块作者";
    $lang->module_license = '版权';
    $lang->module_history = "更新事项 ";
    $lang->category_title = "分类名称";
    $lang->header_text = '头部修饰';
    $lang->footer_text = '尾部修饰';
    $lang->use_category = '使用分类';
    $lang->category_title = '分类名';
    $lang->checked_count = '所选主题数';
    $lang->skin_default_info = '皮肤默认信息';
    $lang->skin_author = '皮肤作者';
    $lang->skin_license = '版权';
    $lang->skin_history = '更新日志';
    $lang->module_copy = "模块复制";
    $lang->module_selector = "模块选择器";
    $lang->do_selected = "把所选模块...";
    $lang->bundle_setup = "批量设置-常规选项";
    $lang->bundle_addition_setup = "批量设置-高级选项";
    $lang->bundle_grant_setup = "批量设置-权限";
    $lang->lang_code = "语言变量";
    $lang->filebox = "文件管理";

    $lang->access_type = '访问方式';
    $lang->access_domain = '域名';
    $lang->access_vid = '站点ID';
    $lang->about_domain = "要创建一个站点必须有一个专用域名。<br/>一级域名或二级域名皆可。输入的时候请把XE安装路径也一起输入。<br />ex) www.xpressengine.com/xe";
    $lang->about_vid = '直接以http://XE安装地址/ID的方式访问。<br/>模块名(mid)不能重复。<br/>模块名要以英文字母开头，且只允许使用英文字母，数字及"_"。';
    $lang->msg_already_registed_vid = '重复的站点ID，请重新输入(注：站点ID不能重复，而且也不能与版面mid重复)。';
    $lang->msg_already_registed_domain = "对不起！已有相同的域名。请重新输入。";

    $lang->header_script = "文件头部脚本";
    $lang->about_header_script = "可以直接输入插入到html中&lt;head&gt;区的代码。<br />可使用&lt;script, &lt;style 或 &lt;meta 等标签。";

    $lang->grant_access = "访问权限";
    $lang->grant_manager = "管理权限";

    $lang->grant_to_all = "不限制";
    $lang->grant_to_login_user = "已登录用户";
    $lang->grant_to_site_user = "子站会员";
    $lang->grant_to_group = "特定用户组";

    $lang->cmd_add_shortcut = "添加到快捷菜单";
    $lang->cmd_install = "安装";
    $lang->cmd_update = "升级";
    $lang->cmd_manage_category = '分类管理';
    $lang->cmd_manage_grant = '权限管理';
    $lang->cmd_manage_skin = '皮肤管理';
    $lang->cmd_manage_document = '主题管理';
    $lang->cmd_find_module = '查找模块';
    $lang->cmd_find_langcode = '选择语言变量';

    $lang->msg_new_module = "模块生成";
    $lang->msg_update_module = "模块修改";
    $lang->msg_module_name_exists = "已存在的模块名称。请输入其他名称。";
    $lang->msg_category_is_null = '没有登录的分类';
    $lang->msg_grant_is_null = '没有登录的权限对象';
    $lang->msg_no_checked_document = '没有被选择的主题';
    $lang->msg_move_failed = '移动失败！';
    $lang->msg_cannot_delete_for_child = '不能删除有下级分类的分类！';
    $lang->msg_limit_mid ="模块名称只允许英文字母+[英文字母+数字]。";
    $lang->msg_extra_name_exists = '已有的扩展变量名称，请重新输入！';

    $lang->about_browser_title = "显示在浏览器窗口的标题值。 在RSS/Trackback也可以使用。";
    $lang->about_mid = "模块名称只允许使用英文，数字和下划线(最多不能超过40字节)。";
    $lang->about_default = "用没有mid值的网址访问网站时，将会显示默认。";
    $lang->about_module_category = "可以分类管理模块。 模块分类可以在 <a href=\"./?module=admin&amp;act=dispModuleAdminCategory\">模块管理 > 模块分类 </a>中进行管理。";
    $lang->about_description= '管理用使用说明。';
    $lang->about_default = '用没有mid值的网址访问网站时，将会显示默认。';
    $lang->about_header_text = '模块头部修饰内容。(可以使用HTML)';
    $lang->about_footer_text = '模块尾部修饰内容。(可以使用HTML)';
    $lang->about_skin = '可以选择模块皮肤。';
    $lang->about_use_category = '选择此项可以使用分类功能。';
    $lang->about_list_count = '可以指定每页显示的主题数。(默认为20个)';
    $lang->about_search_list_count = '可以指定搜索或选择分类时每页要显示的帖子数(默认为20个)。';
    $lang->about_page_count = '可以指定显示在目录下方的页面数(默认为10个)。 ';
    $lang->about_admin_id = '可以对该模块指定拥有最高管理权限的管理员。';
    $lang->about_grant = '全部解除特定权限的对象时，没有登录的会员也将具有相关权限。';
    $lang->about_grant_deatil = '子站会员是指注册到子站点已在线的用户.';
    $lang->about_module = "除基本library以外XE全部由模块组成。\n模块管理中列出所有已安装的模块，因此易于管理。";
    $lang->about_extra_vars_default_value = '复选/单选缺省值需要多个时,用,(逗号)来分隔。';
    $lang->about_search_virtual_site = "请输入子站点域名后再进行搜索。<br/>子站点以外的模块可以留空搜索。(输入时http://除外)。";
    $lang->about_extra_vars_eid_value = '请输入扩展变量名称(只允许英文字母+[英文字母+数字+_])。';
    $lang->about_langcode = "要想实现多国语言切换请点击[选择语言变量]按钮。";
    $lang->about_file_extension= "只允许%s文件。";
?>
