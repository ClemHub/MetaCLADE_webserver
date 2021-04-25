<?php include('./includes/header.php'); ?>
  <section id ='example'>
	<h2> Examples of annotation</h2>

	<p>
	MyCLADE is designed to annotate domains in protein sequences based on probabilistic models that have proven to be more specific and functionally predictive than consensus models. They have been shown to improve domain architectures. 
	<p>MyCLADE finds domains for proteins that are annotated for the first time, it adds missing domains to known architectures and might provide alternatives for previously annotated domains. MyCLADE is applied to a few sequences with all domain models in its library (containing more than 2 millions models) or to several thousands sequences with a few domains.     
	<p>We present a number of protein sequences illustrating  the way one can use MyCLADE to explore protein sequence annotation through its parameters. We shall also explain how to use MyCLADE towards functional annotation.
	<p>    
	<h4>Running MyCLADE with or without DAMA           on protein sequences </h4>
	<p><a href="http://www.lcqb.upmc.fr/dama/" target="_blank">DAMA</a> helps MyCLADE to reconstruct the most appropriate domain architecture based on combinations of co-occurring domains that have been previously observed. Note that after building an architecture, DAMA adds up new domains to this architecture (with an E-value &lt;1e-10) and this allows to discover new combinations of co-occurring domains. 
      <p>For the discovery of new domains and new architectures, running MyCLADE without DAMA allows to explore unexpected possibilities as illustrated below.<br>
        <br>
      When MyCLADE is run without DAMA, domain overlapping is allowed to a maximum of 10aa. When DAMA is used, the overlapping interval can be decided by the user and possibly forbidden. DAMA allows for a maximum overlap of 30aa.<br>
      <br>      
      <p><strong>Attention:</strong> when MyCLADE is run <strong>with DAMA</strong>, you might want to check whether the domains in the resulting architecture are known to co-occur or not. You can access the <a href="http://pfam.xfam.org/search#tabview=tab3 target="_blank"" target="_blank">online interface</a> proposed in <a href="http://pfam.xfam.org/" target="_blank">Pfam</a> for this. MyCLADE uses Pfam knowledge for building its architectures.      
      <p><br>
      <h3> The Mediterranean Fever gene MEFV and the pyrin protein</h3>
    <p>The gene MEFV plays a central role in inflammation and in fighting infection. It codes for a 781aa long protein known to be constituted by 4 domains: </p>
    <ul>
      <li>PAAD/DAPIN/Pyrin domain</li>
      <li>PRY (SPRY-associated domain)</li>
      <li>SPRY domain</li>
      <li>zf-B box (B-box zinc finger) </li>
    </ul>
    <p>Pfam and InterPro annotate the protein with these 4 domains. </p>
    <p>MyCLADE <strong>without DAMA</strong> identifies two more domains with acceptable E-values (see domains circled in the figure below):</p>
    <ul>
      <li>Vps5 - E-value 8.2e-06</li>
      <li>Josephin - E-value 2.9e-26</li>
    </ul>
    <p>The E-values highlighted by MyCLADE for the four known domains are all higher than those obtained by HMMer in Pfam. Namely, 5.5e-24 for PAAD/DAPIN/Pyrin, 1.9e-18 for PRY (SPRY-associated domain), 1.1e-16 for SPRY domain and 8.8e-07 for zf-B box (B-box zinc finger).</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images//examples/MEFV/MEFV-architecture-noDAMA.png'";?> width="905" height="405"></p>
    <p>The logo associated to the Josephin domain model (E-value 2.9e-26) allows the user to evaluate whether this domain can be plausible within the pyrin architecture. The model has 118 positions and the hit with the sequence covers all of it: </p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/MEFV/josephin-logo.png'";?> width="1165" height="160"></p>
    <p>MyCLADE provides the GO-term association to each domain:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/MEFV/MEFV-GO-noDAMA.png'";?> width="904" height="277"></p>
    <p>MyCLADE <strong>with DAMA</strong> identifies other domains, as illustrated below:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/MEFV/MEFV-withDAMA.png'";?> width="885" height="339"></p>
    <p align="left">The E-values of the domains obtained with DAMA are higher than the ones obtained without DAMA, and therefore less convincing.</p>
    <p align="left">&nbsp;</p>
    <h3>AMY1A - the human amylase</h3>
    <p>The amylase protein is an enzyme catalyzing the first step of digestion in dietary starch and glycogen. It is a 495aa long protein sequence annotated with</p>
    <ul>
      <li>alpha-amylase domain</li>
      <li>alpha-amylase_C domain</li>
    </ul>
    <p>by Pfam and InterPro. MyCLADE <strong>without DAMA</strong> identifies one more domain, the MORN_2, overlapping the alpha-amylase_C domain:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/amylase/architecture-withDama.png'";?> width="860" height="290"></p>
    <p>Note that an overlap of at most 10aa is accepted by MyCLADE run without DAMA. In the case of the MORN_2 domain, the overlap is 9 aa long.</p>
    <p>The two known domains are obtained by MyCLADE with better E-values than for Pfam which identifies them with 2.4e-12 for alpha-amylase and 2.9e-12 for alpha-amylase_C. The logo of the MORN_2 domain explains the E-value 1.1e-4:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/amylase/logo-MORN2.png'";?> width="676" height="215"></p>
    <p align="left">where the model matches the sequence on a 8 aa long motif, where 6 positions display a perfect match. Note that the envelop of the hit extends to a 13 aa long domain containing 2 more positions in the sequence sharing physico-chemical properties with the corresponding positions in the model. The MORN_2 is a repeated motif whose function is yet unknown. Its presence at the start of the alpha-amylase-C domain might suggest a potential binding site for this domain.</p>
    <p align="left">Note that the E-value for the MORN_2 domain is too high (&gt;1e-10) to be identified with MyCLADE run with DAMA. It is a domain that is not known to co-occur with the alpha-amylase and alpha-amylase-C domains and cannot be added as a new domain to an architecture by DAMA.</p>
    <p align="left"><br>
    </p>
    <h3>A UDP-N-acetyl-tunicamine-uracil synthase TunB-like protein (B5GL39)</h3>
    <p>The B5GL39 protein in the <em>Streptomyces clavuligerus</em> genome is 359aa long. It belongs to the Radical SAM superfamily. Pfam and InterPro annotate it with a Radical SAM domain while MyCLADE run <strong>with DAMA</strong> finds both the Radical SAM domain and the SPASM domain. The two domains are known to co-occur.</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/SPASM/B5GL39/MyCLADE.png'";?> width="920" height="463"></p>
    <p>The Structure-Function Linkage Database (SFLD) is a manually curated classification resource describing structure-function relationships for functionally diverse enzyme superfamilies. Notice that SFLD does not identify the SPASM domain neither.</p>
    <p>The logo of the SPASM domain model shows a hit that matches only partially the model. Many of the most conserved positions in the model appear conserved in the sequence:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/SPASM/B5GL39/logo-SPASM.png'";?> width="947" height="161" border="1"></p>
    <p>A second example is the protein S6CPK6:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/SPASM/S6CPK6/Screen Shot 2021-04-07 at 10.53.30.png'";?> width="886" height="278"></p>
    <p>where Pfam identifies only the Radical SAM domain while InterPro and MyCLADE identify both domains.</p>
    <p>The logo of the SPASM domain model shows a large hit with the sequence, a perfect match of the cysteins and of a number of conserved amino acids:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/SPASM/S6CPK6/Spasm-logo.png'";?> width="853" height="142" border="1"><br>
      <br>
      <br>
    </p>
    <h3>A hypothetical protein of <em>Staphylococcus aureus</em> (YP_499998)</h3>
    <p>The YP_499998 is a 367aa long hypothetical protein of <em>Staphylococcus aureus</em>. It is annotated by Pfam in a small region at the end of the protein sequence (329-355) with the TPR_8 domain (Evalue 0.013). InterPro annotates the region starting from position 49 as a TPR repeat region. MyCLADE, <strong>without DAMA</strong>, annotates a TPR_6 domain in the first part of the protein (1-18) and the rest with the NARP1 domain. Note that the NMDA receptor-regulated protein 1 (NARP1) is found in association with the Tetratricopeptide repeat. Also, note that the MyCLADE annotation of NARP1 is independent on knowledge of known architectures and it coincides with the presence of the TPR domain in the sequence, making the annotation particularly interesting.</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/YP_499998/long-version-noDAMA.png'";?> width="948" height="419"></p>
    <p align="left">The model of the NARP1 domain is 355 positions long and almost all its positions, 9-346, match the sequence on positions 21-353. The logo can help to inspect the conserved patterns along the match. Domain TRP_6 logo is shown below:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/YP_499998/TRP6.png'";?> width="668" height="183" border="1"></p>
    <p align="left">MyCLADE <strong>with DAMA</strong> predicts a large number of TPR repeats:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/YP_499998/long-version-with-DAMA.png'";?> width="955" height="508"></p>
    <p align="left">&nbsp;</p>
    <h3>RING finger protein in <em>Plasmodium falciparum</em> (PFE0100w)</h3>
    <p>The <em>Plasmodium falciparum</em> protein PFE0100w is a 1272aa long protein, annotated by Pfam with the single domain VPS11_C (1228-1271; E-value 1.6e-06). InterPro and MyCLADE <strong>with DAMA</strong> annotate it with three domains:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/Plasmodium/Plasmo-w-DAMA.png'";?> width="1160" height="384"></p>
    <p>When MyCLADE is run <strong>without DAMA</strong>, the annotation is augmented by domains that are not known to co-occur together with the  Clathrin and VPS11_C domains:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/Plasmodium/Plasmo-noDama.png'";?> width="1158" height="510"></p>
    <p>Inspection of the model logo of RPW8 highlights several interesting positions in the match of the RPW8 domain model against the sequence. Namely, note the two  cysteines and the high number of conserved amino acids that are found in the sequence:</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/Plasmodium/RPW8.png'";?> width="1171" height="191" border="1"><br>
      <br>
    </p>
    <p align="left">Another interesting domain is the Vps39_2</p>
    <p align="center"><img <?php echo "src='".$appurl."/server_images/examples/Plasmodium/Vps39_2.png'";?> width="1234" height="173" border="1"></p>
    <p>In particular, the Vps39_2 domain is found on the vacuolar sorting protein Vps39 which is a component of the C-Vps complex. Vps39 is thought to be required for the fusion of endosomes and other types of transport intermediates with the vacuole.  This domain is involved in localisation and in mediating the interactions of Vps39 with VPS11, which is also found in this sequence. Note that MyCLADE is run <strong>without DAMA</strong> and that knowledge on the co-occurrence of Vps39_2 and VPS11 is not used in the prediction.<br>
      <br>
    </p>
    <h3>Annotation of hundreds/thousand sequences: the study case of the SPASM/twitch domain<br>
    </h3>
    <p>Protein function for a combination of domains in a sequence is not easily deducible based on the function of single domains. Yet, the use of known domain combinations and GO_terms for single domains can help the user for a fast exploration of potential protein functions with MyCLADE. </p>
    <p>The <a href="http://sfld.rbvi.ucsf.edu/archive/django/index.html" target="_blank">Structure-Function Linkage Database</a> (SFLD) is a manually curated classification resource describing structure-function relationships for functionally diverse enzyme superfamilies (Schnoes et al., 2009; Akiva et al., 2014). Despite their different functions, members of these superfamilies “look alike” making them easy to misannotate. We challenged MyCLADE against these sets of sequences which are difficult not only for functional classification but also for domain annotation. </p>
    <p>Given a set of homologous sequences, one might ask whether they might be annotated or not by a given domain. MyCLADE can be used to annotate homologous sequences with one or a few domains. For instance, the set of enzyme sequences containing the Radical SAM domain found <a href="http://www.lcqb.upmc.fr/profileview/files/SFLD/SPASM/SPASM_domain_mmseqs2.fasta" target="_blank">here</a> has been analyzed with MyCLADE and Pfam to search for the SPASM/twitch domain. MyCLADE found 20 sequences over 129 with the domain while PFAM could identify the domain for only 6 of these sequences. Some sequences from this set have been discussed above in some detail. See the example &quot;A UDP-N-acetyl-tunicamine-uracil synthase TunB-like protein (B5GL39)&quot;.</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p><br>
            </p>
    </p>
    </section>

<?php include('./includes/footer.php'); ?>