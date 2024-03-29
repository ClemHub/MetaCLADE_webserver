<?php include("./includes/header.php"); ?>
<section>
<h2>Results</h2>
<?php

	//Taking form informations
	$form = $_GET["form"];
	$job_id = $_GET["job_id"];
	if($form == 'visualization_file'){
		$dl_file = $appurl."/jobs/".$job_id."/".$job_id.".arch.tsv";
		$name_file = $approot."/jobs/".$job_id."/".$job_id.".arch.tsv";}
	else{
		$parameters = read_parameters_file($approot."/jobs/".$job_id."/parameters.txt");
		if(array_key_exists("Job name", $parameters)){
			echo "<h4>Job: ".$parameters["Job name"]."</h4>";}
		$dama = $parameters["DAMA"];
		$e_value = $parameters['E-value'];
		$name_file = $approot."/jobs/".$job_id."/".$job_id.".arch.tsv";
		$dl_file = $appurl."/jobs/".$job_id."/results.txt";
		$nb_seq = $parameters['Number of sequences'];
		if($dama == 'true'){
			$DAMA_evalue = $parameters["DAMA e-value"];}
		if($form=="small" | $form=='small_example'){
			$pfam = $parameters["PFAM"];}}

	if($form=="small" || $form=="small_example"){
		$domain_list = explode(",", $pfam);
		echo "<form>";
		echo "<fieldset class='form_fs'><legend><h4>Domain visualization:  <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Select the domain table you want to visualize.</span></span></h4></legend>";
		echo "<div id = 'main_pfam'>";
		echo "<h5>Domain table:  <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Select the domain table you want to visualize.</span></span></h5>";
		echo "<select name='domain_select' id='domain_select' onclick='filter_select()'>";
		echo "<option value=''>All</option>";
		foreach($domain_list as $domain_id){
			echo "<option class='other_domains' value='".trim($domain_id)."'>".trim($domain_id)."</option>";}
		echo "</select>";
		echo "</div>";
		echo "<div id = 'other_pfam'>";
		echo "<h5>Other domain:  <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Select the domains you want to visualize with the first one you selected.</span></span></h5>";
		foreach($domain_list as $domain_id){
			echo "<input type='checkbox' id=".$domain_id." name='domain_cb' value=".trim($domain_id).">";
			echo "<label for=".$domain_id.">".$domain_id."</label>";}
		?>
		</div>
		</fieldset>
		</form>
	<?php
	}
	$data = array();
	$all_data = array();
	$best_evalues = array();
	$domain_list = array();
	$seq_id_list = array();
	$domain_count = array();
	$seq_count = array();
	$file_content = fopen($name_file, "r");
	while(!feof($file_content)){
		$line = fgets($file_content);
		if($line != ""){
			$exploded_line = explode("\t", $line);
			$seq_id = $exploded_line[0];
			$domain_id = $exploded_line[4];
			array_push($domain_list, $domain_id);
			if(array_key_exists($seq_id, $data)){
				array_push($all_data[$seq_id], $line);
				array_push($data[$seq_id], $domain_id);
				$seq_count[$seq_id]++;
				if($best_evalues[$seq_id]>$exploded_line[9]){
					$best_evalues[$seq_id]=$exploded_line[9];}}
			else if ($seq_id != ""){
				$seq_count[$seq_id] = 1;
				array_push($seq_id_list, $seq_id);
				$best_evalues[$seq_id]=$exploded_line[9];
				$all_data[$seq_id]=array($line);
				$data[$seq_id]=array($domain_id);}
			if(array_key_exists($domain_id, $domain_count)){
				$domain_count[$domain_id]++;}
			else{
				$domain_count[$domain_id]=1;}}};
	
	echo "<br><a id = 'dl_link' href=".$dl_file." download=results.csv><i class='fa fa-download'></i>Download the CSV resulting file</a>";
		?>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
		<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
		<script>
		var form = '<?php echo $form ?>';


		$(document).ready(function() {
			
			if(form == 'small' || form == 'small_example'){
				var table = $('#result').DataTable( {
					dom: 'lrtip',
					"pageLength": 10,
					"order": [[ 2, "desc" ]],
					"lengthMenu": [ [5, 10, 20, 50, -1], [5, 10, 20, 50, "All"] ],
						});}
			else{
				var table = $('#result').DataTable( {
					dom: 'flrtip',
					"pageLength": 10,
					"order": [[ 2, "desc" ]],
					"lengthMenu": [ [5, 10, 20, 50, -1], [5, 10, 20, 50, "All"] ],
					"language": {
							"search": "<span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>List the Pfam domain you want to see. Separate them with a white-space.</span></span> PFAM list:",
							"searchPlaceholder": "PF00001 PF00003 PF00156"},
						});
				var val = [];
				table.column(1).search(val.join(' ')).draw();}
			var main_dom = ''
			$('#e-value_max').keyup( function() {
				table.draw();});
			$('#seq-filter').on('change', function(){
				table.search(this.value).draw();});
			$('#domain_select').on('change', function(){
				table.search(this.value).draw();});
			$('#domain-filter').on('keyup change', function(){
				main_dom = "(?=.*"+this.value+")";
				table.search(this.value, regex=true).draw()});
			$('input:checkbox').on('change', function () {
			//build a regex filter string with an or(|) condition
				var domains = $('input:checkbox[name="domain_cb"]:checked').map(function() {
				return "(?=.*"+this.value+")";}).get().join('');
				table.search(main_dom.concat(domains), true, false, false).draw();});
		});
		</script>
		
		<div class='table_container'>
		<table id = 'result'>
		<?php
		echo "<thead id='header'>";
			echo "<tr>";
			echo "<th class='table_header'><span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Click on the sequence ID to see the architecture.</span></span> Sequence ID</th>";
			echo "<th class='table_header'>Domain Id</th>";
			echo "<th class='table_header'><span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>After comparing every annotated Pfam domains E-value for each sequences.</span></span> Best e-value </th>";
			echo "<th class='table_header'>Number of hits</th>";
			echo "</tr>";
            echo "</thead>";
            echo "<tfoot>";
            echo "<tr>";
			echo "<th class='table_header'>";
			echo "<select id='seq-filter'>";
			echo "<option value=''>All</option>";
			foreach($seq_id_list as $seq_id){
				echo "<option value='".$seq_id."'>".$seq_id."</option>";
			}
			echo "</select></th>";
			echo "<th class='table_header'>";
			echo "<select id='domain-filter'>";
			echo "<option value=''>All</option>";
			foreach(array_unique($domain_list) as $domain){
				if($domain != ""){
				echo "<option value='".$domain."'>".$domain."</option>";}
			}
			echo "</select></th>";
			echo "<th class='table_header'><input id='e-value_max' type='text' placeholder='E-value max'/></th>";
			echo "<th></th>";
			echo "</tr>";
            echo "</tfoot>";
		echo "<tbody>";
		$db = new SQLite3($approot.'/data/MetaCLADE.db');
		foreach($all_data as $seq_id => $lines){
			echo "<tr><td><a class='table_link' href='architecture.php?form=" . $form ."&job_id=" . $job_id . "&id=" . preg_replace("#[^a-zA-Z0-9]#", "", $seq_id)."'>" . $seq_id . "</a></td>";
			echo "<td>";
			foreach($lines as $line){
				$exploded_line = explode("\t", $line);
				$start = $exploded_line[1];
				$stop = $exploded_line[2];
				$nb_aa = ($stop-$start);
				$domain_id = $exploded_line[4];
				$evalue = $exploded_line[9];
				$bitscore = $exploded_line[10];
				$dd_prob = $exploded_line[11];
				$row = $db->query("SELECT DISTINCT PFAM32.PFAM_acc_nb, PFAM32.Family, PFAM32.Clan_acc_nb, PFAM32.Clan FROM PFAM32 WHERE PFAM32.PFAM_acc_nb='".$domain_id."'");
				$row = $row->fetchArray();
				if($row['Clan_acc_nb'] == ""){
					$row['Clan_acc_nb'] = 'NA';
					$row['Clan'] = 'NA';}
				if(trim($exploded_line[12]) == 'unavailable'){
					$model_species = 'HMMer-3 model';}
				else{
					$model_species = trim($exploded_line[12]);}
				$title_text = "PFAM Acc Number: ".$domain_id."\nFamily: ".$row['Family']."\n\nPosition: ".$start."-".$stop." (".$nb_aa."aa)\n\nClan Acc Number: ".$row['Clan_acc_nb']."\nClan: ".$row['Clan']."\n\nModel species: ".$model_species."\n\nE-value: ".$evalue."\nbitscore: ".$bitscore."\nddProb: ".$dd_prob;	
				$link_id = "http://pfam.xfam.org/family/" . $domain_id;
				echo "<a class = 'table_link' title='".$title_text."' href=".$link_id." target='_blank'>  " . $domain_id . "  </a>";}
				echo "</td><td>".$best_evalues[$seq_id]."</td>";
				echo "</td><td>".count($lines)."</td></tr>";}

	echo "</tbody></table>";	
	echo "</div>";
	?>
	
	<div class='info'>
	<div class = 'seqcount_choice'>
			Show synthesis:
			<label for="yes_seqcount">Yes</label><input type="radio" class='radio_btn' name="seqcount" id="yes_seqcount" value = "true" onclick='ShowHideSeqCount()'/>
			<label for="no_seqcount">No</label><input type="radio" class='radio_btn' name="seqcount" id="no_seqcount" value = "false" onclick='ShowHideSeqCount()' checked/>
	</div>
	<div class='table_container' id='seqcount_container'>
	</br>
	<?php
	echo "Total number of input sequences: ".$nb_seq."</br>"; 
	echo "Sequences with no hit: ".($nb_seq-count($seq_id_list))."</br>"; 
	echo "Sequences with at least one hit: ".count($seq_id_list)."</br>"; 
	?>
	</div>
	</div>
	</br>
	<?php
	//Information button
	if($form != 'visualization_file'){
		echo "<div class='info'>";
		if($parameters["LOGO"]=="false\n")
			echo "<input id=submitLogo type='button' class='bouton_info' value='Build logo' onclick='runLogoBuilding();' />";
		else
		   if($parameters["LOGO"]!="true\n")
			echo "<input id=submitLogo type='button' class='bouton_info'/>";

		echo "</div>";
		echo "<div class='info'>";
		echo "<input type='button' class='bouton_info' value='Search parameters' onclick='close_open_info(this);' />";
		echo "<div class='contenu_info'>";
		if($form == 'small' | $form == 'large' | $form == 'clan' | $form == 'visualization_jobID'){
				echo "<ul><strong>Your job parameters:</strong>";}
		else if($form == 'small_example' | $form == 'large_example' | $form == 'clan_example'){
				echo "<ul><strong>Example parameters:</strong>";} 
		foreach($parameters as $name => $value){
			if($name != "" and $value != "" and $name != 'Email'){
				echo "<li>".$name.": ".$value."</li>";}}
			echo "</ul>";
		echo "</div></div>";}
		?>
	
	</section>

<?php include("./includes/footer.php"); ?>
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
$.fn.dataTable.ext.search.push(
	function( settings, data, dataIndex ) {
		var max = Number($('#e-value_max').val()) || 1;
		var e_value = Number(data[2]) || 0;
		if ((isNaN(max)) || (e_value <= max)){
			return true;}
		return false;});

$(document).ready(function() {
	var table = $('#domcount_table').DataTable();
	$('#domain-filter2').on('keyup change', function(){
				table.search(this.value, regex=true).draw()});
});

$(document).ready(function() {
	var table = $('#seqcount_table').DataTable();
	$('#seq-filter2').on('keyup change', function(){
				table.search(this.value, regex=true).draw()});
});
$(document).ready(function() {
	var params=getParams(window.location.href);
	$.get('getLogoStatus.php', {job_id:params["job_id"]},(status)=>{
	status=status.replace('\n','')
	setlogoText(status);
	if(status!="true" && status!="false")
	   	pollLogoStatus(params["job_id"])	   
	})

})
</script>

