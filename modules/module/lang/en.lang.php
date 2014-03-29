<?php
    /**
     * @file   modules/module/lang/en.lang.php
     * @author NHN (developers@xpressengine.com)
     * @brief  English language pack
     **/

    $lang->virtual_site = "Virtual Site";
    $lang->module_list = "Modules List";
    $lang->module_index = "Modules List";
    $lang->module_category = "Module Category";
    $lang->module_info = "Module Info";
    $lang->add_shortcut = "Add Shortcuts";
    $lang->module_action = "Actions";
    $lang->module_maker = "Module Developer";
    $lang->module_license = 'License';
    $lang->module_history = "Update history";
    $lang->category_title = "Category Title";
    $lang->header_text = 'Header Text';
    $lang->footer_text = 'Footer Text';
    $lang->use_category = 'Enable Category';
    $lang->category_title = 'Category Title';
    $lang->checked_count = 'Number of Checked Articles';
    $lang->skin_default_info = 'Default Skin Info';
    $lang->skin_author = 'Skin Developer';
    $lang->skin_license = 'License';
    $lang->skin_history = 'Update history';
    $lang->module_copy = "Duplicate Module";
    $lang->module_selector = "Module Selector";
    $lang->do_selected = "I want to";
    $lang->bundle_setup = "Bundle Setup";
    $lang->bundle_addition_setup = "Bundle Additional Setup";
    $lang->bundle_grant_setup = "Bundle Permission Setup";
    $lang->lang_code = "Language Code";
    $lang->filebox = "FileBox";

    $lang->access_type = 'Access Type';
    $lang->access_domain = 'With Domain Name';
    $lang->access_vid = 'With Site ID';
    $lang->about_domain = "In order to create more than one virtual site, every club needs to have its own domain name.<br />Sub-domain (e.g., aaa.bbb.com of bbb.com) also can be used. Input the address including the path where XE is installed. <br /> ex) www.xpressengine.com/xe";
    $lang->about_vid = 'Users can access via http://XEaddress/ID. You cannot use same site id as the existing module name(mid).<br />Site id should start with an alphabet character . Alphabet characters, numbers and _ can be used for the site id.';
    $lang->msg_already_registed_vid = 'Already registered site id. Please input another ID.';
    $lang->msg_already_registed_domain = "Domain name has already been used. Please input another domain name.";

    $lang->header_script = "Header Script";
    $lang->about_header_script = "You can input the html script between &lt;header&gt; and &lt;/header&gt; by yourself.<br />You can use &lt;script, &lt;style or &lt;meta tag";

    $lang->grant_access = "Access";
    $lang->grant_manager = "Management";

    $lang->grant_to_all = "All users";
    $lang->grant_to_login_user = "Logged users";
    $lang->grant_to_site_user = "Registered users";
    $lang->grant_to_group = "Specification group users";

    $lang->cmd_add_shortcut = "Add Shortcut";
    $lang->cmd_install = "Install";
    $lang->cmd_update = "Update";
    $lang->cmd_manage_category = 'Manage Categories';
    $lang->cmd_manage_grant = 'Manage Permission';
    $lang->cmd_manage_skin = 'Manage Skins';
    $lang->cmd_manage_document = 'Manage Articles';
    $lang->cmd_find_module = 'Find Module';
    $lang->cmd_find_langcode = 'Find lang code';

    $lang->msg_new_module = "Create new module";
    $lang->msg_update_module = "Modify module";
    $lang->msg_module_name_exists = "The name already exists. Please try another name.";
    $lang->msg_category_is_null = 'There is no registered category.';
    $lang->msg_grant_is_null = 'There is no permission list.';
    $lang->msg_no_checked_document = 'No checked articles exist.';
    $lang->msg_move_failed = 'Failed to move';
    $lang->msg_cannot_delete_for_child = 'Cannot delete a category having child categories.';
	$lang->msg_limit_mid ="Only alphabets+[alphabets+numbers+_] can be used as module name.";
    $lang->msg_extra_name_exists = 'Already registered extra variable name. Please input another name.';

    $lang->about_browser_title = "It will be shown in the browser title. It will be also used in a RSS/Trackback.";
    $lang->about_mid = "The module name will be used like http://address/?mid=ModuleName.\n(Only english alphabet + [english alphabet, numbers, and underscore(_)] are allowed. The maximum length is 40.)";
    $lang->about_default = "If checked, the default will be shown when access to the site without no mid value(mid=NoValue).";
    $lang->about_module_category = "It enables you to manage it through module category.\n The URL for the module manager is <a href=\"./?module=admin&amp;act=dispModuleAdminCategory\">Manage module > Module category </a>.";
    $lang->about_description= 'It is the description only for a manager.';
    $lang->about_default = 'If checked, this module will be shown when users access to the site without mid value (mid=NoValue).';
    $lang->about_header_text = 'The contents will be shown on the top of the module.(html tags available)';
    $lang->about_footer_text = 'The contents will be shown on the bottom of the module.(html tags available)';
    $lang->about_skin = 'You may choose a module skin.';
    $lang->about_use_category = 'If checked, category function will be enabled.';
    $lang->about_list_count = 'You can set the number of limit to show article in a page.(default is 20)';
	$lang->about_search_list_count = 'You may set the number of articles to be exposed when you use search or category function. (default is 20)';
    $lang->about_page_count = 'You can set the number of page link to move pages in a bottom of page.(default is 10)';
    $lang->about_admin_id = 'You can grant a manager to have all permissions to the module.';
    $lang->about_grant = 'If you disable all permissions for a specific object, members who has not logged in would get permission.';
    $lang->about_grant_deatil = 'Registered users mean users who signed-up to the virtual sites (e.g., cafeXE).';
    $lang->about_module = "XE consists of modules except basic library.\n [Module Manage] module will show all installed modules and help you to manage them.";

	$lang->about_extra_vars_default_value = 'If multiple default values are needed,	 you can link them with comma(,).';
    $lang->about_search_virtual_site = "Input domain of virtual sites.<br />To search modules of non-virtual site, search with blank";
    $lang->about_langcode = "If you want to configure seperately, use 'find language code'";
    $lang->about_file_extension= "Only %s extension(s) is available.";
?>
