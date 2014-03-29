<?php
/*			░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
			░░  * @File   :  common/lang/vi.lang.php                                              ░░
			░░  * @Author :  NHN (developers@xpressengine.com)                                                 ░░
			░░  * @Trans  :  DucDuy Dao (webmaster@xpressengine.vn)								  ░░
			░░	* @Website:  http://xpressengine.vn												  ░░
			░░  * @Brief  :  Vietnamese Language Pack (Only basic words are included here)        ░░
			░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
*/

    $lang->editor = 'WYSIWYG toàn diện';
    $lang->component_name = 'Thành phần';
    $lang->component_version = 'Phiên bản';
    $lang->component_author = 'Người phát triển';
    $lang->component_link = 'Link';
    $lang->component_date = 'Ngày';
    $lang->component_license = 'Cấp phép';
    $lang->component_history = 'Cập nhật';
    $lang->component_description = 'Mô tả';
    $lang->component_extra_vars = 'Thông tin bổ xung';
    $lang->component_grant = 'Thiết lập quyền';
    $lang->content_style = 'Kiểu dáng của nội dung';
    $lang->content_font = 'Font chữ của nội dung';
    $lang->content_font_size = 'Cỡ chữ của nội dung';

    $lang->about_component = 'Thông tin Thành phần';
    $lang->about_component_grant = 'Những nhóm đã chọn sẽ được phép sử dụng Thành phần mở rộng cho biên tập.<br />(Để trống nếu bạn muốn tất cả các nhóm đều được phép sử dụng.)';
    $lang->about_component_mid = 'Có thể lựa chọn khu vực sử dụng Thành phần.<br />(Nếu để trống là tất cả các khu vực đều có thể sử dụng.)';

    $lang->msg_component_is_not_founded = 'Không tìm thấy biên tập Thành phần %s';
    $lang->msg_component_is_inserted = 'Đã chèn Thành phần được chọn.';
    $lang->msg_component_is_first_order = 'Lựa chọn Thành phần trước một khu vực.';
    $lang->msg_component_is_last_order = 'Lựa chọn Thành phần sau một khu vực.';
    $lang->msg_load_saved_doc = "Đã có bài viết tự động lưu. Bạn có muốn khôi phục nó không?\nBài viết tự động lưu sẽ tự động hủy sau khi bạn hoàn thành bài viết này và bấm 'Gửi'";
    $lang->msg_auto_saved = 'Đã tự động lưu';

    $lang->cmd_disable = 'Không hoạt động';
    $lang->cmd_enable = 'Hoạt động';

    $lang->editor_skin = 'Giao diện gửi bài';
    $lang->upload_file_grant = 'Quyền Upload';
    $lang->enable_default_component_grant = 'Quyền sử dụng Thành phần cơ bản';
    $lang->enable_component_grant = 'Quyền sử dụng Thành phần';
    $lang->enable_html_grant = 'Quyền sử dụng HTML';
    $lang->enable_autosave = 'Tự động lưu';
    $lang->height_resizable = 'Mở rộng chiều cao';
    $lang->editor_height = 'Chiều cao khung viết bài';

    $lang->about_editor_skin = 'Bạn có thể chọn kiểu gửi bài.';
    $lang->about_content_style = 'Bạn có thể chọn kiểu viết bài hay kiểu hiển thị.';
    $lang->about_content_font = 'Bạn có thể chọn Font chữ để viết và hiển thị trong bài viết.<br/>Để đặt mặc định chỉ Font chữ bạn hay sử dụng, hãy đặt dấu (,) vào giữa các Font.';
	$lang->about_content_font_size = 'Bạn có thể chọn cỡ chữ để viết bài và hiển thị theo định dạng "px" hoặc "em".';
    $lang->about_upload_file_grant = 'Chọn nhóm được phép Upload File. (Để trống nếu bạn muốn tất cả các nhóm đều có thể Upload.)';
    $lang->about_default_component_grant = 'Chọn nhóm được phép sử dụng Thành phần mặc định. (Để trống nếu bạn muốn tất cả đều có thể sử dụng.)';
    $lang->about_editor_height = 'Bạn có thể đặt chiều cao của khung viết bài.';
    $lang->about_editor_height_resizable = 'Đặt chiều cao có thể thay đổi.';
    $lang->about_enable_html_grant = 'Chọn nhóm được phép sử dụng HTML';
    $lang->about_enable_autosave = 'Bạn có thể đặt chức năng Tự động lưu.';

    $lang->edit->fontname = 'Kiểu chữ';
    $lang->edit->fontsize = 'Cỡ chữ';
    $lang->edit->use_paragraph = 'Chức năng Paragraph';
    $lang->edit->fontlist = array(
    'Arial'=>'Arial',
    'Arial Black'=>'Arial Black',
    'Tahoma'=>'Tahoma',
    'Verdana'=>'Verdana',
    'Sans-serif'=>'Sans-serif',
    'Serif'=>'Serif',
    'Monospace'=>'Monospace',
    'Cursive'=>'Cursive',
    'Fantasy'=>'Fantasy',
    );

    $lang->edit->header = 'Tiêu đề lớn';
    $lang->edit->header_list = array(
    'h1' => 'Cỡ 1',
    'h2' => 'Cỡ 2',
    'h3' => 'Cỡ 3',
    'h4' => 'Cỡ 4',
    'h5' => 'Cỡ 5',
    'h6' => 'Cỡ 6',
    );

    $lang->edit->submit = 'Gửi bài';

    $lang->edit->fontcolor = 'Màu chữ';
	$lang->edit->fontcolor_apply = '글자 색 적용';
	$lang->edit->fontcolor_more = '글자 색 더보기';
    $lang->edit->fontbgcolor = 'Màu nền';
	$lang->edit->fontbgcolor_apply = '글자 배경색 적용';
	$lang->edit->fontbgcolor_more = '글자 배경색 더보기';
    $lang->edit->bold = 'Chữ đậm';
    $lang->edit->italic = 'Chữ nghiêng';
    $lang->edit->underline = 'Chứ gạch chân';
    $lang->edit->strike = 'Chữ gạch giữa';
    $lang->edit->sup = 'Chỉ số trên';
    $lang->edit->sub = 'Chỉ số dưới';
    $lang->edit->redo = 'Phía sau';
    $lang->edit->undo = 'Trở lại';
    $lang->edit->align_left = 'Căn trái';
    $lang->edit->align_center = 'Căn giữa';
    $lang->edit->align_right = 'Căn phải';
    $lang->edit->align_justify = 'Căn đều';
    $lang->edit->add_indent = 'Thụt lề';
    $lang->edit->remove_indent = 'Dàn đều';
    $lang->edit->list_number = 'Thứ tự số';
    $lang->edit->list_bullet = 'Thứ tự chấm';
    $lang->edit->remove_format = 'Xóa định dạng';

    $lang->edit->help_remove_format = 'Những Tag đã chọn sẽ bị xóa';
    $lang->edit->help_strike_through = 'Đường kẻ sẽ nằm lên chữ';
    $lang->edit->help_align_full = 'Căn trái và phải';

    $lang->edit->help_fontcolor = 'Màu chữ';
    $lang->edit->help_fontbgcolor = 'Màu nền';
    $lang->edit->help_bold = ' Chữ đậm';
    $lang->edit->help_italic = ' Chữ nghiêng';
    $lang->edit->help_underline = ' Chữ gạch chân';
    $lang->edit->help_strike = ' Chữ gạch giữa';
    $lang->edit->help_sup = 'Chỉ số trên';
    $lang->edit->help_sub = 'Chỉ số dưới';
    $lang->edit->help_redo = 'Tiếp tục';
    $lang->edit->help_undo = 'Trở lại';
    $lang->edit->help_align_left = 'Căn trái';
    $lang->edit->help_align_center = 'Căn giữa';
    $lang->edit->help_align_right = 'Căn phải';
	$lang->edit->help_align_justify = 'Đều hai bên';
    $lang->edit->help_add_indent = 'Thụt vào';
    $lang->edit->help_remove_indent = 'Giãn ra';
    $lang->edit->help_list_number = 'Thứ tự số';
    $lang->edit->help_list_bullet = 'Thứ tự chấm';
    $lang->edit->help_use_paragraph = 'Bấm "Ctrl+Enter" để sử dụng Paragraph. Bấm phím "Alt+S" để gửi.';

    $lang->edit->url = 'URL';
    $lang->edit->blockquote = 'Trích dẫn';
    $lang->edit->table = 'Bảng';
    $lang->edit->image = 'Hình ảnh';
    $lang->edit->multimedia = 'Chèn Media';
    $lang->edit->emoticon = 'Diễn tả cảm xúc';

	$lang->edit->file = '파일';
    $lang->edit->upload = 'Đính kèm';
    $lang->edit->upload_file = 'Đính kèm';
	$lang->edit->upload_list = '첨부 목록';
    $lang->edit->link_file = 'Chèn vào bài viết';
    $lang->edit->delete_selected = 'Xóa lựa chọn';

    $lang->edit->icon_align_article = 'Vị trí trong bài viết';
    $lang->edit->icon_align_left = 'Trái';
    $lang->edit->icon_align_middle = 'Giữa';
    $lang->edit->icon_align_right = 'Phải';

    $lang->about_dblclick_in_editor = 'Thiết lập khi bấm 2 lần vào nền, Chữ, Hình ảnh hoặc Trích dẫn';


    $lang->edit->rich_editor = 'Kiểu trù phú';
    $lang->edit->html_editor = 'Kiểu HTML';
    $lang->edit->extension ='Thành phần mở rộng';
    $lang->edit->help = 'Trợ giúp';
    $lang->edit->help_command = 'Phím nóng';
    
    $lang->edit->lineheight = 'Chiều cao dòng';
	$lang->edit->fontbgsampletext = 'ABC';
	
	$lang->edit->hyperlink = 'Link liên kết';
	$lang->edit->target_blank = 'Mở trang mới';
	
	$lang->edit->quotestyle1 = 'Liền viền trái';
	$lang->edit->quotestyle2 = 'Trích';
	$lang->edit->quotestyle3 = 'Viền liền';
	$lang->edit->quotestyle4 = 'Viền liền+Nền';
	$lang->edit->quotestyle5 = 'Liền đậm';
	$lang->edit->quotestyle6 = 'Viền chấm';
	$lang->edit->quotestyle7 = 'Chấm+Nền';
	$lang->edit->quotestyle8 = 'Loại bỏ';


    $lang->edit->jumptoedit = 'Bỏ qua công cụ chỉnh sửa';
    $lang->edit->set_sel = 'Số hàng cột';
    $lang->edit->row = 'Hàng';
    $lang->edit->col = 'Cột';
    $lang->edit->add_one_row = 'Thêm 1 hàng';
    $lang->edit->del_one_row = 'Xóa 1 hàng';
    $lang->edit->add_one_col = 'Thêm một cột';
    $lang->edit->del_one_col = 'Xóa một cột';

    $lang->edit->table_config = 'Thiết lập viền';
    $lang->edit->border_width = 'Độ rộng';
    $lang->edit->border_color = 'Màu viền';
    $lang->edit->add = 'Thêm';
    $lang->edit->del = 'Xóa';
    $lang->edit->search_color = 'Tìm màu';
    $lang->edit->table_backgroundcolor = 'Màu nền';
    $lang->edit->special_character = 'Kí tự đặc biệt';
    $lang->edit->insert_special_character = 'Chèn kí tự đặc biệt.';
    $lang->edit->close_special_character = 'Tắt bản kí tự đặc biệt.';
    $lang->edit->symbol = 'Biểu tượng';
    $lang->edit->number_unit = 'Số và đơn vị';
    $lang->edit->circle_bracket = 'Vòng tròn, Ngoặc';
    $lang->edit->korean = 'Korean';
    $lang->edit->greece = 'Greek';
    $lang->edit->Latin  = 'Latin';
    $lang->edit->japan  = 'Japanese';
    $lang->edit->selected_symbol  = 'Kí tự đã chọn:';

    $lang->edit->search_replace  = 'Tìm/Ghi đè';
    $lang->edit->close_search_replace  = 'Tắt bảng Tìm/Ghi đè';
    $lang->edit->replace_all  = 'Ghi đè tất cả';
    $lang->edit->search_words  = 'Tìm từ';
    $lang->edit->replace_words  = 'Từ ghi đè';
    $lang->edit->next_search_words  = 'Tìm tiếp';
    $lang->edit->edit_height_control  = 'Đặt kích thước mẫu';

    $lang->edit->merge_cells = 'Nối bảng';
    $lang->edit->split_row = 'Chia hàng';
    $lang->edit->split_col = 'Chia cột';
    
    $lang->edit->toggle_list   = 'Hiện/Ẩn';
    $lang->edit->minimize_list = 'Thu nhỏ';
    
    $lang->edit->move = 'Di chuyển';
	$lang->edit->refresh = 'Làm mới';
    $lang->edit->materials = 'Vật liệu';
    $lang->edit->temporary_savings = 'Danh sách lưu tạm thời';

	$lang->edit->paging_prev = 'Trước';
	$lang->edit->paging_next = 'Tiếp';
	$lang->edit->paging_prev_help = 'Chuyển về trang trước.';
	$lang->edit->paging_next_help = 'Chuyển tới trang tiếp.';

	$lang->edit->toc = 'Board của nội dung';
	$lang->edit->close_help = 'Đóng hướng dẫn';

	$lang->edit->confirm_submit_without_saving = 'Nội dung chưa được lưu.\\nBạn có chắc chắn muốn gửi không?';

	$lang->edit->image_align = 'Sắp xếp hình ảnh';
	$lang->edit->attached_files = 'File đính kèm';

	$lang->edit->fontcolor_input = '폰트색 직접입력';
	$lang->edit->fontbgcolor_input = '배경색 직접입력';
	$lang->edit->pangram = '무궁화 꽃이 피었습니다';

	$lang->edit->table_caption_position = '표 제목(caption) 및 배치';
	$lang->edit->table_caption = '표 제목(caption)';
	$lang->edit->table_header = '머리글 셀(th)';
	$lang->edit->table_header_none = '없음';
	$lang->edit->table_header_left = '왼쪽';
	$lang->edit->table_header_top = '위쪽';
	$lang->edit->table_header_both = '모두';
	$lang->edit->table_size = '표 크기';
	$lang->edit->table_width = '표 폭';

	$lang->edit->upper_left = '상단좌측';
	$lang->edit->upper_center = '상단중앙';
	$lang->edit->upper_right = '상단우측';
	$lang->edit->bottom_left = '하단좌측';
	$lang->edit->bottom_center = '하단중앙';
	$lang->edit->bottom_right = '하단우측';

	$lang->edit->no_image = '첨부된 이미지가 없습니다.';
	$lang->edit->no_multimedia = '첨부된 동영상이 없습니다.';
	$lang->edit->no_attachment = '첨부된 파일이 없습니다.';
	$lang->edit->insert_selected = '선택 넣기';
	$lang->edit->delete_selected = '선택 삭제';

	$lang->edit->fieldset = '글상자';
	$lang->edit->paragraph = '문단';
	
	$lang->edit->autosave_format = '글을 쓰기 시작한지 <strong>%s</strong>이 지났습니다. 마지막 저장 시간은 <strong>%s</strong> 입니다.';
	$lang->edit->autosave_hour = '%d시간';
	$lang->edit->autosave_hours = '%d시간';
	$lang->edit->autosave_min = '%d분';
	$lang->edit->autosave_mins = '%d분';
	$lang->edit->autosave_hour_ago = '%d시간 전';
	$lang->edit->autosave_hours_ago = '%d시간 전';
	$lang->edit->autosave_min_ago = '%d분 전';
	$lang->edit->autosave_mins_ago = '%d분 전';
	
	$lang->edit->upload_not_enough_quota   = '허용된 용량이 부족하여 파일을 첨부할 수 없습니다.';
	$lang->edit->break_or_paragraph = 'Enter는 줄바꿈, Shift+Enter는 문단바꿈입니다.';
?>