function large_form_submission() {
	var seq =  document.large_annotation_form.sequences.value
	var dama = document.querySelector('input[name="dama"]:checked').value
	var fasta_file
	if(seq==""){
		alert("Please enter a set of sequences.")
		document.large_annotation_form.action='large_annotation.html'
	}
	}

function small_form_submission() {
	var seq =  document.small_annotation_form.sequences.value
	var pfam_domains = document.small_annotation_form.pfam_domains.value
	var dama = document.querySelector('input[name="dama"]:checked').value
	var fasta_file
	if(seq=="" && pfam_domains==""){
		alert("Please enter a set of sequences and a list of PFAM domains.")
		document.small_annotation_form.action='small_annotation.html'
	}	
	else if(seq!="" && pfam_domains==""){
		alert("Please enter a list of PFAM domains.")
		document.small_annotation_form.action='small_annotation.html'
	}
	else if(seq=="" && pfam_domains!=""){
		alert("Please enter a set of sequences.")
		document.small_annotation_form.action='small_annotation.html'
	}
	}