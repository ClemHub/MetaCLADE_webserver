<?php include("./includes/header.php"); ?>

	<section class = 'tools'>
		<h2> Annotation of a clan</h2>
		
		<form autocomplete="off" name="clan_annotation_form" method = POST action="submit.php?form=clan" enctype="multipart/form-data" onsubmit="return clan_form_submission(clan_list)">
			<fieldset class='form_fs'><legend><h4>Input data:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>See Help for expected format.</span></span></h4></legend>
			<div class="autocomplete" id='clan_container'>
			<label for="clan">Clan: <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Enter the name of the clan.</span></span></label><br/>
			<input id="clan" type="text" name="clan" placeholder="CL00001" autofocus>
			</div>
			<div id='seq_container'>
			<label for="sequences">Sequences in Fasta format:</label><br/>
			<textarea name="sequences" id = "sequences" rows='10' ></textarea><br/>
			<label for="fasta_file">Upload a Fasta file:</label>
			<input type="file" id="fasta_file" name="fasta_file"/>
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>Parameters:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Parameters settings: hits are filtered by the E-value. DAMA reconstructs the most likely architecture.</span></span></h4></legend>
			
			<div class = 'library_choice'>
			Model library:<br/>


			<label for="reduced_btn">Reduced (&le;50 models/domain)</label><input type="radio" class='radio_btn' name="library" id="reduced_btn" value = "false" />
			&emsp;
			<label for="complete_btn">Complete (&le;350 models/domain)</label><input type="radio" class='radio_btn' name="library" id="complete_btn" value = "true" checked/>
			
			</div>

			<div class = 'metaclade_e-value'>
			<label for='evalue_range'><br/>E-value threshold for MetaCLADE:<br/></label>
			<input type="range" id='evalue_range' name="evalue_range" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_nb.value=this.value" />
			<input type="number" id='evalue_nb' name="evalue_nb" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_range.value=this.value" />
			<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>E-value set to 1e<sup>-3</sup> by default.<br/> Results with E-value greater than 1 are automatically filtered out.</span></span>
			</div>
			
			<div class = 'dama_choice'>
			<br/>Reconstruction of domain architectures with DAMA:<br/>

			<label for="yes_btn">Yes</label><input type="radio" class='radio_btn' name="dama" id="yes_btn" value = "true" onclick='ShowHideDama()'/>

			<label for="no_btn">No</label><input type="radio" class='radio_btn' name="dama" id="no_btn" value = "false" onclick='ShowHideDama()' checked/>
			</div>

			<div id = 'show_dama'>
				<br>
				<label for='dama_evalue_range'>E-value threshold for DAMA:<br/></label>
					<input type="range" id='dama_evalue_range' name="dama_evalue_range" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_nb.value=this.value" />
					<input type="number" id ='dama_evalue_nb' name="dama_evalue_nb" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_range.value=this.value" />
				<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>E-value set to 1e<sup>-10</sup> by default. We advice you to avoid an E-value higher than 1e<sup>-5</sup>.<br/> Results with E-value greater than or equal to 1 are automatically filtered out.</span></span>
				<br><br>
				<label for='overlappingAA_range'>Number of amino acids allowed in the domain overlapping:<br/></label>
					<input type="range" id='overlappingAA_range' name="overlappingAA_range" min='0' max="30" value="30" step='1' oninput="this.form.overlappingAA_nb.value=this.value" />
					<input type="number" id ='overlappingAA_nb' name="overlappingAA_nb" min='0' max="30" value="30" step='1' oninput="this.form.overlappingAA_range.value=this.value" />
				<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Range set to 30 by default. We advice you to avoid a range higher than 30.</span></span>
				<br><br>
				<label for='overlappingMaxDomain_range'>Proportion of domain overlapping allowed (%):<br/></label>
					<input type="range" id='overlappingMaxDomain_range' name="overlappingMaxDomain_range" min='0' max="50" value="50" step='1' oninput="this.form.overlappingMaxDomain_nb.value=this.value" />
					<input type="number" id ='overlappingMaxDomain_nb' name="overlappingMaxDomain_nb" min='0' max="50" value="50" step='1' oninput="this.form.overlappingMaxDomain_range.value=this.value" />
				<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Proportion set to 50% by default. We advice you to avoid a proportion higher than 50%.</span></span>
			
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>Job name:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Optional and used to store your job details.</span></span></h4></legend>

			<div id = 'job_name_div'>
			<input type="text" id="job_name" name="job_name" placeholder = 'Optional'>
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>E-mail address:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Optional and used to send you job details.</span></span></h4></legend>

			<div id = 'email_address'>
			<input type="email" name="email" id="email" pattern=".+@.+\..+" placeholder = 'Optional'>
			</div>
			</fieldset>
			<div id='submission'>
			<br/>
			<input class='btn' type="submit" value="Search" name = "submit"/>&emsp;<input class='btn' type="reset" value="Reset" onclick="reset_btn('clan')"/>&emsp;
			<span class='tooltip'><input class='btn' type="button" value="Example" onclick="fill_exemple_form('clan')"/><span class='tooltiptext'>It uploads a precalculated example, you can choose whether or not to use DAMA. All other parameters are defined with default values.</span></span>
			</div>
		</form>
	</section>
	<script type="text/javascript">
		var fileInput = document.querySelector('#fasta_file');
		fileInput.addEventListener('change', function() {
		var reader = new FileReader();
		reader.addEventListener('load', function() {
			var txt = reader.result
			document.getElementById('sequences').value = txt;});
		reader.readAsText(fileInput.files[0]);});


	
