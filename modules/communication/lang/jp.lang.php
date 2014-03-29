<?php
    /**
     * @file   modules/member/jp.lang.php
     * @author NHN (developers@xpressengine.com) 翻訳：RisaPapa、ミニミ、liahona
     * @brief  日本語言語パッケージ（基本的な内容のみ）
     **/

    $lang->communication = 'コミュニケーション';
    $lang->about_communication = '会員間にメッセージや友達管理などコミュニティ機能を提供するモジュールです。';

    $lang->allow_message = 'メッセージの受信';
    $lang->allow_message_type = array(
             'Y' => '全て受信',
             'N' => '全て受信しない',
             'F' => '友達からのみ受信する',
        );

    $lang->message_box = array(
        'R' => 'メッセージ受信箱',
        'S' => 'メッセージ送信箱',
        'T' => '保存箱',
    );

    $lang->readed_date = '開封時間'; 

    $lang->sender = '送信者';
    $lang->receiver = '受信者';
    $lang->friend_group = '友達グループ';
    $lang->default_friend_group = 'グループ未指定';

    $lang->cmd_send_message = 'メッセージ送信';
    $lang->cmd_reply_message = 'メッセージ返信';
    $lang->cmd_view_friend = '友達リスト';
    $lang->cmd_add_friend = '友達登録';
    $lang->cmd_view_message_box = 'メッセージ';
    $lang->cmd_store = '保存';
    $lang->cmd_add_friend_group = '友達グループ追加';
    $lang->cmd_rename_friend_group = '友達グループ名変更';

    $lang->msg_no_message = 'メッセージがありません。';
    $lang->message_received = 'メッセージが届きました。';

    $lang->msg_title_is_null = 'メッセージのタイトルを入力して下さい。';
    $lang->msg_content_is_null = '内容を入力して下さい。';
    $lang->msg_allow_message_to_friend = '友達からのみメッセージを受信出来るように設定したユーザであるため、送信出来ませんでした。';
    $lang->msg_disallow_message = 'メッセージの受信を拒否している受信者であるため、送信出来ませんでした。';
    $lang->about_allow_message = 'メッセージを受信するかを設定します。';
?>
