<?php
include('./includes/configure.php');
include('./includes/logfunctions.php');

$job_id = $_GET["job_id"];
$wdir=$approot."/jobs/".$job_id;
$command="qsub -pe smp 1 -wd $wdir -N $job_id -l h_rt=48:00:00 $approot/runLogo.sh --work_dir $wdir";
logline($command);
shell_exec("$command");
file_put_contents($approot."/jobs/".$job_id."/parameters.txt", "LOGO\tqueued\n", FILE_APPEND);
?>

