<?php
include("./includes/cookies.php");
include('./includes/configure.php');
include('./includes/logfunctions.php');
include('./includes/functions.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<!-- <link rel="stylesheet" type="text/css"  href="http://localhost:8888/MetaCLADE_webserver/css_style/style.css"> -->
	<link rel="stylesheet" type="text/css"  href="http://localhost:1234/MetaCLADE_webserver/MyCLADE/css_style/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src='https://kit.fontawesome.com/a076d05399.js'></script>
	<title>Home</title>
</head>

<body>
<div id='container'>
	<div id="header-menu">
		<header><h1>MyCLADE</h1></header>
		<div class="topnav" id="myTopnav">
			<!-- <a href="http://localhost:8888/MetaCLADE_webserver/MyCLADE/home.php" class = 'active'>Home</a> -->
			<a href="http://localhost:1234/MetaCLADE_webserver/MyCLADE/home.php" class = 'active'>Home</a>
			<div class='dropdown'>
				<button class="dropbtn">Tools
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
					<a href="http://localhost:1234/MetaCLADE_webserver/MyCLADE/small_annotation.php">Few domains annotation</a>
					<!-- <a href="http://localhost:8888/MetaCLADE_webserver/MyCLADE/small_annotation.php">Few domains annotation</a> -->
					<a href="http://localhost:1234/MetaCLADE_webserver/MyCLADE/large_annotation.php">All domains annotation</a>
					<!-- <a href="http://localhost:8888/MetaCLADE_webserver/MyCLADE/large_annotation.php">All domains annotation</a> -->
				</div>
			</div>
			<div class='dropdown'>
				<button class="dropbtn">Help
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
					<!-- <a href="http://localhost:8888/MetaCLADE_webserver/MyCLADE/help.php#input">Input</a> -->
					<a href="http://localhost:1234/MetaCLADE_webserver/MyCLADE/help.php#input">Input</a>
					<!-- <a href="http://localhost:8888/MetaCLADE_webserver/MyCLADE/help.php#output">Output</a> -->
					<a href="http://localhost:1234/MetaCLADE_webserver/MyCLADE/help.php#output">Output</a>
					<!-- <a href="http://localhost:8888/MetaCLADE_webserver/MyCLADE/help.php#method">Method</a> -->
					<a href="http://localhost:1234/MetaCLADE_webserver/MyCLADE/help.php#method">Method</a>
				</div>
			</div>
			<!-- <a href="http://localhost:8888/MetaCLADE_webserver/MyCLADE/references.php">References</a> -->
			<a href="http://localhost:1234/MetaCLADE_webserver/MyCLADE/references.php">References</a>
			<!-- <a href="http://localhost:8888/MetaCLADE_webserver/MyCLADE/contact.php">Contact</a> -->
			<a href="http://localhost:1234/MetaCLADE_webserver/MyCLADE/contact.php">Contact</a>
			<a href="javascript:void(0);" class="icon" onclick="nav_function()">&#9776;</a>
		</div>
	</div>