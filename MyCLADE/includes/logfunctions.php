<?php
include "configure.php";

//Executed at the beginning of a PHP script, get basinc info from the URL and sets up some variables.
//You can modify it according to your set
function startup(&$jobid, &$jobdir)
{
global $approot;
if(isset($_GET["jobid"]))
  {
	$jobid=$_GET["jobid"];
	$jobdir="$approot/jobs/$jobid";
    return true;
  }
else{
	$jobid=null;
    $jobdir=null;
	return false;
   }
}

#Useful for debugging
#Logs $line to the apacher error_log. 
function logline($line)
{
$bt=debug_backtrace();
global $jobid;
if(count($bt)>2)
    $logstr_root=basename($bt[1]['file']).":".$bt[1]['line']."( $jobid )";
else
   $logstr_root="( $jobid )";
error_log("$logstr_root:$line");
}

#Useful for debugging.
#Executes $cmd and logs it on error_log apache file
function exec_and_log($cmd, &$lines=null){

	global $jobid;
	$result=array();
	exec($cmd, $result, $return_var );
	if($return_var){
		logline("[exitcode:$return_var]: $cmd");
		foreach ($result as $line) {
  		  logline($line);
		}
		}
	else
		{
 		 $lines= $result;
		}
return $return_var;
}
#Useful for debugging.
#Like fopen but logs it on error_log apache file
function fopen_and_log($filename,$fmode)
{
$stream = fopen( $filename,$fmode);
 if($stream==FALSE)
  {logline("Unable to open file $filename");
  }
 return $stream;
}


?>