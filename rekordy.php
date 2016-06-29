<?php

// min teplota za den
$min_tlak =  dibi::select('min(tlak)as minn, datum, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->limit(1)->fetchAll();
// max teplota za den
$max_tlak =  dibi::select('max(tlak) as maxx, datum, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->limit(1)->fetchAll();


$datum = date("Y").'-'.date("m").'-'.date("d").'';

$datum = date("Y,m,d,H,i", strtotime($datum. "-1 day"));
// min teplota za den
$min_tlak_vcera =  dibi::select('min(tlak)as minn, datum, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->limit(1)->fetchAll();
// max teplota za den
$max_tlak_vcera =  dibi::select('max(tlak) as maxx, datum, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->limit(1)->fetchAll();
$min_teplota_vcera =  dibi::select('min(teplota)as minn, datum, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->limit(1)->fetchAll();
// max teplota za den
$max_teplota_vcera =  dibi::select('max(teplota) as maxx, datum, cas')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'DESC')->limit(1)->fetchAll();



$min_tlak_celkove =  dibi::select('min(tlak) as minn, datum')->from('teplota')->orderBy('id', 'DESC')->limit(1)->fetchAll();
$max_tlak_celkove =  dibi::select('max(tlak) as maxx, datum')->from('teplota')->orderBy('id', 'DESC')->limit(1)->fetchAll();
$min_teplota_celkove =  dibi::select('min(teplota)as minn, datum')->from('teplota')->orderBy('id', 'DESC')->limit(1)->fetchAll();
$max_teplota_celkove =  dibi::select('max(teplota) as maxx, datum')->from('teplota')->orderBy('id', 'DESC')->limit(1)->fetchAll();
$max_teplota_celkove_datum =  dibi::select('*')->from('teplota')->limit(1)->where('teplota=%s', round ($max_teplota_celkove[0]->maxx, 2))->fetchAll();
$min_teplota_celkove_datum = dibi::select('*')->from('teplota')->limit(1)->where('teplota=%s', round ($min_teplota_celkove[0]->minn, 2))->fetchAll();
$min_tlak_celkove_datum = dibi::select('*')->from('teplota')->limit(1)->where('tlak=%s', $min_tlak_celkove[0]->minn)->fetchAll();
$max_tlak_celkove_datum = dibi::select('*')->from('teplota')->limit(1)->where('tlak=%s', $max_tlak_celkove[0]->maxx)->fetchAll();



?>
<div id="in"><script src="./design/js/highcharts.js"></script><script src="./design/js/highcharts-more.js"></script><script src="./design/js/modules/exporting.js"></script>

<div id="obsah">


	
	<div class='tabs tabs_animate'>
	<div id='tab-1'>
	<div id="rek_l">
		<table>
			<tr><td colspan="3" class="nazev">Rekordy za dnešný deň</td></tr>
			<?php
			?>
			<tr><td>Najvyššia teplota:</td><td></td><td style="color:red;font-weight:bold;"><?php  echo round ($max_teplota, 2);  ?> °C</td></tr>
			<tr><td>Najnižšia teplota:</td><td></td><td style="color:blue;font-weight:bold;"><?php  echo  round ($min_teplota, 2); ?> °C</td></tr>
			<tr><td>Najvyžší tlak:</td><td></td><td><?php echo $max_tlak[0]->maxx ?> Pa</td></tr>
			<tr><td>Najnižší tlak:</td><td></td><td><?php echo $min_tlak[0]->minn ?> Pa</td></tr>
		</table>
	</div>
	<div id="rek_r">
		<table>
			<tr><td colspan="3" class="nazev">Rekordy za včerajší deň</td></tr>
			<tr><td>Najvyššia teplota:</td><td></td><td style="color:red;font-weight:bold;"><?php  echo round ($max_teplota_vcera[0]->maxx, 2);  ?> °C</td></tr>
			<tr><td>Najnižšia teplota:</td><td></td><td style="color:blue;font-weight:bold;"><?php  echo  round ($min_teplota_vcera[0]->minn, 2); ?>  °C</td></tr>
			<tr><td>Najvyžší tlak:</td><td></td><td><?php echo $max_tlak_vcera[0]->maxx ?> Pa</td></tr>
			<tr><td>Najnižší tlak:</td><td></td><td><?php echo $min_tlak_vcera[0]->minn ?> Pa</td></tr>
		</table>
	</div>
	<h2>Celkové rekordy</h2>
	<table id="rek">
		<tr><td class="rek">Rekord</td><td class="dat">Datum</td><td class="hod">Hodnota</td></tr>
		<tr><td>Najvyžšia nameraná teplota</td><td><?php echo $max_teplota_celkove_datum[0]->datum->format('Y-m-d').' o '.$max_teplota_celkove_datum[0]->cas  ?></td><td style="color:red;font-weight:bold;"><?php  echo round ($max_teplota_celkove[0]->maxx, 2);  ?> °C</td></tr>
		<tr><td>Najnižšia nameraná teplota</td><td><?php echo $min_teplota_celkove_datum[0]->datum->format('Y-m-d').' o '.$min_teplota_celkove_datum[0]->cas  ?></td><td style="color:blue;font-weight:bold;"><?php  echo  round ($min_teplota_celkove[0]->minn, 2); ?> °C</td></tr>
		<tr><td>Najvyžší tlak</td><td><?php echo $max_tlak_celkove_datum[0]->datum->format('Y-m-d').' o '.$max_tlak_celkove_datum[0]->cas  ?></td><td><?php echo $max_tlak_celkove[0]->maxx ?> Pa</td></tr>
		<tr><td>Najnižší tlak</td><td><?php echo $min_tlak_celkove_datum[0]->datum->format('Y-m-d').' o '.$min_tlak_celkove_datum[0]->cas  ?></td><td><?php echo $min_tlak_celkove[0]->minn ?> Pa</td></tr>
	</table>
	</div>
	<div id='tab-2'>
	<div id="rek_l">
		<table>
			<tr><td colspan="3" class="nazev">Rekordy za rok 2015</td></tr>
			<?php
			?>
			<tr><td>Najvyššia teplota:</td><td></td><td style="color:red;font-weight:bold;">37 °C</td></tr>
			<tr><td>Najnižšia teplota:</td><td></td><td style="color:blue;font-weight:bold;">- 6,18 °C</td></tr>
		</table>
	</div>
	<div id="rek_r">
		<table>
			<tr><td colspan="3" class="nazev">Rekordy za rok 2016 ku dňu 23.6.2016</td></tr>
			<tr><td>Najvyššia teplota:</td><td></td><td style="color:red;font-weight:bold;">35,75 °C</td></tr>
			<tr><td>Najnižšia teplota:</td><td></td><td style="color:blue;font-weight:bold;">- 9,93 °C</td></tr>

		</table>
	</div>
	</div>	

		<ul class='horizontal'>
			<li><a href="#tab-1">Dnes včera</a></li>
			<li><a href="#tab-2">Za rok</a></li>
		</ul>
	</div>
	</div>	
	
	
</div></div>
	<script type="text/javascript" src="./design/js/vendor/waypoints.min.js"></script>
	<script type="text/javascript" src="./design/js/vendor/waypoints-sticky.min.js"></script>
	<script type="text/javascript" src="./design/js/vendor/jquery.tabslet.min.js"></script>
	<script src="./design/js/initializers.js"></script>