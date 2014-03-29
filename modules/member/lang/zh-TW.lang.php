<?php
    /**
     * @file   modules/member/lang/zh-TW.lang.php
     * @author NHN (developers@xpressengine.com) 翻譯：royallin
     * @brief  會員(member)模組正體中文語言
     **/

    $lang->member = '會員';
    $lang->member_default_info = '基本資料';
    $lang->member_extend_info = '延伸資料';
    $lang->default_group_1 = "準會員";
    $lang->default_group_2 = "正會員";
    $lang->admin_group = "管理組";
    $lang->keep_signed = '自動登入';
    $lang->remember_user_id = '儲存 ID';
    $lang->already_logged = '您已經登入！';
    $lang->denied_user_id = '被禁止的帳號。';
    $lang->null_user_id = '請輸入帳號。';
    $lang->null_password = '請輸入密碼。';
    $lang->invalid_authorization = '還沒有認證！';
    $lang->invalid_user_id= '該帳號不存在，請檢查您的輸入是否有誤！';
    $lang->invalid_password = '您的密碼不正確！';
    $lang->invalid_new_password = '新密碼不能與舊密碼相同';
    $lang->allow_mailing = '接收郵件';
    $lang->denied = '禁止使用';
    $lang->is_admin = '最高管理權限';
    $lang->group = '群組';
    $lang->group_title = '群組標題';
    $lang->group_srl = '群組編號';
    $lang->signature = '簽名檔';
    $lang->profile_image = '個人圖片';
    $lang->profile_image_max_width = '寬度限制';
    $lang->profile_image_max_height = '高度限制';
    $lang->image_name = '暱稱圖片';
    $lang->image_name_max_width = '寬度限制';
    $lang->image_name_max_height = '高度限制';
    $lang->image_mark = '用戶圖示';
    $lang->image_mark_max_width = '寬度限制';
    $lang->image_mark_max_height = '高度限制';
    $lang->group_image_mark = '群組圖示';
    $lang->group_image_mark_max_width = '寬度限制';
    $lang->group_image_mark_max_height = '高度限制';
    $lang->group_image_mark_order = '群組圖示順序';
    $lang->signature_max_height = '簽名檔高度限制';
    $lang->enable_openid = '支援 OpenID';
    $lang->enable_join = '允許會員註冊';
    $lang->enable_confirm = '使用郵件認證';
    $lang->enable_ssl = '使用 SSL 功能';
    $lang->security_sign_in = '使用安全登入';
    $lang->limit_day = '認證限制';
    $lang->limit_date = '限制日期';
    $lang->after_login_url = '登入後頁面轉向';
    $lang->after_logout_url = '登出後頁面轉向';
    $lang->redirect_url = '會員註冊後頁面轉向';
    $lang->agreement = '會員使用條款';
    $lang->accept_agreement = '同意條款';
    $lang->member_info = '會員資料';
    $lang->current_password = '舊密碼';
    $lang->openid = 'OpenID';
    $lang->allow_message = '接收短訊息';
    $lang->allow_message_type = array(
            'Y' => '全部允許',
            'F' => '允許好友',
            'N' => '全部禁止',
    );
    $lang->about_allow_message = '可選擇是否接收短訊息。';
    $lang->logged_users = '線上會員';

    $lang->webmaster_name = '管理員名稱';
    $lang->webmaster_email = '管理員電子郵件';

    $lang->about_keep_signed = '關閉瀏覽器後也將維持登入狀態。<br />使用此功能，可解決每次訪問都要輸入帳號及密碼的麻煩。<br />為防止個人資料洩露，在網咖，學校等公共場所，請務必要確認解除登入狀態。';
    $lang->about_keep_warning = '關閉瀏覽器後也將維持登入狀態。<br />使用此功能，可解決每次訪問都要輸入帳號及密碼的麻煩。<br />為防止個人資料洩露，在網咖，學校等公共場所，請務必要確認解除登入狀態。';
    $lang->about_webmaster_name = '請輸入認證所需的電子郵件地址或管理其他網站時要使用的網站管理員名稱。(預設 : webmaster)';
    $lang->about_webmaster_email = '請輸入網站管理員的電子郵件地址。';

    $lang->search_target_list = array(
        'user_id' => '帳號',
        'user_name' => '姓名',
        'nick_name' => '暱稱',
        'email_address' => '電子郵件',
        'regdate' => '註冊日期',
        'regdate_more' => '註冊日期(以上)',
        'regdate_less' => '註冊日期(以下)',
        'last_login' => '最近登入',
        'last_login_more' => '最近登入(以上)',
        'last_login_less' => '最近登入(以下)',
        'extra_vars' => '延伸變數',
    );

    $lang->cmd_login = '登入';
    $lang->cmd_logout = '登出';
    $lang->cmd_signup = '會員註冊';
    $lang->cmd_site_signup = '加入';
    $lang->cmd_modify_member_info = '修改會員資料';
    $lang->cmd_modify_member_password = '修改密碼';
    $lang->cmd_view_member_info = '檢視會員資料';
    $lang->cmd_leave = '退出';
    $lang->cmd_find_member_account = '查詢帳號/密碼';
    $lang->cmd_resend_auth_mail = '重寄認證郵件';

    $lang->cmd_member_list = '會員列表';
    $lang->cmd_module_config = '基本設置';
    $lang->cmd_member_group = '群組管理';
    $lang->cmd_send_mail = '發送郵件';
    $lang->cmd_manage_id = '禁止帳號管理';
    $lang->cmd_manage_form = '註冊表單管理';
    $lang->cmd_view_own_document = '檢視發表主題';
	$lang->cmd_view_own_comment = '작성 댓글 보기';
    $lang->cmd_manage_member_info = '管理會員資料';
    $lang->cmd_trace_document = '主題追蹤';
    $lang->cmd_trace_comment = '評論追蹤';
    $lang->cmd_view_scrapped_document = '檢視收藏';
    $lang->cmd_view_saved_document = '檢視臨時儲存箱';
    $lang->cmd_send_email = '發送郵件';

    $lang->msg_email_not_exists = '找不到您輸入的郵件地址。';

    $lang->msg_alreay_scrapped = '已收藏的主題！';

    $lang->msg_cart_is_null = '請選擇對象。';
    $lang->msg_checked_file_is_deleted = '已刪除%d個附檔。';

    $lang->msg_find_account_title = '註冊資料。';
    $lang->msg_find_account_info = '您要尋找的註冊資料如下。';
    $lang->msg_find_account_comment = '按底下的連結，您的註冊密碼將更新為上述系統自動建立的密碼。<br />請重新登入，將密碼更改為您想要的密碼。';
    $lang->msg_confirm_account_title = '會員註冊';
    $lang->msg_confirm_account_info = '您的註冊資料如下:';
    $lang->msg_confirm_account_comment = '請按下面連結完成會員認證。';
    $lang->msg_auth_mail_sent = '已向%s發送了認證郵件。請確認！！';
    $lang->msg_confirm_mail_sent = '已向%s發送了認證郵件。請確認！！';
    $lang->msg_invalid_auth_key = '錯誤的註冊資料請求。<br />請重新尋找帳號及密碼，或聯繫管理員。';
    $lang->msg_success_authed = '新註冊的資料已得到認證。請用郵件中的新密碼修改成您要想使用的密碼。';
    $lang->msg_success_confirmed = '註冊資料已成功確認！';

    $lang->msg_new_member = '會員註冊';
    $lang->msg_update_member = '修改會員資料';
    $lang->msg_leave_member = '會員退出';
    $lang->msg_group_is_null = '無群組。';
    $lang->msg_not_delete_default = '無法刪除基本項目';
    $lang->msg_not_exists_member = '不存在的帳號';
    $lang->msg_cannot_delete_admin = '無法解除管理員帳號，請解除管理後再刪除';
    $lang->msg_exists_user_id = '重複的帳號，請重新輸入。';
    $lang->msg_exists_email_address = '重複的電子郵件地址，請重新輸入電子郵件地址。';
    $lang->msg_exists_nick_name = '重複的暱稱，請重新輸入。';
    $lang->msg_signup_disabled = '無法註冊會員';
    $lang->msg_already_logged = '您是註冊會員。';
    $lang->msg_not_logged = '您還沒登入。';
    $lang->msg_insert_group_name = '請輸入群組名稱';
    $lang->msg_check_group = '請選擇群組';

    $lang->msg_not_uploaded_profile_image = '無法登錄個人圖片！';
    $lang->msg_not_uploaded_image_name = '無法登錄暱稱圖片！';
    $lang->msg_not_uploaded_image_mark = '無法登錄用戶圖示！';
    $lang->msg_not_uploaded_group_image_mark = '無法登錄群組圖示！';

    $lang->msg_accept_agreement = '您必須同意條款。';

    $lang->msg_user_denied = '您輸入的帳號已禁止使用！';
    $lang->msg_user_not_confirmed = '您的註冊資料還沒有被確認，請確認您的電子郵箱。';
    $lang->msg_user_limited = '您輸入的帳號%s以後才可以開始使用。';

    $lang->about_user_id = '帳號必須由 3~20 字以內的英文+數字組成，開頭必須是英文。';
    $lang->about_password = '密碼必須在 6~20 字以內。';
    $lang->about_user_name = '姓名必須是 2~20 字以內。';
    $lang->about_nick_name = '暱稱必須是 2~20 字以內。';
    $lang->about_email_address = '電子郵件地址除郵件認證外，當修改密碼或忘記密碼時也可以使用。';
    $lang->about_homepage = '請輸入您的網址。';
    $lang->about_blog_url = '請輸入部落格網址。';
    $lang->about_birthday = '請輸入您的出生年月日。';
    $lang->about_allow_mailing = '不選擇此項，以後無法接收站內發送的重要資料。';
    $lang->about_denied = '選擇時不能使用此帳號。';
    $lang->about_is_admin = '選擇時將具有最高管理權限。';
    $lang->about_member_description = '管理員對會員的註記。';
    $lang->about_group = '一個帳號可擁有多個群組。';

    $lang->about_column_type = '請選擇要新增的註冊表單格式。';
    $lang->about_column_name = '請輸入在樣板中可以使用的英文名稱。(變數名稱)';
    $lang->about_column_title = '註冊或修改/檢視資料時要顯示的標題。';
    $lang->about_default_value = '可以設置預設值。';
    $lang->about_active = '必須選擇此項後才可以正常啟用。';
    $lang->about_form_description = '說明欄裡輸入的內容，在註冊時會顯示。';
    $lang->about_required = '註冊時成為必填項目。';

    $lang->about_enable_openid = '想要網站支援 OpenID 時，請勾選此項。';
    $lang->about_enable_join = '選擇此項後，用戶才可以註冊。';
    $lang->about_enable_confirm = '為確認會員註冊資料，會向會員輸入的郵件地址發送註冊認證郵件。';
    $lang->about_enable_ssl = '如主機提供 SSL 認證服務，新會員註冊/修改會員資料/登入等資料的傳送將使用 SSL(https) 認證。';
    $lang->about_limit_day = '註冊會員後的認證有效期限。';
    $lang->about_limit_date = '直到指定日期，否則該帳號都無法登入。';
    $lang->about_after_login_url = '可以指定登入後的頁面轉向網址(留空為目前頁面)。';
    $lang->about_after_logout_url = '可以指定登出後的頁面轉向網址(留空為目前頁面)。';
    $lang->about_redirect_url = '請輸入會員註冊後的頁面轉向網址。(留空為返回前頁)';
    $lang->about_agreement = '沒有會員條款時不會顯示。';

    $lang->about_image_name = '用戶暱稱可以用圖片替代。';
    $lang->about_image_mark = '顯示在用戶暱稱前的圖示。';
    $lang->about_group_image_mark = '顯示在用戶群組前的圖示。';
    $lang->about_profile_image = '可以使用個人圖片。';
    $lang->about_signature_max_height = '可以限制簽名檔高度(零或留空為不限制)。';
    $lang->about_accept_agreement = '已閱讀全部條款並同意。';

    $lang->about_member_default = '將成為註冊會員時的預設群組。';

    $lang->about_openid = '用 OpenID 註冊時，該網站只儲存帳號和郵件等基本資料，密碼和認證處理是在提供 OpenID 服務的網站中得到解決。';
    $lang->about_openid_leave = '刪除 OpenID 就等於永久刪除站內會員的資料。<br />被刪除後，再重新登錄就等於新會員註冊，因此對以前自己寫的主題將失去其權限。';
    $lang->about_find_member_account = '帳號/密碼將發送到您註冊時，所輸入的電子郵件當中。<br />輸入註冊時的電子郵件地址後，請按「查詢帳號/密碼」按鈕。<br />';

    $lang->about_member = "可以新增/修改/刪除會員及管理群組或註冊表單的會員管理模組。\n此模組不僅可以建立預設群組以外的其他群組來管理會員，並且通過註冊表單的管理獲得會員基本資料以外的延伸資料。";
    $lang->about_ssl_port = '請輸入想要使用 SSL 預設埠口以外的埠口。';
    $lang->add_openid = '新增 OpenID';

    $lang->about_resend_auth_mail = '如果沒有收到認證郵件可以再重寄一次。';
    $lang->no_article = '主題不存在';

    $lang->find_account_question = '密碼提示問答';
    $lang->find_account_answer = '비밀번호 찾기 답변';
    $lang->about_find_account_question = '可透過帳號、電子郵件和設定提示問答來獲得臨時密碼。';
    $lang->find_account_question_items = array(''
                                        ,'其他電子郵件?'
                                        ,'我最愛的是?'
                                        ,'我讀的國小是?'
                                        ,'我的出生地?'
                                        ,'我的理想?'
                                        ,'母親的姓名?'
                                        ,'父親的姓名?'
                                        ,'最喜歡的顏色?'
                                        ,'最愛的食物是?'
                                        );

    $lang->temp_password = '臨時密碼';
    $lang->cmd_get_temp_password = '取得臨時密碼';
    $lang->about_get_temp_password = '請再登入後變更密碼。';
    $lang->msg_question_not_exists = '尚未輸入提示問答';
    $lang->msg_answer_not_matches = '答案不正確';

    $lang->change_password_date = '密碼更新';
    $lang->about_change_password_date = '可設定密碼更新週期，將會定期通知更換密碼。 (設為零則不使用)';
	$lang->msg_kr_address = '읍, 면, 동 이름으로 검색하세요.';
	$lang->msg_kr_address_etc = '請輸入剩餘的地址(街道)。';
	$lang->cmd_search_again   = '再找一次';
?>
