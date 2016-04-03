<html>
<body>
<form id="qiwi-form" action="http://w.qiwi.ru/setInetBill.do" method="get" accept-charset="windows-1251"">
    <input type="hidden" name="to" value="<?php echo $_REQUEST['to']?>">
    <input type="hidden" name="from" value="<?php echo $_REQUEST['from']?>">
    <input type="hidden" name="com" value="<?php echo $_REQUEST['com']?>"/>
    <input type="hidden" name="amount_rub" value="<?php echo $_REQUEST['amount_rub']?>"/>
    <input type="hidden" name="amount_kop" value="<?php echo $_REQUEST['amount_kop']?>"/>
</form>
<<script>
    document.getElementById('qiwi-form').submit();
</script>
</body>
</html>