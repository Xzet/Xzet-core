<?php
/*			░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
			░░  * @File   :  common/lang/vi.lang.php                                              ░░
			░░  * @Author :  NHN (developers@xpressengine.com)                                                 ░░
			░░  * @Trans  :  Đào Đức Duy (ducduy.dao.vn@vietxe.net)								  ░░
			░░	* @Website:  http://vietxe.net													  ░░
			░░  * @Brief  :  Vietnamese Language Pack (Only basic words are included here)        ░░
			░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░	   		*/

    $lang->communication = 'Thông báo';
    $lang->about_communication = 'Module này thực hiện chức năng giao tiếp, tin nhắn hay bạn bè.';

    $lang->allow_message = 'Nhận tin nhắn';
    $lang->allow_message_type = array(
             'Y' => 'Nhận tất cả',
             'N' => 'Từ chối tất cả',
             'F' => 'Chỉ bạn bè',
        );

    $lang->message_box = array(
        'R' => 'Đã nhận',
        'S' => 'Gửi',
        'T' => 'Hòm thư',
    );
    $lang->readed_date = "Ngày đọc"; 

    $lang->sender = 'Người gửi';
    $lang->receiver = 'Người nhận';
    $lang->friend_group = 'Nhóm bạn';
    $lang->default_friend_group = 'Nhóm mặc định';

    $lang->cmd_send_message = 'Gửi tin nhắn';
    $lang->cmd_reply_message = 'Trả lời tin nhắn';
    $lang->cmd_view_friend = 'Bạn bè';
    $lang->cmd_add_friend = 'Thêm bạn';
    $lang->cmd_view_message_box = 'Hộp tin nhắn';
    $lang->cmd_store = "Lưu";
    $lang->cmd_add_friend_group = 'Thêm nhóm bạn';
    $lang->cmd_rename_friend_group = 'Sử tên nhóm';

    $lang->msg_no_message = 'Không có tin nhắn nào.';
    $lang->message_received = 'Bạn có tin nhắn mới.';

    $lang->msg_title_is_null = 'Xin vui lòng nhập tiêu đề của tin nhắn.';
    $lang->msg_content_is_null = 'Xin vui lòng nhập nội dung.';
    $lang->msg_allow_message_to_friend = "Không thể gửi vì người nhận chỉ chấp nhận những tin nhắn từ bạn bè của họ.";
    $lang->msg_disallow_message = 'Không thể gửi vì người nhận đã từ chối nhận tin nhắn.';

    $lang->about_allow_message = 'Bạn có thể đồng ý nhận tin nhắn.';
?>
