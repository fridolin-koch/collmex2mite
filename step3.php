<?php 
ini_set('display_errors',1);
echo '<?xml version="1.0" encoding="UTF-8"?>'
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Collmex2Mite :: Step 3</title>
	<meta charset="utf-8">
	
</head>

<body>
<?php
require_once 'lib/Mite/Mite.php';
require_once './config.php';
// instantiate the object with your credentials
$mite = new Mite\Mite($miteConfig['endpoint'], $miteConfig['apiKey']);

foreach($_POST['import'] as $import)
{
	$entry = unserialize(base64_decode($import));
	
	$date = DateTime::createFromFormat('Ymd',$entry['csv'][6]);
	
	$start = explode(':',$entry['csv'][7]);
	$end = explode(':',$entry['csv'][8]);
	$pause = explode(':',$entry['csv'][9]);
	
	$startDate = clone $date;
	$endDate = clone $date;
	
	$startDate->setTime($start[0],$start[1]);
	
	$endDate->setTime($end[0],$end[1]);
	//create pause interval
	$pauseInterval = new DateInterval('PT' . intval($pause[0]) . 'H' . intval($pause[1]) . 'M' );
	
//	echo $endDate->format('r') . ' |<br>';
	$endDate->sub($pauseInterval);
//	echo $endDate->format('r'). ' |<br>';
	
	$dateDiff = $startDate->diff($endDate);
	
	//var_dump($dateDiff);
	//var_dump( $dateDiff->h*60 + $dateDiff->i );
	$worktime = ($dateDiff->h*60 + $dateDiff->i);
	
	$newtime = $mite->addTime(
	    $date->format('Y-m-d'),          // date of time entry
	    $worktime,                    // time in seconds
	    utf8_encode($entry['csv'][5]), 
	    $mite->getMyself()->id, // user id
	    $entry['project']->id,        // optional: project id
	    false                   // optional: service id
	);
	
	printf('<p>%s - %s bis %s (Pause: %s) | %s min | %s | %s | %s | %s</p>', $date->format('Y-m-d'), $entry['csv'][7] , $entry['csv'][8], $entry['csv'][9] ,$worktime,utf8_encode($entry['csv'][5]),$mite->getMyself()->id,$entry['project']->id,false);

}

?>

</body>
</html>
