<?php
    /**
     * @file   modules/point/lang/ko.lang.php
     * @author NHN (developers@xpressengine.com)
     * @brief  Default language pack of point module
     **/

    $lang->point = "Puan"; 
    $lang->level = "Seviye"; 

    $lang->about_point_module = "Yazmaya/silmeye, yorum eklemeye/yorum silmeye puan atayabilirsiniz.";
    $lang->about_act_config = "Pano/blog gibi her modülün, \"yazma/silme/yorum ekleme/yorum silme gibi kendi eylemleri bulunmaktadır. \".<br />Pano/blog harici, puan sistemli link modüllerine davranış değerleri ekleyebilirsiniz.<br />Virgül(,) çoklu değerleri ayıracaktır."; 

    $lang->max_level = 'Azami Seviye';
    $lang->about_max_level = 'Azami seviyeyi ayarlayabilirsiniz. Seviye simgelerine değer verirken dikkate alınmalıdır. Verebileceğiniz en yüksek değer 1000dir.'; 

    $lang->level_icon = 'Seviye Simgesi';
    $lang->about_level_icon = 'Seviye simgesi yolu "./module/point/icons/[seviye].gif" olmalıdır. Azami seviye farklı simge setiyle gösterilebilir. Bu yüzden lütfen dikkatli olunuz.'; 

    $lang->point_name = 'Puan Adı';
    $lang->about_point_name = 'Puan için bir isim veya birim belirleyebilirsiniz'; 

    $lang->level_point = 'Seviye Puanı';
    $lang->about_level_point = 'Puan herhangi bir seviyeye gelince veya bir puan seviyesinin altına düşünce, seviye otomatik ayarlanacaktır.'; 

    $lang->disable_download = 'İndirmeleri Yasakla';
    $lang->about_disable_download = "Yeterli puan olmadığı zaman indirme yapamayacaklardır. (Resim dosyaları harici)"; 
    $lang->disable_read_document = 'Okumayı Yasakla';
    $lang->about_disable_read_document = 'Kullanıcıların yeterli puanı olmadığında, makaleleri okuyamayacaklardır.';

    $lang->level_point_calc = 'Puan başına Puan Hesaplaması';
    $lang->expression = 'Lütfen seviye değişkenini kullanarak Javascript formülü ekleyiniz <b></b>. örn) Math.pow(i, 2) * 90';
    $lang->cmd_exp_calc = 'Hesapla';
    $lang->cmd_exp_reset = 'Sıfırla';

    $lang->cmd_point_recal = 'Puan Sıfırla';
	$lang->about_cmd_point_recal = 'Sadece makalelerdeki/yorumlardaki/eklerdeki/katılımlardaki tüm puanlar sıfırlanacaktır.<br />Sıfırlamadan sonra sadece, website aktiviteleri yapan üyeler, giriş puanı alacaklardır.<br />Lütfen bu özelliği sadece veri taşıma veya cidden gerekliliği olduğu durumlarda kullanınız.';

    $lang->point_link_group = 'Seviyeye Göre Grup Değiştirme';
    $lang->point_group_reset_and_add = 'Düzenlenmiş grupları sıfırla ve yeni gruplar ekle';
    $lang->point_group_add_only = 'Sadece yeni gruplara';
    $lang->about_point_link_group = 'Belirli bir grup için seviye belirliyorsanız, kullanıcılar gruba o seviyenin puanına eriştiklerinde atanacaklardır.';

    $lang->about_module_point = "Her modül için puan ayarlayabilirsiniz. Hiçbir değer atanmayan modüller varsayılan puan sistemini kullanacaktır.<br />Tersi hareket durumunda tüm puanlar iade edilecektir.";

    $lang->point_signup = 'Kayıt Olmaya';
    $lang->point_insert_document = 'Yazıya';
    $lang->point_delete_document = 'Silmeye';
    $lang->point_insert_comment = 'Yorum Eklemeye';
    $lang->point_delete_comment = 'Yorum Silmeye';
    $lang->point_upload_file = 'Karşıya Yüklemeye (upload)';
    $lang->point_delete_file = 'Dosyaları Silmeye';
    $lang->point_download_file = 'Dosyaları İndirmeye (resimler hariç)';
    $lang->point_read_document = 'Okumaya';
    $lang->point_voted = 'Önerilene';
    $lang->point_blamed = 'Suçlanana';


    $lang->cmd_point_config = 'Varsayılan Ayar';
    $lang->cmd_point_module_config = 'Modül Ayarı';
    $lang->cmd_point_act_config = 'Eylem Ayarı';
    $lang->cmd_point_member_list = 'Üye Puan listesi';

    $lang->msg_cannot_download = "İndirmek için yeteri puanınız bulunmamaktadır";
    $lang->msg_disallow_by_point = "Makaleyi okumak için daha fazla puana ihtiyacınız var (%d lazımken, %d puanınız var)";

    $lang->point_recal_message = 'Puan Düzeltiliyor. (%d / %d)';
    $lang->point_recal_finished = 'Puan tekrar hesaplaması bitti.';
?>
