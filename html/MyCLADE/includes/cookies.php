<?php
	unset($_COOKIE['dama']);
	unset($_COOKIE['evalue']);
	unset($_COOKIE['dama-evalue']);
	setcookie('dama', $_POST['dama']);
	setcookie('evalue', $_POST['evalue_range']);
	setcookie('dama-evalue', $_POST['dama_evalue_range'])
?>