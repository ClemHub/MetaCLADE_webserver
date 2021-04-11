function validate_one_seq(seq, nb_seq){
	if (seq.trim() != ""){
		var lines = seq.trim().split('\n');
		var name = lines[0];
		if(name[0] == '>'){
			lines.splice(0, 1);}
		else{
			return "The sequence n°"+nb_seq+name+" should have an ID starting with a '>'.";}
		seq = lines.join('').trim();
		if(seq == ''){
			return "Your sequence "+ name +" misses its sequence.";}
		else{
			var chain = /^[ACDEFGHIKLMNPQRSTUVWY\s]+$/i.test(seq);
			if(chain==true){
				return true;}
			else{
				return "Your sequence "+ name +" contains characters that are not amino acids.";}}}}

function count_fastaseq(fasta){
	var seq = fasta.split(/(?=>|\n\n)/);
	return seq.length}

function validateFasta(fasta, max_seq){
	var seq = fasta.split(/(?=>|\n\n)/);
	return seq.length}

function validateFasta(fasta, max_seq){
	var reg=new RegExp("[\>{\n\s+\s}]+", "g");
	var seq = fasta.split(/(?=>|\n\n)/);
	var valid = true;
	var nb_seq = 0;
	for(s in seq){
		nb_seq++
		if(nb_seq<=max_seq){
			if(seq[s]){
				valid = validate_one_seq(seq[s], nb_seq)
				if(valid != true){
					break}}}
		else{
			valid = "There are more than "+max_seq+" sequences in your input data."
		}}
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
				return "The domain format is not correct: "+pfam_list[pfam]}}}
	return true;}

function validate_one_line(file_line, line_index){
	var msg = "";
	file_line = file_line.replace(/\s\s+/g, '\t');
	var line = file_line.trim().split('\t');
	if(line.length != 13){
		msg = msg+"Your file  misses some information. Please check the separator which must be tabulations\n";
		return msg;}
	else if(file_line == "SeqID	Seq start	Seq stop	Seq length	Domain ID	Model ID	Model start	Model stop	Model size	E-value	Biscore	Accuracy	Species of the template used for the model"){
		msg = msg+"Please, remove the header\n";
		return msg;}
	else{
		var int_indexes = [1, 2, 3, 6, 7, 8];
		for(i in int_indexes){
			if(!parseInt(line[int_indexes[i]])){
				msg = msg+"Be careful, on line "+line_index+", the column "+int_indexes[i]+" should be an integer\n"}}
		var float_indexes = [9, 10, 11];
		for(i in [9, 10, 11]){
			if(!parseFloat(line[float_indexes[i]])){
				msg = msg+"Be careful, on line "+line_index+", the column "+float_indexes[i]+" should be a float\n"}}
		if(msg == ""){
			return true;}
		else{
			return msg;}}}

function visualization_form_submission(){
	var file = document.visualization_form_file.sequences.value;
	var valid = true;
	var line_index = 0
	if(file==""){
		alert("Please, enter:\n-An annotation file.");
		valid = false;}
	else{
		var line = file.split('\n')
		line_index++
		for(l in line){
			if(line[l]){
				valid = validate_one_line(line[l], line_index);
				if(valid != true){
					alert(valid)
					valid = false;
					break;}}}}
	return valid;}

function large_form_submission(){
	var seq =  document.large_annotation_form.sequences.value;
	var msg_seq = validateFasta(seq, 3000);
	if(seq==""){
		alert("Please, enter:\n-A set of sequences or browse a fasta file.");
		return false}
	else if(seq != "" && msg_seq != true){
		alert("Please:\n-"+msg_seq);
		return false}
	else{
		return true}}

function clan_form_submission(clan_list){
	var seq =  document.clan_annotation_form.sequences.value;
	var clan = document.clan_annotation_form.clan.value;
	var n = clan_list.includes(clan);
	var msg_seq = validateFasta(seq, 3000);
	if(seq=="" && clan==""){
		alert("Please, enter:\n-A set of sequences or browse a fasta file\n-A Pfam clan.");
		return false}
	else if(seq=="" && n){
		alert("Please, enter:\n-A set of sequences or browse a fasta file.");
		return false}
	else if(seq=="" && !n){
		alert("Please, enter:\n-A valid Pfam clan\n-A set of sequences or browse a fasta file.");
		return false}
	else if(seq != "" && msg_seq != true && !n){
		alert("Please, enter:\n-A valid Pfam clan\n-"+msg_seq);
		return false}
	else if(seq != "" && msg_seq != true && n){
		alert("Please:\n-"+msg_seq);
		return false}
	else if(seq != "" && msg_seq == true && !n){
		alert("Please:\n-A valid Pfam clan");
		return false}
	else{
		return true}}

