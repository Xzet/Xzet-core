<?php
    /**
     * @file   en.lang.php
     * @author NHN (developers@xpressengine.com)
     * @brief  English Language Pack (Only Basic Things)
     **/

    $lang->member = 'Üye';
    $lang->member_default_info = 'Temel Bilgi';
    $lang->member_extend_info = 'Detaylı Bilgi';
    $lang->default_group_1 = "Asil Üye";
    $lang->default_group_2 = "Sıradan Üye";
    $lang->admin_group = "Grup Yönetimi";
    $lang->keep_signed = 'Beni bağlı tut';
    $lang->remember_user_id = 'Kimliği Hatırla';
    $lang->already_logged = "Zaten giriş yaptınız";
    $lang->denied_user_id = 'Yasaklanmış bir kimlik girdiniz.';
    $lang->null_user_id = 'Lütfen kimliğinizi giriniz';
    $lang->null_password = 'Lütfen şifrenizi giriniz';
    $lang->invalid_authorization = 'Hesap etkinleştirilmemiş.';
    $lang->invalid_user_id= "Geçerli olmayan bir kimlik girdiniz";
    $lang->invalid_password = 'Hatalı bir şifre girdiniz';
    $lang->invalid_new_password = 'Yeni şifre, eski şifreyle aynı olmamalı.';
    $lang->allow_mailing = 'Mailleşmeyi Aç';
    $lang->denied = 'Yasaklanmış';
    $lang->is_admin = 'Süperadmin Yetkisi';
    $lang->group = 'Grup';
    $lang->group_title = 'Grup Adı';
    $lang->group_srl = 'Grup Numarası';
    $lang->signature = 'İmza';
    $lang->profile_image = 'Profil Resmi';
    $lang->profile_image_max_width = 'En Yük. Genişlik';
    $lang->profile_image_max_height = 'En Yük. Uzunluk';
    $lang->image_name = 'Resim İsmi';
    $lang->image_name_max_width = 'En Yük. Genişlik';
    $lang->image_name_max_height = 'En Yük. Uzunluk';
    $lang->image_mark = 'Resim İmi';
    $lang->image_mark_max_width = 'En Yük. Genişlik';
    $lang->image_mark_max_height = 'En Yük. Uzunluk';
    $lang->group_image_mark = 'Grup Simgesi';
    $lang->group_image_mark_max_width = 'En Yük. Genişlik';
    $lang->group_image_mark_max_height = 'En Yük. Uzunluk';
    $lang->group_image_mark_order = 'Grup Simge Sıralaması';
    $lang->signature_max_height = 'En Yük. İmza Uzunluğu';
    $lang->enable_openid = 'OpenID Aç';
    $lang->enable_join = 'Yeni Üye Kabul Et';
    $lang->enable_confirm = 'E-posta Doğrulaması';
    $lang->enable_ssl = 'SSL Etkinleştir';
    $lang->security_sign_in = 'Gelişmiş güvenliği kullanarak giriş yap';
    $lang->limit_day = 'Geçici Zaman Sınırı';
    $lang->limit_date = 'Zaman Sınırı';
    $lang->after_login_url = 'Giriş yaptıktan sonraki URL';
    $lang->after_logout_url = 'Çıkış yaptıktan sonraki URL';
    $lang->redirect_url = 'Giriş yaptıktan sonraki URL';
    $lang->agreement = 'Üyelik Sözleşmesi';
    $lang->accept_agreement = 'Kabul Et';
    $lang->member_info = 'Üye Bilgisi';
    $lang->current_password = 'Geçerli Şifre';
    $lang->openid = 'OpenID';
    $lang->allow_message = 'Mesajlaşmaya İzin Ver';
    $lang->allow_message_type = array(
            'Y' => 'Tümüne İzin Ver',
            'F' => 'Arkadaşlara İzin Ver',
            'N' => 'Tümünü Geri Çevir',
    );
    $lang->about_allow_message = 'Mesajlaşmaya izin verebilir/reddedebilirsiniz.';
    $lang->logged_users = 'Giriş yapmış Kullanıcılar';

    $lang->webmaster_name = "Webmaster İsmi";
    $lang->webmaster_email = "Webmaster E-postası";

    $lang->about_keep_signed = 'Tarayıcıyı kapatsanız da sisteme bağlı kalacaksınız.\n\nEğer ortak bir bilgisayar kullanıyorsanız, kişisel bilgilerinizin gizliliği için bunu önermiyoruz';
    $lang->about_keep_warning = 'Tarayıcıyı kapatsanız da sisteme bağlı kalacaksınız. Eğer ortak bir bilgisayar kullanıyorsanız, kişisel bilgilerinizin gizliliği için bunu önermiyoruz';
	$lang->about_webmaster_name = "Lütfen onay maillerinde veya site yönetiminde kullanılacak webmaster ismini giriniz. (varsayılan : webmaster)";
    $lang->about_webmaster_email = "Lütfen webmaster e-posta adresini giriniz.";

    $lang->search_target_list = array(
        'user_id' => 'Kimlik',
        'user_name' => 'İsim',
        'nick_name' => 'Takma Ad',
        'email_address' => 'E-posta Adresi',
        'regdate' => 'Kayıt Tarihi',
        'regdate_more' => 'Kayıt Tarihi (detaylı)',
        'regdate_less' => 'Kayıt Tarihi (basit)',
        'last_login' => 'Son Giriş Tarihi',
        'last_login_more' => 'Son Giriş Tarihi (detaylı)',
        'last_login_less' => 'Son Giriş Tarihi (basit)',
        'extra_vars' => 'Fazladan Çeş.',
    );

    $lang->cmd_login = 'Giriş Yap';
    $lang->cmd_logout = 'Çıkış Yap';
    $lang->cmd_signup = 'Kaydol';
    $lang->cmd_site_signup = 'Kaydol';
    $lang->cmd_modify_member_info = 'Üye Bilgilerini Düzenle';
    $lang->cmd_modify_member_password = 'Şifreyi Düzenle';
    $lang->cmd_view_member_info = 'Üye Bilgisi';
    $lang->cmd_leave = 'Ayrıl';
    $lang->cmd_find_member_account = 'Hesap Bilgisi Bul';
	$lang->cmd_resend_auth_mail = 'Etkinleştirme Maili Talebinde Bulun';

    $lang->cmd_member_list = 'Üye Listesi';
    $lang->cmd_module_config = 'Varsayılan Ayar';
    $lang->cmd_member_group = 'Üye Grupları';
    $lang->cmd_send_mail = 'Mail Gönder';
    $lang->cmd_manage_id = 'Yasaklanmış Kimlikler';
    $lang->cmd_manage_form = 'Kayıt Formu';
    $lang->cmd_view_own_document = 'Yazılmış Makaleler';
	$lang->cmd_view_own_comment = '작성 댓글 보기';
    $lang->cmd_manage_member_info = 'Üye Bilgisini Yönet';
    $lang->cmd_trace_document = 'Yazılmış Makalelerini Gör';
    $lang->cmd_trace_comment = 'Yazılmış Yorumlarını Gör';
    $lang->cmd_view_scrapped_document = 'Hasarlılar';
    $lang->cmd_view_saved_document = 'Kayıtlı Makaleler';
    $lang->cmd_send_email = 'Mail Gönder';

    $lang->msg_email_not_exists = "Geçerli olmayan bir e-posta adresi girdiniz";

    $lang->msg_alreay_scrapped = 'Bu makale zaten hasarlı';

    $lang->msg_cart_is_null = 'Lütfen hedefi seçiniz';
    $lang->msg_checked_file_is_deleted = '%d isimli ek dosya silindi';

    $lang->msg_find_account_title = 'Hesap Bilgisi';
    $lang->msg_find_account_info = 'Talep edilen hesap bilgisi.';
    $lang->msg_find_account_comment = 'Şifreniz aşağıdaki linke tıkladığınızda, yukardaki gibi değiştirilecektir.<br />Lütfen giriş yaptıktan sonra şifrenizi değiştiriniz.';
    $lang->msg_confirm_account_title = 'Zeroboard XE Hesap Etkinleştirmesi';
    $lang->msg_confirm_account_info = 'Hesap Bilginiz:';
    $lang->msg_confirm_account_comment = 'Hesabınızı etkinleştirmek için takip eden linke tıklayınız.';
    $lang->msg_auth_mail_sent = 'Etknileştirme maili %s adresine gönderildi. Lütfen mailinizi kontrol ediniz.';
    $lang->msg_confirm_mail_sent = '%s adresine etkinleştirme maili gönderdik. Lütfen mailinizi kontrol ediniz.';
    $lang->msg_invalid_auth_key = 'Geçersiz doğrulama talebi.<br />Lütfen hesap bilgisini tekrar bulmayı deneyin ya da yöneticilerle iletişime geçin.';
    $lang->msg_success_authed = 'Hesabınız başarıyla etkinleştirildi ve giriş yapıldı.\n Lütfen mailinize gelen şifreyi kullanarak yeni şifreinizi oluşturunuz.';
    $lang->msg_success_confirmed = 'Hesabınız başarıyla etkinleştirildi.';

    $lang->msg_new_member = 'Üye Ekle';
    $lang->msg_update_member = 'Üye Bilgisini Düzenle';
    $lang->msg_leave_member = 'Ayrıl';
    $lang->msg_group_is_null = 'Grup yok';
    $lang->msg_not_delete_default = 'Varsayılan ögeler silinemez';
    $lang->msg_not_exists_member = "Geçersiz üye";
    $lang->msg_cannot_delete_admin = 'Admin Kimliği silinemez. Kimliği yönetimden çıkartıp tekrar deneyiniz.';
    $lang->msg_exists_user_id = 'Bu kimlik önceden alınmış. Lütfen başka bir kimlik deneyiniz.';
    $lang->msg_exists_email_address = 'Bu mail adresi zaten kullanımda. Lütfen başka bir mail adresi deneyiniz.';
    $lang->msg_exists_nick_name = 'Bu rumuz önceden alınmış. Lütfen başka bir rumuz deneyiniz.';
    $lang->msg_signup_disabled = 'Kayıt olma yetkiniz bulunmamaktadır';
    $lang->msg_already_logged = 'Zaten giriş yapmış bulunmaktasınız';
    $lang->msg_not_logged = 'Lütfen önce giriş yapınız';
    $lang->msg_insert_group_name = 'Lütfen grup ismini giriniz';
    $lang->msg_check_group = 'Lütfen grubu seçiniz';

    $lang->msg_not_uploaded_profile_image = 'Profil resmi kaydedilemedi';
    $lang->msg_not_uploaded_image_name = 'Resim İsmi kaydedilemedi';
    $lang->msg_not_uploaded_image_mark = 'Resim İmi kaydedilemedi';
    $lang->msg_not_uploaded_group_image_mark = 'Grup resim imi kaydedilemedi';

    $lang->msg_accept_agreement = 'Sözleşmeyi kabul etmeniz gerekmektedir';

    $lang->msg_user_denied = 'Yasaklanmış bir Kimlik girdiniz';
    $lang->msg_user_not_confirmed = 'Hesabınız henüz etkinleştirilmemiş. Lütfen mailinizi kontrol ediniz.';
    $lang->msg_user_limited = 'Bu kimlik, %s Tarihinden sonra kullanılabilir';

    $lang->about_user_id = 'Kullanıcı kimliği 3~20 karakter uzunluğunda ve hem harf hem rakam içermeli ve ilk bir harfle başlamalıdır.';
    $lang->about_password = 'Şifre 6~20 karakter uzunluğunda olmalıdır';
    $lang->about_user_name = 'İsim 2~20 karakter uzunluğunda olmalıdır';
    $lang->about_nick_name = 'Rumuz 2~20 karakter uzunluğunda olmalıdır';
    $lang->about_email_address = 'E-posta adresiniz, e-posta onaylamasından sonra şifre düzenlemesi için kullanılacaktır';
    $lang->about_homepage = 'Lütfen varsa website adresinizi giriniz';
    $lang->about_blog_url = 'Lütfen varsa blog adresinizi giriniz';
    $lang->about_birthday = 'Lütfen doğum tarihinizi giriniz';
    $lang->about_allow_mailing = "Eğer mailleşmeyi etkinleştirmezseniz, grup maillerini alamayacaksınız";
    $lang->about_denied = 'Kimliği yasaklanmak için işaretleyiniz';
    $lang->about_is_admin = 'Superadmin yetkisi vermek için işaretleyiniz';
    $lang->about_member_description = "Yöneticinin kullanıcılar hakkındaki kısa notu";
    $lang->about_group = 'Bir kimlik, birçok grubun üyesi olabilir';

    $lang->about_column_type = 'Lütfen detaylı kayıt formunun biçimini ayarlayınız';
    $lang->about_column_name = 'Lütfen şablonda kullanılabilecek İngilizce bir isim giriniz. (isim değişkendir)';
    $lang->about_column_title = 'Bu girdi, kayıt veya üye bilgisi düzenleme/görüntüleme formunda gösterilecektir';
    $lang->about_default_value = 'Varsayılan değerleri ayarlayabilirsiniz';
    $lang->about_active = 'Kayıt formunu gösterebilmeniz için, öğeleri etkinleştirdiğinizden emin olun';
    $lang->about_form_description = 'Eğer tanım formuna giriş yaparsanız, bu girdiler giriş formunda görünecektir';
    $lang->about_required = 'Eğer işaretlerseniz, kayıt için gerekli bir öğe olacaktır';

    $lang->about_enable_openid = 'Eğer OpenID hizmeti sunmak istiyorsanız, lütfen işaretleyiniz';
    $lang->about_enable_join = 'Yeni üyelerin sitenize kayıt yapmalarına izin vermek istiyorsanız lütfen işaretleyiniz';
    $lang->about_enable_confirm = 'Yeni üyelerin hesaplarını e-posta yoluyla etkinleştirmelerini istiyorsanız lütfen işaretleyiniz.';
    $lang->about_enable_ssl = 'Kayıt Ol/Üye Bilgisini Değiştir/Giriş Yap \'daki kişisel bilgiler, eğer sunucunuz da SSL servisi veriyorsa, SSL (https) olarak ayarlanabilir.';
    $lang->about_limit_day = 'Kayıttan sonraki hesap etkinleştirme süresini kısıtlayabilirsiniz';
    $lang->about_limit_date = 'Üyeler, belirlenen tarihe kadar kayıt yapamazlar';
    $lang->about_after_login_url = 'Giriş yaptıktan sonraki gidilecek URL\'yi ayarlayabilirsiniz. Boş bırakmak, geçerli sayfayı bırakır.';
    $lang->about_after_logout_url = 'Çıkış yaptıktan sonraki gidilecek URL\'yi ayarlayabilirsiniz. Boş bırakmak, geçerli sayfa manasına gelir.';
    $lang->about_redirect_url = 'Lütfen üyelerin kayıt olduktan sonraki yönlendirilecekleri URL\'yi giriniz. Bır url girilmediğinde, kayıt sayfasından bir önceki sayfaya yönlendirileceklerdir.';
    $lang->about_agreement = "Eğer boş bırakılmadıysa, Kayıt Sözleşmesi görüntülenecektir";

    $lang->about_image_name = "Üyeler, metin yerine resim adı kullanabileceklerdir";
    $lang->about_image_mark = "Üyeler, isimlerinin önünde işaret resmi kullanabilirler";
    $lang->about_group_image_mark = "Kullanıcıların isimlerinin önlerinde görüntülenecek grup işaretleri kullanabilirsiniz";
    $lang->about_profile_image = 'Üyeler, profil resmi kullanabilirler';
    $lang->about_accept_agreement = "Anlaşmayı okudum ve kabul ediyorum";

    $lang->about_member_default = 'Varsayılan grup, kullanıcı kayıt olduktan sonra yönlendirileceği grup olacaktır';

    $lang->about_openid = 'OpenID olarak giriş yaptığınızda, e-posta adresi ya da kimliğiniz gibi temel bilgi sitede kaydedilecektir, şifre ve onaylama yönetimi işlemi, hizmet veren geçerli OpenID tarafından yapılacaktır';
    $lang->about_openid_leave = 'OpenID\'nin bu bölünmesi, üyelik bilginizin bu siteden silinmesi demektir.<br />Bölünmeden sonra giriş yaparsanız, yeni bir üye gibi kabul edilecekseniz, bu yüzden eski makalelerinize ulaşma yetkiniz olmayacaktır.';

    $lang->about_member = "Üye modülü size; üyeleri oluşturmada, düzenlemede, silmede ve kayıt formunu ya da grupları düzenlemede yardımcı olur.\nÜyeleri yönetmek için özel gruplar ekleyebilir ve aynı zamanda kayıt formunu değiştirerek üyeler hakkında ek bilgi alabilirsiniz.";
    $lang->about_find_member_account = 'Hesap bilginiz, kayıt olduğunuz e-posta adresine gönderilecektir.<br />Kayıt esnasında kullandığınız e-posta adresini giriniz ve "Hesap Bilgisini Bul" tuşuna basınız.<br />';
	$lang->about_ssl_port = 'Eğer varsayılan SSL portundan farklı bir port kullanıyorsanız, lütfen giriniz';
    $lang->add_openid = 'OpenID Ekle';

	$lang->about_resend_auth_mail = 'Eğer daha önce etkinleştirmediyseniz, etkinleştirme maili talebinde bulunabilirsiniz';
    $lang->no_article = 'Hiçbir makale bulunmuyor';

	$lang->find_account_question = 'Geçici şifre sorusu';
	$lang->about_find_account_question = 'Kimliğinize, mail adresinize ya da cevapladığınız soruya göre geçici şifre edinebilirsiniz.';
	$lang->find_account_question_items = array(''
										,'Diğer e-posta adresiniz nedir?'
										,'En sevdiğiniz şey?'
										,'Mezun olduğunuz ilkokul?'
										,'Memleketiniz neresi?'
										,'İdeal eşiniz nasıl olmalı?'
										,"Annenizin adı?"
										,"Babanızın adı?"
										,'En sevdiğiniz renk?'
										,'En sevdiğiniz yemek?'
										);
	$lang->temp_password = 'Geçici şifre';
	$lang->cmd_get_temp_password = 'Geçici bir şifre edin';
	$lang->about_get_temp_password = 'Giriş yaptıktan sonra şifrenizi değiştirin.';
	$lang->msg_question_not_exists = 'Geçici şifre için güvenlik sorusunu belirlemediniz.';
	$lang->msg_answer_not_matches = 'Soru için verdiğiniz cevap doğru değil.';

	$lang->change_password_date = 'Şifre yenileme döngüsü';
	$lang->about_change_password_date = 'Eğer buna bir değer biçerseniz, şifrenizi belirli aralıklarla değiştirmeniz konusunda bildirim alacaksanız (eğer 0 olarak ayarlandıysa, devredışıdır)';
?>
