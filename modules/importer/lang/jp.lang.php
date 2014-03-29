<?php
    /**
     * @file   modules/importer/lang/jp.lang.php
     * @author NHN (developers@xpressengine.com) 翻訳：RisaPapa、ミニミ
     * @brief  Importer(importer) モジュールの基本言語パッケージ
     **/

    // ボタンに使用する用語
    $lang->cmd_sync_member = '同期化';
    $lang->cmd_continue = '続ける';
    $lang->preprocessing = 'データ移転のため、準備中です。';

    // 項目
    $lang->importer = 'XEデータ変換';
    $lang->source_type = 'データ変換の対象';
    $lang->type_member = '会員情報';
    $lang->type_message = 'メッセージ情報';
    $lang->type_ttxml = 'TTXML';
    $lang->type_module = '書き込みデータ情報';
    $lang->type_syncmember = '会員情報同期化';
    $lang->target_module = '対象モジュール';
    $lang->xml_file = 'XMLファイル';

    $lang->import_step_title = array(
        1 => 'Step 1. 移転先を選択',
        12 => 'Step 1-2. 対象モジュール選択',
        13 => 'Step 1-3. 対象カテゴリ選択',
        2 => 'Step 2. XMLファイルアップロード',
        3 => 'Step 2. 会員情報と書き込みデータの同期化',
        99 => 'データ移転',
    );

    $lang->import_step_desc = array(
        1 => '変換するXMLファイルの種類を選択して下さい。',
        12 => 'データ変換を行う対象モジュールを選択して下さい。',
        121 => '書き込み:',
        122 => 'ゲストブック:',
        13 => 'データ変換を行う対象カテゴリを選択して下さい。',
        2 => "データ変換を行うXMLファイルパスを入力して下さい。同じアカウントのサーバ上では、相対または絶対パスを、異なるサーバにアップロードされている場合は「http://アドレス..」を入力して下さい。",
        3 => '会員情報と書き込みデータの情報の変換を行った後、データが合わない場合があります。この時に同期化を行うと「user_id」をもとに正しく動作するようにします。',
        99 => 'データを移転しています。',
    );

    // 案内/警告
    $lang->msg_sync_member = '同期化ボタンをクリックすると会員情報と書き込みデータの情報の同期化が始まります。';
    $lang->msg_no_xml_file = 'XMLファイルが見つかりません。パスをもう一度確認して下さい。';
    $lang->msg_invalid_xml_file = 'XMLファイルのフォーマットが正しくありません。';
    $lang->msg_importing = '%d個のデータの内、%d個を変換中です（止まったままの場合は「続ける」ボタンをクリックして下さい）。';
    $lang->msg_import_finished = '%d/%d個のデータ変換が完了しました。場合によって変換されていないデータがあることもあります。';
    $lang->msg_sync_completed = '会員情報、書き込みデータ、コメントのデータの同期化（変換）が完了しました。';

    // その他..
    $lang->about_type_member = 'データ変換の対象が会員情報の場合は選択して下さい。';
    $lang->about_type_message = 'データ移転対象がメッセージの場合選択して下さい。';
    $lang->about_type_ttxml = 'データ移転対象が、TTXML(textcube系列)の場合選択して下さい。';
    $lang->about_ttxml_user_id = 'TTXML移転時に投稿者として指定するユーザIDを入力して下さい（すでに加入されているIDでなければなりません）。';
    $lang->about_type_module = 'データ変換の対象が書き込みデータである場合は選択して下さい。';
    $lang->about_type_syncmember = '会員情報と書き込みデータなどの変換を行った後、会員情報を同期化する必要がある場合は、選択して下さい。';
    $lang->about_importer = "ゼロボード4、zb5betaまたは他のプログラムの書き込みデータをXEのデータに変換することが出来ます。\n変換するためには、<a href=\"http://svn.xpressengine.com/zeroboard_xe/migration_tools/\" onclick=\"winopen(this.href);return false;\">XML Exporter</a>を利用して変換したい書き込みデータをXMLファイルで作成してアップロードして下さい。";
    $lang->about_target_path = "添付ファイルをダウンロードするためには、ゼロボード4がインストールされた場所を入力して下さい。同じサーバ上にある場合は「/home/ID/public_html/bbs」のように入力し、他のサーバにある場合は、「http://ドメイン/bbs」のようにゼロボードがインストールされているURLを入力して下さい。";
?>
