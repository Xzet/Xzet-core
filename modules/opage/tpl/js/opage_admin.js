/**
 * @file   modules/opage/js/opage_admin.js
 * @author NHN (developers@xpressengine.com)
 * @brief  opage모듈의 관리자용 javascript
 **/

/* 모듈 생성 후 */
function completeInsertOpage(ret_obj) {
    var error = ret_obj['error'];
    var message = ret_obj['message'];

    var opage = ret_obj['opage'];
    var module_srl = ret_obj['module_srl'];

    alert(message);

    var url = '';
    if(location.href.getQuery('module')=='admin') {
        url = current_url.setQuery('module_srl',module_srl).setQuery('act','dispOpageAdminInsert');
        if(opage) url = url.setQuery('opage',opage);
    } else {
        url = current_url.setQuery('act','').setQuery('module_srl','');
    }

    location.href = url;
}

/* 모듈 삭제 후 */
function completeDeleteOpage(ret_obj) {
    var error = ret_obj['error'];
    var message = ret_obj['message'];
    var opage = ret_obj['opage'];
    alert(message);

    var url = current_url.setQuery('act','dispOpageAdminContent');
    if(opage) url = url.setQuery('opage',opage);

    location.href = url;
}

/* 카테고리 이동 */
function doChangeCategory(fo_obj) {
    var module_category_srl = fo_obj.module_category_srl.options[fo_obj.module_category_srl.selectedIndex].value;
    if(module_category_srl==-1) {
        location.href = current_url.setQuery('act','dispModuleAdminCategory');
        return false;
    }
    return true;
}

/* 일괄 설정 */
function doCartSetup(url) {
    var module_srl = new Array();
    jQuery('#fo_list input[name=cart]:checked').each(function() {
        module_srl[module_srl.length] = jQuery(this).val();
    });

    if(module_srl.length<1) return;

    url += "&module_srls="+module_srl.join(',');
    popopen(url,'modulesSetup');
}
