<?php
		session_start();
		$_SESSION = array();
		$_SESSION['dama'] = $_POST['dama'];
		$_SESSION['evalue'] = $_POST['evalue_nb'];
		$_SESSION['DAMA-evalue'] = $_POST['dama_evalue_range'];
		$_SESSION['sequences'] = $_POST['sequences'];
		$_SESSION['pfam_domains'] = $_POST['pfam_domains'];
		$_SESSION['email'] = $_POST['email'];
		$form = $_GET['form'];
		if($form=='small' || $form=='large'){
			header("location: submit.php?form=".$form);}
		else if ($form == 'large_example' || $form=='small_example'){
			header("location: results.php?form=".$form);}
?>