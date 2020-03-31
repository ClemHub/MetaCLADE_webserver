function validate_one_seq(seq) {
	if (!seq) {return false;}
	seq = seq.trim();
	var lines = seq.split('\n');

	if (seq[0] == '>') {lines.splice(0, 1);}
	else{return false;}
	seq = lines.join('').trim();

	if (!seq) {return false;}

	return /^[ACDEFGHIKLMNPQRSTUVWY\s]+$/i.test(seq);}

function validateFasta(fasta) {
	var seq = fasta.split(/(?=\>)/);
	for(s in seq){
		if(seq[s]){
			valid = validate_one_seq(seq[s])
			if(!valid)
				{break}}}
	return valid}

function validatePFAM(pfam_list){
	var pfam_exp = /^PF\d{5}$/;
	pfam_list = pfam_list.split(',');
	list_len = pfam_list.length;
	if(list_len > 10){
		valid = false;}
	else{
		valid = true;
		for(var pfam in pfam_list){
			if(!pfam_exp.test(pfam_list[pfam])){
				valid = false;
				break;}}}
	return valid;}

function large_form_submission(){
	var seq =  document.large_annotation_form.sequences.value;
	var valid = true;
	if(seq==""){
		alert("Please enter a set of sequences or browse a fasta file.");
		valid = false}
	else if(seq != "" && !validateFasta(seq)){
		alert("Please respect the Fasta format.")
		valid = false}
	return valid;}

function small_form_submission() {
	var seq =  document.small_annotation_form.sequences.value.trim();
	var pfam_domains = document.small_annotation_form.pfam_domains.value.trim();
	var valid = true;
	if(seq!="" && pfam_domains==""){
		alert("Please, enter a list of PFAM domains and do not enter more than 10 domains.")
		valid = false}
	else if(seq=="" && pfam_domains!=""){
		if(!validatePFAM(pfam_domains)){
			alert("\tPlease:\n-Enter a set of sequences or browse a fasta file\n-Respect the PFAM domain format and do not enter more than 10 domains.")
			valid = false}
		else{
			alert("\tPlease, enter:\n-A set of sequences or browse a fasta file.")
			valid = false}}
	else if(seq=="" && pfam_domains==""){
		alert("\tPlease, enter:\n-A set of sequences manually or through a fasta file\n-A list of 10 PFAM domains maximum.")
		valid = false}
	else if(seq !="" && pfam_domains!=""){
		if(seq != "" && !validateFasta(seq) && !validatePFAM(pfam_domains)){
			alert("\tPlease, respect:\n-The PFAM domain format and do not enter more than 10 domains.\n-The fasta format")
			valid = false}
		else if(seq != "" && !validateFasta(seq) && validatePFAM(pfam_domains)){
			alert("\tPlease, respect:\n-The Fasta format")
			valid = false}
		else if(seq!="" && validateFasta(seq) && !validatePFAM(pfam_domains)){
			alert("\tPlease, respect:\n-The PFAM domain format and do not enter more than 10 domains.")
			valid = false}
		}
	return valid}

function fill_exemple_form(form){
	if(form == 'small'){
		document.small_annotation_form.pfam_domains.value = "PF00875,PF03441,PF03167,PF12546"
		//fetch('http://localhost:8888/MetaCLADE_webserver/MyCLADE/fasta_file/example.fasta')
		fetch('http://localhost/MetaCLADE_webserver/MyCLADE/fasta_file/example.fasta')
		.then(response => response.text())
		.then((data) => {document.small_annotation_form.sequences.value = data })
		document.small_annotation_form.action = 'results.php?form=small_example';
		document.getElementById("pfam_domains").disabled = true;}
	else{
		//fetch('http://localhost:8888/MetaCLADE_webserver/MyCLADE/fasta_file/example.fasta')
		fetch('http://localhost/MetaCLADE_webserver/MyCLADE/fasta_file/example.fasta')
		.then(response => response.text())
		.then((data) => {document.large_annotation_form.sequences.value = data })
		document.large_annotation_form.action = 'results.php?form=large_example'}
	document.getElementById("dama_evalue_nb").value = 1e-10;
	document.getElementById("dama_evalue_range").value = 1e-10;
	document.getElementById("dama_evalue_nb").disabled = true;
	document.getElementById("dama_evalue_range").disabled = true;
	document.getElementById("evalue_nb").value = 1e-3;
	document.getElementById("evalue_range").value = 1e-3;
	document.getElementById("evalue_nb").disabled = true;
	document.getElementById("email").disabled = true;
	document.getElementById("evalue_range").disabled = true;
	document.getElementById("sequences").disabled = true;}

