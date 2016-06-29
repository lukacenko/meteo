<?php
ini_set('display_errors', 0);
$root = 'cam';

$it = new RecursiveDirectoryIterator("cam/");
$display = Array ( 'jpeg', 'jpg' );
$pole = array();

foreach(new RecursiveIteratorIterator($it) as $file)
{
    if (in_array(strtolower(array_pop(explode('.', $file))), $display))
	{
		$pole[filemtime($file)]  = $file;
		//echo $file . "<br/> \n";
		//unlink($file);
		}
}
	ksort($pole);
	$posledne = end($pole);
	$posledne = $posledne->getPathName();

foreach(new RecursiveIteratorIterator($it) as $file)
{
    if (in_array(strtolower(array_pop(explode('.', $file))), $display))
	{
		$pole[filemtime($file)]  = $file;
		if($file != $posledne){ 
		unlink($file);
		}
		}
}
?>
<img src="<?php echo $posledne; ?>" style="display: block;margin: 0 auto;">

