<?php
$db = mysql_connect('46.229.230.86' , 'zn006300' ,'kjimitaq' ) or die('Nemozem sa
pripojit k databaze');
mysql_select_db('zn006301db', $db) or die(mysql_error($db));
?>


<html>
<head>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css"/>
    <script>
        $(function () {
            $("#datepicker").datepicker();
            $("#format").change(function () {
                $("#datepicker").datepicker("option", "dateFormat", $(this).val());
            });
        });
    </script>
    <meta http-equiv="content-type" content="text/html; charset=windows-1250">

    <title> Pridanie </title>
</head>
<body>
<div id="teplota" style="margin-left:auto; margin-right: auto; text-align: center">
<form action="pridaj.php" method="post">
    <p>Deň: <input type="text" name="de"><br/>
        Čas: <input type="text" name="ca" value="'00-00'"><br/>
        Teplota: <input type="text" name="te" value="'5'"><br/>
        Dátum: <input type="text" name="da" value="'2013-08-14'"><br/>
        Vlhkosť: <input type="text" name="vl" value="'00'"><br/>
        Tlak: <input type="text" name="tl" value="'0000'"> <br/>
		Poznamka <textarea rows="4" cols="50" name="p1"></textarea> <br />   
    </p>
    <input type="submit" name="submit" value="ODOSLAT"><br/>
</form>
</div>
</body>
</html>
