<?php
/**
 * Пример вызова методов на стороне QIWI Кошелька (https://ishop.qiwi.ru/services/ishop?wsdl)
 * Создаваемый объект класса IShopServerWSService реализует все методы, которые описаны в документации.
 * Для вызова необходимо сформировать "объект-запрос" (класс которого описан в файле IShopServerWSService.php)
 * и вызвать метод класса IShopServerWSService передав в него этот объект в виде параметра.
 * 
 **/
 
/*
 * Настройки магазина
 */ 
define('LOGIN', 0000);
define('PASSWORD', '****');


// просмотр SOAP-запросов/ответов (для отладки)
define('TRACE', 1);
 
include("IShopServerWSService.php");

$service = new IShopServerWSService('IShopServerWS.wsdl', array('location'      => 'http://ishop.qiwi.ru/services/ishop', 'trace' => TRACE));

/**
 * @param $txn_id - номер отменяемого счета
 *
 */
function cancelBill($txn_id) {
	global $service;
	
	// Формирует объект-запрос
	$params = new cancelBill();
	$params->login = LOGIN;
	$params->password = PASSWORD;
	$params->txn = $txn_id;
	
	// вызываем метод сервиса с параметрами
	$res = $service->cancelBill($params);

	// выводим результат
	print($res->cancelBillResult);

	// для отладки (вывод тела запроса)
	// print($service->__getLastRequest());
}


/**
 * @param $phone (string) - номер телефона (QIWI Кошелька), на который будет выставляться счет
 * @param $amount (string) - сумма к оплате (в формете "рубли"."копейки")
 * @param $txn_id (string) - номер счета (уникальная в пределах магазина)
 * @param $comment (string) - комментарий
 * @param $lifetime (string) - срок действия счета (в формате dd.mm.yyyy HH:MM:SS)
 * @param $alarm (int) - уведомление
 * @param $create (bool) - выставлять незарегистрированному пользователю
 *
 **/
function createBill($phone, $amount, $txn_id, $comment, $lifetime='', $alarm=0, $create=true) {
	global $service;
	
	$params = new createBill();
	$params->login = LOGIN; // логин
	$params->password = PASSWORD; // пароль
	$params->user = $phone; // пользователь, которому выставляется счет
	$params->amount = ''.$amount; // сумма
	$params->comment = $comment; // комментарий
	$params->txn = $txn_id; // номер заказа
	$params->lifetime = $lifetime; // время жизни (если пусто, используется по умолчанию 30 дней)
	
	// уведомлять пользователя о выставленном счете (0 - нет, 1 - послать СМС, 2 - сделать звонок)
	// уведомления платные для магазина, доступны только магазинам, зарегистрированным по схеме "Именной кошелёк"
	$params->alarm = $alarm; 

	// выставлять счет незарегистрированному пользователю
	// false - возвращать ошибку в случае, если пользователь не зарегистрирован
	// true - выставлять счет всегда
	$params->create = $create;

	$res = $service->createBill($params);

	$rc = $res->createBillResult;
	return $rc;
}

// пример работы
$rc = createBill('8888888888', '0.01', 'TEST-1', 'Test bill');

// проверить код $rc, выдать ошибку/рекомендацию пользователю в зависимости от кода
// вывод для отладки:
print($rc);

?>
