<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 11.01.13
 * Time: 10:26
 * To change this template use File | Settings | File Templates.
 */

$ip = '94.228.205.2';
$login = 'hotspot';
$pass = "hotspot1234";

require('routeros_api.php');
$API = new routeros_api();
$API->debug = false;

if ($API->connect($ip, $login, $pass)) {
    echo "OK!!!";
    $API->disconnect();
} else {
    echo "can't connect";
}