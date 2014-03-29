<?php
    /**
     * @file   modules/module/lang/en.lang.php
     * @author NHN (developers@xpressengine.com)
     * @brief  English language pack
     **/

    $lang->virtual_site = "Sanal Siteler";
    $lang->module_list = "Modül Listesi";
    $lang->module_index = "Modül Listesi";
    $lang->module_category = "Modül Kategorisi";
    $lang->module_info = "Modül Bilgisi";
    $lang->add_shortcut = "Kısayol Ekle";
    $lang->module_action = "Eylemler";
    $lang->module_maker = "Modül Geliştirici";
    $lang->module_license = 'Lisans';
    $lang->module_history = "Güncelleştirme Geçmişi";
    $lang->category_title = "Kategori Başlığı";
    $lang->header_text = 'Başlık Metni';
    $lang->footer_text = 'Sayfa Altlığı Metni';
    $lang->use_category = 'Kategoriyi Etkinleştir';
    $lang->category_title = 'Kategori Başlığı';
    $lang->checked_count = 'Denetlenen Makale Sayısı';
    $lang->skin_default_info = 'Varsayılan Dış Görünüm Bilgisi';
    $lang->skin_author = 'Dış Görünüm Geliştiricisi';
    $lang->skin_license = 'Lisans';
    $lang->skin_history = 'Güncelleştirme Geçmişi';
    $lang->module_copy = "Çoğaltma Modülü";
    $lang->module_selector = "Modül Seçini";
    $lang->do_selected = "Şunu yap";
    $lang->bundle_setup = "Toplu Ayar";
    $lang->bundle_addition_setup = "Toplu Ek Ayar";
    $lang->bundle_grant_setup = "Toplu Yetki Ayarı";
    $lang->lang_code = "Dil Kodu";
    $lang->filebox = "Dosya Kutusu";

    $lang->access_type = 'Erişim Türü';
    $lang->access_domain = 'Alan adıyla';
    $lang->access_vid = 'Site kimliğiyle';
    $lang->about_domain = "Birden fazla sanal website oluşturabilmek için, her birinin kendi alanadına ihtiyacı vardır.<br />Alt-alanadı (örn., aaa.bbb.com of bbb.com) da kullanılabilir. XE'nin de içinde kurulu olduğu yolun adresini giriniz. <br /> örn.) www.xpressengine.com/xe";
    $lang->about_vid = 'Kullanıcılar, http://XEaddress/ID adresiyle ulaşabilirler. Varolan bir modül adıyla(mid) aynı olan site kimliği kullanamazsınız .<br />Site kimliği bir harfle başlamaladır . Alfabetik karakterler, sayılar ve _ işareti site kimliği için kullanılabilir.';
    $lang->msg_already_registed_vid = 'Önceden kaydedilmiş site kimliği. Lütfen başka bir kimlik giriniz.';
    $lang->msg_already_registed_domain = "Bu alanadı önceden kullanıldı. Lütfen farklı bir alanadı giriniz.";

    $lang->header_script = "Başlık Betiği(script)";
    $lang->about_header_script = "Html betiğini(script) &lt;header&gt; ile &lt;/header&gt; arasına kendiniz ekleyebilirsiniz.<br /> &lt;script, &lt;style or &lt;meta tag kullanabilirsiniz";

    $lang->grant_access = "Yetki";
    $lang->grant_manager = "Yönetim";

    $lang->grant_to_all = "Tüm kullanıcılar";
    $lang->grant_to_login_user = "Oturum açmış kullanıcılar";
    $lang->grant_to_site_user = "Kayıtlı kullanıcılar";
    $lang->grant_to_group = "Belirli grup kullanıcıları";

    $lang->cmd_add_shortcut = "Kısayol Ekle";
    $lang->cmd_install = "Kur";
    $lang->cmd_update = "Güncelleştir";
    $lang->cmd_manage_category = 'Kategorileri Yönet';
    $lang->cmd_manage_grant = 'Yetkileri Yönet';
    $lang->cmd_manage_skin = 'Dış Görünümleri Yönet';
    $lang->cmd_manage_document = 'Makaleleri Yönet';
    $lang->cmd_find_module = 'Modül Bul';
    $lang->cmd_find_langcode = 'Dil kodu bul';

    $lang->msg_new_module = "Yeni modül oluştur";
    $lang->msg_update_module = "Modül Düzelt";
    $lang->msg_module_name_exists = "Bu isim önceden zaten alınmış. Lütfen başka bir tane deneyiniz.";
    $lang->msg_category_is_null = 'Kayıtlı kategori yok.';
    $lang->msg_grant_is_null = 'Yetki listesi yok.';
    $lang->msg_no_checked_document = 'Denetlenmiş makaleler bulunmamakta.';
    $lang->msg_move_failed = 'Taşıma hatası';
    $lang->msg_cannot_delete_for_child = 'Alt kategorileri olan kategoriler silinemez.';
	$lang->msg_limit_mid ="Sadece harfler+[harfler+sayılar+_] modül ismi olarak kullanılabilir.";
    $lang->msg_extra_name_exists = 'Önceden kayıtlı değişken isim. Lütfen başka bir tane giriniz.';

    $lang->about_browser_title = "Bu girdi tarayıcı başlığında gösterilecektir. Aynı zamanda RSS/Geri İzleme(trackback)\'de de kullanılacaktır.";
    $lang->about_mid = "Modül ismi, http://adres/?mid=Modulismi şeklinde kullanılacaktır.\n(Sadece ingilizce harflere + [ingilizce harflere, sayılara, ve altçizgiye (_)] izin verilmiştir. Azami uzunluk 40 karakterdir.)";
    $lang->about_default = "İşaretlenmişse, modül kimlik değeri(mid=Değer Yok) olmadan siteye erişirken varsayılan kullanılacaktır.";
    $lang->about_module_category = "Size, modül kategorisi yoluyla yönetme imkanı sunar.\n Modül Yöneticisi için URL: <a href=\"./?module=admin&amp;act=dispModuleAdminCategory\">Manage module > Modül Kategorisi</a>dir.";
    $lang->about_description= 'Sadece yöneticilerin görebileceği açıklamadır.';
    $lang->about_default = 'Eğer işaretlenmişse, kullanıcılar siteye modül kimlik değeri (mid=değer yok) olmadan erişirken bu modül görüntülenecektir .';
    $lang->about_header_text = 'Modülün üst kısmında gösterilecek içeriklerdir.(html etiketleri etkin)';
    $lang->about_footer_text = 'Modülün alt kısmında gösterilecek içeriklerdir.(html etiketleri etkin)';
    $lang->about_skin = 'Modül dış görünümünü seçebilirsiniz.';
    $lang->about_use_category = 'İşaretlediğiniz taktirde, kategori özelliği etkinleştirilecektir.';
    $lang->about_list_count = 'Sayfada gösterilecek makalelerin sayı üstsınırını ayarlayabilirsiniz.(varsayılan : 20)';
	$lang->about_search_list_count = 'Kategori veya arama özelliğini kullanırken gösterilecek makalelerin sayısını ayarlayabilirsiniz. (varsayılan : 20)';
    $lang->about_page_count = 'Sayfanın alt kısmında sayfa geçiş linklerinin sayısını ayarlayabilirsiniz.(varsayılan : 10)';
    $lang->about_admin_id = 'Modüle tam yetkisi olan bir yönetici atayabilirsiniz.';
    $lang->about_grant = 'Belirli bir nesne için tüm yetkileri kapatırsanız, siteye üye girişi yapmamış üyeler yetki sahibi olacaklardır.';
    $lang->about_grant_deatil = 'Kayıtlı kullanıcı, sanal sitelere (örn., cafeXE) kayıt olmuş kullanıcı demektir .';
    $lang->about_module = "XE, temel kitaplık dışında, modüllerden oluşmaktadır.\n [Modül Yönetimi] modülü, size kurulu tüm modülleri gösterecek ve onları yönetmenize yardımcı olacaktır.";

	$lang->about_extra_vars_default_value = 'Eğer çoklu varsayılan değerler gerekiyorsa,	 onları virgülle(,) köprüleyebilirsiniz.';
    $lang->about_search_virtual_site = "Sanal sitelerin alanadlarını giriniz.<br />Sanal olmayan sitelerin modüllerini aramak için, boş arama yapınız";
    $lang->about_langcode = "Eğer tek tek yapılandırma yapmak istiyorsanız, 'dil kodu bul'\' u kullanınız.";
    $lang->about_file_extension= "Yalnızca %s uzantı(ları) mevucut.";
?>
