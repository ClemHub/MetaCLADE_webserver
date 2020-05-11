function validate_one_seq(seq){
	var lines = seq.trim().split('\n');
	name = lines[0];
	if(name[0] == '>'){
		lines.splice(0, 1);}
	else{
		return name+" should start with a '>'.";}
	seq = lines.join('').trim();
	var chain = /^[ACDEFGHIKLMNPQRSTUVWY\s]+$/i.test(seq);
	if(chain==true){
		return true;}
	else{
		return "Your sequence "+ name +" contains elements that are not amino acids";}}

function validateFasta(fasta){
	var seq = fasta.split(/(?=\>)/);
	var valid = true
	for(s in seq){
		if(seq[s]){
			valid = validate_one_seq(seq[s])
			if(valid != true){
				break}}}
	return valid}

function validatePFAM(pfam_list){
	var pfam_exp = /^PF\d{5}$/;
	var i = 0;
	pfam_list = pfam_list.split(',');
	list_len = pfam_list.length;
	if(list_len > 10){
		return "There are more than 10 domains";}
	else{
		for(var pfam in pfam_list){
			i++
			if(!pfam_exp.test(pfam_list[pfam])){
				return "The "+i+"th domain format is not correct"}}}
	return true;}

function large_form_submission(){
	var seq =  document.large_annotation_form.sequences.value;
	var msg_seq = validateFasta(seq);
	if(seq==""){
		alert("Please enter a set of sequences or browse a fasta file.");
		return false}
	else if(seq != "" && msg_seq != true){
		alert(msg_seq);
		return false}
	else{
		return true}}

function small_form_submission(){
	var seq =  document.small_annotation_form.sequences.value.trim();
	var pfam_domains = document.small_annotation_form.pfam_domains.value.trim();
	var msg_pfam = validatePFAM(pfam_domains);
	var msg_seq = validateFasta(seq);
	var valid = true;
	if(seq=="" && pfam_domains==""){
		alert("\tPlease, enter:\n-A set of sequences or browse a fasta file\n-A list of 10 Pfam domains maximum.");
		valid = false;}
	else if (seq!="" && pfam_domains==""){
		if(msg_seq != true){
			alert("\tPlease:\n-Enter a list of Pfam domains and do not enter more than 10 domains\n-"+msg_seq);
			valid = false;}
		else{
			alert("\tPlease:\n-Enter a list of Pfam domains and do not enter more than 10 domains");
			valid = false;}}
	else if(seq=="" && pfam_domains!=""){
		if(msg_pfam != true){
			alert("\tPlease:\n-Enter a set of sequences or browse a fasta file\n-"+msg_pfam);
			valid = false;}
		else{
			alert("\tPlease, enter:\n-A set of sequences or browse a fasta file.");
			valid = false;}}
	else if(seq !="" && pfam_domains!=""){
		if(msg_seq != true && msg_pfam != true){
			alert("\tPlease, respect:\n-"+msg_pfam+"\n-"+msg_seq);
			valid = false;}
		else if(msg_seq != true && msg_pfam == true){
			alert("\tPlease, respect:\n-"+msg_seq);
			valid = false;}
		else if(msg_seq == true && msg_pfam != true){
			alert("\tPlease, respect:\n-"+msg_pfam);
			valid = false;}}
	return valid;}

function fill_exemple_form(form){
	if(form == 'small'){
		document.small_annotation_form.pfam_domains.value = "PF00875,PF03441,PF03167,PF12546";
		fetch('/MetaCLADE_webserver/data/example.fasta')
		.then(response => response.text())
		.then((data) => {document.small_annotation_form.sequences.value = data })
		document.small_annotation_form.action = 'results.php?form=small_example';
		document.getElementById("pfam_domains").disabled = true;}
	else{
		fetch('/MetaCLADE_webserver/data/example.fasta')
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
		document.small_annotation_form.action = 'cookies.php?form=small';
		document.getElementById("pfam_domains").disabled = false;}
	else{
		document.large_annotation_form.action = 'cookies.php?form=large';}
	document.getElementById("sequences").disabled = false;
	document.getElementById("evalue_nb").disabled = false;
	document.getElementById("evalue_range").disabled = false;
	document.getElementById("dama_evalue_nb").disabled = false;
	document.getElementById("dama_evalue_range").disabled = false;
	document.getElementById("email").disabled = false;
	document.getElementById("show_dama").style.display = "none";}

function nav_function(){
	var x = document.getElementById("myTopnav");
	if (x.className === "topnav") {
		x.className += " responsive";}
	else{
		x.className = "topnav";}}

