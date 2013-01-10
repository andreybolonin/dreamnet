<?php
/**
 * �� ���� ������ �������� ����������� �� QIWI ��������.
 * SoapServer ������ �������� SOAP-������, ��������� �������� ����� login, password, txn, status,
 * �������� �� � ������ ������ Param � �������� ������� updateBill ������� ������ TestServer.
 *
 * ������ ��������� ��������� ����������� ������ ���� � updateBill.
 */

require_once ('./qiwi/IShopServerWSService.php');
include_once ('./config.php');

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
        global $tarifs;
        // ��������� password, login

        // � ����������� �� ������� ����� $param->status ������ ������ ������ � ��������
        if ($param->status == 60) {

            // ����� �������
            // ����� ����� �� ������ ����� ($param->txn), �������� ��� ����������

            $service = new IShopServerWSService('./qiwi/IShopServerWS.wsdl', array('location'      => 'http://ishop.qiwi.ru/services/ishop', 'trace' => TRACE));


            $checkBillParams = new CheckBill();
            $checkBillParams->login = LOGIN;
            $checkBillParams->password = PASSWORD;
            $checkBillParams->txn = $param->txn;

            //var_dump($service->checkBill($checkBillParams));
            $bill = $service->checkBill($checkBillParams);
            $f = fopen(__DIR__ . '/result.txt', 'w');
            fwrite($f, print_r($param, true));
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
            $dsn = $dsn = DBTYPE . ":dbname=" . DBNAME . ";host=" . DBHOST;            ;
            $db = new PDO($dsn, DBLOGIN, DBPASS);

            /**
             * ��������� ������� ������� � ����.
             */
            $sql = "SELECT `id`, `user`, `com`
                    FROM dreamnet
                    WHERE `status` = 5
                        AND `user` = ?";
            $sth = $db->prepare($sql);
            $sth->execute(array($bill->user));
            $row = $sth->fetchAll();

            if (empty($row)) {
                $temp = new Response();
                $temp->updateBillResult = 1;
                return $temp;
            }

            $row = array_shift($row);

            /**
             * ��������, ���� �� ������������ � �������, � ���� ��, �� �� ������� �� �� ������
             */

            $sql = $sql = "SELECT `id`, `user`, `com`, DATE_FORMAT(`endtime`, '%d.%m.%Y %H:%i:%s') AS endtime, `ammount`
                    FROM dreamnet
                    WHERE `endtime` > NOW()
                        AND `status` != 5
                        AND `user` = ?";
            $sth = $db->prepare($sql);
            $sth->execute(array($bill->user));
            $row2 = $sth->fetchAll();

            if (!empty($row2)) {
                $row2 = array_shift($row2);

                if ($row2['com'] == $row['com']){
                    //���������� �����
                    $sql = "UPDATE dreamnet
                        SET `endtime` = :newtime,
                            `ammount` = :ammount
                        WHERE `id` = :id";
                    $ammount = $row2['ammount'] + $bill->amount;
                    $addtime = isset($tarifs[(int)$bill->amount]) ? $tarifs[(int)$bill->amount] : 0;
                    $endtime = strtotime($row2['endtime']) + $addtime;
                    $endtime = date('Y-m-d H:i:s', $endtime);
                    $stf = $db->prepare($sql);
                    $stf->execute(array(
                        ':newtime' => $endtime,
                        ':ammount' => $ammount,
                        ':id' => $row2['id']
                    ));

                    //������� ��������� ������
                    $sql = "DELETE FROM dreamnet WHERE  `id` = ?";
                    $stf = $db->prepare($sql);
                    $stf->execute(array($row['id']));
                }
                $temp = new Response();
                $temp->updateBillResult = 0;
                return $temp;
            }

            $sql = "INSERT INTO dreamnet (`user`, `ammount`, `date`, `status`, `endtime`, `com`)
                VALUES (?, ?, ?, ?, ?, ?)";
            $time = strtotime($bill->date);
            $addtime = isset($tarifs[(int)$bill->amount]) ? $tarifs[(int)$bill->amount] : 0;
            $endtime = $time + $addtime;

            $sth = $db->prepare($sql);
            $sth->execute(array(
                $bill->user,
                $bill->amount,
                date('Y-m-d H:s:i', $time),
                60,
                date('Y-m-d H:s:i', $endtime),
                $row['com']
            ));

            //������� ��������� ������
            $sql = "DELETE FROM dreamnet WHERE  `id` = ?";
            $stf = $db->prepare($sql);
            $stf->execute(array($row['id']));




        } else if ($param->status > 100) {
            // ����� �� ������� (������� �������������, ������������ ������� �� ������� � �.�.)
            // ����� ����� �� ������ ����� ($param->txn), �������� ��� ������������
        } else if ($param->status >= 50 && $param->status < 60) {
            // ���� � �������� ����������
        } else {
            // ����������� ������ ������
        }

        // ��������� ����� �� �����������
        // ���� ��� �������� �� ���������� ������� ������ � �������� ������ �������, �������� ����� 0
        // $temp->updateBillResult = 0
        // ���� ��������� ��������� ������ (��������, ������������� ��), �������� ��������� �����
        // � ���� ������ QIWI ������ ����� ������������ �������� ��������� ����������� ���� �� ������� ��� 0
        // ��� �� ������� 24 ����
        $temp = new Response();
        $temp->updateBillResult = 0;
        return $temp;
    }
}
?>
