<?php
include("./includes/header.php"); 
?>

	<section id='Error'>
	<h2> Your job has stopped... </h2>
		<p class = 'text'>
		<?php
		$form = $_GET["form"];
		$job_id = $_GET["job_id"];
		if($form != 'visualization'){
			echo "Your job <strong>".$job_id."</strong> failed.<br>";
			echo "<br>Please <a class='table_link' href='".$appurl."/contact.php'>contact</a> the webdeveloper to understand why.<br>"; //insert link to the contact page
			echo "Transmit the job ID so the web developer may help you understand where the problem is coming from.<br>";}
		else{
			echo "Your job <strong>".$job_id."</strong> is not available anymore.<br>";}					
		?>

	</section>


<?php include("./includes/footer.php"); ?>