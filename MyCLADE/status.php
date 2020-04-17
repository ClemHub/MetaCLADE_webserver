<?php
include("./includes/header.php"); 
?>

	<section id='Status'>
	<h2> Your job is running... </h2>
    <h3>This server is a multi-source domain annotation for quantitative <em>metagenomic and metatranscriptomic</em> functional profiling.</h3>
		<p class = 'text'>
		<?php
        $job_id = $_GET['job_id'];
        echo "Your job is still running.<br>";
        echo "You can save the link to access the results later thanks to this link<br>";
        echo "This page will be refreshed every 20 seconds<br>";

        header("refresh: 20");

		?>
	</section>


<?php include("./includes/footer.php"); ?>