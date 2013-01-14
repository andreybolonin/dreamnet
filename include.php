<?php
require (__DIR__ . '/config.php');

function parseSMS($text, $params)
{
    foreach ($params as $key => $param) {
        $text = str_replace('{%' . $key . '%}', $param, $text);
    }

    return $text;
}


$dsn = DBTYPE . ":dbname=" . DBNAME . ";host=" . DBHOST;
$db = new PDO($dsn, DBLOGIN, DBPASS);