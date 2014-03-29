<?php
    /**
     * @file   modules/opage/lang/zh-TW.lang.php
     * @author NHN (developers@xpressengine.com) 翻譯：royallin
     * @brief  外部頁面(opage)模組正體中文語言
     **/

    $lang->opage = "外部頁面";
    $lang->opage_path = "外部頁面路徑";
    $lang->opage_caching_interval = "暫存時間設置";

    $lang->about_opage = "此模組可把外部HTML或PHP檔案插入到XE中的模組。<br />可以使用絕對路徑及相對路徑。而且使用以『http://』開頭的網址時，可以把外部主機中的頁面插入到XE當中。";
    $lang->about_opage_path= "請輸入外部檔案路徑。<br />可以使用像『/path1/path2/sample.php』的絕對路徑或是『../path2/sample.php』的相對路徑。<br />如果是用『http://url/sample.php』之類的網址，會將此頁面的結果顯示到XE內部的指定位置。<br />目前安裝的XE絕對路徑如下:<br />";
    $lang->about_opage_caching_interval = "單位為分。暫存時間內頁面將輸出臨時儲存的資料。<br />輸出外部主機訊息或資料時，如消耗資源很大，盡量把暫存時間設大一點。<br />『0』表示不暫存。";
	$lang->opage_mobile_path = 'Location of External Document for Mobile View';
    $lang->about_opage_mobile_path= "Please input the location of external document for mobile view. If not inputted, it uses the the external document specified above.<br />Both absolute path such as '/path1/path2/sample.php' or relative path such as '../path2/sample.php' can be used.<br />If you input the path like 'http://url/sample.php' , the result will be received and then displayed.<br />This is current XE's absolute path.<br />";
?>
