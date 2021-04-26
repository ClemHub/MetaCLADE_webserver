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
	<p>The first and third library types require a list of up to 2000 sequences in FASTA format (possibly uploaded). The second library type requires a smaller dataset of up to 200 sequences. </p>
	<p>The list of input sequences is checked for format requirements. Several <strong>error messages</strong>  suggest the user how to correct the FASTA file given as input:</p>
	<p>-	<em>name</em> should start with a '&gt;'<br>
      -	Your sequence <em>name</em> misses its sequence<br>
      -	Your sequence <em>name</em> contains characters that are not amino acids<br>
      -	There are more than <em>max_seq</em> sequences in your input data<br>
      - 	The sequence <em>n°seq_nb</em> should have an ID starting with a &quot;&gt;&quot;<br>
      -     You have a trailing whitespace before your sequence <em>n°seq_nb</em></p>
	<p>where <em>name</em>, <em>max_seq</em> and <em>n°seq_nb</em> are context dependent variables; <em>name</em> corresponds to the sequence identifier where the problem is found; <em>max_seq</em>refers to the maximum number of sequences that can be given as input (values which can be different depending on the type of annotation selected); n°seq_nb refers to the n-th sequence in the list given as input.</p>
	<p>Several <strong>parameter values</strong> can be chosen: </p>
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
	<p>Note that if DAMA is not selected, domain overlapping is allowed with less than 10aa (MetaCLADE default value).</p>
	<p>By changing the parameters, the user can explore potentially new annotations as illustrated in the examples' page.</p>
	<p><strong>Logos</strong> can be generated for all domain hits. The user can decide to build logos either by marking the corresponding input option or after building the architectures. This second option is suggested when many sequences are annotated.</p>
	<p>The user can provide an <strong>e-mail address </strong>to obtain an identifier to access the data online after the job is completed. </p>
	<p><strong>Annotation files </strong>produced in previous run of MyCLADE can be provided as input and displayed graphically with the server. </p>
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
	<p>While a job runs, five different alerts are provided:	  </p>
	<blockquote>
	  <p>- Status of your job: job submission<br>
	    - Status of your job: searching (step 1)<br>
	    - Status of your job: filtering (step 2)<br>
	    - Status of your job: architecture reconstruction (step 3)<br>
	    - Status of your job: logo reconstruction, if selected (step 4)</p>
	  </blockquote>
	<p>For step 3, dedicated to the reconstruction of the domain architecture, the user can decide to call <a href="http://www.lcqb.upmc.fr/DAMA/" target="_blank" class="table_link">DAMA</a> <a href="https://academic.oup.com/bioinformatics/article/32/3/345/1743675" target="_blank" class="table_link">(Bernardes et al. 2016)</a>, a tool that considers domain co-occurrence and domain overlapping to combine several domains into most probable architectures.<br>
	  <br>
	</p>
	<p></p>
	  <h3 id='output'>Output</h3>
	<p>
      MyCLADE output is organised in two main pages: &quot;Results&quot; and &quot;Architecture&quot;. <br>
	  <br>
	</p>
	<h4>The "Results" page</h4>
	<p align="center"><img <?php echo "src='".$appurl."/server_images/help_page/results-page-final.png'";?> width="571" height="814"></p>
	<p>For each sequence in the input dataset, the most reliable domain annotation is described in this table with four columns: </p>
	<blockquote>
      <p align="justify"> <strong>sequence ID</strong>: it links to a graphical representation of the architecture where relevant information on the domain hits  and the list of GO-terms associated to the domains is provided.</p>
	  <p> <strong>domain ID</strong>: list of annotated domains describing the domain architecture proposed by MyCLADE. Each domain links to the Pfam webpage.</p>
	  <p> <strong>best E-value</strong>: best e-value among the annotated domains of the architecture</p>
	  <p><strong>number of hits</strong>: number of domains occurring in the architecture. Note that domains might be repeated.</p>
	  </blockquote>
	<p>By hovering over a domain ID, a synthesis of the annotation for that domain hit is reported in a tooltip. By clicking the domain ID, one access the Pfam website for that domain. </p>
	<p>By clicking over a sequence ID, one opens the &quot;Architecture&quot; page for that sequence.	  </p>
	A <strong>CSV file</strong> containing all results can be downloaded from the top of the page.
	  This file provides multiple information on each domain hit: sequence ID, domain start and end positions, domain length, domain identifier (Pfam accession number), model identifier, model hit start and stop positions, model size, E-value of the hit, bitscore, domain dependent probability score, species of the template sequence used to build the model.</p>
	  <p>A <strong>synthesis of the annotation</strong> of the input file can be accessed. It reports how many sequences have either no hit or at least one hit over the total number of sequences in the input file.</p>
	  <p>A <strong>synthesis of the</strong> <strong>job parameters</strong> selected by the user is accessible.</p>
	  <p>If <strong>logos</strong> have not been already generated (from the option in the input page), the  user can build logos by clicking on the corresponding button. They will be built on the background and will be accessible from the architecture page, for each sequence and each domain hit.<br>
	    <br>
      </p>
	  <h4>The "Architecture" page</h4>
	<p>This page shows a graphical representation of the annotated sequence.
	    Below  
      the graphical scheme, the user can visit two tables reporting annotation details and GO-terms,  for each annotated domain, and a list of logos, for the models used to identify the domains, matching the sequence. </p>
	<blockquote>
	  <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/amylase/Details-page-AMY1A-noDAMA.png'";?> alt="" width="856" height="453" border="1"></p>
	  </blockquote>
	<p>For each input sequence, MyCLADE provides an interactive graphical representation of the domain architecture. When MyCLADE is run <strong>with DAMA</strong>, you might want to check whether the domains in the resulting architecture are known to co-occur or not. You can access the <a href="http://pfam.xfam.org/search#tabview=tab3 target="_blank"" target="_blank">online interface</a> proposed in <a href="http://pfam.xfam.org/" target="_blank">Pfam</a> for this. MyCLADE uses Pfam knowledge for building its architectures.    
	<blockquote>
	  <p align="center"><img <?php echo "src='".$appurl."/server_images/help_page/Screen Shot 2021-04-09 at 08.58.48.png'";?> width="1009" height="113"></p>
	  </blockquote>
	<p>A presentation of the information on the annotated domain is available through an interactive display by hovering after the graphical representation of the domain. The size of the domain gets bigger in order to visualize correctly the domain length within the sequence  and display the full domain in case of an overlap.  The tooltip details the information associated to the annotation: Pfam family, initial and final position of the domain in the sequence (they correspond to the positions of the envelope provided by HMMER, bounding where the domain’s alignment most probably lies), the species from which the probabilistic model used to annotate was generated, the E-value, the bit-score and the domain-dependent probability scores of the hit, the associated clan identifier and clan family. </p>
	<blockquote>
	  <p align="center"><img <?php echo "src='".$appurl."/server_images/help_page/Screen Shot 2021-04-09 at 08.46.06.png'";?> width="403" height="227"></p>
	  </blockquote>
	<p>Two tables collect all details on the annotation and associated GO-terms. Multiple links to Pfam and QuickGO databases are available for an easy retrieval of general domain and clan information. They are highlighted in green.</p>
	<blockquote>
	  <p align="center"><img <?php echo "src='".$appurl."/server_images/help_page/Screen Shot 2021-04-09 at 08.55.37.png'";?> width="1013" height="115" border="1"></p>
	  </blockquote>
	<p align="justify"><strong>Annotations details</strong>: domain ID, Pfam family, domain start and end position, species from which the model is constructed, E-value, bitscore and domain dependent probability score.</p>
	<blockquote>
	  <p align="center"><img <?php echo "src='".$appurl."/server_images/help_page/Screen Shot 2021-04-09 at 08.55.55.png'";?> width="1005" height="93"></p>
	  </blockquote>
	<p><strong>GO-terms</strong>: domain ID, Pfam family, Pfam clan ID, Pfam clan family, GO-terms.</p>
	<p><strong>For each domain hit</strong>, MyCLADE produces <strong>a logo  </strong> describing the match between the model and the sequence. The start and stop positions correspond to the starting and ending positions of the model hit against the sequence identified by HMMER. Note that these positions do not determine the envelope defining the domain, which usually corresponds to a larger interval.</p>
	<p>Logos have been generated with hmmlogo in <a href="http://hmmer.org" target="_blank" >HMMER</a> and <a href="https://logomaker.readthedocs.io/en/latest/" target="_blank" >Logomaker</a>. Within a logo, the height of the stack of letters corresponds to the conservation at that position, and the height of each letter within a stack depends on the frequency of that letter at that position. Residues are colored according to the ClustalX coloring scheme grouping amino acids by their physico-chemical properties: glycine (G) in orange, proline (P) in yellow, small or hydrophobic (A,V,L,I,M,F,W) in purple, hydroxyl or amine amino acids (S,T,N,Q) in green, charged amino-acids (D,E,R,K) in red and histidine or tyrosine (H,Y) in cyan. The symbol “*” shows a perfect match between the most frequent letter in the logo and the letter in the sequence; the symbol “+” shows that the letter in the sequence and the most frequent letter in the logo share the same physico-chemical group. </p>
	<p align="center"><img <?php echo "src='".$appurl."/server_images/help_page/logos.png'";?> width="753" height="366" border="1"></p>
	<p align="left">By hovering over the sequence with the mouse, the position of the letter within the sequence appears:</p>
	<p align="center"><img <?php echo "src='".$appurl."/server_images/help_page/hovering-the-logo.png'";?> width="511" height="261" border="1"></p>
	<p align="left">This feature is useful when the alignment of the logo with the sequence produces gaps in the logo of the model.</p>
	<p align="left">Logos are svg files and can be imported for insertion in articles.<br>
	  <br>
	</p>
	<h3 id = 'computation-time'>MyCLADE computation time</h3>
	<p>MyCLADE can annotate sequences with a limited number of targeted domains (see panel A in the figure below) or with all Pfam32 domains (see panel B). A runtime evaluation of MyCLADE was performed on these two possibilities for small (5, 10, 25 and 50 sequences) and large (100 and 200 sequences) protein datasets. On targeted domains, from 10 to a few hundred, MyCLADE annotates hundreds of sequences in less than a minute while on all Pfam32 domains it takes less than a hour.  </p>
	<p align="center"><img <?php echo "src='".$appurl."/server_images/help_page/time.png'";?> width="554" height="486"></p>
	<p>The estimates are realized with the complete model library. To evaluate the dependence of computation time on the number of available models, we considered the restricted library and observed that computation time is greatly reduced (panels A and B) at the cost of an expected decrease of the number of annotated domains: on the 200 sequences of the test dataset, 637 domains are annotated with the complete library and 561 with the restricted one. </p>
	<p>MyCLADE performance has been evaluated <strong>without DAMA</strong> because DAMA computing time is negligeable (for a few hundred proteins, the architecture reconstruction takes less than a few seconds) as described in Table 2 of (<a href="https://academic.oup.com/bioinformatics/article/32/3/345/1743675?login=true" target="_blank" >Bernardes et al.  <em>Bioinformatics</em>, 32:345–353, 2016</a>):</p>
	<p align="center"><img <?php echo "src='".$appurl."/server_images/help_page/Table time DAMA.png'"?> width="586" height="211"></p>
	<h3 align="justify"><br>
	  To analyse large datasets: MetaCLADE and DAMA downloads<br>
      </h3>
	<p align="justify">For an analysis of large files, not possible with MyCLADE, the user can locally install the new improved version of <a href="http://www.lcqb.upmc.fr/metaclade/" target="_blank" >MetaCLADE</a>, MetaCLADE v2. The SCM/CCM model library based on Pfam32 is available at the same address. The model library based on Pfam34 will come soon. </p>
	<p align="left">DAMA can be retrieved <a href="http://www.lcqb.upmc.fr/dama/" target="_blank" >here</a>. </p>
	<p align="left">Note that:</p>
	<ol>
	  <li>In <a href="http://www.lcqb.upmc.fr/metaclade/" target="_blank" >MetaCLADE</a>, SCM and CCM models are retrievable from two files, one for SCM and the other for CCM models. They have to be installed and run with the MetaCLADE program.  </li>
	  <li>Note that DAMA is not integrated in MetaCLADE and should be run independently if the user wants to take into account known architectures, providing a better confidence in the annotation. MyCLADE includes DAMA and the user can exploit it simply as an option.</li>
	  <li>MyCLADE provides the annotation associated scores and GO-terms allowing an interface that can help to evaluate the confidence. This feature is not present in MetaCLADE.</li>
	  <li>Logos can be built in MyCLADE for each domain hit, helping to evaluate the confidence in the annotation. This feature is not present in MetaCLADE.</li>
	</ol>
	<p>Compared to the original version of MetaCLADE, in MetaCLADE v2: </p>
	<ul>
	  <li>the code has been optimized; </li>
	  <li>Pfam FULL is used, instead of SEED, to cluster the initial set of homologous sequences; </li>
	  <li>the selection of representative sequences spanning the tree of life follows a new strategy, as described in “Model library for Pfam domains” of the MyCLADE article; </li>
	  <li>the search for similar sequences is realized with hhblits instead of psi-blast for the construction of HMMs instead of PSSMs.<br>
        <br>
      </li>
	</ul>
	<p align="left"><br>
	  </p>
	<p>&nbsp;</p>
	<blockquote>
	  <p align="center">	    </p>
	  </p>
	  </blockquote>
	</section>
	<?php include('./includes/footer.php'); ?>
