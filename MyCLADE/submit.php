
<?php
include("./includes/header.php"); 
?>

	<section id='Submission'>
	<h2> Your job is running... </h2>
    <h3>This server is a multi-source domain annotation for quantitative <em>metagenomic and metatranscriptomic</em> functional profiling.</h3>
		<p class = 'text'>
		<?php 

		function submit($jobid, $email){
			mkdir('http://localhost/MetaCLADE_webserver/MyCLADE/jobs/'.$jobid, 0, true);
			$sequences = $_POST['sequences'];
			echo $sequences.'<br>';
			file_put_contents('http://localhost/MetaCLADE_webserver/MyCLADE/jobs/'.$jobid.'/data.fa', $sequences);
			$e_value = $_SESSION['evalue'];
			echo 'e-value: '.$e-value.' <br>';
			$dama = $_SESSION['dama'];
			echo 'DAMA: '.$dama.' <br>';
			$args = escapeshellarg('-i http://localhost/MetaCLADE_webserver/MyCLADE/jobs/'.$jobid.'/data.fa')." ".escapeshellarg('-N '.$jobid)." ".escapeshellarg('-e '.$e_value)." ".escapeshellarg('-W http://localhost/MetaCLADE_webserver/MyCLADE/jobs/'.$jobid)." ".escapeshellarg('--sge ')." ".escapeshellarg('--pe smp ')." ".escapeshellarg('-j '.$nb_jobs)." ";
			if($dama == 'true'){
				$DAMA_evalue = $_SESSION['DAMA-evalue'];
				echo 'DAMA e-value: '.$DAMA_evalue.' <br>';	
				$args = $args . escapeshellarg('-a ') . escapeshellarg(' -E ' . $DAMA_evalue);}
			if($_GET['form']=='small'){
				$pfam = $_POST['pfam_domains'];
				echo 'PFAM domains: '.$pfam.' <br>';	
				$args = $args .escapeshellarg('-d '.$pfam);}
			//ARGS is the list of arguments you have extracted from your form. Only this is escaped because it is the only things given by the user. 
			//Sublit your job
			echo "command is launch<br>";
			$command="qsub -wd http://localhost/MetaCLADE_webserver/MyCLADE/jobs -N $jobid http://localhost/MetaCLADE_webserver/MyCLADE/run.sh " . $args;
			echo 'command: '.$command.'<br>';
			$output = shell_exec("$command");
			echo "after shell_exec<br>";
			echo 'output: '.$output.'<br>';
			//$link="$appurl/status.php?jobid=$jobid"; #status.php is a page that show he status of your job
			$msg="<strong>Your job has been correctly submitted</strong><br><br>";
			$msg= $msg . "You can follow job progress as well as downloading the results going to <a target=_blank href=$link> $link </a><br>";
			$msg= $msg . "<br>Your data will be removed one month after the end of the job.<br>";
			$msg= $msg . "The job will be stopped if longer than 48 hours.<br>";
			$msg= $msg . "If you need some help, contact the web developer ($webdevel).<br>";
			return $msg;};

		$jobid = generateRandomString();
		echo 'Your job ID is:'.$jobid;
		$msg = submit($jobid, $email);
		echo $msg;
		$email = $_POST['email'];
		if($email){
			$mail_header= "Subject: $appname queued ( $jobid )\n";
			$mail_header= $mail_header . "Content-Type: text/html\n";
			$mail_header= $mail_header . "MIME-Version:\n";
			$mail= $mail_header . "\n".$msg;
			sendMail($mail, "no-reply@lcqb.upmc.fr", $email);};

		//redirection vers page de resultats avec ?form et ?jobid
		//header("location: $hostname/$appname/status.php?job_id=$jobid&email=$email");
		?>
	</section>


<?php include("./includes/footer.php"); ?>