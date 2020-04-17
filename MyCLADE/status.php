<?php
include("./includes/header.php"); 
?>

	<section id='Status'>
	<h2> Your job is running... </h2>
    <h3>This server is a multi-source domain annotation for quantitative <em>metagenomic and metatranscriptomic</em> functional profiling.</h3>
		<p class = 'text'>
		<?php
        $form = $_GET['form'];
        $job_id = $_GET['job_id'];
        echo "Your job ".$job_id." is still running.<br>";
        echo "You can save the link to access the results later thanks to this link<br>";
        echo "This page will be refreshed every 20 seconds<br>";

        echo $approot."/MyCLADE/jobs/".$job_id."/".$job_id."/results/3_arch/".$job_id.".arch.txt";
        if(file_exists($approot."/MyCLADE/jobs/".$job_id."/".$job_id."/results/3_arch/".$job_id.".arch.txt")){
            header("location: $hostname/$appname/MyCLADE/results.php?form=".$form."&job_id=".$jobid.".&email=".$email);
            }
        else{
            header("refresh: 20");}
        

		?>
	</section>


<?php include("./includes/footer.php"); ?>