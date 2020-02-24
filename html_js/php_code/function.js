function large_form_submission() {
	var seq =  document.large_annotation_form.sequences.value
	var dama = document.querySelector('input[name="dama"]:checked').value
	var fasta_file = document.large_annotation_form.querySelector("input[name=fasta_file]")
	console.log(fasta_file)
	if(seq=="" && fasta_file.files.length === 0){
		alert("Please enter a set of sequences or browse a fasta file.")
		document.large_annotation_form.action='large_annotation.html'
	}
	}

function small_form_submission() {
	var seq =  document.small_annotation_form.sequences.value
	var pfam_domains = document.small_annotation_form.pfam_domains.value
	var dama = document.querySelector('input[name="dama"]:checked').value
	var fasta_file = document.small_annotation_form.querySelector("input[name=fasta_file]")
	avail_data = false
	if(seq!="" || fasta_file.files.length !== 0){
		if(seq!=""){
			alert(seq)
		}
		avail_data = true
	}
	if(avail_data == false && pfam_domains==""){
		alert("Please, enter a set of sequences manually or through a fasta file and a list of PFAM domains.")
		document.small_annotation_form.action='small_annotation.html'
	}	
	else if(avail_data == true && pfam_domains==""){
		alert("Please, enter a list of PFAM domains.")
		document.small_annotation_form.action='small_annotation.html'
	}
	else if(avail_data == false && pfam_domains!=""){
		alert("Please, enter a set of sequences or browse a fasta file.")
		document.small_annotation_form.action='small_annotation.html'
	}
	}