function small_form_submission(){
	var seq =  document.small_annotation_form.sequences.value.trim();
	var pfam_domains = document.small_annotation_form.pfam_domains.value.trim();
	var msg_pfam = validatePFAM(pfam_domains);
	var msg_seq = validateFasta(seq, 3000);
	var valid = true;
	if(seq=="" && pfam_domains==""){
		alert("Please, enter:\n-A set of sequences or browse a fasta file\n-A list of 10 Pfam domains maximum.");
		valid = false;}
	else if (seq!="" && pfam_domains==""){
		if(msg_seq != true){
			alert("Please:\n-Enter a list of Pfam domains and do not enter more than 10 domains\n-"+msg_seq);
			valid = false;}
		else{
			alert("Please:\n-Enter a list of Pfam domains and do not enter more than 10 domains");
			valid = false;}}
	else if(seq=="" && pfam_domains!=""){
		if(msg_pfam != true){
			alert("Please:\n-Enter a set of sequences or browse a fasta file\n-"+msg_pfam);
			valid = false;}
		else{
			alert("Please, enter:\n-A set of sequences or browse a fasta file.");
			valid = false;}}
	else if(seq !="" && pfam_domains!=""){
		if(msg_seq != true && msg_pfam != true){
			alert("Please, respect:\n-"+msg_pfam+"\n-"+msg_seq);
			valid = false;}
		else if(msg_seq != true && msg_pfam == true){
			alert("Please, respect:\n-"+msg_seq);
			valid = false;}
		else if(msg_seq == true && msg_pfam != true){
			alert("Please, respect:\n-"+msg_pfam);
			valid = false;}}
	return valid;}

function fill_exemple_form(form){
	if(form == 'small'){
		document.small_annotation_form.pfam_domains.value = "PF13424,PF14689,PF00128,PF02806,PF00875,PF03441";
		fetch('/myclade/data/example.fasta')
		.then(response => response.text())
		.then((data) => {document.small_annotation_form.sequences.value = data })
		document.small_annotation_form.action = 'submit.php?form=small_example';
		document.getElementById("pfam_domains").disabled = true;}
	else if(form == 'large'){
		fetch('/myclade/data/example.fasta')
		.then(response => response.text())
		.then((data) => {document.large_annotation_form.sequences.value = data })
		document.large_annotation_form.action = 'submit.php?form=large_example'}
	else if(form == 'clan'){
		fetch('/myclade/data/example.fasta')
		.then(response => response.text())
		.then((data) => {document.clan_annotation_form.sequences.value = data })
		document.clan_annotation_form.clan.value = 'CL0039 - HUP';
		document.clan_annotation_form.action = 'submit.php?form=clan_example'
		document.getElementById("clan").disabled = true;}
	document.getElementById("job_name").disabled = true;
	document.getElementById("reduced_btn").checked = false;
	document.getElementById("reduced_btn").disabled = true;
	document.getElementById("complete_btn").checked = true;
	document.getElementById("complete_btn").disabled = true;
	document.getElementById("dama_evalue_nb").value = 1e-10;
	document.getElementById("dama_evalue_range").value = 1e-10;
	document.getElementById("dama_evalue_nb").disabled = true;
	document.getElementById("dama_evalue_range").disabled = true;
	document.getElementById("evalue_nb").value = 1e-3;
	document.getElementById("evalue_range").value = 1e-3;
	document.getElementById("evalue_nb").disabled = true;
	document.getElementById("email").disabled = true;
	document.getElementById("evalue_range").disabled = true;
	document.getElementById("sequences").disabled = true;
	document.getElementById("overlappingAA_range").disabled = true;
	document.getElementById("overlappingAA_range").value = 30;
	document.getElementById("overlappingAA_nb").value = 30;
	document.getElementById("overlappingAA_nb").disabled = true;
	document.getElementById("overlappingMaxDomain_range").disabled = true;
	document.getElementById("overlappingMaxDomain_range").value = 50;
	document.getElementById("overlappingMaxDomain_nb").disabled = true;
	document.getElementById("overlappingMaxDomain_nb").value = 50;
}


function reset_btn(form){
	if(form == 'small'){
		document.small_annotation_form.action = 'submit.php?form=small';
		document.getElementById("pfam_domains").disabled = false;}
	else if(form == 'large'){
		document.large_annotation_form.action = 'submit.php?form=large';}
	else if(form == 'clan'){
		document.getElementById("clan").disabled = false;
		document.clan_annotation_form.action = 'submit.php?form=clan';}
	document.getElementById("job_name").disabled = false;
	document.getElementById("reduced_btn").disabled = false;
	document.getElementById("complete_btn").disabled = false;
	document.getElementById("sequences").disabled = false;
	document.getElementById("evalue_nb").disabled = false;
	document.getElementById("evalue_range").disabled = false;
	document.getElementById("dama_evalue_nb").disabled = false;
	document.getElementById("dama_evalue_range").disabled = false;
	document.getElementById("email").disabled = false;
	document.getElementById("overlappingAA_range").disabled = false;
	document.getElementById("overlappingAA_nb").disabled = false;
	document.getElementById("overlappingMaxDomain_range").disabled = false;
	document.getElementById("overlappingMaxDomain_nb").disabled = false;
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

function ShowHideLogo(){
	var yes_logo = document.getElementById("yes_logo");
	var goterms_container = document.getElementById("logo_container");
	goterms_container.style.display = yes_logo.checked ? "block" : "none";}

function ShowHideSeqCount(){
	var yes_seqcount = document.getElementById("yes_seqcount");
	var seqcount_container = document.getElementById("seqcount_container");
	seqcount_container.style.display = yes_seqcount.checked ? "block" : "none";}

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
			if(col_nb == 5){
				if (Number(x.innerHTML) > Number(y.innerHTML)) {
					shouldSwitch = true;
					break;}}
			else if(col_nb == 6 || col_nb == 7){
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
	if(n)
		{fetch('/MetaCLADE_webserver/data/clans/'+clan+'.txt')
		.then(response => response.text())
		.then((data) => {document.clan_annotation_form.pfam_domains.value = data })
		document.getElementById("pfam_domains").disabled = true;}
	else{
		alert('This clan is not available.')}}
