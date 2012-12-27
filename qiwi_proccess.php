<?php

if ($_REQUEST['com'] && $_REQUEST['to']) {
    $dsn = 'mysql:dbname=znachok_test;host=znachok.mysql.ukraine.com.ua';
    $db = new PDO($dsn, 'znachok_test', '3dqkhnz5');

    $sql = "SELECT `id`, `status` FROM `dreamnet` WHERE `status` > ? AND `user` = ?";
    $stf = $db->prepare($sql);
    $stf->execute(array(0, $_REQUEST['to']));

    $pays = $stf->fetchAll();

    foreach ($pays as $pay) {
        if ($pay['status'] == 5) {
            exit;
        }
    }
    $sql = "INSERT INTO `dreamnet` (`user`, `com`, `status`) VALUES (?, ?, ?)";
    $stf = $db->prepare($sql);
    $stf->execute(array($_REQUEST['to'], $_REQUEST['com'], 5));
    include_once('./qiwi-form.php');




}