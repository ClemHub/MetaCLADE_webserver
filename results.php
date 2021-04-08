<?php
include("./includes/header.php");
?>
	<section>
	<h2>Results</h2>
	<?php

	//Taking form informations
	$form = $_GET["form"];
	$job_id = $_GET["job_id"];
	if($form == 'visualization_file'){
		$dl_file = $appurl."/jobs/".$job_id."/results.txt";
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
		echo "<select name='domain_table' id='domain_select' onchange='filter_table()'>";
		echo "<option value=''>--Please select a domain--</option>";
		foreach($domain_list as $domain_id){
			echo "<option class='other_domains' value='$domain_id'>$domain_id</option>";}
		echo "</select>";
		echo "</div>";
		echo "<div id = 'other_pfam'>";
		echo "<h5>Other domain:  <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Select the domains you want to visualize with the first one you selected.</span></span></h5>";
		foreach($domain_list as $domain_id){
			echo "<input type='checkbox' id=".$domain_id." name='domain_cb' value=".$domain_id.">";
			echo "<label for=".$domain_id.">".$domain_id."</label>";}
		?>
		</div>
		<br><input class='btn' type='button' value='Search' name = 'search' onclick='filter_all_domains()'/><input class='btn' type='reset' value='Reset' onclick='reset_table("result")'/>
		</fieldset>
		</form>
	<?php
	}
	$data = array();
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
				array_push($data[$seq_id], $domain_id);
				$seq_count[$seq_id]++;
				if($best_evalues[$seq_id]>$exploded_line[9]){
					$best_evalues[$seq_id]=$exploded_line[9];}}
			else if ($seq_id != ""){
				$seq_count[$seq_id] = 1;
				array_push($seq_id_list, $seq_id);
				$best_evalues[$seq_id]=$exploded_line[9];
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
		$.fn.dataTable.ext.search.push(
			function( settings, data, dataIndex ) {
				var max = Number($('#max').val()) || 1;
				var e_value = Number(data[2]) || 0;
				if ((isNaN(max)) || (e_value <= max)){
					return true;}
				return false;
		});
		

		$(document).ready(function() {
			var form = '<?php echo $form ?>';
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
			$('#max').on( 'keyup change', function () {
            	table.draw();});
			$('#seq-filter').on('change', function(){
				table.search(this.value).draw();});
			$('#domain-filter').on('keyup change', function(){
				table.search(this.value, regex=true).draw()});

		});
		</script>
		
		<div class='table_container'>
		<table id = 'result'>
		<thead id='header'>
			<tr>
			<th class='table_header'><span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Click on the sequence ID to see the architecture.</span></span> Sequence ID</th>
			<th class='table_header'>Domain Id</th>
			<th class='table_header'><span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>After comparing every annotated Pfam domains E-value for each sequences.</span></span> Best e-value </th>
			</tr>
		</thead>
		<tfoot>
			<tr>
			<?php
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
			?>
			<th class='table_header'><input id='max' type='text' placeholder='E-value max'/></th>
			</tr>
		</tfoot>
		<tbody>
		<?php

	foreach($data as $seq_id => $domains){
		echo "<tr><td><a class='table_link' href='architecture.php?form=" . $form ."&job_id=" . $job_id . "&id=" . preg_replace("#[^a-zA-Z0-9]#", "", $seq_id)."'>" . $seq_id . "</a></td>";
		echo "<td>";
		foreach($domains as $domain_id){
			$link_id = "http://pfam.xfam.org/family/" . $domain_id;
			echo "<a class = 'table_link' href=".$link_id." target='_blank'>  " . $domain_id . "  </a>";}
		echo "</td><td>".$best_evalues[$seq_id]."</td></tr>";}
	echo "</tbody></table>";	
	echo "</div>";
	?>
	<div class='info'>
	<div class = 'domcount_choice'>
			Show domains synthesis:
			<label for="yes_domcount">Yes</label><input type="radio" class='radio_btn' name="domcount" id="yes_domcount" value = "true" onclick='ShowHideDomCount()'/>
			<label for="no_domcount">No</label><input type="radio" class='radio_btn' name="domcount" id="no_domcount" value = "false" onclick='ShowHideDomCount()' checked/>
	</div>
	<div class='table_container' id='domcount_container'>
	</br>
	Occurences of each annotated domains.
	</br>
	<table id='domcount_table'>
	<thead>
		<tr>
			<th class='table_header'>Domain ID</th>
			<th class='table_header'>Occurrences</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
		<?php
		echo "<th class='table_header'>";
		echo "<select id='domain-filter2'>";
		echo "<option value=''>All</option>";
		foreach(array_unique($domain_list) as $domain){
			if($domain != ""){
			echo "<option value='".$domain."'>".$domain."</option>";}
		}
		echo "</select></th>";
		?>
		<th class='table_header'></th>
		</tr>
	</tfoot>
	<tbody>
		<?php 
		foreach($domain_count as $id => $count){
			echo "<tr><td>$id</td><td>$count</td></tr>";}
		?>
	</tbody>
	</table>
	</div>
	</div>
	<div class='info'>
	<div class = 'seqcount_choice'>
			Show sequence synthesis:
			<label for="yes_seqcount">Yes</label><input type="radio" class='radio_btn' name="seqcount" id="yes_seqcount" value = "true" onclick='ShowHideSeqCount()'/>
			<label for="no_seqcount">No</label><input type="radio" class='radio_btn' name="seqcount" id="no_seqcount" value = "false" onclick='ShowHideSeqCount()' checked/>
	</div>
	<div class='table_container' id='seqcount_container'>
	</br>
	Number of hits for each sequences given in input.</br>
	<?php
	echo "Total number of input sequences: ".$nb_seq."</br>"; 
	echo "Sequences with no hit: ".($nb_seq-count($seq_id_list))."</br>"; 
	echo "Sequences with at least one hit: ".count($seq_id_list)."</br>"; 
	?>
	</br>
	<table id='seqcount_table'>
	<thead>
		<tr>
			<th class='table_header'>Sequence ID</th>
			<th class='table_header'>Occurrences</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
		<?php
		echo "<th class='table_header'>";
		echo "<select id='seq-filter2'>";
		echo "<option value=''>All</option>";
		foreach(array_unique($seq_id_list) as $seq){
			if($seq != ""){
			echo "<option value='".$seq."'>".$seq."</option>";}
		}
		echo "</select></th>";
		?>
		<th class='table_header'></th>
		</tr>
	</tfoot>
	<tbody>
		<?php 
		foreach($seq_count as $id => $count){
			echo "<tr><td>$id</td><td>$count</td></tr>";}
		?>
	</tbody>
	</table>
	</div>
	</div>
	</br>
	<?php
	//Information button
	if($form != 'visualization_file'){
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

<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>

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
</script>

<?php include("./includes/footer.php"); ?>