function reset_btn(form){
	if(form == 'small'){
		document.small_annotation_form.action = 'results.php?form=small';
		document.getElementById("pfam_domains").disabled = false;
	}
	else{
		document.large_annotation_form.action = 'results.php?form=large';
	}
	document.getElementById("sequences").disabled = false;
	
	document.getElementById("evalue_nb").disabled = false;
	document.getElementById("evalue_range").disabled = false;
	document.getElementById("dama_evalue_nb").disabled = false;
	document.getElementById("dama_evalue_range").disabled = false;
	document.getElementById("email").disabled = false;
	document.getElementById("show_dama").style.display = "none";}

function nav_function() {
	var x = document.getElementById("myTopnav");
	if (x.className === "topnav") {
		x.className += " responsive";}
	else{
		x.className = "topnav";}}

function close_open_info(bouton) {
	var divContenu = bouton.nextSibling;
	if(divContenu.nodeType == 3) {divContenu = divContenu.nextSibling;}
	if(divContenu.style.display == 'block') {
		divContenu.style.display = 'none';
	}
	else {
		divContenu.style.display = 'block';}}

function ShowHideDiv() {
	var yes_btn = document.getElementById("yes_btn");
	var evalue_dama = document.getElementById("show_dama");
	evalue_dama.style.display = yes_btn.checked ? "block" : "none";}

function ran_col() {
	var color = '#';
	var letters = ['000000','FF0000','00FF00','0000FF','FFFF00','00FFFF','FF00FF','C0C0C0'];
	color += letters[Math.floor(Math.random() * letters.length)];
	document.getElementById('posts').style.background = color;}

function showTooltip(evt, text) {
	let tooltip = document.getElementById("tooltip");
	tooltip.innerHTML = text;
	tooltip.style.display = "block";
	tooltip.style.left = evt.pageX + 10 + 'px';
	tooltip.style.top = evt.pageY + 10 + 'px';  }

function hideTooltip() {
	var tooltip = document.getElementById("tooltip");
	tooltip.style.display = "none";}

var fileInput = document.querySelector('#fasta_file');
fileInput.addEventListener('change', function() {
    var reader = new FileReader();
    reader.addEventListener('load', function() {
		var txt = reader.result
		document.getElementById('sequences').value = txt;
	});
    reader.readAsText(fileInput.files[0]);
});

function sortTable(col_nb) {
	var table, rows, switching, i, x, y, shouldSwitch;
	table = document.getElementById("data_table");
	switching = true;
	/*Make a loop that will continue until
	no switching has been done:*/
	while (switching) {
	  //start by saying: no switching is done:
	  switching = false;
	  rows = table.rows;
	  /*Loop through all table rows (except the
	  first, which contains table headers):*/
	  for (i = 1; i < (rows.length - 1); i++) {
		//start by saying there should be no switching:
		shouldSwitch = false;
		/*Get the two elements you want to compare,
		one from current row and one from the next:*/
		x = rows[i].getElementsByTagName("TD")[col_nb];
		y = rows[i + 1].getElementsByTagName("TD")[col_nb];
		//check if the two rows should switch place:
		//alert(x.innerHTML)
		//alert(y.innerHTML)
		if (x.innerHTML > y.innerHTML) {
		  //if so, mark as a switch and break the loop:
		  //alert('ok')
		  shouldSwitch = true;
		  break;
		}
	  }
	  if (shouldSwitch) {
		/*If a switch has been marked, make the switch
		and mark that a switch has been done:*/
		rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
		switching = true;
	  }
	}
  }