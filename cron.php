<?php
require (__DIR__ . '/config.php');
require('routeros_api.php');
require __DIR__ . '/sms/mainsms.class.php';

function parseSMS($text, $params)
{
    foreach ($params as $key => $param) {
        $text = str_replace('{%' . $key . '%}', $param, $text);
    }

    return $text;
}

$dsn = DBTYPE . ":dbname=" . DBNAME . ";host=" . DBHOST;
$db = new PDO($dsn, DBLOGIN, DBPASS);

$API = new routeros_api();
$SMS = new MainSMS(SMSLOGIN, SMSPASS);

$API->debug = false;

foreach ($routers as $key => $router) {

    if ($API->connect($router['ip'], $router['login'], $router['pass'])) {
        /**
         * Удаляем пользователей, у которых закончилось время.
         */

        $sql = "SELECT `id`, `user`
            FROM `dreamnet`
            WHERE `endtime` < ?
                AND `status` != ?
                AND `status` != ?
                AND `com` = ?";
        $stf = $db->prepare($sql);
        $stf->execute(array(date('Y:m:d H:s:i'), 0, 5, $key));
        $rows = $stf->fetchAll();
        $rows_for_delete = array();
        $counter = array();
        foreach ($rows as $user) {
            $BRIDGEINFO = $API->comm('/ip/hotspot/user/print', array(
                ".proplist" => ".id",
                "?name" =>$user['user']
            ));
            $API->comm('/ip/hotspot/user/remove', array(
                ".id"=>$BRIDGEINFO[0]['.id'],
            ));

            $sms_success = $SMS->sendSMS($user['user'], parseSMS(ADDTEXT, array('user' => $user['user'])), SMSSENDER);

            $rows_for_delete[] = $user['id'];
            $counter[] = '?';
        }

        if (!empty($rows_for_delete)) {

            $update_sql = "UPDATE dreamnet SET `status` = ? WHERE `id` IN (" . implode(', ', $counter) . ")";
            $stf = $db->prepare($update_sql);
            $stf->execute(array_merge(array(0), $rows_for_delete));
        }

        echo "***********************************************";
        echo "<h3>Роутер {$router['ip']}</h3>";
        echo "Польователи, удаленные с роутера " . $router['ip'] . ":";
        var_dump($rows);

        /**
         * Добавяем новых поьзователей
         */

        $new_sql =  "SELECT `user`, `date` FROM dreamnet WHERE `status` = ? AND `com` = ?";
        $stf = $db->prepare($new_sql);
        $stf->execute(array(60, $key));
        $rows = $stf->fetchAll();
        $add_users = array();

        foreach ($rows as $user) {
            $name = $user['user'];
            $pass = substr(md5(time()), 0, 5);
            $API->comm("/ip/hotspot/user/add", array(
                "name"     => $name,
                "password" => $pass,
                "profile" => "tariff_1",
                "comment"  => "{$user['user']} {$user['date']}",
            ));
            $add_users[] = array(
                'name' => $name,
                'pass' => $pass
            );
            $sms_success = $SMS->sendSMS(parseSMS(ADDTEXT, array('user' => $name, 'pass' => $pass)), SMSSENDER);
            if ($sms_success) {
                echo "<br />SMS for {$user['user']} is sent<br />>";
            }
        }

        $update_sql = "UPDATE dreamnet SET `status` = ? WHERE `status` = ? AND `com` = ?";
        $stf = $db->prepare($update_sql);
        $stf->execute(array(100, 60, $key));

        echo "Польователи добавленные на роутер " . $router['ip'];
        var_dump($add_users);
        echo "***********************************************<br />";





        /*$API->comm('/ip/hotspot/user/remove', array(
        ".id"=>$BRIDGEINFO[0]['.id'],
    ));*/

       // $users = $API->comm('/ip/hotspot/user/getall', array());


        $API->disconnect();

        //var_dump($users, $BRIDGEINFO);

    } else {
        echo 'can\'t connect!';
    }
}

