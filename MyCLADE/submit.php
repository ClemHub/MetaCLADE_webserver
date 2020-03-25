
<?php
include("./includes/header.php"); 
?>

	<section id='Submission'>
	<h2> Your job is running... </h2>
    <h3>This server is a multi-source domain annotation for quantitative <em>metagenomic and metatranscriptomic</em> functional profiling.</h3>
		<p class = 'text'>
		<?php 

		function generateRandomString($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];}
			return $randomString;};

		function submit($jobid, $email){
			//ARGS is the list of arguments you have extracted from your form. Only this is escaped because it is the only things given by the user.
<<<<<<< HEAD
			$command="qsub -w $approot/jobs -N $jobid $approot/run.sh ".escapeshellarg(ARGS); 
			//Sublit your job
			shell_exec("$command");
			$link="$appurl/status.php?jobid=$jobid"; #status.php is a page that show he status of your job
			$msg="<strong>Your job has been correctly submitted</strong><br><br>";
			$msg= $msg . "You can follow job progress as well as downloading the results going to <a target=_blank href=$link> $link </a><br>";
			$msg= $msg . "<br>Your data will be removed one month after the end of the job.<br>";
			$msg= $msg . "The job will be stopped if longer than 48 hours.<br>";
			$msg= $msg . "If you need some help, contact the web developer ($webdevel).<br>";
			return $msg;};

		if($_GET['form']=='large'){
				$sequences = $_POST['sequences'];
				file_put_contents('./fasta_file/fasta_tmp.fa', $sequences);
=======
				$command="qsub -w $approot/jobs -N $jobid $approot/run.sh ".escapeshellarg(ARGS); 
			//Sublit your job
				shell_exec("$command");
			
				$link="$appurl/status.php?jobid=$jobid"; #status.php is a page that show he status of your job
				$msg="<strong>Your job has been correctly submitted</strong><br><br>";
				$msg= $msg . "You can follow job progress as well as downloading the results going to <a target=_blank href=$link> $link </a><br>";
				$msg= $msg . "<br>Your data will be removed one month after the end of the job.<br>";
				$msg= $msg . "The job will be stopped if longer than 48 hours.<br>";
				$msg= $msg . "If you need some help, contact the web developer ($webdevel).<br>";
>>>>>>> 363cb446f3bb8db09ecd62e4fe08b73284d60df0

				return $msg;};

		$jobid = generateRandomString();
		mkdir('http://localhost/MetaCLADE_webserver/MyCLADE/jobs/'.$jobid);
		$sequences = $_POST['sequences'];
		file_put_contents('http://localhost/MetaCLADE_webserver/MyCLADE/jobs/'.$jobid.'/data.fa', $sequences);
		$e_value = $_SESSION['evalue'];
		if($_GET['form']=='large'){
				$dama = $_SESSION['dama'];
				if($dama){
<<<<<<< HEAD
					$DAMA_evalue = $_SESSION['DAMA-evalue'];}
				$e_value = $_SESSION['evalue'];}
=======
					$DAMA_evalue = $_SESSION['DAMA-evalue'];}}
>>>>>>> 363cb446f3bb8db09ecd62e4fe08b73284d60df0

		else if($_GET['small']=='large'){
				$pfam = $_POST['pfam_domains'];
				$dama = $_SESSION['dama'];
				if($dama){
<<<<<<< HEAD
					$DAMA_evalue = $_SESSION['DAMA-evalue'];}
				$e_value = $_SESSION['evalue'];}

		$jobid = generateRandomString();
=======
					$DAMA_evalue = $_SESSION['DAMA-evalue'];}}
>>>>>>> 363cb446f3bb8db09ecd62e4fe08b73284d60df0

		$msg = submit($jobid, $email);

		$email = $_POST['email'];
<<<<<<< HEAD
		if($email){                                                                                                                                                                        
=======
		if($email){
>>>>>>> 363cb446f3bb8db09ecd62e4fe08b73284d60df0
			$mail_header= "Subject: $appname queued ( $jobid )\n";
			$mail_header= $mail_header . "Content-Type: text/html\n";
			$mail_header= $mail_header . "MIME-Version:\n";
			$mail= $mail_header . "\n".$msg;
			sendMail($mail, "no-reply@lcqb.upmc.fr", $email);};

<<<<<<< HEAD
		header("location: $hostname/$appname/status.php?jobid=$jobid&email=$email");

	?>
=======
		header("location: $hostname/$appname/status.php?job_id=$jobid&email=$email");
		?>
>>>>>>> 363cb446f3bb8db09ecd62e4fe08b73284d60df0
	</section>


<?php include("./includes/footer.php"); ?>