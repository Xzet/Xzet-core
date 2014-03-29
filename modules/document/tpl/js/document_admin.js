
/**
 * @brief 모든 생성된 섬네일 삭제하는 액션 호출
 **/
function doDeleteAllThumbnail() {
    exec_xml('document','procDocumentAdminDeleteAllThumbnail', [], completeDeleteAllThumbnail);
}

function completeDeleteAllThumbnail(ret_obj) {
    alert(ret_obj['message']);
    location.reload();
}

/* 선택된 글의 삭제 또는 이동 */
function doManageDocument(type) {
    var fo_obj = jQuery("#fo_management").get(0);
    fo_obj.type.value = type;

    procFilter(fo_obj, manage_checked_document);
}

/* 선택된 글의 삭제 또는 이동 후 */
function completeManageDocument(ret_obj) {
    if(opener) { 
        opener.window.location.href = opener.window.current_url.setQuery('document_srl','');
    }
    alert(ret_obj['message']);
    window.close();
}

/* 선택된 모듈의 카테고리 목록을 가져오는 함수 */
function doGetCategoryFromModule(module_srl) {
    var params = new Array();
    params['module_srl'] = module_srl;

    var response_tags = ['error','message','categories'];

    exec_xml('document','getDocumentCategories',params, completeGetCategoryFromModules, response_tags);
}

function completeGetCategoryFromModules(ret_obj, response_tags) {
    var obj = jQuery('#target_category').get(0);
    var length = obj.options.length;
    for(var i=0;i<length;i++) obj.remove(0);

    var categories = ret_obj['categories'];
    if(!categories) return;

	var depth_str = '-';
	for(var i=0; i < 5; i++) depth_str += depth_str;

    var category_list = categories.split("\n");
    for(var i=0;i<category_list.length;i++) {
        var item = category_list[i];

        var pos = item.indexOf(',');
        var category_srl = item.substr(0,pos);

        var item = item.substr(pos+1, item.length);
        var pos = item.indexOf(',');
        var depth = item.substr(0,pos);

        var category_title = item.substr(pos+1,item.length);
        if(!category_srl || !category_title) continue;

		if (depth > 0) category_title = depth_str.substr(0, depth) + ' ' + category_title;
        var opt = new Option(category_title, category_srl, false, false);
        obj.options[obj.options.length] = opt;
    }
}

function doCancelDeclare() {
    var document_srl = [];
    jQuery('#fo_list input[name=cart]:checked').each(function() {
        document_srl[document_srl.length] = jQuery(this).val();
    });

    if(document_srl.length<1) return;

    var params = {document_srl : document_srl.join(',')};

    exec_xml('document','procDocumentAdminCancelDeclare', params, completeCancelDeclare);
}

function completeCancelDeclare(ret_obj) {
    location.reload();
}

function insertSelectedModule(id, module_srl, mid, browser_title) {
    jQuery('#_'+id).val(browser_title+' ('+mid+')');
    jQuery('#'+id).val(module_srl);
    doGetCategoryFromModule(module_srl);
}

function completeInsertExtraVar(ret_obj) {
    alert(ret_obj['message']);
    location.href = current_url.setQuery('type','').setQuery('selected_var_idx','');
}

function completeInsertAlias(ret_obj) {
    alert(ret_obj['message']);
    location.href = current_url;
}

function insertSelectedModule(id, module_srl, mid, browser_title) {
    if(current_url.getQuery('act')=='dispDocumentManageDocument') {
        jQuery('#_'+id).val(browser_title+' ('+mid+')');
        jQuery('#'+id).val(module_srl);
        doGetCategoryFromModule(module_srl);
    } else {
        location.href = current_url.setQuery('module_srl',module_srl);
    }
}

function deleteByFilter(target_srl, filter)
{
    jQuery('#target_srl').val(target_srl);
    var hF = jQuery("deleteForm")[0];
    procFilter(hF, filter);
}

function executeFilterByTargetSrl(form_name, target_srl, filter)
{
    jQuery('#target_srl').val(target_srl);
    var hF = jQuery('#'+form_name)[0];
    procFilter(hF, filter);
}

function doDeleteExtraKey(module_srl, var_idx) {
    var fo_obj = jQuery('#fo_delete')[0];
    fo_obj.module_srl.value = module_srl;
    fo_obj.var_idx.value = var_idx;
    return procFilter(fo_obj, delete_extra_var);
}

function moveVar(type, module_srl, var_idx) {
    var params = {
		type       : type,
		module_srl : module_srl,
		var_idx    : var_idx
	};
    var response_tags = ['error','message'];
    exec_xml('document','procDocumentAdminMoveExtraVar', params, function() { location.reload() });
}

function completeRestoreTrash(ret_obj) {
    alert(ret_obj['message']);
    location.href = current_url;
}
