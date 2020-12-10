<?php
include("./includes/header.php"); 
?>

	<section id='Status'>
	<h2> Your job is running... </h2>
		<p class = 'text'>
		<?php
		$form = $_GET["form"];
		$job_id = $_GET["job_id"];
		echo "Your job <strong>".$job_id."</strong> is running.<br>";
		echo "<br>You can save the link to access the results later (your job will be available for two months):<br>"; 
		echo "<a href=$hostname/$appname/status.php?form=".$form."&job_id=".$job_id.">$hostname/$appname/status.php?form=".$form."&job_id=".$job_id."</a><br>";
		$parameters = read_parameters_file($approot."/jobs/".$job_id."/parameters.txt");

		echo "<ul><br><strong>Your job parameters:</strong><br>";
		foreach($parameters as $name => $value){
			if($name != "" and $value != "" and $name != "Email"){
				echo "<li>".$name.": ".$value."</li>";}}
		echo "</ul>";

		$status = shell_exec("qstat -u 'metaclade' -j ".$job_id);

		$output =  glob($approot."/jobs/".$job_id."/".$job_id.".*");
		$error = false;
		$end = false;
		if($output){
			foreach($output as $file){
				if(preg_match("/[a-zA-Z0-9]+\.e[0-9]+/", $file)){
					$last_line = file($file);
					if(count($last_line) > 0){
						$last_line = $last_line[count($last_line)-1];
						if (preg_match("/architecture job finished successfully/", $last_line) || preg_match("/removing temporary files/", $last_line)){
							$end = true;}
						else if (preg_match("/failed|exit|error/", $last_line)){
							$error = true;}
						else if (preg_match("/submission|creating/", $last_line)){
							echo '<br><strong>Status of your job:</strong> submission and creation of your job<br>';}
						else if (preg_match("/search/", $last_line)){
							echo '<br><strong>Status of your job:</strong> search job (step 1)<br>';}
						else if (preg_match("/filter/", $last_line)){
							echo '<br><strong>Status of your job:</strong> filter job (step 2)<br>';}
						else if (preg_match("/architecture/", $last_line)){
							echo '<br><strong>Status of your job:</strong> architecture job (step 3)<br>';}}}
				else if(preg_match("/[a-zA-Z0-9]+\.o[0-9]+/", $file)){
					$last_line = file($file);
					if(count($last_line) > 0){
						$last_line = $last_line[count($last_line)-1];
						if (preg_match("/failed|exit|error/", $last_line)){
							$error = true;}}}}
			if($end == false && $status == ""){
				$error = true;}
			echo "<br>This page will be refreshed every 10 seconds<br>";
			if($end){
				//echo "<br><br>The end<br>";}
				file_put_contents($approot."/jobs/".$job_id."/results.txt", "SeqID\tSeq start\tSeq stop\tSeq length\tDomain ID\tModel ID\tModel start\tModel stop\tModel size\tE-value\tBitscore\tDomain-dependent probability\tSpecies of the template used for the model\n");
				$data = file_get_contents($approot."/jobs/".$job_id."/".$job_id.".arch.txt");
				$data = str_replace("unavailable", "HMMer-3 model", $data);
				file_put_contents($approot."/jobs/".$job_id."/results.txt", $data, FILE_APPEND);
				header("location: $hostname/$appname/results.php?form=".$form."&job_id=".$job_id);}
			else if($error){
				//echo "<br><br>Error<br>";}
				header("location: $hostname/$appname/error.php?form=".$form."&job_id=".$job_id);}
			else{
				//echo "<br><br>Nothing done yet<br>";}}
				header("refresh: 10");}}
		else{
			echo '<br><strong>Status of your job:</strong> submission of your job';
			header("refresh: 10");}



		?>
	</section>


<?php include("./includes/footer.php"); ?>
