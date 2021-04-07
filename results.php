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
	$seq_id_count = array();
	$file_content = fopen($name_file, "r");
	while(!feof($file_content)){
		$line = fgets($file_content);
		if($line != ""){
			$exploded_line = explode("\t", $line);
			$seq_id = $exploded_line[0];
			$domain_id = $exploded_line[4];
			array_push($domain_list, $domain_id);
			if(array_key_exists($seq_id, $data)){
				$seq_id_count[$seq_id]++;
				array_push($data[$seq_id], $domain_id);
				if($best_evalues[$seq_id]>$exploded_line[9]){
					$best_evalues[$seq_id]=$exploded_line[9];}}
			else if ($seq_id != ""){
				array_push($seq_id_list, $seq_id);
				$seq_id_count[$seq_id] = 1;
				$best_evalues[$seq_id]=$exploded_line[9];
				$data[$seq_id]=array($domain_id);}}};
	
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
		<table id = result>
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

	foreach($data as $seq_id => $domain_list){
		echo "<tr><td><a class='table_link' href='architecture.php?form=" . $form ."&job_id=" . $job_id . "&id=" . preg_replace("#[^a-zA-Z0-9]#", "", $seq_id)."'>" . $seq_id . "</a></td>";
		echo "<td>";
		foreach($domain_list as $domain_id){
			$link_id = "http://pfam.xfam.org/family/" . $domain_id;
			echo "<a class = 'table_link' href=".$link_id." target='_blank'>  " . $domain_id . "  </a>";}
		echo "</td><td>".$best_evalues[$seq_id]."</td></tr>";}
	echo "</tbody></table>";	
	echo "</div>";
	?>
	<div class='info'>
	<div class = 'count_choice'>
			Show hits counts:
			<label for="yes_count">Yes</label><input type="radio" class='radio_btn' name="count" id="yes_count" value = "true" onclick='ShowHideCount()'/>
			<label for="no_count">No</label><input type="radio" class='radio_btn' name="count" id="no_count" value = "false" onclick='ShowHideCount()' checked/>
	</div>
	<div class='table_container' id='count_container'>
	<table id='logo_table'>
	<thead>
		<tr>
		<th class='table_header'>Hits</th>
		<th class='table_header'>0</th>
		<th class='table_header'>1+</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<th class='table_header'>Sequence</th>
		<th class='table_header'><?php echo $nb_seq-count($seq_id_list);?></th>
		<th class='table_header'><?php echo count($seq_id_list);?> </th>
		</tr>
	</tbody>
	</table>
	</div>
	</div>
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
<?php include("./includes/footer.php"); ?>