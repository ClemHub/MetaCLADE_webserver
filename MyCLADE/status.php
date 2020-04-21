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
        echo "You can save the link to access the results later thanks to this link:<br>"; 
        echo "<a href=$hostname/$appname/MyCLADE/status.php?form=".$form."&job_id=".$job_id."&email=".$email.">$hostname/$appname/MyCLADE/status.php?form=".$form."&job_id=".$job_id."</a><br>";
        echo "<br>This page will be refreshed every 10 seconds<br>";

        if(file_exists($approot."/MyCLADE/jobs/".$job_id."/".$job_id."/results/3_arch/".$job_id.".arch.txt")){
            header("location: $hostname/$appname/MyCLADE/results.php?form=".$form."&job_id=".$job_id."&email=".$email);
            }
        else{
            header("refresh: 10");}

		?>
	</section>


<?php include("./includes/footer.php"); ?>