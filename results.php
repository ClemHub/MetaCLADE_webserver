<?php
include("./includes/header.php");
?>
	<section>
	<h2>Results</h2>
	<?php

	//Taking form informations
	$form = $_GET["form"];
	if($form=="small" || $form=="large" || $form=='clan'){
		$job_id = $_GET["job_id"];
		$parameters = read_parameters_file($approot."/jobs/".$job_id."/parameters.txt");
		$dama = $parameters["DAMA"];
		$e_value = $parameters['E-value'];
		$name_file = $approot."/jobs/".$job_id."/".$job_id.".arch.txt";
		$dl_file = $appurl."/jobs/".$job_id."/results.txt";
		if($dama == true){
			$DAMA_evalue = $parameters["DAMA e-value"];}
		if($form=="small"){
			$pfam = $parameters["PFAM"];}}
	else if($form=="large_example"){
		$dama = $_POST["dama"];
		$e_value = 0.001;
		if($dama == "true"){
			$job_id = 'large_example_withDAMA';
			$DAMA_evalue = 1e-10;
			$name_file = $approot."/jobs/large_example_withDAMA/large_example_withDAMA.arch.txt";
			$dl_file = $appurl."/jobs/large_example_withDAMA/results.txt";}
		else if($dama == "false"){
			$job_id = 'large_example_withoutDAMA';
			$name_file = $approot."/jobs/large_example_withoutDAMA/large_example_withoutDAMA.arch.txt";
			$dl_file = $appurl."/jobs/large_example_withoutDAMA/results.txt";}}
	else if($form=="clan_example"){
		$dama = $_POST["dama"];
		$clan = 'CL0039';
		$e_value = 0.001;
		if($dama == "true"){
			$job_id = 'clan_example_withDAMA';
			$DAMA_evalue = 1e-10;
			$name_file = $approot."/jobs/clan_example_withDAMA/clan_example_withDAMA.arch.txt";
			$dl_file = $appurl."/jobs/clan_example_withDAMA/results.txt";}
		else if($dama == "false"){
			$job_id = 'clan_example_withoutDAMA';
			$name_file = $approot."/jobs/clan_example_withoutDAMA/clan_example_withoutDAMA.arch.txt";
			$dl_file = $appurl."/jobs/clan_example_withoutDAMA/results.txt";}}
	else if($form=="small_example"){
		$e_value = 0.001;
		$dama = $_POST["dama"];
		$pfam = "PF00875,PF03441,PF03167,PF12546";
		if($dama == "true"){
			$DAMA_evalue = 1e-10;
			$job_id = 'small_example_withDAMA';
			$name_file = $approot."/jobs/small_example_withDAMA/small_example_withDAMA.arch.txt";
			$dl_file = $appurl."/jobs/small_example_withDAMA/results.txt";}
		else if($dama == "false"){
			$job_id = 'small_example_withoutDAMA';
			$name_file = $approot."/jobs/small_example_withoutDAMA/small_example_withoutDAMA.arch.txt";
			$dl_file = $appurl."/jobs/small_example_withoutDAMA/results.txt";}}
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
	$file_content = fopen($name_file, "r");
	while(!feof($file_content)){
		$line = fgets($file_content);
		$exploded_line = explode("\t", $line);
		$seq_id = $exploded_line[0];
		$domain_id = $exploded_line[4];
		array_push($domain_list, $domain_id);
		if(array_key_exists($seq_id, $data)){
			array_push($data[$seq_id], $domain_id);
			if($best_evalues[$seq_id]>$exploded_line[9]){
				$best_evalues[$seq_id]=$exploded_line[9];}}
		else if ($seq_id != ""){
			array_push($seq_id_list, $seq_id);
			$best_evalues[$seq_id]=$exploded_line[9];
			$data[$seq_id]=array($domain_id);}};

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
		echo "<tr><td><a class='table_link' href='architecture.php?form=" . $form ."&id=" . $seq_id . "&job_id=" . htmlspecialchars($job_id) . "'>" . $seq_id . "</a></td>";
		echo "<td>";
		foreach($domain_list as $domain_id){
			$link_id = "http://pfam.xfam.org/family/" . $domain_id;
			echo "<a class = 'table_link' href=".$link_id." target='_blank'>  " . $domain_id . "  </a>";}
		echo "</td><td>".$best_evalues[$seq_id]."</td></tr>";}
	echo "</tbody></table>";
	?>		
	</div>
	
	<!--Information button--> 
	<div class='info'>
	<input type='button' class='bouton_info' value='Info' onclick='close_open_info(this);' />
	<div class='contenu_info'>
	<h4>Parameters of the test:</h4>
	<?php
	if($form == 'small'){
		echo 'Only the domains ID you gave in input was searched into the sequences.<br>';
		echo "<ul><br><strong>Your job parameters:</strong><br>";
		foreach($parameters as $name => $value){
			if($name != "" and $value != ""){
				echo "<li>".$name.": ".$value."</li>";}}
		echo "</ul>";
	}
	else if($form == 'large'){
		echo 'All the domain library was used to analyse the sequences entered.<br>';
		echo "<ul><br><strong>Your job parameters:</strong><br>";
		foreach($parameters as $name => $value){
			if($name != "" and $value != ""){
				echo "<li>".$name.": ".$value."</li>";}}
		echo "</ul>";
	}
	else if($form == 'clan'){
		echo 'Only the domains belonging to the Pfam clan you selected was used to analyse the sequences entered.<br>';
		echo "<ul><br><strong>Your job parameters:</strong><br>";
		foreach($parameters as $name => $value){
			if($name != "" and $value != ""){
				echo "<li>".$name.": ".$value."</li>";}}
		echo "</ul>";
	}
	else if($form == 'small_example'){
		echo 'Only the domains ID gave as an example was used to treat our test data set.<br>';
		echo "<ul><br><strong>Your job parameters:</strong><br>";
		echo 'E-value: ' . $e_value . '<br>';
		echo 'Pfam: ' . $pfam . '<br>';
		echo 'DAMA: ' . $dama . '<br>';
		if($dama == true){
			echo 'DAMA E-value: ' . $DAMA_evalue;
			echo 'Amino acids overlappping: 30<br>' ;
			echo 'Max domain overlapping (%): 50<br>' ;
		}
	}
	else if($form == 'large_example'){
		echo 'All the domain of the library was used to treat our test data set.<br>';
		echo "<ul><br><strong>Your job parameters:</strong><br>";
		echo 'E-value: ' . $e_value . '<br>';
		echo 'DAMA: ' . $dama . '<br>';
		if($dama == true){
			echo 'DAMA E-value: ' . $DAMA_evalue;
			echo 'Amino acids overlappping: 30<br>' ;
			echo 'Max domain overlapping (%): 50<br>' ;
		}
	}
	else if($form == 'clan_example'){
		echo 'Only the domains belonging to the Pfam clan you selected was used to treat our test data set.<br>';
		echo "<ul><br><strong>Your job parameters:</strong><br>";
		echo 'E-value: ' . $e_value . '<br>';
		echo 'Clan: ' . $clan . '<br>';
		echo 'DAMA: ' . $dama . '<br>';
		if($dama == true){
			echo 'DAMA E-value: ' . $DAMA_evalue;
			echo 'Amino acids overlappping: 30<br>' ;
			echo 'Max domain overlapping (%): 50<br>' ;
		}
	}
	echo '<br><br>';
	?>

	</div>
	</div>
	</section>
<?php include("./includes/footer.php"); ?>
