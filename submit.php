<?php
include("./includes/header.php"); 
?>
	<section id='Submission'>
	<h2> Your job has been submitted... </h2>
		<p class = 'text'>
		<?php
		$form = $_GET['form'];
		$job_id = generateRandomString()."_".date("dmY");
		echo 'Your job ID is: '.$job_id,'<br>';
		$oldmask = umask(0);
		mkdir($approot.'/jobs/'.$job_id, 0777, true);
		umask($oldmask);
		if ($form == 'visualization'){
			file_put_contents($approot."/jobs/".$job_id."/visualization.txt", $_POST["sequences"]);
			header("location: $hostname/$appname/results.php?form=".$form."&job_id=".$job_id);}
		else{
			$email = $_POST['email'];
			if($_POST['library'] == 'true'){
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Library\tComplete\n", FILE_APPEND);
				$parameters['Library'] = "Complete";}}
			else if($_POST['library'] == 'false'){
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Library\tReduced\n", FILE_APPEND);
				$parameters['Library'] = "Reduced";}}
			if($form=='small'){
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "PFAM\t".$_POST["pfam_domains"]."\n", FILE_APPEND);
				$parameters['PFAM'] = $_POST["pfam_domains"];
			}
			else if($form=='clan'){
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Clan\t".$_POST["clan"]."\n", FILE_APPEND);
				$parameters['Clan'] = $_POST["clan"];}
			file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "DAMA\t".$_POST["dama"]."\n", FILE_APPEND);		
			$parameters['DAMA'] = $_POST["dama"];
			file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "E-value\t".$_POST["evalue_nb"]."\n", FILE_APPEND);
			$parameters['E-value'] = $_POST["evalue_nb"];
			if($_POST["dama"] == 'true'){
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "DAMA e-value\t".$_POST["dama_evalue_nb"]."\n", FILE_APPEND);
				$parameters['DAMA e-value'] = $_POST["dama_evalue_nb"];
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Amino acids overlappping\t".$_POST["overlappingAA_nb"]."\n", FILE_APPEND);
				$parameters['Amino acids overlappping'] = $_POST["overlappingAA_nb"];
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Max domain overlapping (%)\t".$_POST["overlappingMaxDomain_nb"]."\n", FILE_APPEND);
				$parameters['Max domain overlapping (%)'] = $_POST["overlappingMaxDomain_nb"];}
			file_put_contents($approot.'/jobs/'.$job_id.'/data.fa', $_POST["sequences"]);
			$msg = submit($job_id, $parameters);
			echo $msg;
			if($_POST['email'] != ""){
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Email\t".$email."\n", FILE_APPEND);
				echo "An email has bens send";
				$mail_header= "Subject: $appname queued (".$job_id.")\n";
				$mail_header= $mail_header . "Content-Type: text/html\n";
				$mail_header= $mail_header . "MIME-Version:\n";
				$mail= $mail_header . "\n".$msg;
				mail($email, "no-reply@lcqb.upmc.fr", $mail);};

			header("location: $hostname/$appname/status.php?form=".$form."&job_id=".$job_id);}
		?>
	</section>
<?php include("./includes/footer.php"); ?>
