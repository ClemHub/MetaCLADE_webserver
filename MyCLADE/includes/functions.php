<?php
function results_to_db($conn, $name_file){
	$file = file($name_file);
	foreach($file as $row){
		$row = preg_split("/\t/", $row);
		$sql = "INSERT INTO MetaCLADE_results VALUES ('$row[0]', $row[1], $row[2], $row[3], '$row[4]', '$row[5]', $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], '$row[12]')";
		$request = $conn->query($sql);}
		if(!request){
			echo("Error description: " . $mysqli -> error);}};


?>