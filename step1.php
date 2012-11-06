<?php 
ini_set('display_errors',1);
echo '<?xml version="1.0" encoding="UTF-8"?>'
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Collmex2Mite :: Step 1</title>
	<meta charset="utf-8">
</head>

<body>
<?php
require_once 'lib/Mite/Mite.php';
// instantiate the object with your credentials
$mite = new Mite\Mite('https://airmotion.mite.yo.lk', '9163f83ab1bebcb');
$projectSelector = '';
foreach($mite->getProjects() as $project)	
{
	if(!$project->archived)
		$projectSelector .= '<option value="'.base64_encode(serialize($project)).'">' . $project->name . ' ('.$project->customer_name.')</option>';
}

?>
	<form action="step2.php" method="POST">
		<table>
		<thead>
			<tr>
				<th>Projekt</th>
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
		$lines = file($_FILES['uploadedfile']['tmp_name']);

		foreach($lines as $key => $line)
		{	
			echo '<tr>';
			$fields = str_getcsv($line,';',null);
			//var_dump($fields);
			if($fields[0] == 'CMXACT')	{

				$string = '	<td><input type="hidden" name="data[]" value="'.base64_encode(serialize($fields)).'" />%s</td>
							<td><select name="projects[]">%s</select></td>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>';
				printf($string,$fields[1],$projectSelector,$fields[4],utf8_encode($fields[5]),$fields[6],$fields[7],$fields[8],$fields[9]);

			}
			echo '</tr>';
		}
	
		?>
		</table>
		<input type="submit">
	</form>
</body>
</html>
