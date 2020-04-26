<?php
include('./includes/configure.php');
include('./includes/logfunctions.php');
include('./includes/functions.php');


echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
	echo "<meta charset='utf-8' />";
	echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'> ";
	echo "<link rel='stylesheet' type='text/css'  href='".$appurl."/css_style/style.css'>";
	echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>";
	echo "<script src='https://kit.fontawesome.com/a076d05399.js'></script>";
	echo "<title>MetaCLADE</title></head>";


	echo "<body>";
		echo "<div id='container'>";
			echo "<div id='header-menu'>";
				echo "<header><h1>MyCLADE</h1></header>";
				echo "<div class='topnav' id='myTopnav'>";
					echo "<a href='".$appurl."/index.php' class = 'active'>Home</a>";
				echo "<div class='dropdown'>";
				echo "<button class='dropbtn'>Tools";
				echo "<i class='fa fa-caret-down'></i>";
				echo "</button>";
				echo "<div class='dropdown-content'>";
				echo "<a href='".$appurl."/small_annotation.php'>Few domains annotation</a>";
				echo "<a href='".$appurl."/large_annotation.php'>All domains annotation</a>";
				echo "</div>";
				echo "</div>";
				echo "<div class='dropdown'>";
				echo "<button class='dropbtn'>Help";
				echo "<i class='fa fa-caret-down'></i>";
				echo "</button>";
				echo "<div class='dropdown-content'>";
				echo "<a href='".$appurl."/help.php#input'>Input</a>";
				echo "<a href='".$appurl."/help.php#output'>Output</a>";
				echo "<a href='".$appurl."/help.php#method'>Method</a>";
				echo "</div>";
				echo "</div>";
				echo "<a href='".$appurl."/references.php'>References</a>";
				echo "<a href='".$appurl."/contact.php'>Contact</a>";
				echo "<a href='javascript:void(0);' class='icon' onclick='nav_function()'>&#9776;</a>";
				echo "</div>";
				echo "</div>";
?>