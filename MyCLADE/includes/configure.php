<?php 
$appname  = 'MetaCLADE_webserver';
$appsroot = '/var/www/html'; #It is "/appsdata" on production whatever you want on development. Anyway, has to be accessible to apache
$approot  = $appsroot.'/'.$appname;  #Directory where the app is hosted. It has to be accessible from apache user
$hostname = 'http://localhost'; #hostname is "wwww.lcqb.upmc.fr" in production phase, your host ip during development
$appurl   = $hostname.'/'.$appname; #In production ask the sysadmin for making this address avalaible from the external.
$webdevel = 'life@is.hard'; #mail address of the webdevelopment
?>