<?php
require('routeros_api.php');

$API = new routeros_api();

$API->debug = false;

if ($API->connect('94.228.205.2', 'hotspot', 'hotspot1234')) {


    $BRIDGEINFO = $API->comm('/ip/hotspot/user/print', array(
        ".proplist" => ".id",
        "?name" => "usertest"
    ));

    /*$API->comm('/ip/hotspot/user/remove', array(
        ".id"=>$BRIDGEINFO[0]['.id'],
    ));*/

    $users = $API->comm('/ip/hotspot/user/getall', array());


    $API->disconnect();

    var_dump($users, $BRIDGEINFO);

} else {
    echo 'can\'t connect!';
}

