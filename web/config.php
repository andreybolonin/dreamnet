<?php
/**
 * QIWI configs.
 */
define('LOGIN', 4321);
define('PASSWORD', 'yfljtkbrjvfhs');
define('TRACE', 1);

/*
 * router configs
 */

$routers = array(
    'WiFi:kalininec' => array(
        'ip' => '94.228.205.2',
        'login' => 'hotspot',
        'pass' => 'hotspot1234',
    ),
    'WiFi:zvenigorod' => array(
        'ip' => '94.228.205.2',
        'login' => 'hotspot',
        'pass' => 'hotspot1234',
    ),
    'WiFi:yakovlevskoe' => array(
        'ip' => '94.228.205.2',
        'login' => 'hotspot',
        'pass' => 'hotspot1234',
    ),
    'WiFi:selyatino' => array(
        'ip' => '94.228.205.2',
        'login' => 'hotspot',
        'pass' => 'hotspot1234',
    ),
    'WiFi:aprelevka' => array(
        'ip' => '94.228.205.2',
        'login' => 'hotspot',
        'pass' => 'hotspot1234',
    ),
    'WiFi:naro-fominsk' => array(
        'ip' => '94.228.205.2',
        'login' => 'hotspot',
        'pass' => 'hotspot1234',
    ),
);
//������. ����� � ��������.
$tarifs = array(
    0 => 900,
    10 => 3600,
    100 => 24 * 3600,
    500 => 30 * 24 * 3600,
    800 => 30 * 24 * 3600,
);
$tarif_names = array(
    0 => '�������� 15 ���.',
    10 => '���-���',
    100 => '�����',
    500 => '���������',
    800 => '��������� ����',
);

/*
 * datebase configs
 */

define('DBHOST', 'znachok.mysql.ukraine.com.ua');
define('DBNAME', 'znachok_test');
define('DBLOGIN', 'znachok_test');
define('DBPASS', '3dqkhnz5');
define('DBTYPE', 'mysql');

/*
 * SMS configs
 */
define('SMSLOGIN', 'dreamnet');
define('SMSPASS', 'adc6e247a98b1');
define('SMSSENDER', 'DreamLine');
// ������ ������ ����������
//��� ����������� ����� ������������ ����������� ����������� {%user%}
//��� ����������� ������ - {%pass%}
//��� ����������� ����� �������� - {%endtime%}
//������� ������ - \n
//��� ��������� ��������� ������� � ����������, ����� ����� ������� ���������.
define('ADDTEXT', "Login: {%user%}\nPassword: {%pass%}\n Data - {%endtime%}");
//������ ������ ��������
//������������� ����� {%pass%} � {%endtime%} �� �����������
define('DELETETEXT', 'Vse usligi otklucheni');
