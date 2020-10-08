<?php include("./includes/header.php"); ?>

	<section class = 'tools'>
		<h2> Annotation of a clan</h2>
		
		<form autocomplete="off" name="visualization_form" method = POST action="submit.php?form=visualisation" enctype="multipart/form-data" onsubmit="return visualization_form_submission()">
			<fieldset class='form_fs'><legend><h4>Input file:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>See Help for expected format.</span></span></h4></legend>
			<div id='seq_container'>
			<label for="fasta_file">Upload a Fasta file:</label>
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
