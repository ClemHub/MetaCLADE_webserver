<?php include("./includes/header.php"); ?>

	<section id ='help'>
	<h2> Help </h2>

	<h3 id='input'>Input</h3>
	<p>
	Required inputs according the method used:
	<ul class = 'spaced_list'>
		<li> Annotation of a few domains in large and small datasets of sequences</li><ul class = 'subspaced_list'>
			<li> Set of sequences (200 maximum)</li></ul>
		<li> Annotation of small datasets of sequences with all Pfam domains</li><ul class = 'spaced_list'>
			<li> Set of sequences (3000 maximum)</li>
			<li> List of PFAM domains (10 maximum)</li></ul>
		<li> Annotation of a clan's domains in large and small datasets of sequences</li><ul class = 'spaced_list'>
			<li> Set of sequences (3000 maximum)</li>
			<li> Pfam clan</li></ul>
	</ul>
	</p>
	<p>
	Choice of features value:
	<ul class='spaced_list'>
		<li> MetaCLADE e-value threshold: default value set to 1e-10</li>
		<li> Use of DAMA or not:</li><ul class = 'spaced_list'>
			<li> DAMA E-value: default value set to 1e-3</li>
			<li> Number of amino acids allowed in the domain overlapping: default value set to 30 and it is used as the greatest possible value</li>
			<li> Domain overlapping is allowed for a given proportion (%) of the match: default value set to 50% and it is used as the greatest possible value</li></ul>
	</ul>
	</p>
	<h3 id='pipeline'>Pipeline</h3>
	<p>
	MyCLADE runs MetaCLADE which is a method used for the annotation of protein domains in metagenomic or metatranscriptomic reads.<br/>
	It uses a library of probabilistic models that includes the Pfam sequence consensus models (SCM) and at most 350 clade-centered models (CCM), with an average of 161 models per domain. Those models have been constructed for all 17 929 domains in Pfam32.<br>
	The pipeline goes as followed:
	<ol type="1" class="ordonned_list">
		<li>Searching domain hits: each sequence is scanned with the model library in order to identity all domain hits. 
		Each hit is defined by a bit-score (PSI-Blast/HMMer score) and a mean bit-score (bitscore of the result divided by its length) used to evaluate the likelihood of the hit to represent a true annotation</li>
		<li>Filtering domain hits: knowing that every domain might be represented by a large amount of models, thus sequences might as well be annotated to a a lot. This is why the filtering happened as followed:</li>
		<ul class='subspaced_list'>
            <li> Elimination of all redundancy of overlapping hits associated to the same domain as well for SCMs as for CCMs</li>
			<li> Every hits whose bit-score is grader than a domain-specific lower bound identified by a Naive Bayes classifier applied to each Pfam and whose probability of being a true positive is greater than 0.9</li>
			<li> Hits are filtered to a ranking function based on the bit-score and the identity percentage computed with respect to the model consensus sequence</li>
		</ul>
		<li>Architecture: the user can decide to call DAMA, a tool that considers domain co-occurrence and domain overlapping, and that combines several domains into most probable architecture.</li>
	</ol>
	</p>
	<h3 id='output'>Output:</h3>
	<h4>Results page:</h4>
	<p>
	A link allows the user to download the MetaCLADE output file, which is constructed as followed:
	<ul class='spaced_list'>
		<li> Sequence ID</li>
		<li> Domain start position</li>
		<li> Domain stop position</li>
		<li> Domain length</li>
		<li> Domain identifier (Pfam accession number)</li>
		<li> Model identifier</li>
		<li> Model start</li>
		<li> Model stop</li>
		<li> Model size</li>
		<li> E-value</li>
		<li> Bitscore</li>
		<li> Accuracy value in the interval [0,1]</li>
		<li> Species of the template used to build the model</li>
	</ul>
	<br>
	MyCLADE provides an interactive graphical visualisation of the domain architecture with the description of the domain annotation. <br>
	<h4>Main results page:</h4>
	In the first place, the main results page represents the most reliable domain architecture in a table constructed with the three following columns:
	<ul class='spaced_list'>
		<li> Sequence ID: related to a link returning the schematic representation of the architecture for the given sequence, some statistic measures, and the GO-terms.</li>
		<li> Architecture: every annotated domain </li>
		<li> Best E-value: best e-value after comparing every annotated domain's e-value</li>
	</ul>
	</p>
	<h4>Architecture page:</h4>
	<p>
	This page shows the schematic representation of the annotated sequence.<br>
	You can move your mouse over a domain to see a tooltip detailing all the informations that goes with this annotation such as the E-value, the bitscore, the model species and the position of the domain.<br>
	Moreover, the size gets bigger in order to visualise correctly the length of the domain especially when some domains overlap.<br>
	Bellowed the scheme, you can chose wheter or not to display two tables:
	<ul class='spaced_list'>
		<li> Annotations details for every annotated domain: Pfam family, the domain position, the model species, E-value, the bitscore and the accuracy.</li>
		<li> GO-terms table for every annotated domain: Pfam family, Pfam clan ID and Pfam clan family, GO-terms.</li>
	</ul>
	Every Pfam domains, Pfam clans, and GO-terms are respectively linked to those pages 
	</p>
	</section>

<?php include("./includes/footer.php"); ?>