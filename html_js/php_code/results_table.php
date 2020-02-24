<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css"  href="../css_style/style.css">
	<title>Results</title>
</head>

<body>
	<?php include("./header.php"); ?>
	<div id="content">
	<?php include("./menu.php"); ?>
	<section>
	<h2>Results</h2>
	<table>
	<tr>
		<td class='table_header'>Sequence ID</td>
		<td class='table_header'>Sequence start</td>
		<td class='table_header'>Sequence End</td>
		<td class='table_header'>Domain Id</td>
	</tr>
	<tr>
		<td rowspan=3>tr|V7B5W0|V7B5W0_PHAVU</td>
		<td>285</td>
		<td>476</td>
		<td>PF03441</td>
	</tr>
	<tr>
		<td>7</td>
		<td>173</td>
		<td>PF00875</td>
	</tr>
	<tr>
		<td>505</td>
		<td>591</td>
		<td>PF12546</td>
	</tr>
	<tr>
		<td rowspan=3>tr|A0A072NB93|A0A072NB93_9DEIO</td>
		<td>591</td>
		<td>753</td>
		<td>PF03167</td>
	</tr>
	<tr>
		<td>274</td>
		<td>469</td>
		<td>PF03441</td>
	</tr>
	<tr>
		<td>12</td>
		<td>141</td>
		<td>PF00875</td>
	</tr>
	<tr>
		<td rowspan=3>tr|F0RPZ8|F0RPZ8_DEIPM</td>
		<td>586</td>
		<td>748</td>
		<td>PF03167</td>
	</tr>
	<tr>
		<td>267</td>
		<td>461</td>
		<td>PF03441</td>
	</tr>
	<tr>
		<td>5</td>
		<td>127</td>
		<td>PF00875</td>
	</tr>
	</table>
	Maybe put some scheme representing the architecture (either the common ones either every domain identified)
	</section>
	</div>
	<?php include("./footer.php"); ?>
</body>
</html>
