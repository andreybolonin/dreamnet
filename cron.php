<?php

require __DIR__.'/include.php';
require 'routeros_api.php';
require __DIR__.'/sms/mainsms.class.php';

$dsn = DBTYPE.':dbname='.DBNAME.';host='.DBHOST;
$db = new PDO($dsn, DBLOGIN, DBPASS);

$API = new routeros_api();
$SMS = new MainSMS(SMSLOGIN, SMSPASS);

$API->debug = false;

foreach ($routers as $key => $router) {
    if ($API->connect($router['ip'], $router['login'], $router['pass'])) {
        /*
         * Удаляем пользователей, у которых закончилось время.
         */

        $sql = 'SELECT `id`, `user`
            FROM `dreamnet`
            WHERE `endtime` < ?
                AND `status` != ?
                AND `status` != ?
                AND `com` = ?';
        $stf = $db->prepare($sql);
        $stf->execute(array(date('Y:m:d H:s:i'), 0, 5, $key));
        $rows = $stf->fetchAll();
        $rows_for_delete = array();
        $counter = array();
        foreach ($rows as $user) {
            $BRIDGEINFO = $API->comm('/ip/hotspot/user/print', array(
                '.proplist' => '.id',
                '?name' => $user['user'],
            ));
            $API->comm('/ip/hotspot/user/remove', array(
                '.id' => $BRIDGEINFO[0]['.id'],
            ));

            /*
             * Логируем
             */

            $sql = 'INSERT INTO `dreamnet_log` (`date`, `user`, `type`, `status`) VALUES (NOW(), ?, ?, ?)';
            $stf = $db->prepare($sql);
            $stf->execute(array(
                $user['user'],
                'delete',
                'OK',
            ));

            $sms_success = $SMS->sendSMS($user['user'], parseSMS(DELETETEXT, array('user' => $user['user'])), SMSSENDER);

            $rows_for_delete[] = $user['id'];
            $counter[] = '?';
        }

        if (!empty($rows_for_delete)) {
            $update_sql = 'UPDATE dreamnet SET `status` = ? WHERE `id` IN ('.implode(', ', $counter).')';
            $stf = $db->prepare($update_sql);
            $stf->execute(array_merge(array(0), $rows_for_delete));
        }

        echo '***********************************************';
        echo "<h3>Router {$router['ip']}</h3>";
        echo 'Users deleted from router '.$router['ip'].':';
        echo '<pre>'.print_r($rows, true).'</pre>';

        /*
         * Добавяем новых поьзователей
         */

        $new_sql = 'SELECT `user`, `date`, `endtime` FROM dreamnet WHERE `status` = ? AND `com` = ?';
        $stf = $db->prepare($new_sql);
        $stf->execute(array(60, $key));
        $rows = $stf->fetchAll();
        $add_users = array();

        foreach ($rows as $user) {
            $name = $user['user'];
            $pass = substr(md5(time()), 0, 5);
            $API->comm('/ip/hotspot/user/add', array(
                'name' => $name,
                'password' => $pass,
                'profile' => 'tariff_1',
                'comment' => "{$user['user']} {$user['date']}",
            ));

            $add_users[] = array(
                'name' => $name,
                'pass' => $pass,
            );
            $sms_params = array(
                'user' => $name,
                'pass' => $pass,
                'endtime' => $user['endtime'],
            );
            $BRIDGEINFO = $API->comm('/ip/hotspot/user/print', array(
                '.proplist' => '.id',
                '?name' => $name,
            ));

            $log_status = 'OK';

            if (empty($BRIDGEINFO)) {
                $sql = 'UPDATE `dreamnet` SET `status` = ? WHERE `id` = ?';
                $stf = $db->prepare($sql);
                $stf->execute(array(75, $user['id']));
                $log_status = 'FAILED';
            }

            /*
             * Логируем
             */
            $sql = 'INSERT INTO `dreamnet_log` (`date`, `user`, `type`, `status`) VALUES (NOW(), ?, ?, ?)';
            $stf = $db->prepare($sql);
            $stf->execute(array(
                $name,
                'add',
                $log_status,
            ));
            if ($log_status == 'FAILED') {
                continue;
            }

            $sms_text = parseSMS(ADDTEXT, $sms_params);
            $sms_success = $SMS->sendSMS($name, $sms_text, SMSSENDER);
            if ($sms_success) {
                echo "<br />SMS for {$user['user']} is sent<br />";
                echo "SMS Text: $sms_text<br />";
            }
        }

        $update_sql = 'UPDATE dreamnet SET `status` = ? WHERE `status` = ? AND `com` = ?';
        $stf = $db->prepare($update_sql);
        $stf->execute(array(100, 60, $key));

        echo 'Users added to router '.$router['ip'];
        echo '<pre>'.print_r($add_users, true).'</pre>';
        echo '***********************************************<br />';

        /*$API->comm('/ip/hotspot/user/remove', array(
        ".id"=>$BRIDGEINFO[0]['.id'],
    ));*/

        //$users = $API->comm('/ip/hotspot/user/getall', array());
        //var_dump($users);

        $API->disconnect();

        //var_dump($users, $BRIDGEINFO);
    } else {
        echo 'can\'t connect!';
    }
}
$update_sql = 'UPDATE dreamnet SET `status` = ? WHERE `status` = ?';
$stf = $db->prepare($update_sql);
$stf->execute(array(60, 75));
