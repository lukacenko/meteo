<?php 

//$strankovanie =  dibi::select('*')->from('teplota')->fetchAssoc('id');
$strankovanie =  dibi::query('SELECT COUNT(id) as pocet FROM teplota')->fetch();
//$num=count($strankovanie);
$num = $strankovanie->pocet;

//$ctrl je pomocná premenná
/*
if ($ctrl1<>1) 
{
	$strana=1;
}//endif
*/
$strana=1;

$limit=266;
$celkovy_pocet=$num;
$pocet_stran=ceil($celkovy_pocet/$limit);
$strana = 1;
if(isset($_GET['strana'])){
$strana = $_GET['strana'];
}
$pociatok=($strana*$limit)-$limit;
$mapovanie = array(
'Mon' => "PONDELOK",
'Tue' => "UTOROK",
'Wed' => "STREDA",
'Thu' => "STVRTOK",
'Fri' => "PIATOK",
'Sat' => "SOBOTA",
'Sun' => "NEDELA"
);
if(!isset($_SESSION['prihlasenie'])){
	$_SESSION['prihlasenie'] = false;
}

$row = dibi::select('*')->from('teplota')->orderBy('id', 'DESC')->limit($limit)->offset($pociatok)->fetchAll(); 
?>
<div id="in"><script src="./design/js/highcharts.js"></script><script src="./design/js/highcharts-more.js"></script><script src="./design/js/modules/exporting.js"></script>

<div id="obsah">
<center>
<h2> Databáza s históriou teploty vo Velčiciach </h2>
<!--h2> <a href="admin.php">Pridat teplotu </a> </h2-->
<table border="1" cellpadding="2" cellspacin="2" style="width:70%; margin-left:auto; margin-right:auto; text-align:center;" class="table">
<th> Deň      </th>
<th> Čas</th>
<th> Dátum            </th>
<th> Teplota </th>
<th> Vlhkosť </th>
<th> Tlak </th>
<?php
if($_SESSION['prihlasenie'] == true){
echo  '<th> Možnosti </th>';
}
?>

</tr>
<?php

$i = 0;
foreach($row as $k => $h){	


foreach($mapovanie as $kk => $hh){

if($h['den'] == $kk){
$den = $hh;
}
}
echo '<tr>';
echo '<td>'.$den.'</td>';
echo '<td>'.$h->cas.'Hod </td>';
echo '<td>'.$h->datum->format('Y-m-d').'</td>';
echo '<td>'.$h->teplota .'°C</td>';
echo '<td>'.$h->vlhkost  .'%</td>';
echo '<td>'.$h->tlak  .'hPA</td>';
if($_SESSION['prihlasenie'] == true){
echo  '<td> <a href="delete.php?type=teplota&id='.$h['id'].'"> ODSTRANIT </a> </td>';
}
echo '</tr>';

}
//end while

$i++;
echo '</table>';
echo '<div style="width:800px; margin-left:auto; margin-right:auto;">';
for ($i=1; $i<=$pocet_stran; $i++)
{
	if ($i<>$strana) 
	{
		echo "<a href=\"index.php?id=zoznam&ctrl=1&strana=",$i,"\">",$i,"</a> | ";
	}
	else 
	{
		echo "<font color=\"#FF0000\">",$i,"</font> | ";
	}//endif
}//endfor

if ($strana<>$pocet_stran)
{
	echo "<a href=\"index.php?id=zoznam&ctrl=1&strana=",$strana+1,"\">nasledujúca strana</a>";
}//endif
echo '</div>';
?>


</div></div>