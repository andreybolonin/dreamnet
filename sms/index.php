<?php
#
# mainsms.ru
# Пример использования API
#

# Подключаем класс
require_once dirname(__FILE__).'/mainsms.class.php';
session_start();


/* 
  создаём экземпляр класса MainSms

  Список параметров ($project, $key, $useSSL = false, $testMode = false)
  $project - название проекта, берется со страницы http://mainsms.ru/office/api_account
  $key - ключ проекта, берется со страницы http://mainsms.ru/office/api_account
  $useSSL - не обязательный параметр, если true то взаимодействие будет осуществляться по протоколу https иначе http
  $testMode - не обязательный параметр, если true то сообщения не будут отправляться и деньги не будут списываться(используется для отладки)
*/
$api = new MainSMS('your_project_name', 'your_api_key', false, false);


if(isset($_REQUEST['clear'])){
  unset($_SESSION['sms'] );
}

// Post отправка смс
if(isset($_POST['send'])) { 
  if(!empty($_POST['sender']) && !empty($_POST['recipients']) && !empty($_POST['text'])){

    // Отправляем сообщение
    $api->sendSMS($_POST['recipients'], $_POST['text'], $_POST['sender']);
    
    //запомним ответ в сессию, для отображения
    $_SESSION['sms'][] = Array( 
      "response" => $api->getResponse(), 
      "text"=>$_POST['text'], 
      "sender"=>$_POST['sender'], 
      "time"=>time(), 
    );

  }
}

?>
<html>
  <head>
    <title>Пример использования mainsms.ru::API</title>
  </head>
  <body>
    <div id="content" style="float:left;">
      <form id="send" method="post" action="?">
        <h1>Пример использования API</h1>
        <fieldset>
          <legend>Отправить SMS</legend>
          <table>
            <tr>
              <td>Отправитель:</td>
              <td><input maxlength="11" type="text" name="sender" value=""></td>
              <td><small>латинские буквы и цифры - от 3х до 11, цифры - от 5х до 11</small></td>
            </tr>
            <tr>
              <td>Номер получателя:</td>
              <td><input type="text" value="" name="recipients"></td>
              <td><small>номер телефона или телефонов - через запятую</small></td>
            </tr>
            <tr>
              <td>Текст сообщения:</td>
              <td colspan="2"><textarea rows="5" name="text" cols="50"></textarea></td>
            </tr>
            <tr>
              <td colspan="3"><input type="submit" name="send" value="Отправить сообщение"></td>
            </tr>
          </table>
        </fieldset>
      </form>
      <br><br>

      <?php if(isset($_REQUEST['status'])) {?>
        <?php if(isset($_SESSION['sms'][$_REQUEST['status']])) {

            $status_of_message = Array(
              'enqueued' => 'В очереди на отправление',
              'accepted' => 'Принято к отправлению',
              'delivered' => 'Доставлено',
              'non-delivered' => 'Отклонено',
            );

            $sms = $_SESSION['sms'][$_REQUEST['status']];
            $result = $api->checkStatus($sms['response']['messages_id']);
          ?>
          <fieldset>
            <legend>Статус сообщения #<?php echo $_REQUEST['status'];?> (<?php echo date('H:i:s',$sms['time']);?>)</legend>

              <div><?php echo "<b>".$sms['sender']."</b>: ".$sms['text'];?></div>
              <table border="1" cellpadding="5" cellspacing="0">
                <tr><td>ID сообщения</td><td>Статус</td></tr>
                <?php foreach($result as $k=>$v){
                  echo "<tr><td>$k</td><td>{$status_of_message[$v]}</td></tr>";
                }?>
              </table>
            
          </fieldset>
          <br><br>
        <?php } ?>
      <?php } ?>
  

      <?php 
      if(isset($_SESSION['sms'])) { 
        if(!empty($_SESSION['sms'])) {  ?>
          <fieldset>
            <legend>Отправленные смс <a href="?clear">Очистить лист</a></legend>
            <table border="1" cellpadding="5" cellspacing="0">
              <thead>
                <tr>
                  <td>#</td>
                  <td>Отправитель</td>
                  <td>Получатели</td>
                  <td>Текст смс</td>
                  <td>Время отправления</td>
                  <td>Доставленно в mainsms.ru</td>
                  <td>Статус</td>
                </tr>
              </thead>
              <tbody>
              <?php $i=0; foreach($_SESSION['sms'] as $sms) {  ?>
                <tr>
                  <td><?php echo $i;?></td>
                  <td><?php echo $sms['sender'];?></td>
                  <td><?php echo implode(",",$sms['response']['recipients']);?></td>
                  <td><?php echo $sms['text'];?></td>
                  <td><?php echo date('H:i:s',$sms['time']);?></td>
                  <td><?php echo $sms['response']['status']=='success'?'Да':'Ошибка';?></td>
                  <td><a href="?status=<?php echo $i;?>">Запросить</a></td>
                </tr>
              <?php $i++; } ?>
              </tbody>
            </table>
          </fieldset>
          <?php 
        }
      }
      ?>
    </div>
  </body>
</html>



