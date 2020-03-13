<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<link rel="stylesheet" type="text/css"  href="http://localhost/css_style/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src='https://kit.fontawesome.com/a076d05399.js'></script>
	<title>Home</title>
</head>

<body>
<div id='container'>
	<div id="header-menu">
		<header><h1>MyCLADE</h1></header>
		<div class="topnav" id="myTopnav">
			<a href="http://localhost/MyCLADE/home.php" class = 'active'>Home</a>
			<div class='dropdown'>
				<button class="dropbtn">Tools
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
					<a href="http://localhost/MyCLADE/small_annotation.php">Few domains annotation</a>
					<a href="http://localhost/MyCLADE/large_annotation.php">All domains annotation</a>
				</div>
			</div>
			<div class='dropdown'>
				<button class="dropbtn">Help
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
					<a href="http://localhost/MyCLADE/help.php#input">Input</a>
					<a href="http://localhost/MyCLADE/help.php#output">Output</a>
					<a href="http://localhost/MyCLADE/help.php#method">Method</a>
				</div>
			</div>
			<a href="http://localhost/MyCLADE/references.php">References</a>
			<a href="http://localhost/MyCLADE/contact.php">Contact</a>
			<a href="javascript:void(0);" class="icon" onclick="nav_function()">&#9776;</a>
		</div>
	</div>

	<?php

	function results_to_db($conn, $name_file){
		$file = file($name_file);
		foreach($file as $row){
			$row = preg_split("/\t/", $row);
			$sql = "INSERT INTO MetaCLADE_results VALUES ('$row[0]', $row[1], $row[2], $row[3], '$row[4]', '$row[5]', $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], '$row[12]')";
			$request = $conn->query($sql);}
			if(!request){
				echo("Error description: " . $mysqli -> error);}}
	?>