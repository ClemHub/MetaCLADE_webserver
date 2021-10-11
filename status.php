<?php
include("./includes/header.php"); 
?>

	<section id='Status'>
	<h2> Your job is running... </h2>
		<p class = 'text'>
		<?php
		$form = $_GET["form"];
		$job_id = $_GET["job_id"];
		$existing_job=false;

//Since parameters.txt is created at submission time, it is present also if the job is not yet running so it can be used as flag for signaling job existence
		$parameters = read_parameters_file($approot."/jobs/".$job_id."/parameters.txt");
		$existing_job=count($parameters)>0;
		if( ! $existing_job)
		  {
		     $status_txt="Not Existing";
		     header("location: $hostname/$appname/error.php?form=".$form."&job_id=".$job_id);
		}

		$cmd = "qstat -a|awk '$4==\"".$job_id."\"{print $10}'";
		exec($cmd, $output, $exe_status);
		$status=$output[0];//Status can be Q=Queued R=Run C=Completed
		$status_txt="Completed";

		if($status=="R")
		      $status_txt = "Running";
		else if($status=="Q")
		      $status_txt = "Queued";
		else if($status=="C")
		      $status_txt = "Completed";
		else if($status=="E"){
		      $status_txt = "Error";
		       header("location: $hostname/$appname/error.php?form=".$form."&job_id=".$job_id);
		     }
			
		echo "Your job <strong>".$job_id."</strong> is ".$status_txt."<br>";
		echo "<br>You can save the link to access the results later (your job will be available for two months):<br>"; 
		echo "<a href=$hostname/$appname/status.php?form=".$form."&job_id=".$job_id.">$hostname/$appname/status.php?form=".$form."&job_id=".$job_id."</a><br>";

		if(trim($parameters['LOGO']) == 'true'){$total_step = 4;}
		else if(trim($parameters['LOGO']) == 'false'){$total_step = 3;}
	
		echo "<ul><br><strong>Your job parameters:</strong><br>";
		foreach($parameters as $name => $value){
			if($name != "" and $value != "" and $name != "Email"){
				echo "<li>".$name.": ".$value."</li>";}}
		echo "</ul>";

		$output =  glob($approot."/jobs/".$job_id."/".$job_id.".*");
		$error = false;
		$end = false;
		$step = 'submission';
		$nb_step = 0;
		if($output){
			foreach($output as $file){
					if(preg_match("/[a-zA-Z0-9]+\.e[0-9]+/", $file)){
						$last_line = file($file);
						if(count($last_line) > 0){
							$last_line = $last_line[count($last_line)-1];
							if (preg_match("/logo job finished successfully/", $last_line) || preg_match("/removing temporary files/", $last_line)){
								$end = true;}
							else if (preg_match("/failed|exit|error/", $last_line)){
								$error = true;}
							else if (preg_match("/submission|creating/", $last_line)){
								$step = 'submission';
								$nb_step = 0;}
							else if (preg_match("/search/", $last_line)){
								$step = 'searching';
								$nb_step = 1;}
							else if (preg_match("/filter/", $last_line)){
								$step = 'filtering';
								$nb_step = 2;}
							else if (preg_match("/architecture/", $last_line)){
								$step = 'architecture reconstruction';
								$nb_step = 3;}
							else if (preg_match("/logo/", $last_line)){
								$step = 'logo reconstruction';
								$nb_step = 4;}}}
				else if(preg_match("/[a-zA-Z0-9]+\.o[0-9]+/", $file)){
					$last_line = file($file);
					if(count($last_line) > 0){
						$last_line = $last_line[count($last_line)-1];
						if (preg_match("/failed|exit|error/", $last_line)){
							$error = true;}}}}
			echo "<br>This page will be refreshed every 10 seconds<br>";
			if($end){
				//echo "<br><br>The end<br>";}
				file_put_contents($approot."/jobs/".$job_id."/results.txt", "SeqID\tSeq start\tSeq stop\tSeq length\tDomain ID\tDomain family\tPfam link\tModel ID\tModel start\tModel stop\tModel size\tE-value\tBitscore\tDomain-dependent probability\tSpecies of the template used for the model\tGO-terms\tGO-terms link\n");
				$data = file_get_contents($approot."/jobs/".$job_id."/".$job_id.".arch.tsv");
				$data = str_replace("unavailable", "HMMer-3 model", $data);
				$db = new SQLite3($approot.'/data/MetaCLADE.db');
				foreach(explode("\n", $data) as $line){
					if($line != ""){
						$exploded_line = explode("\t", $line);
						$pfam = $exploded_line[4];
						$row = $db->query("SELECT DISTINCT PFAM32.Family FROM PFAM32 WHERE PFAM32.Pfam_acc_nb='".$pfam."'");
						$row = $row->fetchArray();
						$fam = $row['Family'];
						array_splice($exploded_line, 5, 0, $fam);
						$pfam_link = "http://pfam.xfam.org/family/".$pfam;
						array_splice($exploded_line, 6, 0, $pfam_link);
						$request = $db->query("SELECT * FROM GO_terms WHERE Domain='".$pfam."'");
						$go_terms = "";
						while($db_results = $request->fetchArray()){
							if($go_terms == ""){
								$go_terms = $db_results["GO_term"];
								$go_terms_link = "https://www.ebi.ac.uk/QuickGO/term/".substr(explode(' ', $db_results["GO_term"])[0], 0, -1);}
							else{
								$go_terms = $go_terms.','.$db_results["GO_term"];
								$go_terms_link = $go_terms_link . " https://www.ebi.ac.uk/QuickGO/term/".substr(explode(' ', $db_results["GO_term"])[0], 0, -1);}}
						if($go_terms == ""){
							$go_terms = "NA";
							$go_terms_link = "NA";}
						array_push($exploded_line, $go_terms, $go_terms_link);
					$new_line = join("\t", $exploded_line);
					file_put_contents($approot."/jobs/".$job_id."/results.txt", $new_line."\n", FILE_APPEND);}}
	
				if($parameters['Email'] != ""){
					echo "An email has been send";
					$from= '-F MyCLADE -f myclade@lcqb.upmc.fr'; #. PHP_EOL;
					$mail_header= "Content-Type: text/html; charset=ISO-8559-1". PHP_EOL;
					$mail_header= $mail_header . "MIME-Version:". PHP_EOL;
					$link = $appurl."/results.php?form=".$form."&job_id=".$job_id; 
					$msg= "Your job <b>".$job_id."</b> is now over.<br>Your results are available at the following link: ".$link."<br>";
					$msg= $msg . "Your data will be removed one month after the end of the job.<br>";
					$msg= $msg . "If you need some help, contact the web developer (".$webdevel.").<br>";
					mail("<".$parameters['Email'].">", "MyCLADE results (".$job_id.")", $msg, $mail_header,$from);};
				header("location: $hostname/$appname/results.php?form=".$form."&job_id=".$job_id);}
			else if($error){
				header("location: $hostname/$appname/error.php?form=".$form."&job_id=".$job_id);
				}
			else{
				echo '<br><strong>Status of your job: </strong>'.$step.' (step '.$nb_step.'/'.$total_step.')';
				header("refresh: 10");}}
		else{
			echo '<br><strong>Status of your job: </strong>'.$step.' (step '.$nb_step.'/'.$total_step.')';
			header("refresh: 10");}

		?>
	</section>


<?php include("./includes/footer.php"); ?>
