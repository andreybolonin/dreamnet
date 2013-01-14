<?php
require (__DIR__ . '/include.php');
require('routeros_api.php');
require __DIR__ . '/sms/mainsms.class.php';

$errors = array();
$user_value = "";
$end_date_value = "";
$router_value = "";
if (isset ($_POST['send'])) {

    $API = new routeros_api();
    $SMS = new MainSMS(SMSLOGIN, SMSPASS);

    $API->debug = false;

    $router_info = $routers[$_POST['router']];

    if ($API->connect($router_info['ip'], $router_info['login'], $router_info['pass'])) {
        /**
         * Проверяем, есть ли такой пользователь на роутере
         */
        $BRIDGEINFO = $API->comm('/ip/hotspot/user/print', array(
            ".proplist" => ".id",
            "?name" =>$_POST['user']
        ));
        if (!empty($BRIDGEINFO)) {
            $errors[] = "Такой пользователь уже есть на этом роутере";
        }

        /**
         * Если все хорошо - добавляем пользователя
         */

        if (empty($errors)) {
            $time = $tarifs[$_POST['date']];
            $end_time = time() + $time;
            $name = $_POST['user'];
            $pass = substr(md5(time()), 0, 5);
            $API->comm("/ip/hotspot/user/add", array(
                "name"     => $name,
                "password" => $pass,
                "profile" => "tariff_1",
                "comment"  => "{$_POST['user']} " . date('Y-m-d H:i:s'),
            ));
            //шлем смс
            $add_users[] = array(
                'name' => $name,
                'pass' => $pass
            );
            $sms_params = array(
                'user' => $name,
                'pass' => $pass,
                'endtime' => date('Y-m-d H:i:s', $end_time)
            );
            $sms_text = parseSMS(ADDTEXT, $sms_params);
            $sms_success = $SMS->sendSMS($name, $sms_text, SMSSENDER);

            //Если в базе есть такой оплативший пользователь - обновляем его, если нету - добавляем

            $sql = "SELECT `id` FROM `dreamnet` WHERE `user` = ? AND `status`=? AND `com` = ?";
            $stf = $db->prepare($sql);
            $stf->execute(array($name, 60, $_POST['router']));
            $users = $stf->fetchAll();

            if (empty($users)) {
                $sql = "INSERT INTO `dreamnet` (`user`, `ammount`, `date`, `status`, `endtime`, `com`) VALUES (?, ?, ?, ?, ?, ?)";
                $stf = $db->prepare($sql);
                $stf->execute(array(
                    $name,
                    $_POST['date'],
                    date('Y-m-d H:i:s'),
                    100,
                    date('Y-m-d H:i:s', $end_time),
                    $_POST['router']
                ));
            } else {
                $sql = "UPDATE `dreamnet` SET
                    `status` = ?,
                    `endtime` = ?
                    WHERE `id` = ?";
                $stf = $db->prepare($sql);
                $stf->execute(array(
                    100,
                    date('Y-m-d H:i:s', $end_time),
                    $users[0]['id']
                ));
            }
        }

    }else {
        $errors[] = "Не могу подключится к роутеру";
    }

    if (!empty ($errors)) {
        $user_value = $_POST['user'];
        $end_date_value = $_POST['date'];
        $router_value = $_POST['router'];
    }
}

?>
<html>
<head>
    <title>Добавить пользоватея на роутер</title>
</head>
<body>
<?php if (!empty($errors)) : ?>
    <h3>Ошибки</h3>
    <ul>
    <?php foreach ($errors as $error) : ?>
        <li><?php echo $error; ?></li>
    <?php endforeach;?>
    </ul>
<?php endif;?>
<form action="" method="post">
    <table>
        <tr>
            <td>Введите имя пользователя (телефон)</td>
            <td><input type="text" name="user" value="<?php echo $user_value?>" /></td>
        </tr>
        <tr>
            <td>Тариф</td>
            <td>
                <select name="date">
                    <?php foreach ($tarif_names as $key => $name) : ?>
                        <?php $selected = ($key == $end_date_value) ? " selectd='selected'" : ''?>
                        <option value="<?php echo $key?>"<?php echo $selected?>><?php echo $name?></option>
                    <?php endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Роутер</td>
            <td>
                <select name="router">
                    <?php foreach ($routers as $key => $router) : ?>
                    <?php $selected = ($key ==$router_value) ? " selected='slected'" : '' ?>
                    <option value="<?php echo $key?>"<?php echo $selected?>><?php echo $key?></option>
                    <?php endforeach;?>
                </select>
            </td>
        </tr>
    </table>
    <input type="submit" name="send" value="Add" />
</form>
</body>
</html>