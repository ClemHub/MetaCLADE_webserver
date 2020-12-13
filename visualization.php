<?php include("./includes/header.php"); ?>

	<section class = 'tools'>
		<h2>Visualization of an annotation file</h2>
		
		<form autocomplete="off" name="visualization_form" method = POST action="submit.php?form=visualization" enctype="multipart/form-data" onsubmit="return visualization_form_submission()">
			<div>
			To visualize annotations made by this webserver, the format must be unchanged (don't put the header details of your result).
			</div>
			<fieldset class='form_fs'><legend><h4> Results ID:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>The randomly generated ID during submission.</span></span></h4></legend>
			<div id = 'job_name_div'>
			<input type="text" id="job_ID" name="job_ID" placeholder = 'Optional'>
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>Or input file:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>See Help for expected format.</span></span></h4></legend>
			<div id='seq_container'>
			<label for="fasta_file">Upload an annotation file:</label>
			<textarea name="sequences" id = "sequences" rows='10' placeholder="Example: tr|A0A072NB93|A0A072NB93_9DEIO  12      141     766     PF00875 A6FVP5_9RHOB_1-137      1       129     130     1.4e-35 111.3   0.97    Roseobacter sp. AzwK-3b"></textarea><br/>
			<input type="file" id="fasta_file" name="fasta_file"/>
			</div>
			</fieldset>
			<div id='submission'>
			<br/>
			<input class='btn' type="submit" value="Search" name = "submit"/><input class='btn' type="reset" value="Reset" onclick="reset_btn('visualisation')"/>
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
