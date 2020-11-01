<?php include("./includes/header.php"); ?>

	<section class = 'tools'>
		<h2> Annotation of a few domains </h2>
		
		<form name="small_annotation_form" method = POST action="submit.php?form=small" enctype="multipart/form-data" onsubmit="return small_form_submission()">
			<fieldset class='form_fs'><legend><h4>Input data:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>See Help for expected format.<br>And do not enter more than 10 domains.</span></span></h4></legend>
			<div id='pfam_container'>
			<label for="pfam_domains">PFAM accession number: <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Do not enter more than 10 domains.</span></span></label><br/>
			<textarea name="pfam_domains" id = "pfam_domains" rows='10' placeholder="Example:&#10;PF04523,PF06584,PF06325" autofocus></textarea>
			
			</div>

			<div id='seq_container'>
			<label for="sequences">Sequences in Fasta format:</label><br/>
			<textarea name="sequences" id = "sequences" rows='10' placeholder="Example:&#10;>SeqID_1&#10;sequence_1&#10;>SeqID_2&#10;sequence_2&#10;"></textarea><br/>
			<label for="fasta_file">Upload a Fasta file:</label>
			<input type="file" id="fasta_file" name="fasta_file"/>
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>Parameters:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Parameters settings: hits are filtered by the E-value. DAMA reconstructs the most likely architecture.</span></span></h4></legend>
			
			<div class = 'library_choice'>
			<br/>Model library:<br/>

			<label for="complete_btn">Complete (&le;350 models)</label><input type="radio" name="library" id="complete_btn" value = "true" checked/>

			<label for="reduced_btn">Reduced (&le;50 models)</label><input type="radio" name="library" id="reduced_btn" value = "false" />
			</div>

			<div class = 'metaclade_e-value'>
			<label for='evalue_range'><br/>E-value threshold for MetaCLADE:<br/></label>
			<input type="range" id='evalue_range' name="evalue_range" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_nb.value=this.value" />
			<input type="number" id='evalue_nb' name="evalue_nb" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_range.value=this.value" />
			<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>E-value set to 1e<sup>-3</sup> by default.<br/> Results with E-value greater than 1 are automatically filtered out.</span></span>
			</div>
			
			<div class = 'dama_choice'>
			<br/>Reconstruction of domain architectures with DAMA:<br/>

			<label for="yes_btn">Yes</label><input type="radio" name="dama" id="yes_btn" value = "true" onclick='ShowHideDama()'/>

			<label for="no_btn">No</label><input type="radio" name="dama" id="no_btn" value = "false" onclick='ShowHideDama()' checked/>
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
			<fieldset class='form_fs'><legend><h4>E-mail adress:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Optional and used to send you job details.</span></span></h4></legend>

			<div id = 'email_address'>
			<input type="email" name="email" id="email" pattern=".+@.+\..+" placeholder = 'Optional'>
			</div>
			</fieldset>
			<div id='submission'>
			<br/>
			<input class='btn' type="submit" value="Search" name = "submit"/><input class='btn' type="reset" value="Reset" onclick="reset_btn('small')"/>
			<span class='tooltip'><input class='btn' type="button" value="Example " onclick="fill_exemple_form('small')"/><span class='tooltiptext'>It uploads a precalculated example, you can choose wheter or not to use DAMA. All the other parameters are defined with default values.</span></span>
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
	</script>
<?php include("./includes/footer.php"); ?>