function autocomplete(inp, arr) {
	/*the autocomplete function takes two arguments,
	the text field element and an array of possible autocompleted values:*/
	var currentFocus;
	/*execute a function when someone writes in the text field:*/
	inp.addEventListener("input", function(e) {
		var a, b, i, val = this.value;
		/*close any already open lists of autocompleted values*/
		closeAllLists();
		if (!val) { return false;}
		currentFocus = -1;
		/*create a DIV element that will contain the items (values):*/
		a = document.createElement("DIV");
		a.setAttribute("id", this.id + "autocomplete-list");
		a.setAttribute("class", "autocomplete-items");
		a.setAttribute("style", "");
		/*append the DIV element as a child of the autocomplete container:*/
		this.parentNode.appendChild(a);
		/*for each item in the array...*/
		for (i = 0; i < arr.length; i++) {
		  /*check if the item starts with the same letters as the text field value:*/
		  if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
			/*create a DIV element for each matching element:*/
			b = document.createElement("DIV");
			/*make the matching letters bold:*/
			b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
			b.innerHTML += arr[i].substr(val.length);
			/*insert a input field that will hold the current array item's value:*/
			b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
			/*execute a function when someone clicks on the item value (DIV element):*/
				b.addEventListener("click", function(e) {
				/*insert the value for the autocomplete text field:*/
				inp.value = this.getElementsByTagName("input")[0].value;
				/*close the list of autocompleted values,
				(or any other open lists of autocompleted values:*/
				closeAllLists();});
			a.appendChild(b);}}});
	/*execute a function presses a key on the keyboard:*/
	inp.addEventListener("keydown", function(e) {
		var x = document.getElementById(this.id + "autocomplete-list");
		if (x) x = x.getElementsByTagName("div");
		if (e.keyCode == 40) {
		  /*If the arrow DOWN key is pressed,
		  increase the currentFocus variable:*/
		  currentFocus++;
		  /*and and make the current item more visible:*/
		  addActive(x);}
		else if (e.keyCode == 38) { //up
		  /*If the arrow UP key is pressed,
		  decrease the currentFocus variable:*/
		  currentFocus--;
		  /*and and make the current item more visible:*/
		  addActive(x);}
		else if (e.keyCode == 13) {
		  /*If the ENTER key is pressed, prevent the form from being submitted,*/
		  e.preventDefault();
		  if (currentFocus > -1) {
			/*and simulate a click on the "active" item:*/
			if (x) x[currentFocus].click();}}});
	function addActive(x) {
	  /*a function to classify an item as "active":*/
	  if (!x) return false;
	  /*start by removing the "active" class on all items:*/
	  removeActive(x);
	  if (currentFocus >= x.length) currentFocus = 0;
	  if (currentFocus < 0) currentFocus = (x.length - 1);
	  /*add class "autocomplete-active":*/
	  x[currentFocus].classList.add("autocomplete-active");}
	function removeActive(x) {
	  /*a function to remove the "active" class from all autocomplete items:*/
	  for (var i = 0; i < x.length; i++) {
		x[i].classList.remove("autocomplete-active");}}
	function closeAllLists(elmnt) {
	  /*close all autocomplete lists in the document,
	  except the one passed as an argument:*/
	  var x = document.getElementsByClassName("autocomplete-items");
	  for (var i = 0; i < x.length; i++) {
		if (elmnt != x[i] && elmnt != inp) {
		x[i].parentNode.removeChild(x[i]);}}}
	/*execute a function when someone clicks in the document:*/
	document.addEventListener("click", function (e) {
	closeAllLists(e.target);});}

	var clan_list = ['CL0192 - GPCR_A', 'CL0023 - P-loop_NTPase', 'CL0079 - Cystine-knot', 'CL0001 - EGF', 'CL0190 - HSP20', 'CL0108 - Actin_ATPase', 'CL0007 - KH', 'CL0541 - SH2-like', 'CL0010 - SH3', 'CL0607 - TNF_receptor', 'CL0117 - uPAR_Ly6_toxin', 'CL0465 - Ank', 'CL0168 - PAN', 'CL0129 - Peptidase_AA', 'CL0029 - Cupin', 'CL0159 - E-set', 'CL0375 - Transporter', 'CL0333 - gCrystallin', 'CL0121 - Cystatin', 'CL0328 - 2heme_cytochrom', 'CL0318 - Cytochrome-c', 'CL0196 - DSRM', 'CL0220 - EF_hand', 'CL0344 - 4Fe-4S', 'CL0451 - FnI-like', 'CL0602 - Kringle', 'CL0090 - Globin', 'CL0497 - GST_C', 'CL0063 - NADP_Rossmann', 'CL0123 - HTH', 'CL0011 - Ig', 'CL0239 - Insulin', 'CL0005 - Kazal', 'CL0202 - GBD', 'CL0004 - Concanavalin', 'CL0186 - Beta_propeller', 'CL0056 - C_Lectin', 'CL0030 - Ion_channel', 'CL0116 - Calycin', 'CL0037 - Lysozyme', 'CL0434 - Sialidase', 'CL0629 - PLA2', 'CL0016 - PKinase', 'CL0304 - CheY', 'CL0055 - Nucleoplasmin', 'CL0219 - RNase_H', 'CL0221 - RRM', 'CL0027 - RdRP', 'CL0015 - MFS', 'CL0172 - Thioredoxin', 'CL0630 - PSI', 'CL0124 - Peptidase_PA', 'CL0566 - Tubulin', 'CL0128 - vWA-like', 'CL0361 - C2H2-zf', 'CL0229 - RING', 'CL0511 - Retroviral_zf', 'CL0031 - Phosphatase', 'CL0053 - 4H_Cytokine', 'CL0167 - Zn_Beta_Ribbon', 'CL0046 - Thiolase', 'CL0486 - Fer2', 'CL0125 - Peptidase_CA', 'CL0256 - Enolase_TIM', 'CL0327 - Pilus', 'CL0026 - CU_oxidase', 'CL0014 - Glutaminase_I', 'CL0286 - GCS', 'CL0036 - TIM_barrel', 'CL0012 - Histone', 'CL0058 - Glyco_hydro_tim', 'CL0343 - MHC', 'CL0006 - C1', 'CL0461 - Metallothionein', 'CL0536 - HEXAPEP', 'CL0039 - HUP', 'CL0065 - Cyclin', 'CL0028 - AB_hydrolase', 'CL0194 - DNA_pol_B-like', 'CL0617 - Peroxidase', 'CL0013 - Beta-lactamase', 'CL0425 - ComplexI-N', 'CL0422 - Fibrinogen_C', 'CL0043 - Chelatase', 'CL0163 - Calcineurin', 'CL0040 - tRNA_synt_II', 'CL0061 - PLP_aminotran', 'CL0533 - PRTase-like', 'CL0475 - Cyclophil-like', 'CL0492 - S4', 'CL0021 - OB', 'CL0296 - GroES', 'CL0066 - Trefoil', 'CL0154 - C2', 'CL0266 - PH', 'CL0018 - bZIP', 'CL0099 - ALDH-like', 'CL0072 - Ubiquitin', 'CL0091 - NAD_Ferredoxin', 'CL0208 - UBC', 'CL0270 - Iso_DH', 'CL0399 - Asp-glut_race', 'CL0387 - DHFred', 'CL0659 - CAP', 'CL0149 - CoA-acyltrans', 'CL0113 - GT-B', 'CL0329 - S5', 'CL0085 - FAD_DHS', 'CL0062 - APC', 'CL0044 - Ferritin', 'CL0276 - Nucleot_cyclase', 'CL0255 - ATP_synthase', 'CL0548 - IHF-likeDNA-bdg', 'CL0547 - GF_recep_C-rich', 'CL0151 - PK_TIM', 'CL0392 - Chaperone-J', 'CL0052 - NTN', 'CL0100 - C1q_TNF', 'CL0237 - HD_PDEase', 'CL0482 - Prolamin', 'CL0431 - PF', 'CL0092 - ADF', 'CL0088 - Alk_phosphatase', 'CL0035 - Peptidase_MH', 'CL0143 - B_Fructosidase', 'CL0487 - FKBP', 'CL0042 - Flavoprotein', 'CL0452 - Tropomyosin-lke', 'CL0205 - Di-copper', 'CL0193 - MBB', 'CL0282 - Serum_albumin', 'CL0290 - EPT_RTPC', 'CL0367 - CI-2', 'CL0652 - S24e_L23_L15e', 'CL0483 - PreATP-grasp', 'CL0261 - NUDIX', 'CL0118 - Ribokinase', 'CL0268 - Pec_lyase-like', 'CL0575 - EFTPs', 'CL0096 - Pept_Inhib_IE', 'CL0071 - His_phosphatase', 'CL0045 - Rubredoxin', 'CL0054 - Knottin_1', 'CL0188 - CH', 'CL0600 - S15_NS1', 'CL0293 - CDC', 'CL0171 - Phospoesterase', 'CL0067 - SIS', 'CL0075 - Defensin', 'CL0347 - Tetraspannin', 'CL0135 - Arrestin_N-like', 'CL0556 - PapD-like', 'CL0613 - Terp_synthase', 'CL0407 - TBP-like', 'CL0592 - beta_Roll', 'CL0516 - ISP-domain', 'CL0105 - Hybrid', 'CL0340 - PTase-anion_tr', 'CL0161 - GAF', 'CL0240 - PFK', 'CL0632 - FERM_M', 'CL0127 - ClpP_crotonase', 'CL0109 - CDA', 'CL0049 - Tudor', 'CL0384 - PLC', 'CL0325 - Form_Glyc_dh', 'CL0603 - AA_dh_N', 'CL0364 - Leu-IlvD', 'CL0106 - 6PGD_C', 'CL0680 - WW', 'CL0177 - PBP', 'CL0209 - Bet_V_1_like', 'CL0267 - S11_L18p', 'CL0126 - Peptidase_MA', 'CL0303 - H2TH', 'CL0204 - Adhesin', 'CL0164 - CUB', 'CL0059 - 6_Hairpin', 'CL0217 - Rotavirus_VP7', 'CL0087 - Acyl-CoA_dh', 'CL0034 - Amidohydrolase', 'CL0551 - BCLiA', 'CL0246 - ISOCOT_Fold', 'CL0254 - THDP-binding', 'CL0224 - DHQS', 'CL0107 - KOW', 'CL0337 - RF', 'CL0385 - Hydrophilin', 'CL0238 - PP2C', 'CL0110 - GT-A', 'CL0302 - Arginase', 'CL0357 - SMAD-FHA', 'CL0378 - ANL', 'CL0114 - HMG-box', 'CL0025 - His_Kinase_A', 'CL0020 - TPR', 'CL0595 - Fusion_gly', 'CL0404 - BPD_transp_1', 'CL0550 - SRCR', 'CL0041 - Death', 'CL0144 - Periplas_BP', 'CL0459 - BRCT-like', 'CL0003 - SAM', 'CL0074 - Matrix', 'CL0326 - Reo_sigma', 'CL0089 - GlnB-like', 'CL0506 - Succ_CoA_synth', 'CL0314 - PP-binding', 'CL0203 - CBD', 'CL0073 - P53-like', 'CL0568 - Man_lectin', 'CL0022 - LRR', 'CL0410 - LEF-8-like', 'CL0426 - HRDC-like', 'CL0287 - Transthyretin', 'CL0257 - Acetyltrans', 'CL0070 - ACT', 'CL0098 - SPOUT', 'CL0382 - DNA-mend', 'CL0466 - PDZ-like', 'CL0236 - PDDEXK', 'CL0148 - Viral_Gag', 'CL0674 - Triple_B_spiral', 'CL0145 - Golgi-transport', 'CL0479 - PLD', 'CL0272 - RGS', 'CL0409 - GAP', 'CL0542 - RAS_GEF_N', 'CL0214 - UBA', 'CL0390 - zf-FYVE-PHD', 'CL0552 - Hect', 'CL0198 - HHH', 'CL0539 - RNase_III', 'CL0537 - CCCH_zf', 'CL0084 - ADP-ribosyl', 'CL0271 - F-box', 'CL0512 - CRAL_TRIO', 'CL0033 - POZ', 'CL0417 - BIR-like', 'CL0093 - Peptidase_CD', 'CL0264 - SGNH_hydrolase', 'CL0241 - ABC_membrane', 'CL0076 - FAD_Lum_binding', 'CL0681 - HAMP', 'CL0094 - Peptidase_ME', 'CL0437 - EF-G_C', 'CL0369 - GHD', 'CL0153 - dUTPase', 'CL0323 - Patatin', 'CL0137 - HAD', 'CL0060 - DNA_clamp', 'CL0622 - Acylphosphatase', 'CL0620 - Antihemostatic', 'CL0201 - Peptidase_SH', 'CL0299 - Peptidase_SF', 'CL0083 - Omega_toxin', 'CL0605 - ssDNA_NP_VP', 'CL0139 - GADPH_aa-bio_dh', 'CL0280 - PIN', 'CL0381 - Metallo-HOrase', 'CL0009 - ENTH_VHS', 'CL0661 - Gain', 'CL0169 - Rep', 'CL0571 - 30K_movement', 'CL0445 - SNARE-fusion', 'CL0505 - Pentapeptide', 'CL0141 - MtN3-like', 'CL0374 - PEP-carboxyk', 'CL0352 - EsxAB', 'CL0588 - Ribos_L15p_L18e', 'CL0346 - Ribo_L29', 'CL0080 - Beta-tent', 'CL0081 - MBD-like', 'CL0649 - PseudoU_synth', 'CL0051 - NTF2', 'CL0464 - 5_3_exonuc_C', 'CL0543 - Viral_gly_cn_dm', 'CL0322 - RND_permease', 'CL0166 - PRD', 'CL0226 - M6PR', 'CL0529 - FMN-dep-NRtase', 'CL0368 - PhosC-NucP1', 'CL0184 - DMT', 'CL0104 - Glyoxalase', 'CL0389 - TRAF', 'CL0448 - Cargo_bd_muHD', 'CL0182 - IT', 'CL0077 - FAD_PCMH', 'CL0142 - Membrane_trans', 'CL0324 - Homing_endonuc', 'CL0353 - TIMP-like', 'CL0199 - DPBB', 'CL0156 - Nucleocapsid', 'CL0183 - PAS_Fold', 'CL0064 - CPA_AT', 'CL0334 - THBO-biosyn', 'CL0453 - Apoptosis-Inhib', 'CL0633 - NusB', 'CL0032 - Dim_A_B_barrel', 'CL0534 - YjgF-like', 'CL0408 - PUP', 'CL0181 - ABC-2', 'CL0611 - Hexon', 'CL0078 - DNA_ligase', 'CL0179 - ATP-grasp', 'CL0158 - GH_CE', 'CL0363 - H-int', 'CL0130 - Peptidase_AD', 'CL0612 - PHM_PNGase_F', 'CL0170 - Peptidase_MD', 'CL0265 - HIT', 'CL0370 - Uteroglobin', 'CL0521 - GOLD-like', 'CL0232 - NifU', 'CL0621 - Colipase', 'CL0345 - Aerolisin_ETX', 'CL0658 - OB_enterotoxin', 'CL0230 - HO', 'CL0335 - FumRed-TM', 'CL0433 - SPFH', 'CL0234 - CTPT', 'CL0644 - Fz', 'CL0395 - Tubby_C', 'CL0292 - LysE', 'CL0103 - Gal_mutarotase', 'CL0082 - MIF', 'CL0509 - RBP11-like', 'CL0397 - TusA-like', 'CL0160 - Methionine_synt', 'CL0212 - SNARE', 'CL0115 - Steroid_dh', 'CL0263 - His-Me_finger', 'CL0339 - PFL-like', 'CL0380 - IDO-like', 'CL0336 - FMN-binding', 'CL0175 - TRASH', 'CL0101 - PELOTA', 'CL0648 - Aha1_BPI', 'CL0396 - Marvel-like', 'CL0356 - AMP_N-like', 'CL0057 - Met_repress', 'CL0359 - Intron-mat_II', 'CL0069 - GFP', 'CL0366 - JAB', 'CL0477 - TRD', 'CL0527 - Sm-like', 'CL0598 - B_GA', 'CL0379 - PgaPase', 'CL0244 - PGBD', 'CL0178 - PUA', 'CL0187 - LysM', 'CL0321 - PLAT', 'CL0520 - Keratin_assoc', 'CL0231 - MazG', 'CL0666 - Vault', 'CL0418 - GIY-YIG', 'CL0213 - ShK-like', 'CL0228 - Acyltransferase', 'CL0222 - MviN_MATE', 'CL0377 - FAH', 'CL0332 - AcetylDC-like', 'CL0525 - pap2', 'CL0289 - FBD', 'CL0050 - HotDog', 'CL0173 - STIR', 'CL0449 - G-PATCH', 'CL0233 - SufE_NifU', 'CL0155 - CBM_14_19', 'CL0086 - FAD_oxidored', 'CL0140 - Viral_NABP', 'CL0670 - 4PPT', 'CL0625 - eIF4e', 'CL0223 - MACRO', 'CL0288 - DAP_epimerase', 'CL0577 - Paramyxovirin_C', 'CL0207 - Rhomboid-like', 'CL0436 - FliG', 'CL0269 - Maf', 'CL0502 - STAS', 'CL0260 - NTP_transf', 'CL0095 - Peptidase_ML', 'CL0413 - Toprim-like', 'CL0316 - Acyl_transf_3', 'CL0619 - Mog1p_PsbP', 'CL0481 - HUH', 'CL0626 - Levi_coat', 'CL0624 - CcdB_PemK', 'CL0584 - FF', 'CL0297 - PhoU', 'CL0639 - Rof', 'CL0283 - LigB', 'CL0503 - SOR', 'CL0362 - RAMPS-Cas5-like', 'CL0315 - Gx_transp', 'CL0646 - T3SS', 'CL0243 - AEP', 'CL0499 - O-anti_assembly', 'CL0522 - YbjQ-like', 'CL0360 - MTH1187-YkoF', 'CL0197 - GME', 'CL0441 - AlbA', 'CL0200 - Prefoldin', 'CL0601 - Cob_adeno_trans', 'CL0273 - CYTH', 'CL0291 - KNTase_C', 'CL0122 - UTRA', 'CL0319 - SHS2', 'CL0376 - Oxa1', 'CL0635 - DmpA_ArgJ', 'CL0572 - TIKI', 'CL0305 - PTH2', 'CL0656 - VSA', 'CL0494 - DnaA_N', 'CL0311 - SCP2', 'CL0306 - HeH', 'CL0419 - T3SS-Chaperone', 'CL0131 - DoxD-like', 'CL0582 - TrkA_C', 'CL0317 - Multiheme_cytos', 'CL0424 - T3SS-hook', 'CL0330 - AVL9', 'CL0573 - KA1-like', 'CL0671 - AAA_lid', 'CL0355 - CheC-like', 'CL0638 - PAZ', 'CL0589 - KIX_like', 'CL0248 - ParBc', 'CL0068 - RIIa', 'CL0457 - 4HB_MCP', 'CL0610 - ETAP', 'CL0673 - GYF', 'CL0532 - LIG', 'CL0609 - sPC4_like', 'CL0206 - TRB', 'CL0618 - MCR', 'CL0570 - PPP-I', 'CL0653 - VCCI', 'CL0498 - Nribosyltransf', 'CL0623 - SRP9_14', 'CL0545 - APCOP-app_sub', 'CL0351 - CHCH', 'CL0651 - Mad2', 'CL0672 - p35', 'CL0439 - NusG-like', 'CL0405 - DNA_b-psBarrel', 'CL0111 - GT-C', 'CL0493 - PTS_EIIC', 'CL0132 - AbrB', 'CL0526 - SUKH', 'CL0298 - tRNA_bind_arm', 'CL0348 - Phage_tail', 'CL0560 - Chalcone-like', 'CL0597 - Post-HMGL', 'CL0484 - Peroxisome', 'CL0349 - SLOG', 'CL0251 - MORN', 'CL0472 - Peptidase_U', 'CL0627 - Antibiotic_NAT', 'CL0663 - CNF1_YfiH', 'CL0365 - MurF-HprK_N', 'CL0136 - Plasmid-antitox', 'CL0157 - Kleisin', 'CL0423 - AhpD-like', 'CL0394 - DsrEFH-like', 'CL0279 - GatB_YqeY', 'CL0245 - EDD', 'CL0416 - Anoctamin-like', 'CL0047 - CuAO_N2_N3', 'CL0616 - SPOC', 'CL0165 - Cache', 'CL0227 - Enolase_N', 'CL0544 - AcylCoA_ox_dh_N', 'CL0476 - tRNA-IECD_N', 'CL0591 - TKC_like', 'CL0614 - L27', 'CL0210 - HNOX-like', 'CL0247 - 2H', 'CL0546 - Hexosaminidase', 'CL0535 - CBM', 'CL0608 - Reductase_C', 'CL0341 - LDH_C', 'CL0275 - HAS-barrel', 'CL0386 - Ant-toxin_C', 'CL0469 - l-integrase_N', 'CL0174 - TetR_C', 'CL0277 - FAD-oxidase_C', 'CL0300 - TAT', 'CL0402 - Cdc48_2-like', 'CL0250 - GAD', 'CL0429 - UcrQ-like', 'CL0176 - Chemosens_recp', 'CL0393 - FucI-AraA_C', 'CL0354 - bBprotInhib', 'CL0195 - DBL', 'CL0650 - AOC_barrel', 'CL0242 - DNA_primase_lrg', 'CL0400 - GG-leader', 'CL0517 - MBOAT-like', 'CL0295 - Vps51', 'CL0133 - AT14A-like', 'CL0274 - WRKY-GCM1', 'CL0458 - IIaaRS-ABD', 'CL0685 - AmpE_CobD-like', 'CL0331 - EpsM', 'CL0615 - ALDC', 'CL0559 - CBD9-like', 'CL0235 - PspA', 'CL0576 - STARBD', 'CL0530 - DNase_I-like', 'CL0320 - PepSY', 'CL0628 - EspA_CesA', 'CL0383 - PheT-TilS', 'CL0446 - Bacteriocin_TLN', 'CL0097 - TypeIII_Chap', 'CL0428 - TolA-TonB-Cterm', 'CL0048 - LolA_LolB', 'CL0146 - Herpes_glyco', 'CL0523 - GAG-polyprotein', 'CL0580 - YqgF', 'CL0430 - CopD_like', 'CL0435 - NPR', 'CL0508 - YkuD', 'CL0342 - TolB_N', 'CL0513 - LCCL-domain', 'CL0373 - Phage-coat', 'CL0645 - Anti-sigma_N', 'CL0112 - Yip1', 'CL0478 - ATPase_I_AtpR', 'CL0147 - Traffic', 'CL0308 - DMSO_reductase', 'CL0490 - PepSY_TM-like', 'CL0442 - Tubulin_C', 'CL0259 - OstA', 'CL0596 - VPS23_C', 'CL0471 - DUF362', 'CL0507 - Fungal_trans', 'CL0540 - GCP', 'CL0500 - Glycine-zipper', 'CL0667 - Fer2_BFD', 'CL0655 - PilP', 'CL0401 - AsmA-like', 'CL0414 - TerB', 'CL0189 - Endonuclease', 'CL0569 - Phage_TTPs', 'CL0307 - FUSC', 'CL0662 - Triple_barrel', 'CL0590 - OML_zippers', 'CL0278 - AIG2', 'CL0372 - Hy-ly_N', 'CL0563 - Holin-II', 'CL0225 - FtsL', 'CL0310 - DinB', 'CL0631 - YgaC_TfoX-N', 'CL0679 - TACC', 'CL0249 - Phage_tail_L', 'CL0564 - Holin-III', 'CL0660 - SHOCT', 'CL0643 - YqbG', 'CL0312 - HemS_ChuX', 'CL0350 - PRC-barrel', 'CL0491 - LYR-like', 'CL0504 - Phage_barrel', 'CL0371 - Inovirus-Coat', 'CL0406 - YjbJ-CsbD-like', 'CL0684 - NFACT_RNA-bind', 'CL0262 - Trigger_C', 'CL0258 - DALR', 'CL0488 - FixH-like', 'CL0411 - Vir', 'CL0284 - Allatostatin', 'CL0412 - Frag1-like', 'CL0281 - CCT', 'CL0567 - Phage_TACs', 'CL0403 - ADC-like', 'CL0450 - FimbA', 'CL0421 - LppaM', 'CL0606 - Phage_fibre', 'CL0470 - UMP_1', 'CL0528 - DUF1214', 'CL0191 - POTRA', 'CL0420 - GlpM-like', 'CL0562 - Holin-V', 'CL0447 - Hypoth_1', 'CL0634 - CheY-binding', 'CL0294 - Sec10', 'CL0285 - YycI_YycH', 'CL0593 - G5', 'CL0398 - RMMBL_DRMBL', 'CL0642 - SOCS_box', 'CL0637 - KaiA_RbsU', 'CL0301 - PA14', 'CL0388 - FadR-C', 'CL0162 - FBA', 'CL0496 - Tad-like', 'CL0515 - LTXXQ-like', 'CL0636 - Leukocidin', 'CL0391 - CAP_C-like', 'CL0455 - MIM-OM_import', 'CL0561 - LisH', 'CL0594 - DUF1735', 'CL0604 - post-AAA', 'CL0677 - GHMP_C', 'CL0489 - SAF', 'CL0654 - WYL', 'CL0462 - TPA-repeat', 'CL0599 - GH57_38_middle', 'CL0524 - MPT63-MPB63', 'CL0668 - Pre-PUA', 'CL0578 - AbiEI', 'CL0456 - Golgi_traff', 'CL0585 - Nucleoporin_A', 'CL0640 - Colicin_D_E5', 'CL0678 - CLIP', 'CL0688 - BECR', 'CL0474 - SICA_like', 'CL0531 - AMP-binding_C', 'CL0647 - FG_rpt', 'CL0565 - GHKL', 'CL0669 - NOG1_N', 'CL0676 - SLATT', 'CL0664 - UB2H', 'CL0586 - CTC1', 'CL0683 - MICOS', 'CL0665 - BET', 'CL0587 - STAND_N', 'CL0682 - MBG', 'CL0687 - Zn_chelatase', 'CL0686 - aG_PT'];
	autocomplete(document.getElementById("clan"), clan_list);
	var test = "Example:\n>SeqID_1\nsequence_1\n>SeqID_2\nsequence_2\n";
	$('textarea').attr('placeholder', placeholder);

	$('textarea').focus(function(){
		if($(this).val() === placeholder){
			$(this).attr('placeholder', '');
		}
	});

	$('textarea').blur(function(){
		if($(this).val() ===''){
			$(this).attr('placeholder', placeholder);
		}    
	});
	</script>
<?php include("./includes/footer.php"); ?>
