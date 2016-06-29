
<?php
$db = mysql_connect('46.229.230.119' , 'fk017200' ,'xhahyqeq' ) or die('Nemozem sa
pripojit k databaze');
mysql_select_db('fk017200db', $db) or die(mysql_error($db));

if (!isset($_GET['type']) || $_GET['do'] != 1 ) 
{
	switch ($_GET['type'])

{ case 'teplota':
 echo 'Skutočne chcete vymazat tuto teplotu ?';
  break;
}

	echo '<a href="'. $_SERVER['REQUEST_URI']. '&do=1"> ano </a>';
echo 'nebo <a href="admin.php"> ne </a>';
}
else
{	
if($_GET[type] = teplota)
{
         $query = 'DELETE FROM teplota  WHERE id = '.$_GET['id'];
         $result = mysql_query($query, $db) or die(mysql_error($db));
?>
Vybrana teplota bola zmazana
<a href="admin.php"> naspet </a>
<?php

}
}
?>
	

