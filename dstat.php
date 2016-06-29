<?php


$mesiace = array(1 => "Január",2 => "Február",3 => "Marec",4 => "Apríl",5 => "Máj",6 => "Jún",7 => "Júl",8 => "August",9 => "September",10 => "Oktober",11 => "November",12 => "December",);
//$datanalabel =  dibi::select('*')->from('teplota')->where('datum=%s', $datum)->orderBy('datum', 'ASC')->fetchAll();

$aktualny_rok = date("Y");
$roky = array();
$zaciatocny_rok = 2010;

while($zaciatocny_rok <= $aktualny_rok){
$roky[] = $zaciatocny_rok;
$zaciatocny_rok++;
};


/*
	try{
		if ($chyba) != 23){
			throw new Exception('Nesprávny format CSV, CSV nebolo dodane v správnom formate na '.$cislo_riadku.' riadku');
		}
	}
	catch (Exception $e) {
		echo  $e;
		$msg = $e;
	}
*/
$datum = date("Y").'-'.date("m").'-'.date("d").'';
$vysledky =  dibi::select('*')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'ASC')->fetchAll();
if(isset($_POST['rok']) && $_POST['rok'] != 0){

	if($_POST['mesiac'] == 0){
		// len podla roku
		$datum_zac = $_POST['rok'].'-01-01';
		$datum_kon = $_POST['rok'].'-12-31';
		$vysledky =  dibi::select('*')->from('teplota')->where('datum>=%s', $datum_zac)->and('datum<=%s', $datum_kon)->orderBy('id', 'ASC')->fetchAll();
	}else{
		// presne specifikovany rok mesiac den
		if($_POST['den'] > 0 && $_POST['den'] < 32){
		$datum = $_POST['rok'].'-'.$_POST['mesiac'].'-'.$_POST['den'].'';
		$vysledky =  dibi::select('*')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'ASC')->fetchAll();
		}else{
		$den_zac = '01';		$den_kon = '31'; 		
		$datum_zac = $_POST['rok'].'-'.$_POST['mesiac'].'-'.$den_zac.'';		
		$datum_kon = $_POST['rok'].'-'.$_POST['mesiac'].'-'.$den_kon.'';
		$vysledky =  dibi::select('*')->from('teplota')->where('datum>=%s', $datum_zac)->and('datum<=%s', $datum_kon)->orderBy('id', 'ASC')->fetchAll();
		}
	}
	}elseif(isset($_POST['dnes'])){
		// aktualny dnes
		$vysledky =  dibi::select('*')->from('teplota')->where('datum=%s', $datum)->orderBy('id', 'ASC')->fetchAll();
	}

$pole_teplot = array();
$pole_vlhkosti = array();
$pole_tlaku = array();
$pole_vietor = array();
$pole_osvetlenia = array();

foreach($vysledky as $hodnota){

	// datum je 2016-02-09 
	$originalDate = $hodnota['datum']->format('Y-m-d').' '.str_replace('Noc', '00:00', str_replace('-', ':', str_replace(' ', ':', trim($hodnota['cas']))));
	$newDate = date("Y,m,d,H,i", strtotime($originalDate. "-1 month"));
	$teplota = str_replace(' ', '' ,str_replace('  ', '', str_replace(',', '.', trim($hodnota['teplota']))));
	$pole_tlaku[] = '[Date.UTC('.$newDate.'),'.(int)$hodnota['tlak'].']';
	$pole_vietor[] = '[Date.UTC('.$newDate.'),'.(float)$hodnota['rychlost_vetra'].']';
	$pole_vlhkosti[] = '[Date.UTC('.$newDate.'),'.(int)$hodnota['vlhkost'].']';
	$pole_osvetlenia[] = '[Date.UTC('.$newDate.'),'.(int)$hodnota['osvetlenie'].']';

	if($teplota != '.'){
		$pole_teplot[] = '[Date.UTC('.$newDate.'),'.(float)$teplota.']';
	}
}
$teplota =  json_encode($pole_teplot);
$tlak_pole = json_encode($pole_tlaku); 
$pole_vlhkosti = json_encode($pole_vlhkosti);
$pole_vietor = json_encode($pole_vietor);
$pole_osvetlenia = json_encode($pole_osvetlenia);
?>

<script>
// pre teplotu
$(function () {
    $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=usdeur.json&callback=?', function (data) {

        $('#container_teplota').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Teplota za dnes'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Kliknite pre priblíženie' : ''
            },
            xAxis: {
                type: 'datetime',
            },
            yAxis: {
                title: {
                    text: 'Dátum'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
            series: [{
                type: 'area',
                name: 'Teplota:',
                //data: [[Date.UTC(2013,5,2,0,0),0.7695],[Date.UTC(2013,5,3,0,0),0.7648],[Date.UTC(2013,5,4),0.7645],[Date.UTC(2015,5,30),0.8950]]
				data: <?php echo str_replace('"', '', $teplota); ?>

            }]
        });
    });
});
// pre tlak
$(function () {
    $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=usdeur.json&callback=?', function (data) {

        $('#container_tlak').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Tlak za dnes'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Kliknite pre priblíženie' : ''
            },
            xAxis: {
                type: 'datetime',
            },
            yAxis: {
                title: {
                    text: 'Dátum'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
            series: [{
                type: 'area',
                name: 'Tlak:',
                data: <?php echo str_replace('"', '', $tlak_pole); ?>
            }]
        });
    });
});// pre vlhkost
$(function () {
    $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=usdeur.json&callback=?', function (data) {

        $('#container_vlhkost').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Vlhkost za dnes'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Kliknite pre priblíženie' : ''
            },
            xAxis: {
                type: 'datetime',
            },
            yAxis: {
                title: {
                    text: 'Dátum'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
            series: [{
                type: 'area',
                name: 'Vlhkosť v %:',
                data: <?php echo str_replace('"', '', $pole_vlhkosti); ?>
            }]
        });
    });
});

