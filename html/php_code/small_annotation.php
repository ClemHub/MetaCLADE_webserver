<?php include("./includes/header.php"); ?>

	<section class = 'tools'>
		<h2> Annotation of a fixed number of domains for a large sequences dataset </h2>
		<h3>Search for domains:</h3>
		
		<form name="small_annotation_form" method = POST action="results_small_annotation.php" enctype="multipart/form-data" onsubmit="return small_form_submission()">
			<fieldset><legend><h4>Input data:</h4></legend>
			<div id='pfam_container'>
			<label for="pfam_domains">PFAM accession number:</label>
			<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Be careful to the format, please separate the domain with a comma ',' like in the exemple.</span></span><br/>
			<textarea name="pfam_domains" id = "pfam_domains" rows='10' cols='50' placeholder="Example:&#10;PF04523,PF06584,PF06325" autofocus>
			
			</textarea>
			
			</div>

			<div id='seq_container'>
			<label for="sequences">Fasta format sequences:</label><br/>
			<textarea name="sequences" id = "sequences" rows='10' cols='50' placeholder="Example:&#10;>SeqID_1&#10;sequence_1&#10;>SeqID_2&#10;sequence_2&#10;"></textarea><br/>
			<label for="fasta_file">Or browse a fasta file:</label>
			<input type="file" id="fasta_file" name="fasta_file"/>
			</div>
			</fieldset>
			<fieldset><legend><h4>Parameters:</h4></legend>
			<div class = 'metaclade_e-value'>
			<label for='evalue_range'>E-value threshold for MetaCLADE:</label>
			<input type="range" id='evalue_range' name="evalue_range" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_nb.value=this.value" />
			<input type="number" id='evalue_nb' name="evalue_nb" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_range.value=this.value" />
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
					<input type="number" id ='dama_evalue_nb' name="dama_evalue_nb" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_range.value=this.value" />
				<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>The default value is set to 1e-10, we advice you to avoid an e-value higher than 1e-5.<br/> Results with an e-value superior to or equal to one will we filtered automatically.</span></span>
			</div>
			</fieldset>
			<div id='submission'>
			<br/>
			<input type="submit" value="Search" name = "submit"/><input type="reset" value="Reset" onclick="reset_btn('small')"/>
			<span class='tooltip'><input type="button" value="Example " onclick="fill_exemple_form('small')"/><span class='tooltiptext'>You are going to load an example dataset precalculated. The parameters are already defined, you can only chose whether to use DAMA or not.</span></span>


			</div>
		</form>
	</section>
<?php include("./includes/footer.php"); ?>