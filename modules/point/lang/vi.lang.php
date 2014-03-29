<?php
/*			░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
			░░  * @File   :  common/lang/vi.lang.php                                              ░░
			░░  * @Author :  NHN (developers@xpressengine.com)                                                 ░░
			░░  * @Trans  :  Đào Đức Duy (ducduy.dao.vn@vietxe.net)								  ░░
			░░	* @Website:  http://vietxe.net													  ░░
			░░  * @Brief  :  Vietnamese Language Pack (Only basic words are included here)        ░░
			░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░	   		*/

    $lang->point = "Điểm"; 
    $lang->level = "Cấp bậc"; 

    $lang->about_point_module = "Bạn có thể đặt mức điểm khi thành viên gửi, thêm bài viết, bình luận, Download, Upload.<br />Nhưng điểm chỉ có thể cho và tích lũy khi Addon điểm được kích hoạt.";
    $lang->about_act_config = "Mỗi Module, Board hay Blog có một mức cho điểm khác nhau khi  \"gửi bài, xóa bài, thêm bài, gửi bình luận, xóa bình luận\".<br />Bạn có thể chỉ thêm những giá trị liên kết với hệ thống điểm vào mỗi Module Blog, Board.<br />Để thêm nhiều giá trị bằng cách sử dụng dấu (,) giữa các giá trị."; 

    $lang->max_level = 'Cấp bậc lớn nhất';
    $lang->about_max_level = 'Bạn có thể quy định cấp bậc lớn nhất. Kiểm tra lại Icon của cấp bậc, và bạn có thể đặt cấp bậc tối đa là 1000.'; 

    $lang->level_icon = 'Icon của cấp bậc';
    $lang->about_level_icon = 'Thư mục chứa Icon của cấp bậc có dạng "./module/point/icons/[level].gif" và cấp độ lớn nhất có thể khác với bộ Icon hiện tại. Vì vậy xin hãy cẩn thận.'; 

    $lang->point_name = 'Tên điểm';
    $lang->about_point_name = 'Bạn có thể đặt tên hay đặt một giá trị nào đó cho điểm.'; 

    $lang->level_point = 'Điểm thăng cấp';
    $lang->about_level_point = 'Cấp độ sẽ được thay đổi khi đạt tới số điểm giới hạn.'; 

    $lang->disable_download = 'Cấm Download';
    $lang->about_disable_download = "Điều này sẽ cấm thành viên Download khi không đủ điểm. (Ngoại trừ File hình ảnh)"; 
    $lang->disable_read_document = 'Cấm đọc bài';
    $lang->about_disable_read_document = 'Nếu số điểm không đủ, thành viên sẽ không thể đọc được bài viết.';

    $lang->level_point_calc = 'Tính toán trên điểm';
    $lang->expression = 'Hãy nhập công thức cần sử dụng <b>i</b>. Ví dụ: Math.pow(i, 2) * 90';
    $lang->cmd_exp_calc = 'Tính toán';
    $lang->cmd_exp_reset = 'Thiết lập lại';

    $lang->cmd_point_recal = 'Thiết lập lại điểm';
	$lang->about_cmd_point_recal = 'Điểm chỉ có thể có được khi gửi bài, bình luận, đính kèm và khi đăng kí.<br />Chỉ có thể thiết lập lại điểm của những thành viên đã đăng kí là thành viên.<br />Xin hãy chỉ sử dụng chức năng này khi bạn chuyển nội dung của Website qua một Website khác.';

    $lang->point_link_group = 'Chuyển nhóm với cấp độ';
    $lang->point_group_reset_and_add = 'Điểm số để thăng cấp cho nhóm mới.';
    $lang->point_group_add_only = 'Chỉ cấp cho nhóm mới';
    $lang->about_point_link_group = 'Nếu bạn đặt cấp độ cho một nhóm đặc biệt nào đó, người sử dụng trong nhóm đó khi đạt đến số điểm giới hạn sẽ tự động được chuyển sang nhóm mới.';

    $lang->about_module_point = "Bạn có thể đặt thang điểm riêng cho mỗi Module, Module nào không được đặt sẽ sử dụng sự thiết lập mặc định.<br />Tất cả điểm sẽ khác khi sử dụng chức năng này.";

    $lang->point_signup = 'Khi đăng kí';
    $lang->point_insert_document = 'Khi gửi bài';
    $lang->point_delete_document = 'Khi xóa bài';
    $lang->point_insert_comment = 'Khi thêm bình luận';
    $lang->point_delete_comment = 'Khi xóa bình luận';
    $lang->point_upload_file = 'Khi Upload';
    $lang->point_delete_file = 'Khi xóa File';
    $lang->point_download_file = 'Khi Download (Trừ hình ảnh)';
    $lang->point_read_document = 'Khi đọc bài';
    $lang->point_voted = 'Khi bình chọn';
    $lang->point_blamed = 'Khi phê bình';


    $lang->cmd_point_config = 'Thiết lập mặc định';
    $lang->cmd_point_module_config = 'Thiết lập Module';
    $lang->cmd_point_act_config = 'Thiết lập khác';
    $lang->cmd_point_member_list = 'Danh sách điểm thành viên';

    $lang->msg_cannot_download = "Bạn không đủ điểm để Download.";
    $lang->msg_disallow_by_point = "Bạn không thể xem được bài viết vì điểm của bạn không đủ. (Điểm yêu cầu: %d, Điểm hiện tại: %d)";

    $lang->point_recal_message = 'Điều chỉnh điểm. (%d / %d)';
    $lang->point_recal_finished = 'Đã kết thúc việc tính toán lại điểm.';
?>
