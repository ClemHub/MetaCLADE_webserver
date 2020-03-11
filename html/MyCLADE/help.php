<?php include("./includes/header.php"); ?>

	<section id ='help'>
	<h2> Help </h2>
	<h3>How to use MetaCLADE</h3>

	<h4 id='input'>Input</h4>
	<p>
	Required inputs according the method used:
	<ul class = 'spaced_list'>
		<li> Few dozens of sequences annotation against a library of probabilistic models</li><ul class = 'subspaced_list'>
			<li> Set of sequences (small amount)</li></ul>
		<li> Thousands of sequences annotation against a fixed small number of probabilistic models</li><ul class = 'spaced_list'>
			<li> Set of sequences (large amount)</li>
			<li> List of PFAM domains</li></ul>
	</ul>
	</p>
	<p>
	Choice of features value:
	<ul class='spaced_list'>
		<li> MetaCLADE e-value threshold: default value set to 1e<sup>-10</sup></li>
		<li> Use of DAMA or not</li>
		<ul class='subspaced_list'>
			<li>DAMA e-value threshold: default value set to 1e<sup>-3</sup></li>
		</ul>
	</ul>
	</p>
	<h4 id='output'>Output</h4>
	
	<p>
	MetaCLADE returns the most reliable domain architecture. In the first place the results are represented through a table detailling all the informations:
	<ul class='spaced_list'>
		<li> Sequence ID </li>
		<li> Sequence start </li>
		<li> Sequence stop </li>
		<li> Domain ID (PFAM accession number) </li>
		<li> Model identifier </li>
		<li> Species used for the model length </li>
		<li> E-value </li>
	</ul>
	</p>
	<p>
	The Sequence ID is related to a link presenting the schematic representation of the results for the given sequence. It represents all the identified domains along the sequence. If you let your mouse over one of the domains, a tooltip will cite the previous informations associated to it.
	</p>

	<h4 id='method'>Method</h4>
	<p>
	MetaCLADE is a method used for the annotation of protein domains in metagenomic or metatranscriptomic reads.<br/>
	Every known domains are represented by a few hundred probabilistic models called clade-centered-models (CCMs) and with consensus models (SCM)
	Pipeline:
	<ol type="1" class="ordonned_list">
		<li>Identification of domain hits</li>
		<li>Selection of domain hits</li>
	</ol>
	</p>
	</section>

<?php include("./includes/footer.php"); ?>