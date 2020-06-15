<?php

function generateRandomString($length = 10) {
	$characters_wo_digit = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = $characters_wo_digit[rand(0, strlen($characters_wo_digit) - 1)];
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];}
	return $randomString;};

function read_parameters_file($file_name, $separator="\t"){
	$file = fopen($file_name, "r");
	$data = array();
	while(!feof($file)){
		$line = fgets($file);
		$line = explode($separator, $line);
		$data[$line[0]] = $line[1];}
	return $data;}

function sort_multiarray($a, $b){
	if(count($a)==count($b)){
		return 0;}
	return count($a)<count($b)?1:-1;}

function submit($job_id, $email){
	global $appurl;
	global $approot;
	global $webdevel;
	$form = $_GET["form"];
	$parameters = read_parameters_file($approot."/jobs/".$job_id."/parameters.txt");
	$e_value = $parameters['E-value'];
	$dama = $parameters['DAMA'];
	$args = "-i ".escapeshellarg("$approot/jobs/".$job_id."/data.fa")." -N ".escapeshellarg($job_id)."  -e ".escapeshellarg($e_value)."  -W ".escapeshellarg("$approot/jobs/");
	if($_POST["dama"] == 'true'){
		$DAMA_evalue = $parameters['DAMA e-value'];	
		$overlappingAA = $parameters['Amino acids overlappping'];
		$overlappingMaxDomain = $parameters['Max domain overlapping (%)'];
		$args = $args." -a -E ".escapeshellarg($DAMA_evalue)." --overlappingAA ".escapeshellarg($overlappingAA)." --overlappingMaxDomain ".escapeshellarg($overlappingMaxDomain);}
	//Submit your job
	if ($form == 'small'){
		$pfam = $parameters['PFAM'];	
		$args = $args." -d ".escapeshellarg($pfam);
		$args = $args." -t ".escapeshellarg(2);
		$command="qsub -pe smp 1 -wd ".$approot."/jobs/".$job_id."/ -N $job_id -l h_rt=48:00:00 -b y /home/blachon/Documents/Tools/metaclade2/metaclade2 --remove-temp ".$args;}
	else if($form == 'large'){
		$args = $args." -t ".escapeshellarg(6);
		$command="qsub -pe smp 2 -wd ".$approot."/jobs/".$job_id."/ -N $job_id -l h_rt=48:00:00 -b y /home/blachon/Documents/Tools/metaclade2/metaclade2 --remove-temp ".$args;}
	else if ($form == 'clan'){
		$pfam = explode(" ", $parameters['Clan'])[0];	
		$args = $args." -D ".$approot."/data/clans/".escapeshellarg($pfam.".txt");	
		$args = $args." -t ".escapeshellarg(4);
		$command="qsub -pe smp 3 -wd ".$approot."/jobs/".$job_id."/ -N $job_id -l h_rt=48:00:00 -b y /home/blachon/Documents/Tools/metaclade2/metaclade2 --remove-temp ".$args;}
	$output = shell_exec("$command");
	$link = $appurl."/status.php?form=".$form."&job_id=".$job_id."&email=".$email; 
	$msg="<strong>Your job has been correctly submitted</strong><br><br>";
	$msg= $msg . "You can follow job progress as well as downloading the results going to <a target=_blank href=$link> $link </a><br>";
	$msg= $msg . "<br>Your data will be removed one month after the end of the job.<br>";
	$msg= $msg . "The job will be stopped if longer than 48 hours.<br>";
	$msg= $msg . "If you need some help, contact the web developer (".$webdevel.").<br>";
	return $msg;};
?>