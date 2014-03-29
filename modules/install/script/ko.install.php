<?php
	// ko/en/...
	$lang = Context::getLangType();

	// insertMenu
	$menu_args->site_srl = 0;
	$menu_args->title = 'welcome_menu';
	$menu_srl = $menu_args->menu_srl = getNextSequence();
	$menu_args->listorder = $menu_srl * -1;

	$output = executeQuery('menu.insertMenu', $menu_args);
	if(!$output->toBool()) return $output;

	// insertMenuItem
		// create 1depth menuitem
	$item_args->menu_srl = $menu_srl;
	$item_args->name = 'menu1';
	$parent_srl = $item_args->menu_item_srl = getNextSequence();
	$item_args->listorder = -1*$item_args->menu_item_srl;

	$output = executeQuery('menu.insertMenuItem', $item_args);
	if(!$output->toBool()) return $output;

		// create 2depth menuitem
	unset($item_args);
	$item_args->menu_srl = $menu_srl;
	$item_args->parent_srl = $parent_srl;
	$item_args->url = 'welcome_page';
	$item_args->name = 'menu1-1';
	$item_args->menu_item_srl = getNextSequence();
	$item_args->listorder = -1*$item_args->menu_item_srl;

	$output = executeQuery('menu.insertMenuItem', $item_args);
	if(!$output->toBool()) return $output;

		// XML 파일을 갱신
	$oMenuAdminController = &getAdminController('menu');
	$oMenuAdminController->makeXmlFile($menu_srl);

	// create Layout
		//extra_vars init
	$extra_vars->colorset = 'default';
	$extra_vars->main_menu = $menu_srl;
	$extra_vars->bottom_menu = $menu_srl;
	$extra_vars->menu_name_list = array();
	$extra_vars->menu_name_list[$menu_srl] = 'welcome_menu';

	$args->site_srl = 0;
	$layout_srl = $args->layout_srl = getNextSequence();
	$args->layout = 'xe_official';
	$args->title = 'welcome_layout';
	$args->layout_type = 'P';

	$oLayoutAdminController = &getAdminController('layout');
	$output = $oLayoutAdminController->insertLayout($args);
	if(!$output->toBool()) return $output;

		// update Layout
	$args->extra_vars = serialize($extra_vars);
	$output = $oLayoutAdminController->updateLayout($args);
	if(!$output->toBool()) return $output;

	// insertPageModule
	$page_args->layout_srl = $layout_srl;
	$page_args->module = 'page';
	$page_args->mid = 'welcome_page';
	$page_args->module_category_srl = 0;
	$page_args->page_caching_interval = 0;
	
	$oModuleController = &getController('module');
	$output = $oModuleController->insertModule($page_args);

	if(!$output->toBool()) return $output;

	$module_srl = $output->get('module_srl');

	// insert PageContents - widget
	$oTemplateHandler = &TemplateHandler::getInstance();

	$oDocumentModel = &getModel('document');
	$oDocumentController = &getController('document');

	$obj->module_srl = $module_srl;
	Context::set('version', __ZBXE_VERSION__);
	$obj->title = 'welcome_document';

	$obj->content = $oTemplateHandler->compile('./modules/install/script/welcome_content', 'welcome_content_'.$lang);

	$output = $oDocumentController->insertDocument($obj);
	if(!$output->toBool()) return $output;
	
	$document_srl = $output->get('document_srl');

	// save PageWidget
	$oModuleModel = &getModel('module');
	$module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
	$module_info->content = '<img src="./common/tpl/images/widget_bg.jpg" class="zbxe_widget_output" widget="widgetContent" style="WIDTH: 100%; FLOAT: left" body="" document_srl="'.$document_srl.'" widget_padding_left="0" widget_padding_right="0" widget_padding_top="0" widget_padding_bottom="0"  />';

	$output = $oModuleController->updateModule($module_info);
	if(!$output->toBool()) return $output;

	// insertFirstModule
	$site_args->site_srl = 0;
	$site_args->index_module_srl = $module_srl;
	$oModuleController->updateSite($site_args);

?>
