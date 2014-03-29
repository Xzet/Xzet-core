<?php
/*			░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
			░░  * @File   :  common/lang/vi.lang.php                                              ░░
			░░  * @Author :  NHN (developers@xpressengine.com)                                                 ░░
			░░  * @Trans  :  Đào Đức Duy (ducduy.dao.vn@vietxe.net)								  ░░
			░░	* @Website:  http://vietxe.net													  ░░
			░░  * @Brief  :  Vietnamese Language Pack (Only basic words are included here)        ░░
			░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░	   		*/

    // words for button
    $lang->cmd_sync_member = 'Đồng bộ hóa';
    $lang->cmd_continue = 'Tiếp tục';
    $lang->preprocessing = 'Đang chuẩn bị cho việc chuyển dữ liệu.';

    // items
    $lang->importer = 'Chuyển Zeroboard Data';
    $lang->source_type = 'Chọn nguồn';
    $lang->type_member = 'Data Thành viên';
    $lang->type_message = 'Data Tin nhắn';
    $lang->type_ttxml = 'TTXML';
    $lang->type_module = 'Data Bài viết';
    $lang->type_syncmember = 'Đồng bộ hóa Data Thành viên';
    $lang->target_module = 'Module';
    $lang->xml_file = 'File XML';

    $lang->import_step_title = array(
        1 => 'Bước 1. Lựa chọn đường dẫn',
        12 => 'Bước 1-2. Chọn Module',
        13 => 'Bước 1-3. Chọn chuyên đề',
        2 => 'Bước 2. Upload File XML',
        3 => 'Bước 2. Đồng bộ hóa Data thành viên và Data bài viết',
        99 => 'Đang đồng bộ hóa dữ liệu.',
    );

    $lang->import_step_desc = array(
        1 => 'Xin hãy chọn Data dưới dạng XML để đồng bộ hóa.',
        12 => 'Xin hãy chọn Module bạn muốn lưu Data.',
        121 => 'Bài viết:',
        122 => 'Sổ lưu niệm:',
        13 => 'Xin hãy chọn chuyên mục để lưu Data.',
        2 => "Xin hãy nhập File Data dưới dạng XML để bắt đầu đồng bộ hóa.\nHãy nhập đường dẫn cho File chứa Data trên Host dưới dạng http://..",
        3 => 'Đã không thể kết nối tới File Data thành viên và bài viết. Nếu đã đúng đường dẫn xin hãy kiểm tra Data với user_id.',
        99 => 'Đang đồng bộ hóa dữ liệu.',
    );

    // guide/alert
    $lang->msg_sync_member = 'Data thành viên và bài viết sẽ được đồng bộ hóa sau khi bấm "Đồng bộ".';
    $lang->msg_no_xml_file = 'Không tìm yhấy File XML. Xin hãy kiểm tra lại đường dẫn!';
    $lang->msg_invalid_xml_file = 'Định dạng File XML không hợp lệ.';
    $lang->msg_importing = 'Đang gửi %d Data của %d. (Nếu việc gửi Data không tiếp tục chạy, hãy bấm "Tiếp tục")';
    $lang->msg_import_finished = '%d/%d của Data đã được đồng bộ thành công. Một số nội dung đã không thể đồng bộ.';
    $lang->msg_sync_completed = 'Đã đồng bộ Data thành công.';

    // blah blah..
    $lang->about_type_member = 'Lựa chọn này sẽ đồng bộ Data thành viên.';
    $lang->about_type_message = 'Lựa chọn này đồng bộ Data tin nhắn.';
    $lang->about_type_ttxml = 'Lựa chọn này sẽ đồng bộ và chuyển đổi Data của Textcube.';
	$lang->about_ttxml_user_id = 'Xin hãy nhập ID sẽ hiển thị là tác giả của những bài viết sau khi Data được chuyển đổi từ TTXML. (ID này phải là thành viên đã đăng kí.)';
    $lang->about_type_module = 'Lựa chọn này sẽ đồng bộ và chuyển đổi các trang và bài viết.';
    $lang->about_type_syncmember = 'Lựa chọn này sẽ đồng bộ thông tin thành viên trước khi đồng bộ Bài viết và thành viên.';
    $lang->about_importer = "Bạn có thể chuyển Data từ Zeroboard4, Zeroboard5 Beta hay Data của những mã nguồn khác vào Data của XE.\nĐể hiểu rõ hơn công việc này, bạn có thể tham khảo cách chuyển đổi Data của bạn vào XE khi bạn đã Upload chúng tại <a href=\"http://svn.xpressengine.com/zeroboard_xe/migration_tools/\" onclick=\"winopen(this.href);return false;\">XML Exporter</a>.";

    $lang->about_target_path = "Để lấy cả File đính kèm tại Zeroboard4, bạn hãy nhập địa chỉ mà đã cài đặt Zeroboard4.\nNếu nó cùng nằm trên một Server, hãy nhập đầy đủ đường dẫn thư mục cài đặt của Zeroboard4. Ví dụ: <b>/home/USERID/public_html/bbs</b>\nNếu khác Server, hãy nhập địa chỉ của Zeroboard4 đã cài đặt. Ví dụ: <b>http://Domain/bbs</b>";
?>
