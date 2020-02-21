function form_submission() {
    var dama = document.querySelectorAll('input[type=radio]:checked');
	console.log(dama);
	var sequences = document.getElementById('sequences').value; 
	alert(sequences)
	var pfam_domains = document.getElementById('pfam_domains').value; 
	alert(pfam_domains)
}