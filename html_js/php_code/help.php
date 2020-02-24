<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css"  href="../css_style/style.css">
	<title>Help</title>
</head>

<body>
	<?php include("./header.php"); ?>
	<div id="content">
	<?php include("./menu.php"); ?>

	<section>
	<h2> Help </h2>
	<h3>How to use MetaCLADE</h3>
	<h4>Input</h4>
	
	<ul class = spaced_list>
		<li>Few dozens of sequences annotated against a library of probabilistic models</li>
		<li>Thousands of sequences to be annotated against a fixed small number of probabilistic models</li>
	</ul>

	<h4>Output</h4>

	<ul class = spaced_list>
		<li>It returns the most reliable domain architecture </li>
	</ul>
	<h4>Method</h4>
	MetaCLADE is a method used for the annotation of protein domains in metagenomic or metatranscriptomic reads.<br/>
	Every known domains are represented by a few hundred probabilistic models called clade-centered-models (CCMs) and with consensus models (SCM)
	Pipeline:
	<ol type="1" class="ordonned_list">
		<li>Identification of domain hits</li>
		<li>Selection of domain hits</li>
	</ol>
	</section>
	</div>

	<?php include("./footer.php"); ?>
</body>
</html>