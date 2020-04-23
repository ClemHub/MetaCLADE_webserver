<?php
include("./includes/header.php"); 
?>

	<section id='Status'>
	<h2> Your job is running... </h2>
	<h3>This server is a multi-source domain annotation for quantitative <em>metagenomic and metatranscriptomic</em> functional profiling.</h3>
		<p class = 'text'>
		<?php
		$form = $_GET["form"];
		$job_id = $_GET["job_id"];
		echo "Your job ".$job_id." is running.<br>";
		echo "You can save the link to access the results later thanks to this link and your job will be available for two month:<br>"; 
		echo "<a href=$hostname/$appname/MyCLADE/status.php?form=".$form."&job_id=".$job_id."&email=".$email.">$hostname/$appname/MyCLADE/status.php?form=".$form."&job_id=".$job_id."</a><br>";

		$output =  glob($approot."/MyCLADE/jobs/".$job_id."/".$job_id.".*");
		$error = false;
		$end = false;
		if($output){
			foreach($output as $file){
				if(preg_match("/[a-zA-Z0-9]+\.e[0-9]+/", $file)){
					$last_line = file($file);
					$last_line = $last_line[count($last_line)-1];
					if (preg_match("/\[main\] architecture job finished successfully/", $last_line)){
						$end = true;}
					else if (preg_match("/failed|exit|error/", $last_line)){
						$error = true;}
					else if (preg_match("/search/", $last_line)){
						echo '<br><strong>Status of your job:</strong> search job (step 1)<br>';}
					else if (preg_match("/filter/", $last_line)){
						echo '<br><strong>Status of your job:</strong> filter job (step 2)<br>';}
					else if (preg_match("/computing architecture/", $last_line)){
						echo '<br><strong>Status of your job:</strong> architecture job (step 3)<br>';}}
				else if(preg_match("/[a-zA-Z0-9]+\.o[0-9]+/", $file)){
					$last_line = file($file);
					$last_line = $last_line[count($last_line)-1];
					if (preg_match("/failed|exit|error/", $last_line)){
						$error = true;}}}
			echo "<br>This page will be refreshed every 10 seconds<br>";
			if($end){
				//echo "<br><br>The end<br>";}
				header("location: $hostname/$appname/MyCLADE/results.php?form=".$form."&job_id=".$job_id."&email=".$email);}
			else if($error){
				//echo "<br><br>Error<br>";}
				header("location: $hostname/$appname/MyCLADE/error.php?form=".$form."&job_id=".$job_id."&email=".$email);}
			else{
				//echo "<br><br>Nothing done yet<br>";}}
				header("refresh: 10");}}
		else{
			echo '<br><strong>Status of your job:</strong>: submission of your job';
			header("refresh: 10");}

		/*if(!empty($output)){
			$e_file = file($output[0]);
			
			$o_file = file($output[0]);
			$last_line = $o_file[count($o_file)-1];
			if ($o_file[$i] == "[main] architecture job finished successfully"){
				$end = true;}
			}
		if($end == true and $error == false){
			header("location: $hostname/$appname/MyCLADE/results.php?form=".$form."&job_id=".$job_id."&email=".$email);}
		else if($end == false and $error = true){
			header("location: $hostname/$appname/MyCLADE/error.php?form=".$form."&job_id=".$job_id."&email=".$email);}
		else{
			header("refresh: 10");}*/

		?>
	</section>


<?php include("./includes/footer.php"); ?>