<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>������</title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="-1" />
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" type="text/css" href="ping.css">
</head>

<body>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
    <td class="top">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
                <td valign="top"><img src="img/pic_5.jpg" width="350" height="320"></td>
                <td valign="top" class="content">
                    <!-- -->
                    <h3>�� ��� ������������ �������� �������</h3>

                    <br><br>
                    <h1>��������� ����</h1>

                    <script type="text/javascript">
                        var ie=document.all;var moz=(navigator.userAgent.indexOf("Mozilla")!=-1);var opera=window.opera;var brodilka="";if(ie&&!opera){brodilka="ie"}else{if(moz){brodilka="moz"}else{if(opera){brodilka="opera"}}}var inputMasks=new Array();function kdown(a,c){var e=a.getAttribute("id");var b=e.substring(0,e.length-1);var d=Number(e.substring(e.length-1));inputMasks[b].BlKPress(d,a,c)}function kup(a,b){if(Number(a.getAttribute("size"))==a.value.length){var f=a.getAttribute("id");var d=f.substring(0,f.length-1);var e=Number((f.substring(f.length-1)))+1;var c=document.getElementById(d+e);if(b!=8&&b!=9){if(c){c.focus()}}else{if(b==8){a.value=a.value.substring(0,a.value.length-1)}}}}function Mask(d){var c="(\\d{3})\\d{3}-\\d{2}-\\d{2}";var f=[];var g=[];var a=[];var e="";var b=function(k){var j=Number(k.substring(3,k.indexOf("}")));var i=d.getAttribute("id");var m=g.length;var l="";var h=function(n){return((n>=48)&&(n<=57))||((n>=96)&&(n<=105))||(n==27)||(n==8)||(n==9)||(n==13)||(n==45)||(n==46)||(n==144)||((n>=33)&&(n<=40))||((n>=16)&&(n<=18))||((n>=112)&&(n<=123))};this.makeInput=function(){return"<input type='text' size='"+j+"' maxlength='"+j+"' id='"+i+m+"' onKeyDown='kdown(this, event)' onKeyUp='kup(this, event.keyCode)' value='"+l+"'>"};this.key=function(n,q){if(opera){return}if(!h(q.keyCode)){switch(brodilka){case"ie":q.cancelBubble=true;q.returnValue=false;break;case"moz":q.preventDefault();q.stopPropagation();break;case"opera":break;default:}return}if(q.keyCode==8&&n.value==""){var s=n.getAttribute("id");var r=s.substring(0,s.length-1);var o=Number(s.substring(s.length-1))-1;var p=document.getElementById(r+o);if(p!=null){p.focus()}}};this.getText=function(){l=document.getElementById(i+m).value;return l};this.setText=function(n){l=n};this.getSize=function(){return j}};this.drawInputs=function(){var k="<span class='Field'>";var l=0;var h=0;for(var j=0;j<a.length;j++){if(a[j]=="p"){k+=f[l];l++}else{k+=g[h].makeInput();h++}}k+="</span>";document.getElementById("div_"+d.getAttribute("id")).innerHTML=k;d.style.display="none"};this.buildFromFields=function(){var i=c;while(i.indexOf("\\")!=-1){var h=i.indexOf("\\");var k="";if(i.substring(0,h)!=""){f[f.length]=i.substring(0,h);a[a.length]="p";i=i.substring(h)}var j=i.indexOf("}");g[g.length]=new b(i.substring(0,j+1),k);i=i.substring(j+1);a[a.length]="b"}if(i!=""){f[f.length]=i;a[a.length]="p"}this.drawInputs()};this.buildFromFields();this.BlKPress=function(j,h,i){g[j].key(h,i)};this.makeHInput=function(){var h=d.getAttribute("name");document.getElementById("div_"+d.getAttribute("id")).innerHTML="<input type='text' readonly='readonly' name='"+h+"' value='"+this.getValue()+"'>"};this.getFName=function(){return d.getAttribute("name")};this.getValue=function(){e="";var k=0;var h=0;for(var j=0;j<a.length;j++){if(a[j]!="p"){e+=g[h].getText();h++}}return e};this.check=function(){for(var h in g){if(g[h].getText().length==0){return false}}return true}};
                    </script>
                    <div class="pay">
                        <!--<form action="http://w.qiwi.ru/setInetBill.do" method="get" accept-charset="windows-1251" onsubmit="return checkSubmit()">-->
                        <form action="http://test.foxweb.com.ua/dreamnet/qiwi_proccess.php" method="get" accept-charset="windows-1251" onsubmit="return checkSubmit()">
                            <input type="hidden" name="com" id="com"><input type="hidden" id="com_" value="">
                            <input type="hidden" name="from" id="from" value="3747">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <th>����� ������ ���������� ��������</th>

                                    <td>
                                        <!-- ����� ��� ����� ������ ��������, �� ������ �������� ��� ���� �� ������� input � ������ 'to'
                                        � �� ������������ javascript, � ���� ������ ������� ������� onsubmit � ����� -->
                                        <field id="idto" mask="(\d{3})\d{3}-\d{2}-\d{2}" name="to"><?php echo $_REQUEST['to']?></field>
                                        <span id="div_idto"></span>

                                        <script type="text/javascript">
                                            inputMasks["idto"] = new Mask(document.getElementById("idto"));

                                            function checkSubmit() {

                                                prefix = document.getElementById("nasp").value;
                                                for (i=0; i<document.getElementById("nasp").options.length; i++) {
                                                    if (document.getElementById("nasp").options[i].selected)
                                                        from = document.getElementById("nasp").options[i].getAttribute("from");
                                                }

                                                if (from==null || prefix==null) {
                                                    alert("�� ������ ���������� �����");
                                                    return false;
                                                }
                                                else
                                                    document.getElementById("from").value = from;

                                                if (inputMasks["idto"].getValue().match(/^\d{10}$/)) {
                                                    document.getElementById("com").value = prefix+document.getElementById("com_").value.replace(prefix,"")
                                                    inputMasks["idto"].makeHInput();
                                                    return true;
                                                } else {
                                                    alert("������� ����� �������� � ����������� ������� � ��� \"8\" � ��� \"+7\"");
                                                    return false;
                                                }
                                            }

                                            function setSumm(obj) {
                                                if (obj.value>0) {
                                                    document.getElementById("amount_rub").value = obj.value;
                                                    document.getElementById("amount_kop").value = "00";
                                                }
                                                else {
                                                    document.getElementById("amount_rub").value = "";
                                                    document.getElementById("amount_kop").value = "";
                                                }
                                            }

                                        </script>
                                    </td>
                                </tr>
                                <tr style="display: none">

                                    <th>���������� �����</th>
                                    <td>
                                        <select id="nasp">
                                            <option value=""></option>
                                            <?php foreach ($routers as $key => $router) : ?>
                                                <?php $selected = ($_REQUEST['com'] == $key) ? 'selected="selected"' : ''?>
                                                <option from="<?php echo $router['from']?>" value="<?php echo $key?>"<?php echo $selected?>><?php echo $router['value']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>

                                    <th>�����</th>
                                    <td>
                                        <select id="tarif" onChange="setSumm(this)">
                                            <option value=""></option>
                                            <option value="50">���-���</option>
                                            <option value="100">�����</option>
                                            <option value="500">���������</option>
                                            <option value="800">��������� ����</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>�����</th>

                                    <td><input type="text" id="amount_rub" style="width: 95px" name="amount_rub"> ���. <input type="text" id="amount_kop" name="amount_kop" maxlength="2" size="2">
                                        ���.
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="2" align="center">
                                        <input type="submit" class="button" value="��������� ����">

                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <br><br><br>
                    <!-- -->
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td class="bottom">
        <blockquote>
            &copy; 2011, Dreamline<br>
            ��� ����� ��������.
        </blockquote>
    </td>
</tr>
</table>
</body>
</html>