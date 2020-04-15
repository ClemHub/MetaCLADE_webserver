<?php
function results_to_db($conn, $name_file){
	$file = file($name_file);
	foreach($file as $row){
		$row = preg_split("/\t/", $row);
		$sql = "INSERT INTO MetaCLADE_results VALUES ('$row[0]', $row[1], $row[2], $row[3], '$row[4]', '$row[5]', $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], '$row[12]')";
		$request = $conn->query($sql);}
		if(!request){
			echo("Error description: " . $mysqli -> error);}};

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];}
	return $randomString;};

function submit($jobid, $email){
	$sequences = $_SESSION['sequences'];
	//echo $sequences.'<br>';
	echo file_exists($appsroot.'/MetaCLADE_webserver/MyCLADE/jobs/'.$jobid);
	echo '<br>';
	file_put_contents($appsroot.'/MetaCLADE_webserver/MyCLADE/jobs/'.$jobid.'/data.fa', $sequences);
	$e_value = $_SESSION['evalue'];
	//echo 'e-value: '.$e-value.' <br>';
	$dama = $_SESSION['dama'];
	//echo 'DAMA: '.$dama.' <br>';
	$args = escapeshellarg('-i /var/www/html/MetaCLADE_webserver/MyCLADE/jobs/'.$jobid.'/data.fa')." ".escapeshellarg('-N '.$jobid)." ".escapeshellarg('-e '.$e_value)." ".escapeshellarg('-W http://localhost:1234/MetaCLADE_webserver/MyCLADE/jobs/'.$jobid)." ".escapeshellarg('--sge ')." ".escapeshellarg('--pe smp ')." ".escapeshellarg('-j '.$nb_jobs)." ";
	if($dama == 'true'){
		$DAMA_evalue = $_SESSION['DAMA-evalue'];
		//echo 'DAMA e-value: '.$DAMA_evalue.' <br>';	
		$args = $args . escapeshellarg('-a ') . escapeshellarg(' -E ' . $DAMA_evalue);}
	if($_GET['form']=='small'){
		$pfam = $_SESSION['pfam_domains'];
		//echo 'PFAM domains: '.$pfam.' <br>';	
		$args = $args .escapeshellarg('-d '.$pfam);}
	//ARGS is the list of arguments you have extracted from your form. Only this is escaped because it is the only things given by the user. 
	//Sublit your job
	echo "command is launch<br>";
	$command="qsub -wd /var/www/html/MetaCLADE_webserver/MyCLADE/jobs/".$jobid."/ -N $jobid /var/www/html/MetaCLADE_webserver/drafts/run_test.sh";
	echo 'command: '.$command.'<br>';
	$output = shell_exec("$command");
	echo "after shell_exec<br>";
	echo 'output: '.$output.'<br>';
	//$link="$appurl/status.php?jobid=$jobid"; #status.php is a page that show he status of your job
	$msg="<strong>Your job has been correctly submitted</strong><br><br>";
	//$msg= $msg . "You can follow job progress as well as downloading the results going to <a target=_blank href=$link> $link </a><br>";
	$msg= $msg . "<br>Your data will be removed one month after the end of the job.<br>";
	$msg= $msg . "The job will be stopped if longer than 48 hours.<br>";
	$msg= $msg . "If you need some help, contact the web developer ($webdevel).<br>";
	return $msg;};
?>