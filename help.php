<?php include("./includes/header.php"); ?>

	<section id ='help'>
	<h2> Help </h2>

	<h3 id='input'>Input</h3>
	<p>
	Required inputs according to the analysis chosen:
	<ul class = 'spaced_list'>
		<li> Annotation of up to 10 domains in large datasets of &#8804 2000 sequences.</li>
		<li> Annotation of all Pfam domains in small datasets of &#8804 200 sequences.</li>
		<li> Annotation of domains in clan in large datasets of &#8804 2000 sequences.</li>
	</ul>
	</p>
	<p>
	Choice of parameters value:
	<ul class='spaced_list'>
		<li> Model library: the complete library of up to 350 models per domain (default value) can be reduced to a library of at most 50 models per domain.</li>
		<li> E-value threshold: 1e-10< (default value).</li>
		<li> If DAMA is used:</li><ul class = 'spaced_list'>
			<li> DAMA E-value: 1e-3 (default value).</li>
			<li> Number of amino acids allowed in domain overlapping: &#8804 30aa (default value).</li>
			<li> Domain matches must cover at least 50% (default value) of the domain average size.</li></ul>
	</ul>
	</p>
	<h3 id='pipeline'>Pipeline</h3>
	<p>
	MyCLADE runs MetaCLADE which is a method used to annotate protein domains in genomic and metagenomic (or metatranscriptomic) amino-acids sequences.
	It uses a library of probabilistic models that, for each domain, includes the Pfam consensus models (SCM) and at most 350 clade-centered models (CCM), with an average of 161 models per domain. Those models have been constructed for all 17 929 domains in Pfam32.<br>
	The pipeline goes as follows:
	<ol type="1" class="ordonned_list">
		<li>Searching for domain hits: each sequence is scanned with the model library in order to identity all domain hits. 
		Each hit is defined by a bit-score (PSI-Blast/HMMer score) and a mean bit-score (bitscore of the result divided by its length) used to evaluate the likelihood of the hit to represent a true annotation.</li>
		<li>Filtering domain hits: each domain is represented by a large amount of models and sequences might be annotated with several of these models. The filtering step is based on the following selection criteria:</li>
		<ul class='subspaced_list'>
            <li> Elimination of all redundant overlapping hits associated to the same domain, identified by SCMs and CCMs models.</li>
			<li> Selection of hits whose bit-score is greater than a domain-specific lower bound identified by a Naive Bayes classifier applied to each Pfam domain and whose probability of being a true positive is greater than 0.9.</li>
			<li> Filtering of hits by a ranking function based on the bit-score and the identity percentage computed with respect to the model consensus sequence.</li>
		</ul>
		<li>Architecture: the user can decide to call DAMA, a tool that considers domain co-occurrence and domain overlapping to combine several domains into most probable architectures.</li>
	</ol>
	</p>
	<h3 id='output'>Output:</h3>
	<p>
	A link allows the user to download the MyCLADE output file (.csv) which provides multiple information on each domain hit:
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
	<h4>The table in "Main results":</h4>
	For each sequence in the input dataset, the most reliable domain architecture is described in a table of three columns:
	<ul class='spaced_list'>
		<li> Sequence ID: it links to a graphical representation of the architecture for the sequence, some statistics, and the GO-terms associated to the domains.</li>
		<li> Architecture: list of annotated domains.</li>
		<li> Best E-value: best e-value among the annotated domains of the architecture.</li>
	</ul>
	</p>
	<h4>The "Architecture":</h4>
	<p>
	The page shows the a graphical representation of annotated sequence.
	The user can move the mouse over a  domain to see a tooltip detailing all the information that  goes with the annotation such as E-value, bitscore, model  species and position of the domain in the sequence.
	Moreover, the size of the domain gets bigger in order to visualize correctly the domain length within the sequence  and display the full domain in case of an overlap. Below  
	the scheme, the user can display two tables reporting,  for each annotated domain:
	<ul class='spaced_list'>
		<li> Annotations details: Pfam family, domain position, model species, E-value, bitscore and accuracy.</li>
		<li> GO-terms: Pfam family, Pfam clan ID, Pfam clan family, GO-terms.</li>
	</ul>
	Pfam domains, Pfam clans, and GO-terms are respectively linked to original online pages. 
	</p>
	</section>

<?php include("./includes/footer.php"); ?>