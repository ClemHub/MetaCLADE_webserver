<?php 
$appname  = 'myclade';
$appdir = 'MetaCLADE_webserver';
$appsroot = '/var/www/html'; #It is "/appsdata" on production whatever you want on development. Anyway, has to be accessible to apache
$approot  = $appsroot.'/'.$appdir;  #Directory where the app is hosted. It has to be accessible from apache user
//$hostname = 'http://localhost:1234'; #hostname is "wwww.lcqb.upmc.fr" in production phase, your host ip during development
$hostname = 'http://www.lcqb.upmc.fr'; #hostname is "wwww.lcqb.upmc.fr" in production phase, your host ip during development
$appurl   = $hostname.'/'.$appname; #In production ask the sysadmin for making this address avalaible from the external.
$webdevel = 'clemence.blachon@sorbonne.universite.fr'; #mail address of the webdevelopment
$metaclade_root = "/home/blachon/Documents/Tools/";
?>
