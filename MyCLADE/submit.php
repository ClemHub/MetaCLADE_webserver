
<?php
include("./includes/header.php"); 
?>

	<section id='Submission'>
	<h2> Your job is running... </h2>
    <h3>This server is a multi-source domain annotation for quantitative <em>metagenomic and metatranscriptomic</em> functional profiling.</h3>
		<p class = 'text'>
		<?php

		$jobid = generateRandomString();
		echo 'Your job ID is:'.$jobid,'<br>';
		//mkdir($appurl.'/MyCLADE/jobs/'.$jobid, 0, true);
		$msg = submit($jobid, $email);
		echo $msg;
		$email = $_POST['email'];
		if($email){
			$mail_header= "Subject: $appname queued ( $jobid )\n";
			$mail_header= $mail_header . "Content-Type: text/html\n";
			$mail_header= $mail_header . "MIME-Version:\n";
			$mail= $mail_header . "\n".$msg;
			sendMail($mail, "no-reply@lcqb.upmc.fr", $email);};

		header("location: $hostname/$appname/status.php?job_id=$jobid&email=$email");
		?>
	</section>


<?php include("./includes/footer.php"); ?>