// pre vietor

$(function () {
    $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=usdeur.json&callback=?', function (data) {

        $('#container_vietor').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Rychlosť vetra'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Kliknite pre priblíženie' : ''
            },
            xAxis: {
                type: 'datetime',
            },
            yAxis: {
                title: {
                    text: 'Dátum'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
            series: [{
                type: 'area',
                name: 'Vietor km/h:',
                data: <?php echo str_replace('"', '', $pole_vietor); ?>
            }]
        });
    });
});



// pre osvetlenie

$(function () {
    $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=usdeur.json&callback=?', function (data) {

        $('#container_osvetlenie').highcharts({
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Osvetlenie'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Kliknite pre priblíženie' : ''
            },
            xAxis: {
                type: 'datetime',
            },
            yAxis: {
                title: {
                    text: 'Dátum'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
            series: [{
                type: 'area',
                name: 'Osvetlenie lux:',
                data: <?php echo str_replace('"', '', $pole_osvetlenia); ?>
            }]
        });
    });
});

</script>


<div id="in"><script src="./design/js/highcharts.js"></script><script src="./design/js/highcharts-more.js"></script><script src="./design/js/modules/exporting.js"></script><div id="obsah">

		<!--// prvky na ovladanie intervalu -->
		<div style="margin-top:10px; margin-left:auto; margin-right:auto; width:350px">
		<form action="index.php" method="post">
		<select class="form-control"  id="rok" name="rok">
		<option value="0"> Rok všetky </option>
		<?php
		foreach($roky as $key => $value){
		if(isset($test)){ unset($test);}
		if(isset($_POST['rok']) && $value == $_POST['rok']){
		$test = 'selected="selected"';
		}else{$test ='';}
		echo '<option '.$test.' value="'.$value.'">'.$value.'</option>'; //close your tags!!
		}
		?>
		</select>

		<select style="margin-top:4px; "class="form-control" name="mesiac" id="mesiac">
		<option value="0"> Mesiac všetky</option>
		<?php

		foreach($mesiace as $key => $value):

		if(isset($test)){ unset($test);}
		if(isset($_POST['mesiac']) && $key == $_POST['mesiac']){
		$test = 'selected="selected"';
		}else{$test ='';}

		echo '<option '.$test.' value="'.$key.'">'.$value.'</option>'; //close your tags!!
		endforeach;
		?>
		</select>

		<select style="margin-top:4px"; class="form-control" name="den" id="den">
		<option value="0">Deň všetky</option>
		<?php
		$i = 1;
		while($i <= 31){

		if(isset($test)){ unset($test);}
		if(isset($_POST['den']) && $i == $_POST['den']){
		$test = 'selected="selected"';
		}else{$test ='';}

		echo '<option '.$test.' value="'.$i.'">'.$i.'</option>'; //close your tags!!
		$i++;
		}
		?>
		</select>
		<input type="submit"  class="btn btn-default" value="Odošli">
		<!--div style="width:100px; margin-left:auto; margin-right:auto; margin-top:25px;"> <input type="submit"  class="btn btn-default" value="Odošli"> </div-->
		</form> 
		</div>
	<div class='tabs tabs_animate'>
		<div id='tab-1'>
			<div id="container_teplota" style="width: 980px; height: 400px;"></div>		</div>
		<div id='tab-2'>
			<div id="container_tlak" style="width: 980px; height: 400px;"></div>		</div>
		<div id='tab-3'>
			<div id="container_vlhkost" style="width: 980px; height: 400px;"></div>		</div>
		<div id='tab-5'>
			<div id="container_vietor" style="width: 980px; height: 400px;"></div>		</div>
		<div id='tab-4'>
			<div id="container_osvetlenie" style="width: 980px; height: 400px;"></div>		</div>
		<ul class='horizontal'>
			<li><a href="#tab-1">Teplota</a></li>
			<li><a href="#tab-2">Tlak</a></li>
			<li><a href="#tab-3">Vlhkosť</a></li>
			<li><a href="#tab-5">Vietor</a></li>
			<li><a href="#tab-4">Osvetlenie</a></li>
		</ul>
	</div>
</div></div>
<!-- JS -->
	<script type="text/javascript" src="./design/js/vendor/waypoints.min.js"></script>
	<script type="text/javascript" src="./design/js/vendor/waypoints-sticky.min.js"></script>
	<script type="text/javascript" src="./design/js/vendor/jquery.tabslet.min.js"></script>
	<script src="./design/js/initializers.js"></script>

