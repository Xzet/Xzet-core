<?php
    /**
     * @file   modules/point/lang/zh-TW.lang.php
     * @author NHN (developers@xpressengine.com) 翻譯：royallin
     * @brief  點數(point)模組正體中文語言
     **/

    $lang->point = "點數"; 
    $lang->level = "等級"; 

    $lang->about_point_module = "點數系統可以在發表/刪除主題，發表/刪除評論，上傳/下載/刪除/檔案等動作時，付出相對應的點數。";
    $lang->about_act_config = "討論板，部落格等模組都有發表/刪除主題，發表/刪除評論等動作。 <br />想要與討論板/部落格之外的模組關聯點數功能時，新增與其各模組功能適合的act值即可。";

    $lang->max_level = '最高等級';
    $lang->about_max_level = '可以指定最高等級。等級共設1000級，因此製作等級圖示時要好好考慮一下。';

    $lang->level_icon = '等級圖示';
    $lang->about_level_icon = '等級圖示格式與位置 ./modules/point/icons/等級.gif，請注意，有時出現最高等級的圖示跟您指定的最高等級圖示不同的現象。';

    $lang->point_name = '點數名稱';
    $lang->about_point_name = '可指定點數名稱或點數單位。';

    $lang->level_point = '等級點數';
    $lang->about_level_point = '點數達到或減少到下列各等級所設置的點數時，將會自動調節相對應等級。';

    $lang->disable_download = '禁止下載';
    $lang->about_disable_download = '沒有點數時，將禁止下載。(圖片除外)';
    $lang->disable_read_document = '禁止閱讀文章';
    $lang->about_disable_read_document = '沒有點數時，將禁止閱讀文章。';

    $lang->level_point_calc = '計算等級點數';
    $lang->expression = '使用等級變數<b>"i"</b>輸入JS數學函數。例: Math.pow(i,2) * 90';
    $lang->cmd_exp_calc = '計算';
    $lang->cmd_exp_reset = '重置';

    $lang->cmd_point_recal = '重置點數';
    $lang->about_cmd_point_recal = '重置點數。即只保留文章/評論/附加檔案/新會員註冊的相關點數項目。<br />其中，重置後的新會員註冊點數，將在會員有相關動作(例如:發表主題/評論等)時，才付與其相對應的點數。<br />此項功能請務必慎用！此項功能只能在資料轉移或真的需要重置所有點數時才可以使用。';

    $lang->point_link_group = '自動升級';
    $lang->point_group_reset_and_add = '重新調整與新增群組';
    $lang->point_group_add_only = '只限新群組';
    $lang->about_point_link_group = '即群組隨等級變化。當等級達到指定等級時，會員所屬群組將自動更新成相對應的群組。';

    $lang->about_module_point = '可以分別對各模組進行點數設置，沒有設置的模組將使用預設值。<br />所有動作在反向操作下將恢復原始值。即：發表主題後再刪除得到的點數為零。';

    $lang->point_signup = '註冊';
    $lang->point_insert_document = '發表主題';
    $lang->point_delete_document = '刪除主題';
    $lang->point_insert_comment = '發表評論';
    $lang->point_delete_comment = '刪除評論';
    $lang->point_upload_file = '上傳檔案';
    $lang->point_delete_file = '刪除檔案';
    $lang->point_download_file = '下載檔案 (圖片除外)';
    $lang->point_read_document = '檢視主題';
    $lang->point_voted = '推薦';
    $lang->point_blamed = '反對';


    $lang->cmd_point_config = '基本設置';
    $lang->cmd_point_module_config = '目標模組設置';
    $lang->cmd_point_act_config = 'Act設置';
    $lang->cmd_point_member_list = '會員點數列表';

    $lang->msg_cannot_download = '點數不足無法下載！';
    $lang->msg_disallow_by_point = "點數不足無法閱讀文章 (需要 : %d, 目前 : %d)";

    $lang->point_recal_message = '計算並套用中(%d / %d)。';
    $lang->point_recal_finished = '點數重新計算並套用完畢。';
?>
