<?php 
ini_set('display_errors',1);
echo '<?xml version="1.0" encoding="UTF-8"?>'
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Collmex2Mite :: Step 2</title>
	
</head>

<body>
	<form action="step3.php" method="POST">
		<table border="1" cellspacing="0">
		<thead>
			<tr>
				<th>Import?</th>
				<th>Projekt Mite</th>
				<th>Tätigkeit</th>
				<th>Beschreibung</th>
				<th>Datum</th>
				<th>Start</th>
				<th>Ende</th>
				<th>Pausen</th>
			</tr>
		</thead>
<?php
require_once 'lib/Mite/Mite.php';
// instantiate the object with your credentials
$mite = new Mite\Mite('https://airmotion.mite.yo.lk', '9163f83ab1bebcb');

foreach($_POST['data'] as $rowNbr => $row)	{
	$fields = unserialize(base64_decode($row));
	$project = unserialize(base64_decode($_POST['projects'][$rowNbr]));

	$data = array('csv' => $fields, 'project' => $project);
	
	if($fields[0] == 'CMXACT')	{

		$string = '<tr>
			<td><label><input type="checkbox" value="'.base64_encode(serialize($data)).'" name="import[]"/>Import?</label></td>
			<td>%s (%d)</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			<td>%s</td>
			';
		printf($string,$project->name,$project->id,$fields[4],utf8_encode($fields[5]),DateTime::createFromFormat('Ymd',$fields[6])->format('d.m.Y'),$fields[7],$fields[8],$fields[9]);

	}
	echo '</tr>';
	
}
?>
		</table>
		<input type="submit">
	</form>
</body>
</html>
