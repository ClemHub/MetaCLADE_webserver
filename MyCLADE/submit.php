<?php include("./includes/cookies.php"); ?>
<?php include("./includes/header.php"); ?>

	<section id='Submission'>
	<h2> Your job is running... </h2>
    <h3>This server is a multi-source domain annotation for quantitative <em>metagenomic and metatranscriptomic</em> functional profiling.</h3>
		<p class = 'text'>
		<?php 

		/*if($_GET['form']=='large'){
				$sequences = $_POST['sequences'];
				file_put_contents('./fasta_file/fasta_tmp.fa', $sequences);

				//Picking up of parameters informations
				$dama = $_SESSION['dama'];
				if($dama){
					$DAMA_evalue = $_SESSION['DAMA-evalue'];}
				$e_value = $_SESSION['evalue'];

				MetaCLADE program
				if($dama){./metaclade2/metaclade2 -i ./fasta_file/fasta_temp.fa -N results -d $pfam -W ../ -j 2}
				else{./metaclade2/metaclade2 -i ./fasta_file/fasta_temp.fa -a -N results -d $pfam -W ../ -j 2}

		}
		else if($_GET['small']=='large'){
				$sequences = $_POST['sequences'];
				file_put_contents('./fasta_file/fasta_tmp.fa', $sequences);
				$pfam = $_POST['pfam_domains'];
				$dama = $_SESSION['dama'];
				if($dama){
					$DAMA_evalue = $_SESSION['DAMA-evalue'];}
				$e_value = $_SESSION['evalue'];
				
				MetaCLADE program
				if($dama){./metaclade2/metaclade2 -i ./fasta_file/fasta_temp.fa -N results -d $pfam -W ../ -j 2}
				else{./metaclade2/metaclade2 -i ./fasta_file/fasta_temp.fa -a -N results -d $pfam -W ../ -j 2}

		} */

		$jobid = generateRandomString();

		$msg=submit($jobid, $email);

		$email = $_POST['email']; //email is a field with the email of the user. If you don't have, just remove this piece of code
		if($email){                                                                                                                                                                        
		$mail_header= "Subject: $appname queued ( $jobid )\n";
		$mail_header= $mail_header . "Content-Type: text/html\n";
		$mail_header= $mail_header . "MIME-Version:\n";
		$mail= $mail_header . "\n".$msg;
		sendMail($mail, "no-reply@lcqb.upmc.fr", $email);
		};

		header("location: $hostname/$appname/status.php?jobid=$jobid&email=$email");
		?>
	</section>


<?php include("./includes/footer.php"); ?>