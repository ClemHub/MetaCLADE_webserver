<?php
include("./includes/header.php"); 
?>
	<section id='Submission'>
	<h2> Your job has been submitted... </h2>
		<p class = 'text'>
		<?php
		$form = $_GET['form'];
		if ($form == 'clan_example' | $form == 'small_example' | $form == 'large_example'){
			$dama = $_POST['dama'];
			if($dama == 'true'){
				$job_id = $form.'_withDAMA';}
			else{
				$job_id = $form.'_withoutDAMA';}
			header("location: $hostname/$appname/results.php?form=".$form."&job_id=".$job_id);
		}
		else if ($form == 'visualization_jobID'){
			$job_name = $_POST["job_ID"];
			if($job_name != ''){
				if(file_exists($approot."/jobs/".$job_name."/".$job_name.".arch.tsv")){
					header("location: $hostname/$appname/results.php?form=".$form."&job_id=".$job_name);
					}
				else if(!file_exists($approot."/jobs/".$job_name."/".$job_name.".arch.tsv")){
					header("location: $hostname/$appname/error.php?form=".$form."&job_id=".$job_name);}}}
		else if ($form == 'visualization_file'){
				$job_id = generateRandomString()."_".date("dmY");
				echo 'Your job ID is: '.$job_id,'<br>';
				$oldmask = umask(0);
				mkdir($approot.'/jobs/'.$job_id, 0777, true);
				umask($oldmask);
				file_put_contents($approot."/jobs/".$job_id."/".$job_id.".arch.tsv", $_POST["sequences"]);
				header("location: $hostname/$appname/results.php?form=".$form."&job_id=".$job_id);}
		else{
			$job_id = generateRandomString()."_".date("Ymd");
			$job_name = $_POST["job_name"];
			echo 'Your job ID is: '.$job_id,'<br>';
			$oldmask = umask(0);
			mkdir($approot.'/jobs/'.$job_id, 0777, true);
			umask($oldmask);
			if($job_name!=""){
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Job name\t".$job_name."\n", FILE_APPEND);}
			if($_POST['library'] == 'true'){
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Library\tAnnotation based on the full domain library\n", FILE_APPEND);
				$parameters['Library'] = "Complete";}
			else if($_POST['library'] == 'false'){
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Library\tAnnotation based on the reduced domain library\n", FILE_APPEND);
				$parameters['Library'] = "Reduced";}
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
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Amino acids overlapping\t".$_POST["overlappingAA_nb"]."\n", FILE_APPEND);
				$parameters['Amino acids overlappping'] = $_POST["overlappingAA_nb"];
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Max domain overlapping (%)\t".$_POST["overlappingMaxDomain_nb"]."\n", FILE_APPEND);
				$parameters['Max domain overlapping (%)'] = $_POST["overlappingMaxDomain_nb"];
			    }
			file_put_contents($approot.'/jobs/'.$job_id.'/data.fa', $_POST["sequences"]);
			file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Number of sequences\t".preg_match_all("/^>|\n>/", $_POST["sequences"])."\n", FILE_APPEND);
			$logo = $_POST["logo"];
			//$logo = 'true';
			file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "LOGO\t".$logo."\n", FILE_APPEND);

			$msg = submit($job_id, $parameters);
			echo $msg;
			$email = $_POST['email'];
			if($_POST['email'] != ""){
				file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "Email\t".$email."\n", FILE_APPEND);
				echo "An email has been send";

				$mail_header= "Subject: MyCLADE queued (".$job_id.")". PHP_EOL;
				$mail_header= 'From: MyCLADE <myclade@lcqb.upmc.fr>'. PHP_EOL;
				$mail_header= $mail_header . "Content-Type: text/html; charset=ISO-8559-1". PHP_EOL;
				$mail_header= $mail_header . "MIME-Version:". PHP_EOL;
				mail("<".$email.">", "MyCLADE queued (".$job_id.")", $msg, $mail_header);};
			echo "<input id='step' name='step' type='hidden' value='submission'>";
			echo "<input id='step_nb' name='step_nb' type='hidden' value='0'>";
			header("location: $hostname/$appname/status.php?form=".$form."&job_id=".$job_id);
		}

		?>
	</section>
<?php include("./includes/footer.php"); ?>
