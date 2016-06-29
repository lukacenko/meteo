<div id="in"><script src="./design/js/highcharts.js"></script><script src="./design/js/highcharts-more.js"></script><script src="./design/js/modules/exporting.js"></script>

<div id="obsah" style="width:650px; margin-left:auto; margin-right:auto;">
<p>
<b>Technické vybavenie meteostanice </b>pozostáva z mini počítača Banana PI, Arduino uno, a modulu Arduino nano na neho napojených snímačov teploty,
vlhkosti a atmosférického tlaku.
</p>
<img src="obrazky/bananapi.jpg"  style="width:300px; height:230px;">  
<img src="obrazky/ds18b20.jpg"  style="width:300px; height:230px;">  
<br />
<p>
<b>Banana Pi </b>je jednodoskový mini počítač veľmi podobný populárnemu Raspberry Pi alebo Orange Pi. 
Vychádza z veľmi podobnej architektúry, avšak ponúka oveľa väčší výkon. Základom počítače je dvoj-jadrový procesor ARM Cortex-A7 s integrovaným GPU jadrom Mali400MP2. 
Doska obsahuje integrovanú operačnú pamäť 1 GB DDR3. Doska neobsahuje žiadnu interní pamäť pre operační systém alebo na  ukladanie súborov, 
ponúka však možnosť pripojenia SD karty či SATA rozhranie pre pripojenie pevného disku. K pripojeniu zobrazovacej jednotky slúži konektor HDMI alebo kompozitný RCA. 
Zvukový výstup ide zapojiť pomocou 3,5 mm JACK alebo HDMI. Na doske sa nachádza tiež integrovaný mikrofón. 
Oproti Raspberry Pi má Banana Pi priamo napojený ethernet adaptér 10/100/1000 s konektorom RJ45. Základnej doska obsahuje dva konektory USB 2.0 a jedného konektoru USB micro slúžiaci pre napájanie.
</p>
<br /><p>
<img src="obrazky/uno.jpg"  style="width:300px; height:230px;">  
<img src="obrazky/nano.jpg" style="width:300px; height:230px;">   
</p>
<br /><p>
Pre meranie teploty je použitý <b>snímač Dallas DS18B20,</b> používajúci tzv. "1-wire" pripojenie. Pripojené k trom vodičom - teda +, zem a data. 
<b>Senzor Bosch BMP180</b> bol vybraný z dôvodu malých rozmerov,
zaradením do kategórie pre predpoveď počasia a jeho malého prúdového odberu.
Napájacie napätie senzora sa pohybuje medzi 1,8 - 3,6 V, čo umožňuje pripojiť
senzor priamo na zbernici I2C, cez ktorú komunikuje. Udávaná absolútna presnosť
senzora pre meranie tlaku je stanovená na ± 1 hPa a pri meraní teploty na ± 1 ∘C. výhoda
tohto senzora je v zobrazovanie teploty na desatinné miesta a zobrazenie tlaku
v jednotkách pascalov.
</p>

<br />
<p>
<b>Senzor BH1750</b> bol vybraný pre meranie denného osvetlenia a grafického znázornenia
doby trvania dňa. Výhodou tohto senzora sú výstupné dáta, ktoré sa nemusí prepočítavať
alebo inak upravovať, pretože výstupná hodnota je priamo v jednotkách
luxoch.

</P>
<br /><P>
<b>Program. </b>
Celé zariadenie sa programuje v jazyku C. Samotný program je súčasťou arduino uno a arduino nano.
Programovanie je veľmi jednoduché, stačí základná znalosť C a PHP.
Program posiela každých 5 minút nasnímané hodnoty internetom do MySQL databázy, kde sa hodnoty ukladajú a pomocou PHP skriptov
následne zobrazujú u návštevníkov webovej stránky. 
</P>
<img src="obrazky/stanica.jpg"  style="width:600px; height:270px;">  
</div></div>