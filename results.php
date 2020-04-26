<?php
include("./includes/header.php");
?>
	<section>
	<h2>Results</h2>
	<?php

	//Taking form informations
	$form = $_GET["form"];
	if($form=="small" || $form=="large"){
		$job_id = $_GET["job_id"];
		$parameters = read_parameters_file($appurl."/jobs/".$job_id."/parameters.txt");
		$dama = $parameters["DAMA"];
		$e_value = $parameters['E-value'];
		$name_file = $appurl."/jobs/".$job_id."/".$job_id."/results/3_arch/".$job_id.".arch.txt";
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
			$name_file = $approot."/jobs/large_example_withDAMA/large_example_withDAMA/results/3_arch/large_example_withDAMA.arch.txt";}
		else if($dama == "false"){
			$job_id = 'large_example_withoutDAMA';
			$name_file = $approot."/jobs/large_example_withoutDAMA/large_example_withoutDAMA/results/3_arch/large_example_withoutDAMA.arch.txt";}}
	else if($form=="small_example"){
		$e_value = 0.001;
		$dama = $_POST["dama"];
		$pfam = "PF00875,PF03441,PF03167,PF12546";
		if($dama == "true"){
			$DAMA_evalue = 1e-10;
			$job_id = 'small_example_withDAMA';
			$name_file = $approot."/jobs/small_example_withDAMA/small_example_withDAMA/results/3_arch/small_example_withDAMA.arch.txt";}
		else if($dama == "false"){
			$job_id = 'small_example_withoutDAMA';
			$name_file = $approot."/jobs/small_example_withoutDAMA/small_example_withoutDAMA/results/3_arch/small_example_withoutDAMA.arch.txt";}}
	if($form=="small" || $form=="small_example"){
		$domain_list = explode(",", $pfam);
		echo "<form action =''>";
		echo "<fieldset class='form_fs'><legend><h4>Domain visualization:  <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Select the domain table you want to visualize.</span></span></h4></legend>";
		echo "<div id = 'main_pfam'>";
		echo "<h5>Domain table:  <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Select the domain table you want to visualize.</span></span></h5>";
		echo "<select name='domain_table' id='domain_select' onchange='filter_table()'>";
		echo "<option value=''>--Please select a domain--</option>";
		foreach($domain_list as $domain_id){
			echo "<option name='other_domains' value='$domain_id'>$domain_id</option>";}
		echo "</select>";
		echo "</div>";
		echo "<div id = 'other_pfam'>";
		echo "<h5>Other domain:  <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Select the domains you want to visualize with the first one you selected.</span></span></h5>";
		foreach($domain_list as $domain_id){
			echo "<input type='checkbox' id=".$domain_id." name='domain_cb' value=".$domain_id.">";
			echo "<label for=".$domain_id.">".$domain_id."</label>";}
		echo "</div>";
		echo "<br><input class='btn' type='button' value='Search' name = 'search' onclick='filter_all_domains()'/><input class='btn' type='reset' value='Reset' onclick='reset_table()'/>";
		echo "</fieldset>";
		echo "</form>";}
	$data = array();
	$domain_list = array();
	$file_content = fopen($name_file, "r");
	while(!feof($file_content)){
		$line = fgets($file_content);
		$exploded_line = explode("\t", $line);
		$seq_id = $exploded_line[0];
		$domain_id = $exploded_line[4];
		array_push($domain_list, $domain_id);
		if(array_key_exists($seq_id, $data)){
			array_push($data[$seq_id], $domain_id);}
		else if ($seq_id != ""){
			$data[$seq_id]=array($domain_id);}}
	//Button that allows the user to download the text files with the results
	echo "<a id = 'dl_link' href=".$name_file." download=results.csv><i class='fa fa-download'></i>Download the CSV resulting file</a>";
		?>
		<!-- Table with the results -->
		<div class='table_container'>
		<table id = result>
		<thead>
			<tr>
			<th class='table_header'>Sequence ID <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Click on the sequence ID to see the architecture.</span></span></th>
			<th class='table_header'>Domain Id</th>
			</tr>
		</thead>
		<tbody>
		<?php

	foreach($data as $seq_id => $domain_list){
		echo "<tr><td><a class='table_link' href='architecture.php?id=" . $seq_id . "&job_id=" . $job_id . "'>" . $seq_id . "</a></td>";
		echo "<td>";
		foreach($domain_list as $domain_id){
			$link_id = "http://pfam.xfam.org/family/" . $domain_id;
			echo "<a class = 'table_link' href=".$link_id." target='_blank'>  " . $domain_id . "  </a>";
		}
		echo "</td></tr>";
	}
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
	}
	else if($form == 'large'){
		echo 'All the domain library was used to analyse the sequences entered.<br>';
	}
	else if($form == 'small_example'){
		echo 'Only the domains ID gave as an example was used to treat our test data set.<br>';
	}
	else if($form == 'large_example'){
		echo 'All the domain of the library was used to treat our test data set.<br>';
	}
	echo 'The MetaCLADE e-value applied: ' . $e_value . '<br>';
	if($dama == true){
		echo 'You choose to use DAMA to your dataset with a cut-off e-value equal to: ' . $DAMA_evalue;}
	else if($dama == false){
		echo 'DAMA was not used to determine the architecture.';}
	echo '<br><br>';
	?>

	</div>
	</div>
	</section>
<?php include("./includes/footer.php"); ?>
