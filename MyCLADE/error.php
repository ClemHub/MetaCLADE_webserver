<?php
include("./includes/header.php"); 
?>

	<section id='Error'>
	<h2> Your job has stopped... </h2>
	<h3>This server is a multi-source domain annotation for quantitative <em>metagenomic and metatranscriptomic</em> functional profiling.</h3>
		<p class = 'text'>
		<?php
		$form = $_GET["form"];
		$job_id = $_GET["job_id"];
		echo "Your job ".$job_id." failed.<br>";
		echo "Please <a class='table_link' href='".$appurl."/MyCLADE/contact.php'>contact</a> the webdeveloper to understand why.<br>"; //insert link to the contact page
		echo "<br>Transmit the job ID so the web developer may help you understand where the problem is coming from.<br>";
		?>
	</section>


<?php include("./includes/footer.php"); ?>