<?php
include_once ('./config.php');
$dsn = DBTYPE . ":dbname=" . DBNAME . ";host=" . DBHOST;
$db = new PDO($dsn, DBLOGIN, DBPASS);

$routers = array(
    'WiFi:kalininec' => array(
        'from' => '3747',
        'value' => 'Калининец'
    ),
    'WiFi:zvenigorod' => array(
        'from' => '3977',
        'value' => 'Звенигород'
    ),
    'WiFi:yakovlevskoe' => array(
        'from' => '4321',
        'value' => 'Яковлевское'
    ),
    'WiFi:selyatino' => array(
        'from' => '3747',
        'value' => 'Селятино'
    ),
    'WiFi:aprelevka' => array(
        'from' => '3747',
        'value' => 'Апрелевка'
    ),
    'WiFi:naro-fominsk' => array(
        'from' => '4321',
        'value' => 'Наро-Фоминск'
    ),
);

function checkTest($phone) {
    global $db;
    $sql = "SELECT `id`, `date` FROM `testaccounts` WHERE `phone` = ?";
    $stf = $db->prepare($sql);
    $stf->execute(array($phone));
    $tests = $stf->fetchAll();

    if (!empty($tests)) {
        return false;
    }

    $sql = "INSERT INTO `testaccounts` (`phone`, `date`) VALUES (?, ?)";
    $stf = $db->prepare($sql);
    $stf->execute(array(
        $phone,
        date('Y-m-d H:i:s')
    ));

    $sql = "INSERT INTO `dreamnet` (`user`, `ammount`, `date`, `status`, `endtime`, `com`) VALUES (?, ?, ?, ?, ?, ?)";
    $stf = $db->prepare($sql);
    $stf->execute(array(
        $phone,
        0,
        date('Y-m-d H:i:s'),
        60,
        date('Y-m-d H:i:s', time()+900),
        $_REQUEST['com']
    ));

    return true;

}
if ($_REQUEST['com'] && $_REQUEST['to']) {

    //Если заказан тестовый доступ
    if (!(int)$_REQUEST['amount_rub']) {
        if (!checkTest($_REQUEST['to'])) {
            include ('error.php');
            exit;
        }

        include ('test_ok.php');
        exit;
    }
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