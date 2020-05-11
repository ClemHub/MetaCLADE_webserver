<?php include("./includes/header.php"); ?>

	<section class = 'tools'>
		<h2> Annotation of a clan in large and small datasets of sequences <br><span id = 'subtitle'>Searching for domains</span></h2>
		
		<form autocomplete="off" name="clan_annotation_form" method = POST action="submit.php?form=clan" enctype="multipart/form-data" onsubmit="return clan_form_submission()">
			<fieldset class='form_fs'><legend><h4>Input data:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>See examples in the Help section for the expected format.<br>And do not enter more than 10 domains.</span></span></h4></legend>
			<div class="autocomplete" id='clan_container'>
			<label for="clan">Clan: <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Enter the name of the clan.</span></span></label><br/>
			<input id="clan" type="text" name="clan" placeholder="CL00001">
			<input type='button' onclick='clan_selection()' value='Search'/>
			</div>
			<div id='pfam_container'>
			<label for="pfam_domains">PFAM accession number: <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Do not enter more than 10 domains.</span></span></label><br/>
			<textarea name="pfam_domains" id = "pfam_domains" rows='10' placeholder="Example:&#10;PF04523,PF06584,PF06325" autofocus></textarea>
			</div>
			<div id='seq_container'>
			<label for="sequences">Sequences in Fasta format:</label><br/>
			<textarea name="sequences" id = "sequences" rows='10' placeholder="Example:&#10;>SeqID_1&#10;sequence_1&#10;>SeqID_2&#10;sequence_2&#10;"></textarea><br/>
			<label for="fasta_file">Upload a Fasta file:</label>
			<input type="file" id="fasta_file" name="fasta_file"/>
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>Parameters:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>The parameters are defined with default values but you can modify them.</span></span></h4></legend>
			<div class = 'metaclade_e-value'>
			<label for='evalue_range'>E-value threshold for MetaCLADE:<br/></label>
			<input type="range" id='evalue_range' name="evalue_range" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_nb.value=this.value" />
			<input type="number" id='evalue_nb' name="evalue_nb" min='0' max="1" value="1e-3" step='1e-10' oninput="this.form.evalue_range.value=this.value" />
			<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>E-value set to 1e<sup>-3</sup> by default.<br/> Results with E-value greater than 1 are automatically filtered out.</span></span>
			</div>
			
			<div class = 'dama_choice'>
			<br/>Do you want to use DAMA?<br/>

			<label for="yes_btn">Yes</label><input type="radio" name="dama" id="yes_btn" value = "true" onclick='ShowHideDama()'/>

			<label for="no_btn">No</label><input type="radio" name="dama" id="no_btn" value = "false" onclick='ShowHideDama()' checked/>
			</div>

			<div id = 'show_dama'>

				<label for='dama_evalue_range'>E-value threshold for DAMA:<br/></label>
					<input type="range" id='dama_evalue_range' name="dama_evalue_range" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_nb.value=this.value" />
					<input type="number" id ='dama_evalue_nb' name="dama_evalue_nb" min='0' max="1" value="1e-10" step='1e-10' oninput="this.form.dama_evalue_range.value=this.value" />
				<span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>E-value set to 1e<sup>-10</sup> by default. We advice you to avoid an E-value higher than 1e<sup>-5</sup>.<br/> Results with E-value greater than or equal to 1 are automatically filtered out.</span></span>
			</div>
			</fieldset>
			<fieldset class='form_fs'><legend><h4>E-mail adress:  <span class='tooltip'><i class="far fa-question-circle"></i><span class='tooltiptext'>Optional and used to send you job details.</span></span></h4></legend>

			<div id = 'email_address'>
			<input type="email" name="email" id="email" pattern=".+@.+\..+" placeholder = 'Optionnal'>
			</div>
			</fieldset>
			<div id='submission'>
			<br/>
			<input class='btn' type="submit" value="Search" name = "submit"/><input class='btn' type="reset" value="Reset" onclick="reset_btn('small')"/>
			<span class='tooltip'><input class='btn' type="button" value="Example " onclick="fill_exemple_form('small')"/><span class='tooltiptext'>It uploads a precalculated example. Results will be filtered with your parameter choice.</span></span>
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


	
function autocomplete(inp, arr) {
	/*the autocomplete function takes two arguments,
	the text field element and an array of possible autocompleted values:*/
	var currentFocus;
	/*execute a function when someone writes in the text field:*/
	inp.addEventListener("input", function(e) {
		var a, b, i, val = this.value;
		/*close any already open lists of autocompleted values*/
		closeAllLists();
		if (!val) { return false;}
		currentFocus = -1;
		/*create a DIV element that will contain the items (values):*/
		a = document.createElement("DIV");
		a.setAttribute("id", this.id + "autocomplete-list");
		a.setAttribute("class", "autocomplete-items");
		a.setAttribute("style", "");
		/*append the DIV element as a child of the autocomplete container:*/
		this.parentNode.appendChild(a);
		/*for each item in the array...*/
		for (i = 0; i < arr.length; i++) {
		  /*check if the item starts with the same letters as the text field value:*/
		  if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
			/*create a DIV element for each matching element:*/
			b = document.createElement("DIV");
			/*make the matching letters bold:*/
			b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
			b.innerHTML += arr[i].substr(val.length);
			/*insert a input field that will hold the current array item's value:*/
			b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
			/*execute a function when someone clicks on the item value (DIV element):*/
				b.addEventListener("click", function(e) {
				/*insert the value for the autocomplete text field:*/
				inp.value = this.getElementsByTagName("input")[0].value;
				/*close the list of autocompleted values,
				(or any other open lists of autocompleted values:*/
				closeAllLists();});
			a.appendChild(b);}}});
	/*execute a function presses a key on the keyboard:*/
	inp.addEventListener("keydown", function(e) {
		var x = document.getElementById(this.id + "autocomplete-list");
		if (x) x = x.getElementsByTagName("div");
		if (e.keyCode == 40) {
		  /*If the arrow DOWN key is pressed,
		  increase the currentFocus variable:*/
		  currentFocus++;
		  /*and and make the current item more visible:*/
		  addActive(x);}
		else if (e.keyCode == 38) { //up
		  /*If the arrow UP key is pressed,
		  decrease the currentFocus variable:*/
		  currentFocus--;
		  /*and and make the current item more visible:*/
		  addActive(x);}
		else if (e.keyCode == 13) {
		  /*If the ENTER key is pressed, prevent the form from being submitted,*/
		  e.preventDefault();
		  if (currentFocus > -1) {
			/*and simulate a click on the "active" item:*/
			if (x) x[currentFocus].click();}}});
	function addActive(x) {
	  /*a function to classify an item as "active":*/
	  if (!x) return false;
	  /*start by removing the "active" class on all items:*/
	  removeActive(x);
	  if (currentFocus >= x.length) currentFocus = 0;
	  if (currentFocus < 0) currentFocus = (x.length - 1);
	  /*add class "autocomplete-active":*/
	  x[currentFocus].classList.add("autocomplete-active");}
	function removeActive(x) {
	  /*a function to remove the "active" class from all autocomplete items:*/
	  for (var i = 0; i < x.length; i++) {
		x[i].classList.remove("autocomplete-active");}}
	function closeAllLists(elmnt) {
	  /*close all autocomplete lists in the document,
	  except the one passed as an argument:*/
	  var x = document.getElementsByClassName("autocomplete-items");
	  for (var i = 0; i < x.length; i++) {
		if (elmnt != x[i] && elmnt != inp) {
		x[i].parentNode.removeChild(x[i]);}}}
	/*execute a function when someone clicks in the document:*/
	document.addEventListener("click", function (e) {
	closeAllLists(e.target);});}

	var clan_list = ["CL0192", "CL0023", "CL0079", "CL0001", "CL0190", "CL0108", "CL0007", "CL0541", "CL0010", "CL0607", "CL0117", "CL0465", "CL0168", "CL0129", "CL0029", "CL0159", "CL0375", "CL0333", "CL0121", "CL0328", "CL0318", "CL0196", "CL0220", "CL0344", "CL0451", "CL0602", "CL0090", "CL0497", "CL0063", "CL0123", "CL0011", "CL0239", "CL0005", "CL0202", "CL0004", "CL0186", "CL0056", "CL0030", "CL0116", "CL0037", "CL0434", "CL0629", "CL0016", "CL0304", "CL0055", "CL0219", "CL0221", "CL0027", "CL0015", "CL0172", "CL0630", "CL0124", "CL0566", "CL0128", "CL0361", "CL0229", "CL0511", "CL0031", "CL0053", "CL0167", "CL0046", "CL0486", "CL0125", "CL0256", "CL0327", "CL0026", "CL0014", "CL0286", "CL0036", "CL0012", "CL0058", "CL0343", "CL0006", "CL0461", "CL0536", "CL0039", "CL0065", "CL0028", "CL0194", "CL0617", "CL0013", "CL0425", "CL0422", "CL0043", "CL0163", "CL0040", "CL0061", "CL0533", "CL0475", "CL0492", "CL0021", "CL0296", "CL0066", "CL0154", "CL0266", "CL0018", "CL0099", "CL0072", "CL0091", "CL0208", "CL0270", "CL0399", "CL0387", "CL0659", "CL0149", "CL0113", "CL0329", "CL0085", "CL0062", "CL0044", "CL0276", "CL0255", "CL0548", "CL0547", "CL0151", "CL0392", "CL0052", "CL0100", "CL0237", "CL0482", "CL0431", "CL0092", "CL0088", "CL0035", "CL0143", "CL0487", "CL0042", "CL0452", "CL0205", "CL0193", "CL0282", "CL0290", "CL0367", "CL0652", "CL0483", "CL0261", "CL0118", "CL0268", "CL0575", "CL0096", "CL0071", "CL0045", "CL0054", "CL0188", "CL0600", "CL0293", "CL0171", "CL0067", "CL0075", "CL0347", "CL0135", "CL0556", "CL0613", "CL0407", "CL0592", "CL0516", "CL0105", "CL0340", "CL0161", "CL0240", "CL0632", "CL0127", "CL0109", "CL0049", "CL0384", "CL0325", "CL0603", "CL0364", "CL0106", "CL0680", "CL0177", "CL0209", "CL0267", "CL0126", "CL0303", "CL0204", "CL0164", "CL0059", "CL0217", "CL0087", "CL0034", "CL0551", "CL0246", "CL0254", "CL0224", "CL0107", "CL0337", "CL0385", "CL0238", "CL0110", "CL0302", "CL0357", "CL0378", "CL0114", "CL0025", "CL0020", "CL0595", "CL0404", "CL0550", "CL0041", "CL0144", "CL0459", "CL0003", "CL0074", "CL0326", "CL0089", "CL0506", "CL0314", "CL0203", "CL0073", "CL0568", "CL0022", "CL0410", "CL0426", "CL0287", "CL0257", "CL0070", "CL0098", "CL0382", "CL0466", "CL0236", "CL0148", "CL0674", "CL0145", "CL0479", "CL0272", "CL0409", "CL0542", "CL0214", "CL0390", "CL0552", "CL0198", "CL0539", "CL0537", "CL0084", "CL0271", "CL0512", "CL0033", "CL0417", "CL0093", "CL0264", "CL0241", "CL0076", "CL0681", "CL0094", "CL0437", "CL0369", "CL0153", "CL0323", "CL0137", "CL0060", "CL0622", "CL0620", "CL0201", "CL0299", "CL0083", "CL0605", "CL0139", "CL0280", "CL0381", "CL0009", "CL0661", "CL0169", "CL0571", "CL0445", "CL0505", "CL0141", "CL0374", "CL0352", "CL0588", "CL0346", "CL0080", "CL0081", "CL0649", "CL0051", "CL0464", "CL0543", "CL0322", "CL0166", "CL0226", "CL0529", "CL0368", "CL0184", "CL0104", "CL0389", "CL0448", "CL0182", "CL0077", "CL0142", "CL0324", "CL0353", "CL0199", "CL0156", "CL0183", "CL0064", "CL0334", "CL0453", "CL0633", "CL0032", "CL0534", "CL0408", "CL0181", "CL0611", "CL0078", "CL0179", "CL0158", "CL0363", "CL0130", "CL0612", "CL0170", "CL0265", "CL0370", "CL0521", "CL0232", "CL0621", "CL0345", "CL0658", "CL0230", "CL0335", "CL0433", "CL0234", "CL0644", "CL0395", "CL0292", "CL0103", "CL0082", "CL0509", "CL0397", "CL0160", "CL0212", "CL0115", "CL0263", "CL0339", "CL0380", "CL0336", "CL0175", "CL0101", "CL0648", "CL0396", "CL0356", "CL0057", "CL0359", "CL0069", "CL0366", "CL0477", "CL0527", "CL0598", "CL0379", "CL0244", "CL0178", "CL0187", "CL0321", "CL0520", "CL0231", "CL0666", "CL0418", "CL0213", "CL0228", "CL0222", "CL0377", "CL0332", "CL0525", "CL0289", "CL0050", "CL0173", "CL0449", "CL0233", "CL0155", "CL0086", "CL0140", "CL0670", "CL0625", "CL0223", "CL0288", "CL0577", "CL0207", "CL0436", "CL0269", "CL0502", "CL0260", "CL0095", "CL0413", "CL0316", "CL0619", "CL0481", "CL0626", "CL0624", "CL0584", "CL0297", "CL0639", "CL0283", "CL0503", "CL0362", "CL0315", "CL0646", "CL0243", "CL0499", "CL0522", "CL0360", "CL0197", "CL0441", "CL0200", "CL0601", "CL0273", "CL0291", "CL0122", "CL0319", "CL0376", "CL0635", "CL0572", "CL0305", "CL0656", "CL0494", "CL0311", "CL0306", "CL0419", "CL0131", "CL0582", "CL0317", "CL0424", "CL0330", "CL0573", "CL0671", "CL0355", "CL0638", "CL0589", "CL0248", "CL0068", "CL0457", "CL0610", "CL0673", "CL0532", "CL0609", "CL0206", "CL0618", "CL0570", "CL0653", "CL0498", "CL0623", "CL0545", "CL0351", "CL0651", "CL0672", "CL0439", "CL0405", "CL0111", "CL0493", "CL0132", "CL0526", "CL0298", "CL0348", "CL0560", "CL0597", "CL0484", "CL0349", "CL0251", "CL0472", "CL0627", "CL0663", "CL0365", "CL0136", "CL0157", "CL0423", "CL0394", "CL0279", "CL0245", "CL0416", "CL0047", "CL0616", "CL0165", "CL0227", "CL0544", "CL0476", "CL0591", "CL0614", "CL0210", "CL0247", "CL0546", "CL0535", "CL0608", "CL0341", "CL0275", "CL0386", "CL0469", "CL0174", "CL0277", "CL0300", "CL0402", "CL0250", "CL0429", "CL0176", "CL0393", "CL0354", "CL0195", "CL0650", "CL0242", "CL0400", "CL0517", "CL0295", "CL0133", "CL0274", "CL0458", "CL0685", "CL0331", "CL0615", "CL0559", "CL0235", "CL0576", "CL0530", "CL0320", "CL0628", "CL0383", "CL0446", "CL0097", "CL0428", "CL0048", "CL0146", "CL0523", "CL0580", "CL0430", "CL0435", "CL0508", "CL0342", "CL0513", "CL0373", "CL0645", "CL0112", "CL0478", "CL0147", "CL0308", "CL0490", "CL0442", "CL0259", "CL0596", "CL0471", "CL0507", "CL0540", "CL0500", "CL0667", "CL0655", "CL0401", "CL0414", "CL0189", "CL0569", "CL0307", "CL0662", "CL0590", "CL0278", "CL0372", "CL0563", "CL0225", "CL0310", "CL0631", "CL0679", "CL0249", "CL0564", "CL0660", "CL0643", "CL0312", "CL0350", "CL0491", "CL0504", "CL0371", "CL0406", "CL0684", "CL0262", "CL0258", "CL0488", "CL0411", "CL0284", "CL0412", "CL0281", "CL0567", "CL0403", "CL0450", "CL0421", "CL0606", "CL0470", "CL0528", "CL0191", "CL0420", "CL0562", "CL0447", "CL0634", "CL0294", "CL0285", "CL0593", "CL0398", "CL0642", "CL0637", "CL0301", "CL0388", "CL0162", "CL0496", "CL0515", "CL0636", "CL0391", "CL0455", "CL0561", "CL0594", "CL0604", "CL0677", "CL0489", "CL0654", "CL0462", "CL0599", "CL0524", "CL0668", "CL0578", "CL0456", "CL0585", "CL0640", "CL0678", "CL0688", "CL0474", "CL0531", "CL0647", "CL0565", "CL0669", "CL0676", "CL0664", "CL0586", "CL0683", "CL0665", "CL0587", "CL0682", "CL0687", "CL0686"];
	autocomplete(document.getElementById("clan"), clan_list);
	</script>
<?php include("./includes/footer.php"); ?>