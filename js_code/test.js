function form_submission() {
	var dama = document.querySelectorAll('input[type=radio]:checked');

	var sequences = document.getElementById('sequences').value; 

	var pfam_domains = document.getElementById('pfam_domains').value; 

	if (sequences && pfam_domains){
		alert("Please complete the field")
	}}
