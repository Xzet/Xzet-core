<?php
  /**
     * @file   ru.lang.php
     * @author NHN (developers@xpressengine.com) | translation by Maslennikov Evgeny aka X-[Vr]bL1s5 | e-mail: x-bliss[a]tut.by; ICQ: 225035467;
     * @brief  Russian basic language pack
     **/

    $lang->virtual_site = "Virtual Site";
    $lang->module_list = "Список модулей";
    $lang->module_index = "Список модулей";
    $lang->module_category = "Категория модуля";
    $lang->module_info = "Информация";
    $lang->add_shortcut = "Добавить ярлыки";
    $lang->module_action = "Действия";
    $lang->module_maker = "Разработчик модуля";
    $lang->module_license = 'Лицензия';
    $lang->module_history = "История обновлений";
    $lang->category_title = "Название категории";
    $lang->header_text = 'Верхний колонтитул';
    $lang->footer_text = 'Нижний колонтитул';
    $lang->use_category = 'Включить категорию';
    $lang->category_title = 'Название категории';
    $lang->checked_count = 'Число выбранных статей'; // translator's note: возможно "checked" следует перевести как "проверенных"
    $lang->skin_default_info = 'Информация стандартного скина';
    $lang->skin_author = 'Разработчик скина';
    $lang->skin_license = 'License';
    $lang->skin_history = 'История обновлений';
    $lang->module_selector = "Module Selector";
    $lang->do_selected = "Выбранные...";
    $lang->bundle_setup = "일괄 기본 설정";
    $lang->bundle_addition_setup = "일괄 추가 설정";
    $lang->bundle_grant_setup = "일괄 권한 설정";
    $lang->lang_code = "Код языка";
    $lang->filebox = "Файлбокс";

    $lang->access_type = 'Способ соединения';
    $lang->access_domain = 'Domain соединения';
    $lang->access_vid = 'Site ID соединение';
    $lang->about_vid = '별도의 도메인이 아닌 http://XE주소/ID 로 접속할 수 있습니다. 모듈명(mid)와 중복될 수 없습니다.<br/>첫글자는 영문으로 시작해야 하고 영문과 숫자 그리고 _ 만 사용할 수 있습니다';
    $lang->msg_already_registed_vid = '이미 등록된 사이트 ID 입니다. 게시판등의 mid와도 중복이 되지 않습니다. 다른 ID를 입력해주세요.';
    $lang->msg_already_registed_domain = '이미 등록된 도메인입니다. 다른 도메인을 사용해주세요';

    $lang->module_copy = "Копировать модуль";

    $lang->header_script = "Скрипт Header";
    $lang->about_header_script = "html의 &lt;header&gt;와 &lt;/header&gt; 사이에 들어가는 코드를 직접 입력할 수 있습니다.<br />&lt;script, &lt;style 또는 &lt;meta 태그등을 이용하실 수 있습니다";

    $lang->grant_access = "Access";
    $lang->grant_manager = "Management";

    $lang->grant_to_all = "All users";
    $lang->grant_to_login_user = "Logged users";
    $lang->grant_to_site_user = "Joined users";
    $lang->grant_to_group = "Specification group users";

    $lang->cmd_add_shortcut = "Добавить ярлык";
    $lang->cmd_install = "Установить";
    $lang->cmd_update = "Обновить";
    $lang->cmd_manage_category = 'Управление категориями';
    $lang->cmd_manage_grant = 'Управление правами доступа';
    $lang->cmd_manage_skin = 'Управление скинами';
    $lang->cmd_manage_document = 'Управление статьями';
    $lang->cmd_find_module = 'Искать модуль';
    $lang->cmd_find_langcode = 'Find lang code';

    $lang->msg_new_module = "Создать новый модуль";
    $lang->msg_update_module = "Изменить модуль";
    $lang->msg_module_name_exists = "Имя уже существует. Пожалуйста, попробуйте другое";
    $lang->msg_category_is_null = 'Зарегистрированной категории не существует.';
    $lang->msg_grant_is_null = 'Списка для управления правами доступа не существует.';
    $lang->msg_no_checked_document = 'Нет выбранных статей.'; // translator's note: выше...
    $lang->msg_move_failed = 'Невозможно переместить';
    $lang->msg_cannot_delete_for_child = 'Невозможно удалить категорию, имеющую дочерние категории.';
    $lang->msg_limit_mid ='모듈이름은 영문+[영문+숫자+_] 만 가능합니다.';
    $lang->msg_extra_name_exists = '이미 존재하는 확장변수 이름입니다. 다른 이름을 입력해주세요.';

    $lang->about_browser_title = "Это будет показано в заголовке браузера. Также, это будет использоваться в RSS/Трекбеке.";
    $lang->about_mid = "Имя модуля будет использовано как http://address/?mid=Имя_модуля.\n(только латиница, цифры и символ подчеркивания(_) разрешены. The maximum length is 40.)";
    $lang->about_default = "Если выбрано, модуль будет главным на сайте. Для доступа не нужен будет идентификатор модуля.";
    $lang->about_module_category = "Это позволяет Вам управлять посредством категорий модулей.\nURL для менеджера модулей <a href=\"./?module=admin&amp;act=dispModuleAdminCategory\">Manage module > Категория Модуля </a>.";
    $lang->about_description= 'Это описание только для менеджера.';
    $lang->about_default = 'Если выбрано, этот модуль будет показан, когда пользователи входят на сайт без идентификатора модуля (mid=NoValue).';
    $lang->about_header_text = 'Это содержимое будет показано сверху модуля. (HTML разрешен)';
    $lang->about_footer_text = 'Это содержимое будет показано снизу модуля. (HTML разрешен)';
    $lang->about_skin = 'Вы можете выбрать скин модуля.';
    $lang->about_use_category = 'Если выбрано, функция категорий будет включена.';
    $lang->about_list_count = 'Вы можете установить лимит показа статей на страницу. (по умолчанию: 20)';
    $lang->about_search_list_count = '검색 또는 카테고리 선택등을 할 경우 표시될 글의 수를 지정하실 수 있습니다. 기본(20개)';
    $lang->about_page_count = 'Вы можете установить число страниц внизу. (по умолчанию: 10)';
    $lang->about_admin_id = 'Вы можете разрешить менеджеру иметь полные права доступа к этому модулю.\nВы можете ввести несколько ID, используя ';
    $lang->about_grant = 'Если Вы отключите все права доступа для отдельного объекта, не прошедшие процедуру входа на сайт пользователи получат доступ.';
    $lang->about_grant_deatil = '가입한 사용자는 cafeXE등 분양형 가상 사이트에 가입을 한 로그인 사용자를 의미합니다';
    $lang->about_module = "XE состоит из модулей, за исключением базовой библиотеки.\n Управление модулем покажет все установленные модули и поможет управлять ими.";

	$lang->about_extra_vars_default_value = 'Если нужно несколько значений по умолчанию, разделите их запятыми(,).';
    $lang->about_search_virtual_site = "가상 사이트(카페XE등)의 도메인을 입력하신 후 검색하세요.<br/>가상 사이트이외의 모듈은 내용을 비우고 검색하시면 됩니다.  (http:// 는 제외)";
    $lang->about_langcode = "언어별로 다르게 설정하고 싶으시면 언어코드 찾기를 이용해주세요";
    $lang->about_file_extension= "%s 파일만 가능합니다.";
?>
