<?php
    /**
     * @file   modules/member/jp.lang.php
     * @author NHN (developers@xpressengine.com) 翻訳：RisaPapa、ミニミ、liahona
     * @brief  日本語言語パッケージ（基本的な内容のみ）
     **/

    $lang->member = '会員';
    $lang->member_default_info = '基本情報';
    $lang->member_extend_info = '追加情報';
    $lang->default_group_1 = '準会員';
    $lang->default_group_2 = '正会員';
    $lang->admin_group = '管理グループ';
    $lang->keep_signed = '次回からID入力を省略';
    $lang->remember_user_id = 'ID保存';
    $lang->already_logged = '既にログインされています。';
    $lang->denied_user_id = '使用が禁じられているＩＤです。';
    $lang->null_user_id = 'ユーザーＩＤをもう一度入力して下さい。';
    $lang->null_password = 'パスワードを入力して下さい。';
    $lang->invalid_authorization = '認証出来ませんでした。';
    $lang->invalid_user_id= '存在しないユーザＩＤです。';
    $lang->invalid_password = '無効なパスワードです。';
    $lang->invalid_new_password = '以前のパスワードと同じパスワードを使う事はできません。';
    $lang->allow_mailing = 'メーリングリストに登録';
    $lang->denied = '使用中止';
    $lang->is_admin = '最高管理権限';
    $lang->group = '所属グループ';
    $lang->group_title = 'グループ名';
    $lang->group_srl = 'グループ番号';
    $lang->signature = '署名';
    $lang->profile_image = 'プロフィール写真';
    $lang->profile_image_max_width = '制限横幅サイズ';
    $lang->profile_image_max_height = '制限縦幅サイズ';
    $lang->image_name = 'イメージ名';
    $lang->image_name_max_width = '制限横幅サイズ';
    $lang->image_name_max_height = '制限縦幅サイズ';
    $lang->image_mark = 'イメージマーク';
    $lang->image_mark_max_width = '制限横幅サイズ';
    $lang->image_mark_max_height = '制限縦幅サイズ';
    $lang->group_image_mark = 'グループ用イメージマーク';
    $lang->group_image_mark_max_width = '制限横幅サイズ';
    $lang->group_image_mark_max_height = '制限縦幅サイズ';
    $lang->group_image_mark_order = 'グループ用イメージマークの順番';
    $lang->signature_max_height = '制限署名欄の高さ';
    $lang->enable_openid = 'OpenIDサポート';
    $lang->enable_join = '会員登録を許可する';
    $lang->enable_confirm = 'メール認証機能を使用';
    $lang->enable_ssl = 'SSL使用';
    $lang->security_sign_in = 'セキュア（SSL）';
    $lang->limit_day = '臨時制限期間（日）';
    $lang->limit_date = '制限日';
    $lang->after_login_url = 'ログイン後、表示するページのURL';
    $lang->after_logout_url = 'ログアウト後、表示するページのURL';
    $lang->redirect_url = '会員登録後、表示するページ';
    $lang->agreement = '会員登録規約';
    $lang->accept_agreement = '規約に同意する';
    $lang->member_info = '会員情報';
    $lang->current_password = '現在のパスワード';
    $lang->openid = 'OpenID';
    $lang->allow_message = 'メッセージ許可';
    $lang->allow_message_type = array(
            'Y' => '全て許可',
            'F' => '登録した友達のみ許可',
            'N' => '全て禁止',
    );
    $lang->about_allow_message = 'メッセージの許可タイプ及び対象を設定します。';
    $lang->logged_users = '現在ログイン中の会員';

    $lang->webmaster_name = 'ウェブマスターのお名前';
    $lang->webmaster_email = 'ウェブマスターのメールアドレス';

    $lang->about_keep_signed = 'ブラウザを閉じてもログイン状態が維持されます。\n\nログイン維持機能を利用すると、次回からログインする必要がなくなります。\n\nただ、インターネットカフェ、学校など公共場所で利用する場合、個人情報が流出する恐れがありますので、必ずログアウトして下さい。';
    $lang->about_keep_warning = 'ブラウザを閉じてもログイン状態が維持されます。\n\nログイン維持機能を利用すると、次回からログインする必要がなくなります。 ただ、インターネットカフェ、学校など公共場所で利用する場合、個人情報が流出する恐れがありますので、必ずログアウトして下さい。';
    $lang->about_webmaster_name = '確認メール、またはサイト管理時に使用されるウェブマスターのお名前を入力して下さい（デフォルト : webmaster）。';
    $lang->about_webmaster_email = 'ウェブマスターのメールアドレスを入力して下さい。';

    $lang->search_target_list = array(
        'user_id' => 'ユーザーＩＤ',
        'user_name' => 'お名前',
        'nick_name' => 'ニックネーム',
        'email_address' => 'メールアドレス',
        'regdate' => '登録日',
        'regdate_more' => '登録日(以上)',
        'regdate_less' => '登録日(以下)',
        'last_login' => '最近のログイン',
        'last_login_more' => '最近ログイン日(以上)',
        'last_login_less' => '最近ログイン日(以下)',
        'extra_vars' => '拡張変数',
    );

    $lang->cmd_login = 'ログイン';
    $lang->cmd_logout = 'ログアウト';
    $lang->cmd_signup = '会員登録';
    $lang->cmd_site_signup = '登録';
    $lang->cmd_modify_member_info = '会員情報修正';
    $lang->cmd_modify_member_password = 'パスワード変更';
    $lang->cmd_view_member_info = '会員情報確認';
    $lang->cmd_leave = '退会';
    $lang->cmd_find_member_account = 'IDとパスワードのリマインダー';
    $lang->cmd_resend_auth_mail = '認証メール再申請';

    $lang->cmd_member_list = '会員リスト';
    $lang->cmd_module_config = '基本設定';
    $lang->cmd_member_group = 'グループ管理';
    $lang->cmd_send_mail = 'メール送信';
    $lang->cmd_manage_id = '禁止ＩＤ管理';
    $lang->cmd_manage_form = '会員登録フォーム管理';
    $lang->cmd_view_own_document = '書き込み履歴';
	$lang->cmd_view_own_comment = '작성 댓글 보기';
    $lang->cmd_manage_member_info = '会員情報管理';
    $lang->cmd_trace_document = '書き込みの追跡';
    $lang->cmd_trace_comment = 'コメント追跡';
    $lang->cmd_view_scrapped_document = 'スクラップ';
    $lang->cmd_view_saved_document = '保存ドキュメント';
    $lang->cmd_send_email = 'メール送信';

    $lang->msg_email_not_exists = '登録されたメールアドレスがありません。';

    $lang->msg_alreay_scrapped = '既にスクラップされたコンテンツです。';

    $lang->msg_cart_is_null = '対象を選択して下さい。';
    $lang->msg_checked_file_is_deleted = "%d個の添付ファイルが削除されました。";

    $lang->msg_find_account_title = '会員IDどパスワードの情報';
    $lang->msg_find_account_info = '登録された会員情報は下記の通りです。';
    $lang->msg_find_account_comment = '下のリンクをクリックすると上のパスワードに変更されます。<br />ログインしてからパスワードを変更して下さい。';
    $lang->msg_confirm_account_title = '確認メールです。';
    $lang->msg_confirm_account_info = '作成した会員の情報';
    $lang->msg_confirm_account_comment = '下記のURLをクリックして会員登録手続きを完了して下さい。';
    $lang->msg_auth_mail_sent = "%s 宛に認証情報内容が送信されました。メールを確認して下さい。";
    $lang->msg_confirm_mail_sent = "%s 宛に確認メールを送信しました。メールをご確認下さい。";
    $lang->msg_invalid_auth_key = '正しくないアカウントの認証要求です。<br />IDとパスワードの検索を行うか、サイト管理者にアカウント情報をお問い合わせ下さい。';
    $lang->msg_success_authed = '認証が正常に行われ、ログイン出来ました。\n必ず確認メールに記載されたパスワードを利用してお好みのパスワードに変更して下さい。';
    $lang->msg_success_confirmed = '会員登録、有難うございます。';

    $lang->msg_new_member = '会員追加';
    $lang->msg_update_member = '会員情報修正';
    $lang->msg_leave_member = '会員退会';
    $lang->msg_group_is_null = '登録されたグループがありません。';
    $lang->msg_not_delete_default = '基本項目は削除出来ません。';
    $lang->msg_not_exists_member = '存在しないユーザＩＤです。';
    $lang->msg_cannot_delete_admin = '管理者ＩＤは削除出来ません。管理者権限を解除した上で削除してみて下さい。';
    $lang->msg_exists_user_id = '既に存在するユーザＩＤです。他のＩＤを入力して下さい。';
    $lang->msg_exists_email_address = '既に存在するメールアドレスです。他のメールアドレスを入力して下さい。';
    $lang->msg_exists_nick_name = '既に存在するニックネームです。他のニックネームを入力して下さい。';
    $lang->msg_signup_disabled = '会員登録が制限されています。<br />サイト管理者にお問合せ下さい。';
    $lang->msg_already_logged = '既に会員に登録されています。';
    $lang->msg_not_logged = 'ログインしていません。';
    $lang->msg_insert_group_name = 'グループ名を入力して下さい。';
    $lang->msg_check_group = 'グループを選択して下さい。';

    $lang->msg_not_uploaded_profile_image = 'プロフィールイメージを登録することが出来ません。';
    $lang->msg_not_uploaded_image_name = 'イメージ名を登録することが出来ません。';
    $lang->msg_not_uploaded_image_mark = 'イメージマークを登録することが出来ません。';
    $lang->msg_not_uploaded_group_image_mark = 'グループ用イメージマークの登録が出来ません。';

    $lang->msg_accept_agreement = '規約に同意しなければなりません。';

    $lang->msg_user_denied = '入力されたユーザＩＤは使用が中止されました。';
    $lang->msg_user_not_confirmed = 'まだメールの確認が出来ませんでした。届いたメールをご確認下さい。';
    $lang->msg_user_limited = '入力されたユーザＩＤは%s以降から使用出来ます。';

    $lang->about_user_id = 'ユーザＩＤは３～２０の英数文字で構成され、最先頭の文字は英字でなければなりません。';
    $lang->about_password = 'パスワードは６～２０文字です。';
    $lang->about_user_name = '名前は２～２０文字です。';
    $lang->about_nick_name = 'ニックネームは２～２０文字です。';
    $lang->about_email_address = 'メールアドレスはメール認証後、パスワード変更または検索などに使用されます。';
    $lang->about_homepage = 'ホームページがある場合は入力して下さい。';
    $lang->about_blog_url = '運用しているブログがあれば入力して下さい。';
    $lang->about_birthday = '生年月日を入力して下さい。';
    $lang->about_allow_mailing = 'メーリングリストにチェックされていない場合は、全体メールの送信時にメールを受け取りません。';
    $lang->about_denied = 'チェックするとユーザＩＤを使用出来ないようにします。';
    $lang->about_is_admin = 'チェックすると最高管理者権限が取得出来ます。';
    $lang->about_member_description = '会員に対する管理者のメモ帳です。';
    $lang->about_group = '一つのユーザＩＤは多数のグループに属することが出来ます。';

    $lang->about_column_type = '追加する登録フォームのタイプを指定して下さい。';
    $lang->about_column_name = 'テンプレートで使用出来る英文字の名前を入力して下さい（変数名）。';
    $lang->about_column_title = '登録または情報修正・閲覧時に表示されるタイトルです。';
    $lang->about_default_value = 'デフォルトで入力される値を指定することが出来ます。';
    $lang->about_active = '活性化（有効化）にチェックを入れないと正常に表示されません。';
    $lang->about_form_description = '説明欄に入力すると登録時に表示されます。';
    $lang->about_required = 'チェックを入れると会員登録時に必須入力項目として設定されます。';

    $lang->about_enable_openid = 'OpenIDをサポートする際にチェックを入れます。';
    $lang->about_enable_join = 'チェックを入れないとユーザが会員に登録出来ません。';
    $lang->about_enable_confirm = '登録されたメールアドレスに確認メールを送信し、会員登録を確認します。';
    $lang->about_enable_ssl = 'サーバーでSSLが可能な場合、会員登録/情報変更/ログイン等の個人情報はSSL(https)経由でサーバーにより安全に送信されます。';
    $lang->about_limit_day = '会員登録後一定の期間中、認証制限を行うことが出来ます。';
    $lang->about_limit_date = '指定された期間まで該当ユーザはログインできなくします。';
    $lang->about_after_login_url = 'ログイン後表示されるページのURLを指定出来ます。指定のない場合、現在のページが維持されます。';
    $lang->about_after_logout_url = 'ログアウト後表示されるページのURLを指定出来ます。指定のない場合、現在のページが維持されます。';
    $lang->about_redirect_url = '会員登録後、表示されるページのURLを指定出来ます。指定のない場合は会員登録する前のページに戻ります。';
    $lang->about_agreement = '会員登録規約がない場合は表示されません。';

    $lang->about_image_name = 'ユーザの名前を文字の代わりにイメージで表示させることが出来ます。';
    $lang->about_image_mark = '使用者の名前の前にマークを付けることが出来ます。';
    $lang->about_group_image_mark = 'ユーザー名の前にグループマークを表示します。';
    $lang->about_profile_image = 'ユーザのプロフィールイメージが使用出来るようにします。';
    $lang->about_signature_max_height = '署名欄の高さのサイズを制限します。 (0 もしくは空の場合は制限なし。)';
    $lang->about_accept_agreement = '登録規約をすべて読んだ上で同意します。';

    $lang->about_member_default = '会員登録時に基本グループとして設定されます。';

    $lang->about_openid = 'OpenIDで登録する際、ＩＤとメールなどの基本情報は、このサイトに保存されますが、パスワードと認証のための処理用の情報は該当するOpenID提供サービス側で行われます。';
    $lang->about_openid_leave = 'OpenIDの退会は現在のサイトから会員情報を削除することを意味します。<br />退会後ログインすると新しく登録することになり、書き込んだコンテンツに対する権限を維持することが出来ません。';
    $lang->about_find_member_account = 'ID/パスワードは登録時に登録されたメールにてお知らせします。<br />登録時に登録したメールアドレスを入力して「IDとパスワードのリマインダー」ボタンをクリックして下さい。<br />';

    $lang->about_member = "会員の作成・修正・削除することが出来、グループの管理、登録フォームの管理などが行える会員管理モジュールです。\nデフォルトで作成されたグループにグループを追加作成して会員管理が出来るようにし、会員登録フォーム管理では基本情報の他、フォームの入力情報を追加することが出来ます。";
    $lang->about_ssl_port = '基本ポート以外のSSLポートを利用する場合、入力して下さい。';
    $lang->add_openid = 'OpenIDの追加';

    $lang->about_resend_auth_mail = '認証メールが届いてなかった場合、再送信の申請が可能です。<br />※申請の前に、当サイトからメールの受信が出来るように設定して下さい。';
    $lang->no_article = '書き込みがありません。';

    $lang->find_account_question = '秘密質問';
    $lang->find_account_answer = '비밀번호 찾기 답변';
    $lang->about_find_account_question = '登録した時、入力したIDとメールアドレス、秘密質問の答えで仮のパスワードをもらえる事ができます。';


    $lang->find_account_question_items = array(''
                                        ,'他のメールアドレスは？'
                                        ,'私の一番大事なものは？'
                                        ,'私の卒業した小学校は？'
                                        ,'私の生まれた街は？'
                                        ,'私の理想型は？'
                                        ,'お母さんのお名前は？'
                                        ,'お父さんのお名前は？'
                                        ,'大好きな色は？'
                                        ,'大好きな食べ物は？'
                                        );

    $lang->temp_password = '仮のパスワード';
    $lang->cmd_get_temp_password = '仮のパスワードをもらう';
    $lang->about_get_temp_password = 'ログインして直ちにパスワードを変更して下さい。';
    $lang->msg_question_not_exists = '秘密質問を決めていません。';
    $lang->msg_answer_not_matches = '秘密質問の答えが正しくありません。';

    $lang->change_password_date = 'パスワード更新周期';
    $lang->about_change_password_date = '設定した更新周期によってパスワード変更のお知らせがもらえます。（０に設定すると非活性化） ';

?>
