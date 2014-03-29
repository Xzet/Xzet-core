<?php
    /**
     * @file   modules/editor/lang/zh-TW.lang.php
     * @author NHN (developers@xpressengine.com) 翻譯：royallin
     * @brief  網頁編輯器(editor)模組正體中文語言
     **/

    $lang->editor = '網頁編輯器';
    $lang->component_name = '組件';
    $lang->component_version = '版本';
    $lang->component_author = '作者';
    $lang->component_link = '連結';
    $lang->component_date = '編寫日期';
    $lang->component_license = '版權';
    $lang->component_history = '更新紀錄';
    $lang->component_description = '說明';
    $lang->component_extra_vars = '變數設置';
    $lang->component_grant = '權限設置'; 
    $lang->content_style = '內容樣式';
    $lang->content_font = '內容字體';
	$lang->content_font_size = '字體大小';

    $lang->about_component = '組件簡介';
    $lang->about_component_grant = '除預設組件外，可設置延伸組件的使用權限<br />(全部解除時，任何用戶都可使用)。';
    $lang->about_component_mid = '可以指定使用編輯器組件的對象。<br />(全部解除時，任何用戶都可使用)。';

    $lang->msg_component_is_not_founded = '找不到%s 組件說明！';
    $lang->msg_component_is_inserted = '您選擇的組件已插入！';
    $lang->msg_component_is_first_order = '您選擇的組件已達最頂端位置！';
    $lang->msg_component_is_last_order = '您選擇的組件已達最底端位置！';
    $lang->msg_load_saved_doc = "有自動儲存的內容，確定要恢復嗎?\n儲存內容後，自動儲存的內容將會被刪除。";
    $lang->msg_auto_saved = '已自動儲存！';

    $lang->cmd_disable = '暫停';
    $lang->cmd_enable = '啟動';

    $lang->editor_skin = '編輯器面板';
    $lang->upload_file_grant = '檔案上傳權限'; 
    $lang->enable_default_component_grant = '預設組件使用權限';
    $lang->enable_component_grant = '組件使用權限';
    $lang->enable_html_grant = 'HTML編輯權限';
    $lang->enable_autosave = '內容自動儲存';
    $lang->height_resizable = '高度調整';
    $lang->editor_height = '編輯器高度';

    $lang->about_editor_skin = '選擇編輯器面板。';
    $lang->about_content_style = '修改內容時，可指定要顯示的內容樣式';
    $lang->about_content_font = '修改內容時，可指定要顯示的內容字體。<br/>如果沒有指定的話，以系統預設為主。<br/> 以逗號(，)區分多個字體。';
	$lang->about_content_font_size = '修改內容時，可指定要顯示的內容字體大小。<br/>請輸入單位，像是12px, 1em等。';
    $lang->about_upload_file_grant = '設置上傳檔案的權限(全部解除為無限制)。';
    $lang->about_default_component_grant = '設置編輯器預設組件的使用權限(全部解除為無限制)。';
    $lang->about_editor_height = '指定編輯器的預設高度。';
    $lang->about_editor_height_resizable = '允許用戶拖曳編輯器高度。';
    $lang->about_enable_html_grant = 'HTML原始碼編輯權限設置。';
    $lang->about_enable_autosave = '發表主題時，開啟內容自動儲存功能。';

    $lang->edit->fontname = '字體';
    $lang->edit->fontsize = '大小';
    $lang->edit->use_paragraph = '段落功能';
    $lang->edit->fontlist = array(
    '新細明體' => '新細明體',
    '標楷體' => '標楷體',
    '細明體' => '細明體',
    'Arial' => 'Arial',
    'Arial Black' => 'Arial Black',
    'Tahoma' => 'Tahoma',
    'Verdana' => 'Verdana',
    'Sans-serif' => 'Sans-serif',
    'Serif' => 'Serif',
    'Monospace' => 'Monospace',
    'Cursive' => 'Cursive',
    'Fantasy' => 'Fantasy',
    );

    $lang->edit->header = '樣式';
    $lang->edit->header_list = array(
    'h1' => '標題 1',
    'h2' => '標題 2',
    'h3' => '標題 3',
    'h4' => '標題 4',
    'h5' => '標題 5',
    'h6' => '標題 6',
    );

    $lang->edit->submit = '確認';

    $lang->edit->fontcolor = '文字顏色';
	$lang->edit->fontcolor_apply = '套用文字顏色';
	$lang->edit->fontcolor_more = '更多文字顏色';
    $lang->edit->fontbgcolor = '背景顏色';
	$lang->edit->fontbgcolor_apply = '套用背景顏色';
	$lang->edit->fontbgcolor_more = '更多背景顏色';
    $lang->edit->bold = '粗體';
    $lang->edit->italic = '斜體';
    $lang->edit->underline = '底線';
    $lang->edit->strike = '虛線';
    $lang->edit->sup = '上標';
    $lang->edit->sub = '下標';
    $lang->edit->redo = '重新操作';
    $lang->edit->undo = '返回操作';
    $lang->edit->align_left = '靠左對齊';
    $lang->edit->align_center = '置中對齊';
    $lang->edit->align_right = '靠右對齊';
    $lang->edit->align_justify = '左右對齊';
    $lang->edit->add_indent = '縮排';
    $lang->edit->remove_indent = '凸排';
    $lang->edit->list_number = '編號';
    $lang->edit->list_bullet = '清單符號';
    $lang->edit->remove_format = '移除格式';

    $lang->edit->help_remove_format = '移除格式';
    $lang->edit->help_strike_through = '文字刪除線';
    $lang->edit->help_align_full = '左右對齊';

    $lang->edit->help_fontcolor = '文字顏色';
    $lang->edit->help_fontbgcolor = '背景顏色';
    $lang->edit->help_bold = '粗體';
    $lang->edit->help_italic = '斜體';
    $lang->edit->help_underline = '底線';
    $lang->edit->help_strike = '虛線';
    $lang->edit->help_sup = '上標';
    $lang->edit->help_sub = '下標';
    $lang->edit->help_redo = '重新操作';
    $lang->edit->help_undo = '返回操作';
    $lang->edit->help_align_left = '靠左對齊';
    $lang->edit->help_align_center = '置中對齊';
    $lang->edit->help_align_right = '靠右對齊';
	$lang->edit->help_align_justify = '左右對齊';
    $lang->edit->help_add_indent = '縮排';
    $lang->edit->help_remove_indent = '凸排';
    $lang->edit->help_list_number = '編號';
    $lang->edit->help_list_bullet = '清單符號';
    $lang->edit->help_use_paragraph = '換行請按 Ctrl+Backspace (快速發表主題：Alt+S)';

    $lang->edit->url = '連結';
    $lang->edit->blockquote = '引用';
    $lang->edit->table = '表格';
    $lang->edit->image = '圖片';
    $lang->edit->multimedia = '影片';
    $lang->edit->emoticon = '表情符號';

	$lang->edit->file = '檔案';
    $lang->edit->upload = '上傳';
    $lang->edit->upload_file = '上傳附檔';
	$lang->edit->upload_list = '檔案列表';
    $lang->edit->link_file = '插入檔案';
    $lang->edit->delete_selected = '刪除所選';

    $lang->edit->icon_align_article = '段落';
    $lang->edit->icon_align_left = '靠左';
    $lang->edit->icon_align_middle = '置中';
    $lang->edit->icon_align_right = '靠右';

    $lang->about_dblclick_in_editor = '對背景，文字，圖片，引用等組件按兩下，即可對其相關組件進行詳細設置。';


    $lang->edit->rich_editor = '所見即得';
    $lang->edit->html_editor = 'HTML';
    $lang->edit->extension ='延伸組件';
    $lang->edit->help = '使用說明';
    $lang->edit->help_command = '熱鍵指引';
    
    $lang->edit->lineheight = '行距';
	$lang->edit->fontbgsampletext = 'ㄅㄆㄇ';
	
	$lang->edit->hyperlink = '超連結';
	$lang->edit->target_blank = '新視窗';
	
	$lang->edit->quotestyle1 = '左側實線';
	$lang->edit->quotestyle2 = '引用符號';
	$lang->edit->quotestyle3 = '實線';
	$lang->edit->quotestyle4 = '實線 + 背景';
	$lang->edit->quotestyle5 = '粗框';
	$lang->edit->quotestyle6 = '虛線';
	$lang->edit->quotestyle7 = '虛線 + 背景';
	$lang->edit->quotestyle8 = '取消';


    $lang->edit->jumptoedit = '跳過編輯工具列';
    $lang->edit->set_sel = '表格';
    $lang->edit->row = '行';
    $lang->edit->col = '列';
    $lang->edit->add_one_row = '新增一行';
    $lang->edit->del_one_row = '刪除一行';
    $lang->edit->add_one_col = '新增一列';
    $lang->edit->del_one_col = '刪除一列';

    $lang->edit->table_config = '設置';
    $lang->edit->border_width = '邊框寬度';
    $lang->edit->border_color = '邊框顏色';
    $lang->edit->add = '新增';
    $lang->edit->del = '刪除';
    $lang->edit->search_color = '其他顏色';
    $lang->edit->table_backgroundcolor = '背景顏色';
    $lang->edit->special_character = '特殊符號';
    $lang->edit->insert_special_character = '插入特殊符號';
    $lang->edit->close_special_character = '關閉';
    $lang->edit->symbol = '一般符號';
    $lang->edit->number_unit = '數字、單位';
    $lang->edit->circle_bracket = '圓、括弧';
    $lang->edit->korean = '韓國語';
    $lang->edit->greece = '希臘語';
    $lang->edit->Latin  = '拉丁語';
    $lang->edit->japan  = '日本語';
    $lang->edit->selected_symbol  = '選擇符號';

    $lang->edit->search_replace  = '搜尋/置換';
    $lang->edit->close_search_replace  = '關閉搜尋/置換圖層';
    $lang->edit->replace_all  = '全部置換';
    $lang->edit->search_words  = '搜尋文字';
    $lang->edit->replace_words  = '置換文字';
    $lang->edit->next_search_words  = '搜尋下一個';
    $lang->edit->edit_height_control  = '設定大小';

	$lang->edit->merge_cells = '合併儲存格';
    $lang->edit->split_row = '分割行';
    $lang->edit->split_col = '分割列';
    
    $lang->edit->toggle_list   = '摺疊/展開';
    $lang->edit->minimize_list = '最小化';
    
    $lang->edit->move = '搬移';
	$lang->edit->refresh = '重整';
    $lang->edit->materials = '素材';
    $lang->edit->temporary_savings = '暫存檔列表';

	$lang->edit->paging_prev = '前頁';
	$lang->edit->paging_next = '次頁';
	$lang->edit->paging_prev_help = '往上一頁';
	$lang->edit->paging_next_help = '往下一頁';

	$lang->edit->toc = '目錄';
	$lang->edit->close_help = '關閉';

	$lang->edit->confirm_submit_without_saving = '有內容尚未儲存。\\n是否要繼續進行?';

	$lang->edit->image_align = '圖片對齊';
	$lang->edit->attached_files = '附加檔案';

	$lang->edit->fontcolor_input = '自訂文字顏色';
	$lang->edit->fontbgcolor_input = '自訂背景顏色';
	$lang->edit->pangram = '實際範例';

	$lang->edit->table_caption_position = '標籤位置';
	$lang->edit->table_caption = '表格標籤(caption)';
	$lang->edit->table_header = '標頭';
	$lang->edit->table_header_none = '無';
	$lang->edit->table_header_left = '左側';
	$lang->edit->table_header_top = '頂端';
	$lang->edit->table_header_both = '兩者';
	$lang->edit->table_size = '大小';
	$lang->edit->table_width = '寬度';

	$lang->edit->upper_left = '左上方';
	$lang->edit->upper_center = '正上方';
	$lang->edit->upper_right = '右上方';
	$lang->edit->bottom_left = '左下方';
	$lang->edit->bottom_center = '正下方';
	$lang->edit->bottom_right = '右下方';

	$lang->edit->no_image = '目前沒有圖片';
	$lang->edit->no_multimedia = '目前沒有影片';
	$lang->edit->no_attachment = '目前沒有附檔';
	$lang->edit->insert_selected = '插入所選';
	$lang->edit->delete_selected = '刪除所選';

	$lang->edit->fieldset = '區域';
	$lang->edit->paragraph = '段落';
	
	$lang->edit->autosave_format = '正在編寫 <strong>%s</strong> 最後儲存時間 <strong>%s</strong> ';
	$lang->edit->autosave_hour = '%d小時';
	$lang->edit->autosave_hours = '%d小時';
	$lang->edit->autosave_min = '%d分';
	$lang->edit->autosave_mins = '%d分';
	$lang->edit->autosave_hour_ago = '%d小時前';
	$lang->edit->autosave_hours_ago = '%d小時前';
	$lang->edit->autosave_min_ago = '%d分前';
	$lang->edit->autosave_mins_ago = '%d分前';
	
	$lang->edit->upload_not_enough_quota   = '已超過上傳大小限制無法上傳附檔。';
	$lang->edit->break_or_paragraph = '按 Enter鍵換行，按 Shift+Enter分隔段落。';
?>
