<?php
require_once ('./routeros_api.php');

$API = new routeros_api();

$API->debug = false;

if ($API->connect('94.228.205.2', 'hotspot', 'hotspot1234')) {

    $API->write('/user/getall');

    $READ = $API->read(false);
    $ARRAY = $API->parse_response($READ);

    var_dump($ARRAY);

    $API->disconnect();

}