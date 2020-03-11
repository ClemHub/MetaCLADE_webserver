<?php
		session_start();
		$_SESSION = array();
		$_SESSION['dama'] = $_POST['dama'];
		$_SESSION['evalue'] = $_POST['evalue_range'];
		$_SESSION['DAMA-evalue'] = $_POST['dama_evalue_range'];
?>