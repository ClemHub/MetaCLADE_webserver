<?php include("./includes/header.php"); ?>

	<section class = 'tools'>
	<h2> Annotation of a small sequences dataset against a complete library of probabilistic domain models </h2>
	<h3>Search for domains:</h3>

		<form name="large_annotation_form" method = "post" action="results_large_annotation.php"  enctype="multipart/form-data" onsubmit="return large_form_submission()">
			<fieldset><legend><h4>Input data:</h4></legend>
			<div class='seq_container'>
			<label for="sequences">Fasta format sequences:</label><br/>
			<textarea name="sequences" id = "sequences" rows='10' cols='50' placeholder="Example:&#10;>SeqID_1&#10;sequence_1&#10;>SeqID_2&#10;sequence_2&#10;" autofocus></textarea><br/>
			<label for="fasta_file">Or browse a fasta file:</label>
			<input type="file" id="fasta_file" name="fasta_file"/>
			</div>
			</fieldset>

			<fieldset><legend><h4>Parameters:</h4></legend>
			<div class = 'metaclade_e-value'>
			<label for='evalue_range'>E-value threshold for MetaCLADE:</label>
			<input type="range" id='evalue_range' name="evalue_range" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_nb.value=this.value" />
			<input type="number" name="evalue_nb" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_range.value=this.value" />
			<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>The default value is set to 1e-3.<br/> Results with an e-value superior to or equal to one will we filtered automatically.</span></span>
			</div>
			
			<div class = 'dama_choice'>
			<br/>Do you want to use DAMA?

			<label for="yes_btn">Yes</label><input type="radio" name="dama" id="yes_btn" value = "true" onclick='ShowHideDiv()'/>

			<label for="no_btn">No</label><input type="radio" name="dama" id="no_btn" value = "false" onclick='ShowHideDiv()' checked/>
			</div>

			<div id = 'show_dama'>

				<label for='dama_evalue_range'>E-value threshold for DAMA:</label>
					<input type="range" id='dama_evalue_range' name="dama_evalue_range" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_nb.value=this.value" />
					<input type="number" name="dama_evalue_nb" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_range.value=this.value" />
				<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>The default value is set to 1e-10, we advice you to avoid an e-value higher than 1e-5.<br/> Results with an e-value superior to or equal to one will we filtered automatically.</span></span>
			</div>
			</fieldset>
			<div id='submission'>
			<br/>
			<input type="submit" value="Search" name = "submit"/><input type="reset" value="Reset" /><input type="button" value="Exemple" onclick="fill_exemple_form('large')"/>
			</div>			
		</form>	
		</section>

<?php include("./includes/footer.php"); ?>