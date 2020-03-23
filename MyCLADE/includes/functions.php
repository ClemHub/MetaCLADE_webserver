<?php 
include 'configure.php';
include 'logfunctions.php';

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];}
	return $randomString;};

function submit($jobid, $email){
//Prepare the environment for the script (saving files for example)

//ARGS is the list of arguments you have extracted from your form. Only this is escaped because it is the only things given by the user.
	$command="qsub -w $approot/jobs -N $jobid $approot/run.sh ".escapeshellarg(ARGS); 
//Sublit your job
	shell_exec("$command");

	$link="$appurl/status.php?jobid=$jobid"; #status.php is a page that show he status of your job

	$msg="<strong>Your job has been correctly submitted</strong><br><br>";
	$msg= $msg . "You can follow job progress as well as downloading the results going to <a target=_blank href=$link> $link </a><br>";

	$msg= $msg . "<br>Your data will be removed one month after the end of the job.<br>";
	$msg= $msg . "The job will be stopped if longer than 48 hours.<br>";


	$msg= $msg . "If you need some help, contact the web developer ($webdevel).<br>";

	return $msg;
};

function results_to_db($conn, $name_file){
	$file = file($name_file);
	foreach($file as $row){
		$row = preg_split("/\t/", $row);
		$sql = "INSERT INTO MetaCLADE_results VALUES ('$row[0]', $row[1], $row[2], $row[3], '$row[4]', '$row[5]', $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], '$row[12]')";
		$request = $conn->query($sql);}
		if(!request){
			echo("Error description: " . $mysqli -> error);}};