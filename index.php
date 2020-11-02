<?php include("./includes/header.php"); ?>

	<section id='home'>
    <h3>MYCLADE: an accurate multi-source domain annotation server designed for a fast exploration of genomic and metagenomic sets of sequences</h3>
		<p class = 'text'>
		The understanding of the ever-increasing number of genomic and metagenomic sequences accumulating in our databases demands for approaches that rapidly "explore" the content of sets of (fragmented) protein sequences with respect to specific domain targets,
		avoiding full domain annotation and full assembly. MYCLADE performs a multi-source domain annotation strategy based on a library of probabilistic domain models associated to each domain. It works in two modes: 
	<ol type="1" class="ordonned_list">
		<li>It explores large dataset of a few thousands amino-acid sequences and extracts those sequences containing few targeted domains.</li>
		<li>It annotates small datasets of a few hundreds amino-acid sequences with the full set of Pfam domains.</li>
	</ol>
		If sequences are sufficiently long, <a href="http://www.lcqb.upmc.fr/dama/" target="_blank" class="table_link">DAMA</a> can be used to accurately resolve protein domain architectures.<br/>
		MyCLADE annotates protein sequences with a library of more than 2.5 million probabilistic models for the whole Pfam32 database (17,929 domains).</p>
	
	</section>

<?php include("./includes/footer.php"); ?>