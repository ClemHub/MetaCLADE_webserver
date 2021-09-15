<?php
include("./includes/functions.php");
include("./includes/configure.php");
$job_id = $_GET["job_id"];
$parameters = read_parameters_file($approot."/jobs/".$job_id."/parameters.txt");
print(str_replace('\\n', '', $parameters["LOGO"]));
?>
