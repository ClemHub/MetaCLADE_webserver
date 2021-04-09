<?php include('./includes/header.php'); ?>
	<section id ='help'>
	<h2> Help </h2>

	<h3 id='input'>Input</h3>
	<p>
	MyCLADE can be run on three different library types defined by:    
	<ul>
	  <li>up to 10 domains chosen by the user </li>
	  <li>all Pfam domains</li>
	  <li> domains in a clan</li>
	  </ul>
	<p>The first and third library types require a list of up to 2000 sequences in FASTA format (possibly uploaded). The second library type requires a smaller dataset of up to 200 sequences. The list of input sequences is checked for format requirements. </p>
	<p>Choice of <strong>parameter values</strong>: </p>
	<blockquote>
	  <p> Model library: each library type is characterized by either 350 or 50 models per domain in the set. The option allows the user to decide on the number of models per domain. </p>
	  <p> E-value threshold: 1e-3 (default value). The user can filter out all hits with an E-value which is greater than the chosen threshold </p>
	  </blockquote>
	<p>The reconstruction of <strong>the best domain architecture</strong> is possible by selecting  <a href="http://www.lcqb.upmc.fr/DAMA/" target="_blank" class="table_link">DAMA</a> together with its three parameters:  </p>
	<ul>
	  <li><a href="http://www.lcqb.upmc.fr/DAMA/" target="_blank" class="table_link">DAMA</a> E-value: 1e-10 (default value)</li>
	  <li> number of amino acids allowed in domain overlapping: &#8804 30aa (default value)</li>
	  <li> domain matches must cover at least 50% (default value) of the domain average size</li>
	  </ul>
	<p>By changing the parameters, the user can explore potentially new annotations as illustrated in the examples' page.</p>
	<p>The user can provide an e-mail address to obtain an identifier to access the data online after the job is completed. </p>
	<p>Annotation files produced in previous run of MyCLADE can be provided as input and displayed graphically with the server. </p>
	<p>&nbsp;</p>
	<h3 id='pipeline'>Pipeline</h3>
	<p>
      MyCLADE runs <a href="http://www.lcqb.upmc.fr/metaclade/" target="_blank" class="table_link">MetaCLADE</a> <a href="https://microbiomejournal.biomedcentral.com/articles/10.1186/s40168-018-0532-2" target="_blank" class="table_link">(Ugarte et al. 2018)</a> which is a method used to annotate protein domains in genomic and metagenomic (or metatranscriptomic) amino-acid sequences.
	      It uses a library of probabilistic models that, for each domain, includes the Pfam consensus models (SCM) and at most 350 clade-centered models (CCM), with an average of 161 models per domain. Those models have been constructed for all 17 929 domains in Pfam32.<br>
      </p>
	<p>The pipeline goes as follows:    </p>
	<blockquote>
	  <p><strong>Searching for domain hits</strong>: each sequence is scanned with the model library in order to identity all domain hits. 
	    Each hit is defined by a bit-score (PSI-Blast/HMMer score) and a mean bit-score (bitscore of the result divided by its length) used to evaluate the likelihood of the hit to represent a true annotation.</p>
	  <p><strong>Filtering domain hits</strong>: each domain is represented by a large amount of models and sequences might be annotated with several of these models. The filtering step is based on the following selection criteria:</p>
	  <p> <strong>Elimination of all redundant overlapping hits</strong> associated to the same domain, identified by SCMs and CCMs models.</p>
	  <p> <strong>Selection of hits</strong> whose bit-score is greater than a domain-specific lower bound identified by a Naive Bayes classifier applied to each Pfam domain and whose probability of being a true positive is greater than 0.9.</p>
	  <p> <strong>Filtering of hits</strong> by a ranking function based on the bit-score and the identity percentage computed with respect to the model consensus sequence.</p>
	  </blockquote>
	<p>While a job runs, four different alerts are provided:	  </p>
	<blockquote>
	  <p>- Status of your job: job submission<br>
	    - Status of your job: searching (step 1/3)<br>
	    - Status of your job: filtering (step 2/3)<br>
	    - Status of your job: architecture reconstruction (step 3/3)</p>
	  </blockquote>
	<p>For the last step, dedicated to the reconstruction of the domain architecture, the user can decide to call <a href="http://www.lcqb.upmc.fr/DAMA/" target="_blank" class="table_link">DAMA</a> <a href="https://academic.oup.com/bioinformatics/article/32/3/345/1743675" target="_blank" class="table_link">(Bernardes et al. 2016)</a>, a tool that considers domain co-occurrence and domain overlapping to combine several domains into most probable architectures.<br>
	  <br>
	</p>
	<p></p>
	  <h3 id='output'>Output</h3>
	<p>
      MyCLADE output is organised in two main pages: &quot;Results&quot; and &quot;Architecture&quot;. </p>
	<p>&nbsp;</p>
	<h4>The "Results" page</h4>
	<p align="center"><img <?php echo "src='".$appurl."/server_images/help_images/results_table.png'";?> alt="" width="1001" height="238" border="1"></p>
	<p>For each sequence in the input dataset, the most reliable domain annotation is described in this table with four columns: </p>
	<blockquote>
      <p> <strong>sequence ID</strong>: it links to a graphical representation of the architecture where relevant information on the domain hits  and the list of GO-terms associated to the domains is provided.</p>
	  <p> <strong>domain ID</strong>: list of annotated domains describing the domain architecture proposed by MyCLADE. Each domain links to the Pfam webpage.</p>
	  <p> <strong>best E-value</strong>: best e-value among the annotated domains of the architecture</p>
	  <p><strong>number of hits</strong>: number of domains occurring in the architecture. Note that domains might be repeated.</p>
	  </blockquote>
	<p>
	  </p>
	  A <strong>CSV file</strong> containing all results can be downloaded from the top of the page.
	  This file provides multiple information on each domain hit: sequence ID, domain start and end positions, domain length, domain identifier (Pfam accession number), model identifier, model hit start and stop positions, model size, E-value of the hit, bitscore, domain dependent probability score, species of the template sequence used to build the model.</p>
	<blockquote>
	  <p>&nbsp;</p>
	  </blockquote>
	<h4>The "Architecture" page</h4>
	<p>This page shows a graphical representation of the annotated sequence.
	    Below  
      the graphical scheme, the user can visit two tables reporting annotation details and GO-terms,  for each annotated domain: </p>
	<blockquote>
	  <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/amylase/Details-page-AMY1A-noDAMA.png'";?> alt="" width="856" height="453" border="1"></p>
	  </blockquote>
	<p>For each input sequence, MyCLADE provides an interactive graphical representation of the domain architecture.</p>
	<blockquote>
	  <p align="center"><img <?php echo "src='".$appurl."/server_images/help_images/domain_arch.png'";?> width="1009" height="113"></p>
	  </blockquote>
	<p>A presentation of the information on the annotated domain is available through an interactive display by hovering after the graphical representation of the domain. The size of the domain gets bigger in order to visualize correctly the domain length within the sequence  and display the full domain in case of an overlap.  The tooltip details the information associated to the annotation: Pfam family, initial and final position of the domain hit in the sequence, the species from which the probabilistic model used to annotate was generated, the E-value, the bit-score and the domain-dependent probability scores of the hit, the associated clan identifier and clan family. </p>
	<blockquote>
	  <p align="center"><img <?php echo "src='".$appurl."/server_images/help_images/hovering_arch.png'";?> width="403" height="227"></p>
	  </blockquote>
	<p>Two tables collect all details on the annotation and associated GO-terms. Multiple links to Pfam and QuickGO databases are available for an easy retrieval of general domain and clan information. </p>
	<blockquote>
	  <p align="center"><img <?php echo "src='".$appurl."/server_images/help_images/pfam_links.png'";?> width="1013" height="115"></p>
	  </blockquote>
	<p align="left"><strong>Annotations details</strong>: domain ID, Pfam family, domain start and end position, species from which the model is constructed, E-value, bitscore and domain dependent probability score.</p>
	<blockquote>
	  <p align="center"><img <?php echo "src='".$appurl."/server_images/help_images/goterms_table.png'";?> width="1005" height="93"></p>
	  </blockquote>
	<p><strong>GO-terms</strong>: domain ID, Pfam family, Pfam clan ID, Pfam clan family, GO-terms.</p>
	<p>&nbsp;</p>
	<blockquote>
	  <p align="center">
	    </p>
	  </p>
	  </blockquote>
	</section>

<?php include('./includes/footer.php'); ?>
