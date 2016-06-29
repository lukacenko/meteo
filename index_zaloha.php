<?php			
ini_set('display_errors','On');

set_error_handler('exceptions_error_handler');                  
error_reporting(E_ALL);
function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
	throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
} 

include_once	"dibi/dibi.php";
include_once	"mailer/libs/nette.min.php";

use Nette\Mail\SmtpMailer;	
use Nette\Mail\Message;
date_default_timezone_set('Europe/Bratislava');
$result = date_sun_info( time(), 48.414590133, 18.300776482 );
// connect to database
dibi::connect([
    'driver'   => 'mysql',
    'host'     => 'localhost',
    'username' => 'lukacenko',
    'password' => '214214',
    'database' => 'teplota',
]);

$mail = new Message;	 
//emailový server
$mailer = new SmtpMailer(array(
	'smtp'=> 'true',
	'port'=> '587',
	'host' => 'mail.nov.sk',
	'username' => 'monitor@nov.sk',
	'password' => 'FloHatPop6',
	'secure' => 'tls',
));

$datum = date("Y").'-'.date("m").'-'.date("d").'';

// min teplota za den
$min_teplota =  dibi::select('min(teplota)as minn, datum, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->limit(1)->fetchAll();
// max teplota za den
$max_teplota =  dibi::select('max(teplota) as maxx, datum, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->limit(1)->fetchAll();
$aktualna_teplota_teraz =  dibi::select('teplota, tlak, vlhkost, teplota_dht11, rychlost_vetra')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->limit(1)->fetchAll();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="./design/favicon.png" rel="icon" type="image/png" />
<title>Meteostanice - BananaPi</title>
<!-- Add font style -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:600,400&subset=latin,latin-ext' rel='stylesheet' type='text/css' />
<!-- Add style for pages -->
<link href="./design/design.css" rel="stylesheet" type="text/css">
<link href="./design/statistika.css" rel="stylesheet" type="text/css">
<link href="./design/others.css" rel="stylesheet" type="text/css">
<script src="./design/js/highcharts.jss"></script>
<!-- JQuery -->
<script type="text/javascript" src="./design/js/jquery-1.9.1.min.js"></script>
<!-- Add javascript for pages -->
<script type="text/javascript" src="./design/js/jquery.min.js"></script>
<!-- jQuery -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<body>
 <div id="pole">
<table id="table_pole"><tr>
<td class="lokalita">Lokalita: Zlaté Moravce, Velčice - Mechov</td>
<td class="aktualizace">Dátum a čas poslednej aktualizácie: <?php echo date("d-m-Y"); ?></td>
<td class="log"><a href="?id=log">Prihlásiť sa</a></td></tr></table></div>

<div id="zahlavi">

		<div style="max-width:100%; max-height:100%; margin:auto; text-align:center">
		<h3 style="color:blue">Min teplota dnes bola: <b> <?php echo round ($min_teplota[0]->minn, 2); ?> °C  </b></h2>
		<h3 style="color:red">Max teplota  dnes bola:  <b> <?php  echo round ($max_teplota[0]->maxx, 2); ?> °C </b></h2>
		</div>
		<div id="aktualniHodnoty" style="width:990px">
		<table id="table_hodnoty">
		<?php
		?>
		<tr><td colspan="2" class="nazev">Teplota</td></tr>
		<tr><td class="logo"><img alt="Teplota" src="icon/teplota.png"></td><td class="hodnota"><?php echo $aktualna_teplota_teraz[0]['teplota']; ?>°C</td></tr>
		</table>

		<table id="table_hodnoty">
		<tr><td colspan="2" class="nazev">Tlak - mora</td></tr>
		<?php 
		
		if(substr($aktualna_teplota_teraz[0]['tlak'], 0, 1) == 1){
			$tlak = substr($aktualna_teplota_teraz[0]['tlak'], 0, 4);
		}else{
			$tlak = substr($aktualna_teplota_teraz[0]['tlak'], 0, 3);
		}
		?>
		<tr><td class="logo"><img alt="Tlak - mora" src="icon/tlak.png"></td><td class="hodnota"><?php echo $tlak; ?> hPa</td></tr>
		</table>
		<table id="table_hodnoty">
		<?php
		$dewpoint = round(((pow(($aktualna_teplota_teraz[0]['vlhkost']/100), 0.125))*(112+0.9*$aktualna_teplota_teraz[0]['teplota'])+(0.1*$aktualna_teplota_teraz[0]['teplota'])-112),1);
		?>
		<tr><td colspan="2" class="nazev">Rosný bod</td></tr>
		<tr><td class="logo"><img alt="Rosný bod" src="icon/rosa.png"></td><td class="hodnota"><?php echo $dewpoint; ?> °C</td></tr>
		</table>
		<table id="table_hodnoty">
		<tr><td colspan="2" class="nazev">Rychlosť vetra</td></tr>
		<tr><td class="logo"><img alt="Dallas" src="icon/vietor.png" style="width:90px; height:85px;"></td><td class="hodnota"><?php echo $aktualna_teplota_teraz[0]['rychlost_vetra']; ?> m/s</td></tr>
		</table>
		<table id="table_hodnoty">
		<tr><td colspan="2" class="nazev">Vlhkosť</td></tr>
		<tr><td class="logo"><img alt="Vlhkosť" src="icon/vlhkost.png"></td><td class="hodnota"><?php echo $aktualna_teplota_teraz[0]['vlhkost']; ?> %</td></tr>
		</table>
		</div>
		<div id="aktualniHodnotyNext" style="width:792px">
		<table id="table_hodnoty">
		<tr><td colspan="2" class="nazev">Východ slnka</td></tr>
		<tr><td class="logo"><img alt="Tlak - absolutný" src="icon/slnko_vychod.png" style="width:90px; height:85px;"></td><td class="hodnota"><?php echo date( 'H:i', $result['sunrise'] ); ?></td></tr>
		</table>
		<table id="table_hodnoty">
		<tr><td colspan="2" class="nazev">Západ slnka</td></tr>
		<tr><td class="logo"><img alt="Teplota DHT11" src="icon/slnko_zapad.png" style="width:90px; height:85px;" ></td><td class="hodnota"><?php echo date( 'H:i', $result['sunset'] ); ?></td></tr>
		</table>
		<table id="table_hodnoty">
		<tr><td colspan="2" class="nazev">Osvetlenie</td></tr>
		<tr><td class="logo"><img alt="Osvetlenie" src="icon/slnko.png"  ></td><td class="hodnota">-- lx</td></tr>
		</table>
		<?php

		$year = date('Y');
		$month = date('n');
		$day = date('j');
		if ($month < 4) {$year = $year - 1; $month = $month + 12;}
		$days_y = 365.25 * $year;
		$days_m = 30.42 * $month;
		$julian = $days_y + $days_m + $day - 694039.09;
		$julian = $julian / 29.53;
		$phase = intval($julian);
		$julian = $julian - $phase;
		$phase = round($julian * 8 + 0.5);
		if ($phase == 8) {$phase = 0;}
		$phase_array = array(array('Nov' => 'new') , array('Dorastajúci kosáčik' => 'waxing_crescent'), array('Prvá štvrť' => 'first_quarter'), array('Dorastajúci Mesiac' => 'waning_gibbous'), array('Spln' => 'full'), array('Cúvajúci mesiac' => 'waxing_gibbous'), array('Posledná štvrť' => 'last_quarter'), array('Ubúdajúci kosáčik' => 'waning_crescent'));
		foreach($phase_array[$phase] as $k => $h){
		$obrazok_mesiaca = '<img src="mesiace/'.$h.'.jpg" style="width:90px; height:85px;" >';
		$text_mesiaca =  $k; 
		}

		?>
		<table id="table_hodnoty">
		<tr><td colspan="2" class="nazev">Fáza mesiaca</td></tr>
		<tr><td class="logo">
		<?php echo $obrazok_mesiaca; ?>
		</td>
		<td class="hodnota">
		<span style="font-size:10px;">
		<b>
		<?php echo $text_mesiaca; ?>
		</b>
		</span>
		</td>
		</tr>
		</table></div>
		</div>
		<div id='cssmenu'>
		<?php
		if(!(isset($_GET['id']))){
			$_GET['id'] = 'index';
		} 
		
		
		
		?>
		<ul>
		<li <?php if($_GET['id'] == "graf"){ echo 'class="active2"';}?>><a href='?id=graf'><span>Graf</span></a></li>
		<li <?php if($_GET['id'] == "rekord"){ echo 'class="active2"';} ?>><a href='?id=rekord'><span>Rekordy</span></a></li>
		<li <?php if($_GET['id'] == "zoznam"){ echo 'class="active2"';} ?>><a href='?id=zoznam'><span>Zoznam</span></a></li>
		<li <?php if($_GET['id'] == "satelity"){ echo 'class="active2"';}?>><a href='?id=satelity'><span>Predpoveď</span></a></li>
		<li <?php if($_GET['id'] == "stanice"){ echo 'class="active2"';}?>><a href='?id=stanice' class='last'><span>O stanici</span></a></li>
		</ul>
		</div>


<?php
if($_GET['id']=="index"){
	require_once 'dstat.php';
}elseif($_GET['id']=="rekord"){
	require_once 'rekordy.php';
}elseif($_GET['id']=="zoznam"){
	require_once 'zoznam.php';
}elseif($_GET['id']=="dstat"){
//	require_once 'dstat.php';
}elseif($_GET['id']=="satelity"){
	require_once 'satelity.php';
}elseif($_GET['id']=="stanice"){
	require_once 'stanica.php';
}else{
	require_once 'dstat.php';
}

?>
</body>
</html>
<?php
$msg = "";
if ($msg!="") 
{
	$mail->setFrom('surda@nov.sk')
	 ->addTo('surda@nov.sk')
	 ->setSubject('Chyba importu v COOP Namestovo')
	 ->setBody($file.' - '.$msg);
	$mailer->send($mail);
} 
?>

