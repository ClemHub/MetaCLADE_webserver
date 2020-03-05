function validate_one_seq(seq) {
	if (!seq) {return false;}
	seq = seq.trim();
	var lines = seq.split('\n');

	if (seq[0] == '>') {lines.splice(0, 1);}
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
	console.log(pfam_list)
	pfam_list = pfam_list.split(',');
	console.log(pfam_list)
	var pfam_exp = /^PF\d{5}$/;
	valid = true;
	for(pfam in pfam_list){
		console.log(pfam_list[pfam])
		if(!pfam_exp.test(pfam_list[pfam])){
			valid = false;
			break;}
	return valid;}}

function large_form_submission(){
	var seq =  document.large_annotation_form.sequences.value;
	var fasta_file = document.large_annotation_form.querySelector("input[name=fasta_file]");
	var valid = true;
	if (seq!="" & fasta_file.files.length !== 0){
		var ans = confirm('You entered sequences manually and browsed a file.\nIf you press OK, the manually entered sequences will be treated.')
		if(ans){valid = true}
		else{valid = false}}
	if(seq=="" && fasta_file.files.length === 0){
		alert("Please enter a set of sequences or browse a fasta file.");
		valid = false}
	else if(seq != "" && !validateFasta(seq)){
		alert("Please respect the Fasta format")
		valid = false}
	return valid;}

function small_form_submission() {
	var seq =  document.small_annotation_form.sequences.value;
	var pfam_domains = document.small_annotation_form.pfam_domains.value;
	var fasta_file = document.small_annotation_form.querySelector("input[name=fasta_file]");
	
	var valid = true;
	if (seq!="" & fasta_file.files.length !== 0){
		var ans = confirm('You entered sequences manually and browsed a file.\nIf you press OK, the manually entered sequences will be treated.')
		if(ans){valid = true}
		else{valid = false}}
	if((seq!="" || fasta_file.files.length !== 0) && pfam_domains==""){
		alert("Please, enter a list of PFAM domains.")
		valid = false}
	else if((seq=="" && fasta_file.files.length === 0) && pfam_domains!=""){
		if(!validatePFAM(pfam_domains)){
			alert("\tPlease:\n-Enter a set of sequences or browse a fasta file\n-Respect the PFAM domain format.")
			valid = false}
		else{
			alert("\tPlease, enter:\n-A set of sequences or browse a fasta file.")
			valid = false}}
	else if((seq=="" && fasta_file.files.length === 0) && pfam_domains==""){
		alert("\tPlease, enter:\n-A set of sequences manually or through a fasta file\n-A list of PFAM domains.")
		valid = false}
	else if((seq !="" || fasta_file.files.length !== 0) && pfam_domains!=""){
		if(seq != "" && !validateFasta(seq) && !validatePFAM(pfam_domains)){
			alert("\tPlease, respect:\n-The PFAM domain format\n-The fasta format")
			valid = false}
		else if(seq != "" && !validateFasta(seq) && validatePFAM(pfam_domains)){
			alert("\tPlease, respect:\n-The Fasta format")
			valid = false}
		else if(((seq!="" && validateFasta(seq)) || seq =="") && !validatePFAM(pfam_domains)){
			alert("\tPlease, respect:\n-The PFAM domain format")
			valid = false}
		}
	return valid}

function fill_exemple_form(form){
	if(form == 'small'){
		document.small_annotation_form.pfam_domains.value = "PF00875,PF03441,PF03167,PF12546"
		fetch('http://localhost/php_code/exemple.fasta')
		.then(response => response.text())
		.then((data) => {document.small_annotation_form.sequences.value = data })
		document.small_annotation_form.action = 'example.php'}
	else{
		fetch('http://localhost/php_code/exemple.fasta')
		.then(response => response.text())
		.then((data) => {document.large_annotation_form.sequences.value = data })
		document.large_annotation_form.action = 'example.php'}}
	

function nav_function() {
	var x = document.getElementById("myTopnav");
	if (x.className === "topnav") {
	  x.className += " responsive";
	} else {
	  x.className = "topnav";
	}
}

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
	evalue_dama.style.display = yes_btn.checked ? "block" : "none";
}

function ran_col() { //function name
	var color = '#'; // hexadecimal starting symbol
	var letters = ['000000','FF0000','00FF00','0000FF','FFFF00','00FFFF','FF00FF','C0C0C0']; //Set your colors here
	color += letters[Math.floor(Math.random() * letters.length)];
	document.getElementById('posts').style.background = color; // Setting the random color on your div element.
}