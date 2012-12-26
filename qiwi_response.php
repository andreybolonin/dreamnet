<?php
/**
 * Ќа этот скрипт приход€т уведомлени€ от QIWI  ошелька.
 * SoapServer парсит вход€щий SOAP-запрос, извлекает значени€ тегов login, password, txn, status,
 * помещает их в объект класса Param и вызывает функцию updateBill объекта класса TestServer.
 *
 * Ћогика обработки магазином уведомлени€ должна быть в updateBill.
 */

require_once ('./qiwi/IShopServerWSService.php');

define('LOGIN', 4321);
define('PASSWORD', 'yfljtkbrjvfhs');

define('TRACE', 1);

$s = new SoapServer('./qiwi/IShopClientWS.wsdl', array('classmap' => array('tns:updateBill' => 'Param', 'tns:updateBillResponse' => 'Response')));
// $s = new SoapServer('IShopClientWS.wsdl');
$s->setClass('TestServer');
$s->handle();

class Response {
    public $updateBillResult;
}

class Param {
    public $login;
    public $password;
    public $txn;
    public $status;
}

class TestServer {
    function updateBill($param) {

        // ¬ыводим все прин€тые параметры в качестве примера и дл€ отладки
        /*$f = fopen(__DIR__ . '/result.txt', 'w');
        fwrite($f, print_r($param, true));
        fclose($f);*/


        // проверить password, login

        // ¬ зависимости от статуса счета $param->status мен€ем статус заказа в магазине
        if ($param->status == 60) {

            // заказ оплачен
            // найти заказ по номеру счета ($param->txn), пометить как оплаченный

            $service = new IShopServerWSService('./qiwi/IShopServerWS.wsdl', array('location'      => 'http://ishop.qiwi.ru/services/ishop', 'trace' => TRACE));


            $checkBillParams = new CheckBill();
            $checkBillParams->login = LOGIN;
            $checkBillParams->password = PASSWORD;
            $checkBillParams->txn = "TEST-1";

            //var_dump($service->checkBill($checkBillParams));
            $bill = $service->checkBill($checkBillParams);
            $f = fopen(__DIR__ . '/result.txt', 'w');
            fwrite($f, print_r($bill, true));
            fclose($f);

            /*require('routeros_api.php');
            $API = new routeros_api();
            $API->debug = false;

            if ($API->connect('94.228.205.2', 'hotspot', 'hotspot1234')) {

                $API->comm("/ip/hotspot/user/add", array(
                    "name"     => "user" . $param->txn,
                    "password" => "pass" . $param->txn,
                    "profile" => "tariff_1",
                    "comment"  => "9250017893",
                ));

                $API->disconnect();

            }*/
            $dsn = 'mysql:dbname=znachok_test;host=znachok.mysql.ukraine.com.ua';
            $db = new PDO($dsn, 'znachok_test', '3dqkhnz5');

            $sql = "INSERT INTO dreamnet (`user`, `ammount`, `date`, `status`, `endtime`)
                VALUES (?, ?, ?, ?, ?)";
            $time = strtotime($bill->date);
            $endtime = $time + 15;

            $sth = $db->prepare($sql);
            $sth->execute(array(
                $bill->user,
                $bill->amount,
                date('Y-m-d H:s:i', $time),
                60,
                date('Y-m-d H:s:i', $endtime)
            ));




        } else if ($param->status > 100) {
            // заказ не оплачен (отменен пользователем, недостаточно средств на балансе и т.п.)
            // найти заказ по номеру счета ($param->txn), пометить как неоплаченный
        } else if ($param->status >= 50 && $param->status < 60) {
            // счет в процессе проведени€
        } else {
            // неизвестный статус заказа
        }

        // формируем ответ на уведомление
        // если все операции по обновлению статуса заказа в магазине прошли успешно, отвечаем кодом 0
        // $temp->updateBillResult = 0
        // если произошли временные ошибки (например, недоступность Ѕƒ), отвечаем ненулевым кодом
        // в этом случае QIWI  ошелЄк будет периодически посылать повторные уведомлени€ пока не получит код 0
        // или не пройдет 24 часа
        $temp = new Response();
        $temp->updateBillResult = 0;
        return $temp;
    }
}
?>
