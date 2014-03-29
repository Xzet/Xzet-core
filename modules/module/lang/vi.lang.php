<?php
/*			░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
			░░  * @File   :  common/lang/vi.lang.php                                              ░░
			░░  * @Author :  NHN (developers@xpressengine.com)                                                 ░░
			░░  * @Trans  :  Đào Đức Duy (ducduy.dao.vn@vietxe.net)								  ░░
			░░	* @Website:  http://vietxe.net													  ░░
			░░  * @Brief  :  Vietnamese Language Pack (Only basic words are included here)        ░░
			░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░	   		*/

    $lang->virtual_site = "Site thực";
    $lang->module_list = "Danh sách Module";
    $lang->module_index = "Danh sách Module";
    $lang->module_category = "Thể loại";
    $lang->module_info = "Thông tin Module";
    $lang->add_shortcut = "Thêm phím tắt";
    $lang->module_action = "Hoạt động";
    $lang->module_maker = "Người thiết kế";
    $lang->module_license = 'Giấy phép';
    $lang->module_history = "Lịch sử cập nhật";
    $lang->category_title = "Tiêu đề phân loại";
    $lang->header_text = 'Nội dung Header';
    $lang->footer_text = 'Nội dung Footer';
    $lang->use_category = 'Mở phân loại';
    $lang->category_title = 'Tiêu đề phân loại';
    $lang->checked_count = 'Số bài viết đã chọn';
    $lang->skin_default_info = 'Thông tin Skin mặc định';
    $lang->skin_author = 'Thiết kế';
    $lang->skin_license = 'Giấy phép';
    $lang->skin_history = 'Lịch sử cập nhật';
    $lang->module_copy = "Nhân bản Module";
    $lang->module_selector = "Chọn lọc Module";
    $lang->do_selected = "Bình chọn / Phê bình.";
    $lang->bundle_setup = "Gói cài đặt";
    $lang->bundle_addition_setup = "Gói cài đặt bổ xung";
    $lang->bundle_grant_setup = "Gói cài đặt cho phép";
    $lang->lang_code = "Mã ngôn ngữ";
    $lang->filebox = "FileBox";

    $lang->access_type = 'Kiểu truy cập';
    $lang->access_domain = 'Với tên miền';
    $lang->access_vid = 'Với ID Website';
    $lang->about_domain = "Để tạo nhiều Website nhỏ, các Website nhỏ này cần những tên miền riêng của mình.<br />Có thể sử dụng những Subdomain dạng aaa.bbb.com của bbb.com. Hãy nhập địa chỉ bao gồm cả Domain cài đặt XE. <br /> Ví dụ: www.vietxe.net/xe";
    $lang->about_vid = 'Người sử dụng có thể truy cập qua http://XEaddress/ID. Bạn không thể sử dụng ID giống nhau và giống tên Module đã có.<br />Teen ID có dạng là các chữ cái, số và dấu gạch dưới (_).';
    $lang->msg_already_registed_vid = 'Tên ID này đã được đăng kí. Xin hãy chọn tên khác.';
    $lang->msg_already_registed_domain = "Tên miền đã được sử dụng. Xin hãy chọn tên khác.";

    $lang->header_script = "Header Script";
    $lang->about_header_script = "Bạn co thể nhập mã dang HTML vào giữa  &lt;header&gt; và &lt;/header&gt;.<br />Bạn có thể sử dụng &lt;script, &lt;style hay &lt;meta tag";

    $lang->grant_access = "Truy cập";
    $lang->grant_manager = "Quản lý";

    $lang->grant_to_all = "Tất cả";
    $lang->grant_to_login_user = "Đã đăng nhập";
    $lang->grant_to_site_user = "Đã đăng kí";
    $lang->grant_to_group = "Nhóm chỉ định";

    $lang->cmd_add_shortcut = "Thêm phím tắt";
    $lang->cmd_install = "Cài đặt";
    $lang->cmd_update = "Cập nhật";
    $lang->cmd_manage_category = 'Quản lý thể loại';
    $lang->cmd_manage_grant = 'Quản lý quyền';
    $lang->cmd_manage_skin = 'Quản lý Skin';
    $lang->cmd_manage_document = 'Quản lý bài viết';
    $lang->cmd_find_module = 'Tìm Module';
    $lang->cmd_find_langcode = 'Tìm mã ngôn ngữ';

    $lang->msg_new_module = "Tạo Module mới";
    $lang->msg_update_module = "Sửa Module";
    $lang->msg_module_name_exists = "Tên này đã được sử dụng. Hãy thử lại với tên khác.";
    $lang->msg_category_is_null = 'Không có phân loại nào được tạo.';
    $lang->msg_grant_is_null = 'Không có danh sách quyền nào.';
    $lang->msg_no_checked_document = 'Không có bài viết nào được kiểm tra.';
    $lang->msg_move_failed = 'Không thể di chuyển';
    $lang->msg_cannot_delete_for_child = 'Không thể xóa được phân loại khi có những phân loại con.';
	$lang->msg_limit_mid ="Tên Module chỉ hỗ trợ định dạng [Kí tự], [Kí tự+Số], [Kí tự+Số+_].";
    $lang->msg_extra_name_exists = 'Tên biến đã được sử dụng. Xin hãy chọn tên khác.';

    $lang->about_browser_title = "Nó sẽ hiển thị trên tiêu đề của trình duyệt và trong RSS/Trackback.";
    $lang->about_mid = "Tên Module được sử dụng dạng http://address/?mid=<b>ModuleName</b>.\n(Chỉ cho phép chữ cái tiếng Anh + [chữ cái tiếng Anh, số, và dấu gạch dưới (_)] và tối đa 40 kí tự.)";
    $lang->about_default = "Nếu chọn, Sẽ hiển thị mặc định là (mid=NoValue) khi truy cập tên Module không đúng.";
    $lang->about_module_category = "Nó cho phép bạn quản lý thông qua Module thể loại.\n URL quản lý có dạng <a href=\"./?module=admin&amp;act=dispModuleAdminCategory\">Quản lý Module > Module thể loại </a>.";
    $lang->about_description= 'Mô tả cho một quản lý.';
    $lang->about_default = 'Nếu chọn, Sẽ hiển thị mặc định là (mid=NoValue) khi truy cập tên Module không đúng.';
    $lang->about_header_text = 'Nội dung sẽ hiển thị trên đầu Module.(sau http tag có sẵn)';
    $lang->about_footer_text = 'Nội dung sẽ hiển thị phía dưới Module.(trước http tag có sẵn)';
    $lang->about_skin = 'Banj có thể chọn Skin cho Module.';
    $lang->about_use_category = 'Nếu chon, chức năng thể loại sẽ hoạt động.';
    $lang->about_list_count = 'Bạn có thể giới hạn bài viết hiển thị trên một trang.(Mặc định là 20)';
	$lang->about_search_list_count = 'Bạn có thể đặt giới hạn số bài viết sẽ hiển thị khi tìm kiếm hay chọn thể loại. (Mặc định là 20)';
    $lang->about_page_count = 'bạn có thể giới hạn số trang liên kết hiển thị phía dưới.(Mặc định là 10)';
    $lang->about_admin_id = 'Bạn có thể đặt quyền hạn cho người sử dụng khi truy cập tới Module.';
    $lang->about_grant = 'Nếu bạn khóa tất cả quyền hạn cho một thành viên đặc biệt nào đó, những thành viên đó sẽ không được phép đăng nhập.';
    $lang->about_grant_deatil = 'Khi thành viên dăng kí tại trang chủ, nghĩa là họ cũng là thành viên của những trang khác (Ví dụ: cafeXE,...).';
    $lang->about_module = "Khu vực Module trong XE ngoại trừ Library là tại [Module Manage]. Tất cả những Module đang có sẽ hiển thị, giúp bạn quản lý một cách dễ dàng.";

	$lang->about_extra_vars_default_value = 'Nếu cần nhiều giá trị mặc định, bạn có thể thêm dấu (,) và giữa các kết nối.';
    $lang->about_search_virtual_site = "Hãy nhập tên miền thực tế của Website.<br />Để tìm kiếm những Module không thực tế của Website hãy để trống.";
    $lang->about_langcode = "Nếu bạn muốn sử dụng định hình riêng, hãy sử dụng 'Tìm kiếm mã ngôn ngữ'";
    $lang->about_file_extension= "Chỉ cho phép những phần mở rộng là: %s.";
?>
