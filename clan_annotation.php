<?php include("./includes/header.php"); ?>

	<section class = 'tools'>
		<h2> Annotation of a clan in large and small datasets of sequences <br><span id = 'subtitle'>Searching for domains</span></h2>
		
		<form autocomplete="off" name="small_annotation_form" method = POST action="submit.php?form=small" enctype="multipart/form-data" onsubmit="return small_form_submission()">
			<fieldset class='form_fs'><legend><h4>Input data:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>See examples in the Help section for the expected format.<br>And do not enter more than 10 domains.</span></span></h4></legend>
			<div class="autocomplete" id='pfam_container'>
			<label for="clan">Clan: <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Enter the name of the clan.</span></span></label><br/>
			<input id="clan" type="text" name="clan" placeholder="CL00001">
			</div>

			<div id='seq_container'>
			<label for="sequences">Sequences in Fasta format:</label><br/>
			<textarea name="sequences" id = "sequences" rows='10' placeholder="Example:&#10;>SeqID_1&#10;sequence_1&#10;>SeqID_2&#10;sequence_2&#10;"></textarea><br/>
			<label for="fasta_file">Upload a Fasta file:</label>
			<input type="file" id="fasta_file" name="fasta_file"/>
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>Parameters:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>The parameters are defined with default values but you can modify them.</span></span></h4></legend>
			<div class = 'metaclade_e-value'>
			<label for='evalue_range'>E-value threshold for MetaCLADE:<br/></label>
			<input type="range" id='evalue_range' name="evalue_range" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_nb.value=this.value" />
			<input type="number" id='evalue_nb' name="evalue_nb" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_range.value=this.value" />
			<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>E-value set to 1e<sup>-3</sup> by default.<br/> Results with E-value greater than 1 are automatically filtered out.</span></span>
			</div>
			
			<div class = 'dama_choice'>
			<br/>Do you want to use DAMA?<br/>

			<label for="yes_btn">Yes</label><input type="radio" name="dama" id="yes_btn" value = "true" onclick='ShowHideDama()'/>

			<label for="no_btn">No</label><input type="radio" name="dama" id="no_btn" value = "false" onclick='ShowHideDama()' checked/>
			</div>

			<div id = 'show_dama'>

				<label for='dama_evalue_range'>E-value threshold for DAMA:<br/></label>
					<input type="range" id='dama_evalue_range' name="dama_evalue_range" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_nb.value=this.value" />
					<input type="number" id ='dama_evalue_nb' name="dama_evalue_nb" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_range.value=this.value" />
				<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>E-value set to 1e<sup>-10</sup> by default. We advice you to avoid an E-value higher than 1e<sup>-5</sup>.<br/> Results with E-value greater than or equal to 1 are automatically filtered out.</span></span>
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>E-mail adress:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Optional and used to send you job details.</span></span></h4></legend>

			<div id = 'email_address'>
			<input type="email" name="email" id="email" pattern=".+@.+\..+" placeholder = 'Optionnal'>
			</div>
			</fieldset>
			<div id='submission'>
			<br/>
			<input class='btn' type="submit" value="Search" name = "submit"/><input class='btn' type="reset" value="Reset" onclick="reset_btn('small')"/>
			<span class='tooltip'><input class='btn' type="button" value="Example " onclick="fill_exemple_form('small')"/><span class='tooltiptext'>It uploads a precalculated example. Results will be filtered with your parameter choice.</span></span>
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