function close_open_info(bouton) {
	var divContenu = bouton.nextSibling;
	if(divContenu.nodeType == 3) {divContenu = divContenu.nextSibling;}
	if(divContenu.style.display == 'block') {
		divContenu.style.display = 'none';}
	else {
		divContenu.style.display = 'block';}}

function ShowHideDama(){
	var yes_btn = document.getElementById("yes_btn");
	var evalue_dama = document.getElementById("show_dama");
	evalue_dama.style.display = yes_btn.checked ? "block" : "none";}

function ShowHideResults(){
	var yes_results = document.getElementById("yes_results");
	var results_container = document.getElementById("results_container");
	results_container.style.display = yes_results.checked ? "block" : "none";}

function ShowHideGoterms(){
	var yes_goterms = document.getElementById("yes_goterms");
	var goterms_container = document.getElementById("goterms_container");
	goterms_container.style.display = yes_goterms.checked ? "block" : "none";}
	
function showTooltip(evt, text){
	let tooltip = document.getElementById("tooltip");
	tooltip.innerHTML = text;
	tooltip.style.display = "block";
	tooltip.style.left = evt.pageX + 10 + 'px';
	tooltip.style.top = evt.pageY + 10 + 'px';}

function hideTooltip(){
	var tooltip = document.getElementById("tooltip");
	tooltip.style.display = "none";}

function sortTable(col_nb){
	var table, rows, switching, i, x, y, shouldSwitch;
	console.log(col_nb);
	table = document.getElementById("data_table");
	switching = true;
	while (switching) {
		switching = false;
		rows = table.rows;
		for (i = 1; i < (rows.length - 1); i++) {
			shouldSwitch = false;
			x = rows[i].getElementsByTagName("TD")[col_nb];
			y = rows[i + 1].getElementsByTagName("TD")[col_nb];
			if(col_nb == 4){
				if (Number(x.innerHTML) > Number(y.innerHTML)) {
					shouldSwitch = true;
					break;}}
			else if(col_nb == 5 || col_nb == 6){
				if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
					shouldSwitch = true;
					break;}}}
		if (shouldSwitch) {
			rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
			switching = true;}}}

function filter_table(){
	var input, filter, table, tr, td, i;
	uncheckAll('other_pfam');
	input = document.getElementById("domain_select");
	filter = input.value.toUpperCase();
	document.getElementById(filter).checked=true;
	table = document.getElementById("result");
	tr = table.getElementsByTagName("tr");
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[1];
		if (td){
			if(td.innerHTML.toUpperCase().indexOf(filter) > -1){
				tr[i].style.display = "";}
			else{
				tr[i].style.display = "none";}}}}

function uncheckAll(divid) {
	var checks = document.querySelectorAll('#' + divid + ' input[type="checkbox"]');
	for(var i =0; i< checks.length;i++){
		var check = checks[i];
		if(!check.disabled){
			check.checked = false;}}}

function reset_table(id){
	var table, tr, i;
	table = document.getElementById(id);
	tr = table.getElementsByTagName("tr");
	for (i = 0; i < tr.length; i++){
		tr[i].style.display = "";}}

function filter_all_domains(){
	var filter, checkboxes, table, tr, td, i, f;
	filter = [];
	checkboxes = document.querySelectorAll('input[type=checkbox]:checked')
	for (i = 0; i < checkboxes.length; i++){
		filter.push(checkboxes[i].value)}
	table = document.getElementById("result");
	tr = table.getElementsByTagName("tr");
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[1];
		if (td) {
			for (f= 0; f < filter.length; f++){
				if (td.innerHTML.toUpperCase().indexOf(filter[f]) > -1) {
					tr[i].style.display = "";}
				else{
					tr[i].style.display = "none";
					break}}}}}

function ShowTooltip(evt, mouseovertext) {
	var tooltip = document.getElementById('tooltip');
	tooltip.setAttribute("x", evt.clientX + 11);
	tooltip.setAttribute("y", evt.clientY + 27);
	tooltip.firstChild.data = mouseovertext;
	tooltip.setAttribute("visibility", "visible");}

function HideTooltip(evt) {
	var tooltip = document.getElementById('tooltip');
	tooltip.setAttribute("visibility", "hidden");}

function clan_selection(clan_list){
	var clan = document.clan_annotation_form.clan.value;
	var n = clan_list.includes(clan);
	fetch('/MetaCLADE_webserver/data/clans/'+clan+'.txt')
	.then(response => response.text())
	.then((data) => {document.clan_annotation_form.pfam_domains.value = data })
	document.getElementById("pfam_domains").disabled = true;
}