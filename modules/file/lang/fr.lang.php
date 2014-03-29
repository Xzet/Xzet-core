<?php
    /**
     * @file   modules/file/lang/fr.lang.php
     * @author NHN (developers@xpressengine.com)  Traduit par Pierre Duvent <PierreDuvent@gmail.com>
     * @brief  Paquet du langage en francais pour le module d\'Annexe
     **/

    $lang->file = 'Annexe';
    $lang->file_name = 'Nom du Fichier';
    $lang->file_size = 'Mesure du Fichier';
    $lang->download_count = 'Somme du Telecharge';
    $lang->status = 'Statut';
    $lang->is_valid = 'Valide';
    $lang->is_stand_by = 'Attente';
    $lang->file_list = 'Liste des Annexes';
    $lang->allow_outlink = '파일 외부 링크';
    $lang->allow_outlink_site = '파일 외부 허용 사이트';
    $lang->allowed_filesize = 'Mesure du Fichier Maximum';
    $lang->allowed_attach_size = 'Somme des Annexes Maximum';
    $lang->allowed_filetypes = 'Extensions consentis';
    $lang->enable_download_group = 'Groupe permis de telecharger';

    $lang->about_allow_outlink = '리퍼러에 따라 파일 외부 링크를 차단할 수 있습니다.(*.wmv, *.mp3등 미디어 파일 제외)';
    $lang->about_allow_outlink_site = '파일 외부 링크 설정에 관계 없이 허용하는 사이트 주소입니다. 여러개 입력시에 줄을 바꿔서 구분해주세요.<br />ex)http://xpressengine.com/';
	$lang->about_allowed_filesize = 'Vous pouvez designer la limite de mesure pour chaque fichier. (Exclure administrateurs)';
    $lang->about_allowed_attach_size = 'Vous pouvez designer la limite de mesure pour chaque document. (Exclure administrateurs)';
    $lang->about_allowed_filetypes = 'Extensions consentis seulement peuvent etre attaches. Pour consentir une extension, utilisez "*.[extention]". Pour consentir plusieurs extensions, utilisez ";" entre chaque extension.<br />ex) *.* ou *.jpg;*.gif;<br />(Exclure Administrateurs)';

    $lang->cmd_delete_checked_file = 'Supprimer item(s) slectionne(s)';
    $lang->cmd_move_to_document = 'Bouger au Document';
    $lang->cmd_download = 'Telecharger';

    $lang->msg_not_permitted_download = 'Vous n\'etes pas permis(e) de telecharger';
    $lang->msg_cart_is_null = 'Choisissez un(des) fichier(s) a supprimer';
    $lang->msg_checked_file_is_deleted = '%d Annexe(s) est(sont) supprime(s)';
    $lang->msg_exceeds_limit_size = 'La mesure de l\'(des) Annexe(s) est plus grande que celle consentie.';
    $lang->msg_file_not_found = '요청하신 파일을 찾을 수 없습니다.';

    $lang->file_search_target_list = array(
        'filename' => 'Nom de Fichier',
        'filesize_more' => 'Mesure de Fichier (octet, surplus)',
        'filesize_mega_more' => '파일크기 (Mb, 이상)',
		'filesize_less' => '파일크기 (byte, 이하)',
		'filesize_mega_less' => '파일크기 (Mb, 이하)',
        'download_count' => 'Telecharges (surplus)',
        'user_id' => '아이디',
        'user_name' => '이름',
        'nick_name' => '닉네임',
        'regdate' => 'Enrgistre',
        'ipaddress' => 'Adresse IP',
    );
	$lang->msg_not_allowed_outlink = 'It is not allowed to download files not from this site.'; 
    $lang->msg_not_permitted_create = '파일 또는 디렉토리를 생성할 수 없습니다.';
	$lang->msg_file_upload_error = '파일 업로드 중 에러가 발생하였습니다.';

?>
