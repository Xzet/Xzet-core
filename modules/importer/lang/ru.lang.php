<?php
  /**
     * @file   ru.lang.php
     * @author NHN (developers@xpressengine.com) | translation by Maslennikov Evgeny aka X-[Vr]bL1s5 | e-mail: x-bliss[a]tut.by; ICQ: 225035467;
     * @brief  Russian basic language pack
     **/

    // слова для кнопок
    $lang->cmd_sync_member = 'Синхронизировать';
    $lang->cmd_continue = 'Продолжить';
    $lang->preprocessing = 'Идет подготовка для импортирования данных...';

    // объекты
    $lang->importer = 'Импортировать данные zeroboard';
    $lang->source_type = 'Предыдущее назначение';
    $lang->type_member = 'Данные пользователей';
    $lang->type_message = 'Данные сообщения';
    $lang->type_ttxml = 'TTXML';
    $lang->type_module = 'Данные статьи';
    $lang->type_syncmember = 'Синхронизировать данные пользователей';
    $lang->target_module = 'Модуль назначения';
    $lang->xml_file = 'XML файл';

    $lang->import_step_title = array(
        1 => 'Шаг 1. Выберите предыдущее назначение',
        12 => 'Шаг 1-2. Выберите модуль назначения',
        13 => 'Шаг 1-3. Выберите категорию назначения',
        2 => 'Шаг 2. Загрузить XML файл',
        3 => 'Шаг 2. Синхронизировать данные пользователей и статей',
        99 => 'Импортировать данные',
    );

    $lang->import_step_desc = array(
        1 => 'Пожалуйста, выберите тип XML файла, который Вы хотите импортировать.',
        12 => 'Пожалуйста, выберите модуль, в который Вы хотите импортировать данные.',
        121 => 'запись:',
        122 => 'посетители:',
        13 => 'Пожалуйста, выберите категорию назначения, в которую Вы хотите импортировать данные.',
        2 => "Пожайлуста, введите расположение XML файла, который Вы хотите импортировать.\nЕсли он находится в одном аккаунте, то введите абсолютный/относительный путь. Если нет, то введите URL, начинающийся с http://...",
        3 => 'Данные пользователей и статей могут быть некорректны после импорта. В таком случае, выполните синхронизацию для восстановления, основанного на user_id.',
        99 => 'Идет процесс импортирования данных',
    );

    // гид/алерт
    $lang->msg_sync_member = 'Синхронизация данных пользователей и статей начнется по нажатию книпоки "Синхронизировать".';
    $lang->msg_no_xml_file = 'XML файл не найден. Пожалуйста, проверьте путь еще раз';
    $lang->msg_invalid_xml_file = 'Неверный тип XML файла.';
    $lang->msg_importing = 'Пишем %d данные %d. (Если процесс "завис", нажмите кнопку "Продолжить")';
    $lang->msg_import_finished = '%d/%d данные были поностью импортированы. В зависимости от ситуации, некоторые данные могут не быть импортированы.';
    $lang->msg_sync_completed = 'Выполнена синхронизация пользователей, статей и комментариев.';

    // blah blah.. чепуха)
    $lang->about_type_member = 'Если Вы импортируете информацию пользователей, выберите эту опцию';
    $lang->about_type_message = 'Выберите, если импортируются данные сообщения';
    $lang->about_type_ttxml = 'Выберите, если импортируются данные TTXML';
    $lang->about_ttxml_user_id = 'Введите ID указанного пользователя, сделавшего запись при импортировании данных TTXML. (Пользователь должен быть зарегистрирован)';
    $lang->about_type_module = 'Если Вы импортируете информацию форума или записей, выберите эту опцию';
    $lang->about_type_syncmember = 'Если Вы пытаетесь синхронизировать информацию пользователей после импорта информации пользователей и записей, выберите эту опцию';
    $lang->about_importer = "Вы можете импортировать данные Zeroboard4, Zeroboard5 Beta или других программ в XE.\nЧтобы импортировать, Вам следует использовать <a href=\"https://github.com/xpressengine/xe-migration-tool/\" onclick=\"winopen(this.href);return false;\">XML Экспортер (XML Exporter)</a>, чтобы конвертировать нужные данные в XML Файл и затем загрузить его.";

    $lang->about_target_path = "Чтобы получить вложения с Zeroboard4, пожалуйста, введите адрес, где установлена  Zeroboard4.\nЕсли она раположена на том же сервере, введите путь к Zeroboard4 как /home/USERID/public_html/bbs\nЕсли нет, введите адрес, где Zeroboard4 установлена. Например: http://Domain/bbs";
?>
