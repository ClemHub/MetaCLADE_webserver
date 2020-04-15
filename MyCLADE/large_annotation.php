<?php include("./includes/header.php"); ?>

	<section class = 'tools'>
	<h2> Annotation of a small sequences dataset against a complete library of probabilistic domain models<br><span id = 'subtitle'>Searching for domains</span></h2>
	
		<form name="large_annotation_form" method = "post" action="cookies.php?form=large"  enctype="multipart/form-data" onsubmit="return large_form_submission()">
			<fieldset class='form_fs'><legend><h4>Input data:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Be careful to the format, take a look at the example to see the format we are expecting.</span></span></h4></legend>
			<div class='seq_container'>
			<label for="sequences">Fasta format sequences:</label><br/>
			<textarea name="sequences" id = "sequences" rows='10' placeholder="Example:&#10;>SeqID_1&#10;sequence_1&#10;>SeqID_2&#10;sequence_2&#10;" autofocus></textarea><br/>
			<label for="fasta_file">Or browse a fasta file:</label>
			<input type="file" id="fasta_file" name="fasta_file""/>
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
					<input type="number" name="dama_evalue_nb" id='dama_evalue_nb' min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_range.value=this.value" />
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
			<input class='btn' type="submit" value="Search" name = "submit"/><input class='btn' type="reset" value="Reset" onclick="reset_btn('large')"/>
			<span class='tooltip'><input class='btn' type="button" value="Example " onclick="fill_exemple_form('large')"/><span class='tooltiptext'>You are going to load an example dataset precalculated. If you modify the parameters it will be considered for the results.</span></span>
			</div>			
		</form>	
		</section>

<?php include("./includes/footer.php"); ?>