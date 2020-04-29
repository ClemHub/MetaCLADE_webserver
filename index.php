<?php include("./includes/header.php"); ?>

	<section id='home'>
	<h2> Home </h2>
    <h3>MYCLADE: an accurate multi-source domain annotation server designed for a fast exploration of metagenomic and metatranscriptomic sets of sequences</h3>
		<p class = 'text'>
		The understanding of the ever-increasing number of metagenomic sequences accumulating in our databases demands for approaches that rapidly "explore" the content of metagenomic datasets with respect to specific domain targets, avoiding full domain annotation and full assembly. MYCLADE performs a multi-source domain annotation strategy based on a library of probabilistic domain models associated to each domain. It works in two modes: 1. it explores a large dataset of sequences to extract those sequences containing a small pool of specific domains or 2. it annotates a small set of sequences with the full set of Pfam domains. If sequences are sufficiently long, <a href="http://www.lcqb.upmc.fr/dama/
		" target="_blank"> DAMA </a> can be used to accurately resolve protein domain architectures.</p>
	</section>

<?php include("./includes/footer.php"); ?>