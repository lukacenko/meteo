<?php			
ini_set('display_errors','On');
ini_set('memory_limit', '1024M');

session_start();
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
    'host'     => '46.229.230.119',
    'username' => 'fk017200',
    'password' => 'xhahyqeq',
    'database' => 'fk017200db',
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
$min_teplota =  dibi::select('min(teplota)as minn, datum, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->fetchSingle();
// max teplota za den
$max_teplota =  dibi::select('max(teplota) as maxx, datum, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->fetchSingle();

// min teplota datum
$min_teplota_datum =  dibi::select('cas')->from('teplota')->where('teplota=%s', $min_teplota)->orderBy('id', 'DESC')->fetchSingle();
// max teplota datum
$max_teplota_datum =  dibi::select('cas')->from('teplota')->where('teplota=%s', $max_teplota)->orderBy('id', 'DESC')->fetchSingle();

$aktualna_teplota_teraz =  dibi::select('teplota, tlak, vlhkost, osvetlenie, rychlost_vetra, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->limit(1)->fetchAll();
if(empty($aktualna_teplota_teraz)){

$aktualna_teplota_teraz =  dibi::select('teplota, tlak, vlhkost, osvetlenie, rychlost_vetra, cas')->from('teplota')->orderBy('id', 'DESC')->limit(1)->fetchAll();

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="./design/favicon.png" rel="icon" type="image/png" />
<title>Meteostanica - Obec Velčice </title>
<meta http-equiv="refresh" content="180" />

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
<?php
$cas = $aktualna_teplota_teraz[0]['cas'];

?>
<td class="aktualizace">Dátum a čas poslednej aktualizácie:<b> <?php echo date("d-m");  echo '  o '.$cas.' hod'?></b> <a href="http://www.iteplota.eu/cam.php">%</a></td>
<td class="log">
<?php
if(isset($_GET['od'])){
	$_SESSION['prihlasenie'] = false;
}

//if(isset($_SESSION) && $_SESSION['prihlasenie'] == true){
if(false){
?>
<a href="?od=log">Odhlásiť sa</a></td></tr></table></div>
<?php
}else{
?>
<a href="?id=log">Prihlásiť sa</a></td></tr></table></div>
<?php
}
?>
<div id="zahlavi">

		<div style="max-width:100%; max-height:100%; margin:auto; text-align:center">
		<h3 style="color:blue"><img src="obrazky/min.png"  style="width:17px; height:27px;"> Min teplota dnes bola: <b> <?php echo round ($min_teplota, 2); ?> °C  a to o <?php echo $min_teplota_datum; ?> hod </b></h2>
		<h3 style="color:red"><img src="obrazky/max.png"  style="width:17px; height:27px;"> Max teplota  dnes bola:  <b> <?php  echo round ($max_teplota, 2); ?> °C a to o <?php echo $max_teplota_datum; ?> hod </b></h2>
		</div>
		<div id="aktualniHodnoty" style="width:1010px">
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
		<tr><td class="logo"><img alt="Dallas" src="icon/vietor.png" style="width:90px; height:85px;"></td><td class="hodnota"><?php echo $aktualna_teplota_teraz[0]['rychlost_vetra']; ?> km/h</td></tr>
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
		<tr><td class="logo"><img alt="Osvetlenie" src="icon/slnko.png"  ></td><td class="hodnota"><?php echo $aktualna_teplota_teraz[0]['osvetlenie'] ?> lx</td></tr>
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
}elseif($_GET['id']=="log"){
	require_once 'login.php';
}else{
	require_once 'dstat.php';
}

?>
</body>
</html>
<?php



$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
function getOS() { 
    global $user_agent;
    $os_platform    =   "Unknown OS Platform";
    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );
    foreach ($os_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }
    }   
    return $os_platform;
}
function getBrowser() {
    global $user_agent;
    $browser        =   "Unknown Browser";
    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/edge/i'       =>  'Edge',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );
    foreach ($browser_array as $regex => $value) { 
        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }
    return $browser;

}

$user_os        =   getOS();
$user_browser   =   getBrowser();
$device_details =   "<strong>Browser: </strong>".$user_browser."<br /><strong>Operating System: </strong>".$user_os."";
$adresa     =   $_SERVER['REMOTE_ADDR'];



$arr = [
    'prehliadac' => $user_browser,
    'os'  => $user_os,
	'ip'  => $adresa,
];


dibi::query('INSERT INTO pristup', $arr);












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

