<?php

include 'configure.php';
include 'logfunctions.php';

#Generate a random jobid. By default it is 10 character long
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
};

function submit($jobid, $email){
//Prepare the environment for the script (saving files for example)

//ARGS is the list of arguments you have extracted from your form. Only this is escaped because it is the only things given by the user.
	$command="qsub -w $approot/jobs -N $jobid $approot/run.sh ".escapeshellarg(ARGS); 
//Sublit your job
	shell_exec("$command");

	$link="$appurl/status.php?jobid=$jobid"; #status.php is a page that show he status of your job

	$msg="<strong>Your job has been correctly submitted</strong><br><br>";
	$msg= $msg . "You can follow job progress as well as downloading the results going to <a target=_blank href=$link> $link </a><br>";

	$msg= $msg . "<br>Your data will be removed one month after the end of the job.<br>";
	$msg= $msg . "The job will be stopped if longer than 48 hours.<br>";


	$msg= $msg . "If you need some help, contact the web developer ($webdevel).<br>";

	return $msg;
};

// File put contents fails if you try to put a file in a directory that doesn't exist.
// This creates the directory.


$jobid = generateRandomString();

$msg=submit($jobid, $email);

$email = $_POST['email']; //email is a field with the email of the user. If you don't have, just remove this piece of code
if($email){                                                                                                                                                                        
$mail_header= "Subject: $appname queued ( $jobid )\n";
$mail_header= $mail_header . "Content-Type: text/html\n";
$mail_header= $mail_header . "MIME-Version:\n";
$mail= $mail_header . "\n".$msg;
sendMail($mail, "no-reply@lcqb.upmc.fr", $email);
};

header("location: $hostname/$appname/status.php?jobid=$jobid&email=$email");

?>
