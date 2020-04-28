<?php include("./includes/header.php"); ?>

	<section class = 'tools'>
		<h2> Annotation of a fixed number of domains for a large sequences dataset <br><span id = 'subtitle'>Searching for domains</span></h2>
		
		<form name="small_annotation_form" method = POST action="submit.php?form=small" enctype="multipart/form-data" onsubmit="return small_form_submission()">
			<fieldset class='form_fs'><legend><h4>Input data:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Be careful to the format, take a look at the example to see the format we are expecting.<br>And do not enter more than 10 domains.</span></span></h4></legend>
			<div id='pfam_container'>
			<label for="pfam_domains">PFAM accession number:</label><br/>
			<textarea name="pfam_domains" id = "pfam_domains" rows='10' placeholder="Example:&#10;PF04523,PF06584,PF06325" autofocus></textarea>
			
			</div>

			<div id='seq_container'>
			<label for="sequences">Fasta format sequences:</label><br/>
			<textarea name="sequences" id = "sequences" rows='10' placeholder="Example:&#10;>SeqID_1&#10;sequence_1&#10;>SeqID_2&#10;sequence_2&#10;"></textarea><br/>
			<label for="fasta_file">Or browse a fasta file:</label>
			<input type="file" id="fasta_file" name="fasta_file"/>
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>Parameters:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>The parameters are defined with default values but you can modify them.</span></span></h4></legend>
			<div class = 'metaclade_e-value'>
			<label for='evalue_range'>E-value threshold for MetaCLADE:<br/></label>
			<input type="range" id='evalue_range' name="evalue_range" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_nb.value=this.value" />
			<input type="number" id='evalue_nb' name="evalue_nb" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_range.value=this.value" />
			<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>The default value is set to 1e<sup>-3</sup>.<br/> Results with an e-value superior to or equal to one will we filtered automatically.</span></span>
			</div>
			
			<div class = 'dama_choice'>
			<br/>Do you want to use DAMA?<br/>

			<label for="yes_btn">Yes</label><input type="radio" name="dama" id="yes_btn" value = "true" onclick='ShowHideDiv()'/>

			<label for="no_btn">No</label><input type="radio" name="dama" id="no_btn" value = "false" onclick='ShowHideDiv()' checked/>
			</div>

			<div id = 'show_dama'>

				<label for='dama_evalue_range'>E-value threshold for DAMA:<br/></label>
					<input type="range" id='dama_evalue_range' name="dama_evalue_range" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_nb.value=this.value" />
					<input type="number" id ='dama_evalue_nb' name="dama_evalue_nb" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_range.value=this.value" />
				<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>The default value is set to 1e<sup>-10</sup>, we advice you to avoid an e-value higher than 1e<sup>-5</sup>.<br/> Results with an e-value superior to or equal to one will we filtered automatically.</span></span>
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>E-mail adress:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>It is optionnal and would be use to send you your job details.</span></span></h4></legend>

			<div id = 'email_address'>
			<input type="email" name="email" id="email" pattern=".+@.+\..+" placeholder = 'Optionnal'>
			</div>
			</fieldset>
			<div id='submission'>
			<br/>
			<input class='btn' type="submit" value="Search" name = "submit"/><input class='btn' type="reset" value="Reset" onclick="reset_btn('small')"/>
			<span class='tooltip'><input class='btn' type="button" value="Example " onclick="fill_exemple_form('small')"/><span class='tooltiptext'>You are going to load an example dataset precalculated. The parameters are already defined, you can only chose whether to use DAMA or not.</span></span>
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