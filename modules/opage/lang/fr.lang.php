<?php
    /**
     * @file   fr.lang.php
     * @author NHN (developers@xpressengine.com) Traduit par PierreDuvent (PierreDuvent@gmail.com)
     * @brief  Paque du langage en français pour le module de Page Extérieure
     **/

    $lang->opage = "Page Extérieure";
    $lang->opage_path = "Localisation du Document Extérieur";
    $lang->opage_caching_interval = "Temps de antémémoire";

    $lang->about_opage = "Ce module vous fait pouvoir utiliser des documents extérieurs en html ou en php dans XE.<br />Il est possible d'utiliser le chemin absolu ou relatif, et si l'URL commence avec 'http://' , il est possible de représenter des pages extérieurs du serveur.";
    $lang->about_opage_path= "Entrez la localisation du document extérieur.<br />Non seulement le chemin absolu comme '/path1/path2/sample.php' mais aussi le chemin relatif comme '../path2/sample.php' peuvent être utilisés.<br />Si vous entrez le chemin comme 'http://url/sample.php', le résultat sera reçu et puis exposé<br />Le chemin suivant, c'est le chemin absolu de XE.<br />";
    $lang->about_opage_caching_interval = "L'unité est minute, et ça exposera des données conservées temporairement pendant le temps assigné.<br />Il est recommandé d'utiliser l'antémémoire pendant le temps convenable si beaucoup de ressource est nécessaire pour représenter les données ou l'information d'autre serveur.<br />La valeur 0 signifie de ne pas utiliser antémémoire.";
	$lang->opage_mobile_path = 'Location of External Document for Mobile View';
    $lang->about_opage_mobile_path= "Please input the location of external document for mobile view. If not inputted, it uses the the external document specified above.<br />Both absolute path such as '/path1/path2/sample.php' or relative path such as '../path2/sample.php' can be used.<br />If you input the path like 'http://url/sample.php' , the result will be received and then displayed.<br />This is current XE's absolute path.<br />";
?>
