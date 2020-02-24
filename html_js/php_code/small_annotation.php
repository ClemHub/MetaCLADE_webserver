<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css"  href="../css_style/style.css">
	<title>Annotation</title>
</head>

<body>
	<?php include("./header.php"); ?>
	<div id="content">
	<?php include("./menu.php"); ?>
	<section>
		<h2> Annotation of a fixed number of domains for a large sequences dataset </h2>
		<h3>Search for domains:</h3>
		
		
		<form name="small_annotation_form" method = POST action="results_table.html" enctype="multipart/form-data" onsubmit="small_form_submission()">
			<p>
			<label for="pfam_domains">Protein accession number:</label><br/>
			<textarea name="pfam_domains" id = "pfam_domains" rows='5' cols='30' placeholder="Ex: PF04523,PF06584,PF06325" autofocus></textarea>
			</p>
			<p>
			<label for="sequences">Fasta format sequences:</label><br/>
			<textarea name="sequences" id = "sequences" rows='5' cols='30' placeholder=">SeqID
sequence"></textarea><br/>
			<label for="fasta_file">Or browse a fasta file:</label>
			<input type="file" id="fasta_file" name="fasta_file"/>
			</p>
			<p>
			Do you want to use DAMA?
			<label for="yes">Yes</label><input type="radio" name="dama" id="yes" value = "yes" checked required/>
			<label for="no">No</label><input type="radio" name="dama" id="no" value = "no" required/>
			</p>
			<input type="submit" value="Search" /><input type="reset" value="Reset" />
		</form>
		</section>
		</div>
		<?php include("./footer.php"); ?>
	<script src="function.js"></script>
</body>
</html>