<div id="in"><script src="./design/js/highcharts.js"></script><script src="./design/js/highcharts-more.js"></script><script src="./design/js/modules/exporting.js"></script><div id="obsah">
	<div class='tabs tabs_animate'>
		<div id='tab-1' style="margin-left:auto; margin-right:auto; width:650px;">
		<?php
		// example of how to use advanced selector features
		include('simple_html_dom.php');
		$html = file_get_html('http://www.shmu.sk/sk/?page=1&id=meteo_radar');
		// Find all images
		foreach($html->find('div.tcenter img') as $element)
		$druzice = $element->src;
		 
		?>
		<p>
		<img src="http://www.shmu.sk/<?php echo $druzice;?>" width="650px" height="410px">
		</p>
		</div>
		<div id='tab-2' style="margin-left:auto; margin-right:auto; width:650px;">
		<?php
		// Create DOM from URL or file
		$radar = file_get_html('http://www.shmu.sk/sk/?page=1&id=meteo_druzica');
		// Find all images
		foreach($radar->find('div.tcenter img') as $element)
		$radar = $element->src;
		?>
		<p>
		<img src="http://www.shmu.sk/<?php echo $radar;?>" width="650px" height="410px">
		</p>
		</div>
		<div id='tab-3' style="margin-left:auto; margin-right:auto; width:650px;">
		<?php
		// Create DOM from URL or file
		$aladin = file_get_html('http://www.shmu.sk/sk/?page=1&id=meteo_num_mgram&nwp_mesto=32714&changed=1&picSelector=5');
		// Find all images
		foreach($aladin->find('div.tcenter img') as $element)
		$aladin = $element->src;
		?>
		<p>
		<img src="http://www.shmu.sk/<?php echo $aladin;?>" width="650px" height="710px">
		</p>
		
		</div>
		<div id='tab-4' style="margin-left:auto; margin-right:auto; width:650px;">
		<?php
		// Create DOM from URL or file
		$aladin = file_get_html('http://www.shmu.sk/sk/?page=1&id=meteo_num_mgram10&nwp_mesto=32714');
		// Find all images
		foreach($aladin->find('div.tcenter img') as $element)
		$aladin = $element->src;
		?>
		<p>
		<img src="http://www.shmu.sk/<?php echo $aladin;?>" width="650px" height="710px">
		</p>
		</div>
		
		<ul class='horizontal'>
			<li><a href="#tab-1">Satelity</a></li>
			<li><a href="#tab-2">Radar</a></li>
			<li><a href="#tab-3">Predpoveď 3 dni</a></li>
			<li><a href="#tab-4">Predpoveď 10 dni</a></li>
		</ul>
		<p style="text-align:center;">Zdroj: http://www.shmu.sk</p>
	</div>
</div></div>
<!-- JS -->
	<script type="text/javascript" src="./design/js/vendor/waypoints.min.js"></script>
	<script type="text/javascript" src="./design/js/vendor/waypoints-sticky.min.js"></script>
	<script type="text/javascript" src="./design/js/vendor/jquery.tabslet.min.js"></script>
	<script src="./design/js/initializers.js"></script>


	
	
	   