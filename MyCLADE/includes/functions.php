<?php

function generateRandomString($length = 10) {
	$characters_wo_digit = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = $characters_wo_digit[rand(0, strlen($characters_wo_digit) - 1)];
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];}
	return $randomString;};

function submit($job_id, $email){
	global $appurl;
	global $approot;
	global $webdevel;
	$sequences = $_SESSION['sequences'];
	echo '<br>';
	file_put_contents($approot.'/MyCLADE/jobs/'.$job_id.'/data.fa', $sequences);
	$e_value = $_SESSION['evalue'];
	$dama = $_SESSION['dama'];
	$form = $_GET["form"];
	$nb_jobs = 2;
	$args = "-i ".escapeshellarg("$approot/MyCLADE/jobs/".$job_id."/data.fa")." -N ".escapeshellarg($job_id)."  -e ".escapeshellarg($e_value)."  -W ".escapeshellarg("$approot/MyCLADE/jobs/".$job_id)."  -j ".escapeshellarg($nb_jobs);
	if($dama == 'true'){
		$DAMA_evalue = $_SESSION['DAMA-evalue'];	
		$args = $args." -a -E ".escapeshellarg($DAMA_evalue);}
	if($_GET['form']=='small'){
		$pfam = $_SESSION['pfam_domains'];	
		$args = $args." -d ".escapeshellarg($pfam);}
	//Submit your job
	if ($form == 'small'){
		$command="qsub -wd ".$approot."/MyCLADE/jobs/".$job_id."/ -N $job_id ".$approot."/MyCLADE/run_small.sh ".$args;}
	else if($form == 'large'){
		$command="qsub -wd ".$approot."/MyCLADE/jobs/".$job_id."/ -N $job_id ".$approot."/MyCLADE/run_large.sh ".$args;}
	$output = shell_exec("$command");
	$link = $appurl."/MyCLADE/status.php?form=".$form."&job_id=".$job_id."&email=".$email; 
	$msg="<strong>Your job has been correctly submitted</strong><br><br>";
	$msg= $msg . "You can follow job progress as well as downloading the results going to <a target=_blank href=$link> $link </a><br>";
	$msg= $msg . "<br>Your data will be removed one month after the end of the job.<br>";
	$msg= $msg . "The job will be stopped if longer than 48 hours.<br>";
	$msg= $msg . "If you need some help, contact the web developer (".$webdevel.").<br>";
	return $msg;};

function read_parameters_file($file_name, $separator="\t"){
	$file = fopen($file_name, "r");
	$data = array();
	while(!feof($file)){
		$line = fgets($file);
		$line = explode($separator, $line);
		$data[$line[0]] = $line[1];}
	return $data;}
?>