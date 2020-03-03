<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
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
			<a href="home.php" class = 'active'>Home</a>
			<div class='dropdown'>
				<button class="dropbtn">Tools
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
					<a href="small_annotation.php">Few domains annotation</a>
					<a href="large_annotation.php">All domains annotation</a>
				</div>
			</div>
			<a href="help.php">Help</a>
			<a href="references.php">References</a>
			<a href="contact.php">Contact</a>
			<a href="javascript:void(0);" class="icon" onclick="nav_function()">&#9776;</a>
		</div>
	</div>