<?php
include("./includes/header.php");
?>
	<section>
	<h2>Results</h2>
	<?php

	//Reinisialisation of the database and insertion of the new results
	$username = "blachon";
	$password = "myclade";
	$database = "METACLADE";
	$conn = mysqli_connect("localhost", $username, $password, $database);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);}

	//Taking form informations
	$form = $_GET['form'];
	$sequences = $_POST['sequences'];
	$dama = $_SESSION['dama'];
	if($form=='small' || $form=='large'){
		$e_value = $_SESSION['evalue'];
		$db_table = 'MetaCLADE_results';
		$name_file = 'http://localhost/MetaCLADE_webserver/MyCLADE/jobs/ID1/testDataSet/results/3_arch/testDataSet.arch.txt';
		if($dama == 'true'){
			$DAMA_evalue = $_SESSION['DAMA-evalue'];
			if($form=='small'){
				$pfam = $_POST['pfam_domains'];}}
		$sql = "DELETE FROM ".$db_table;
		$request = $conn->query($sql);
		results_to_db($conn, $name_file);}
	else if($form=='small_example' || $form=='large_example'){
		$e_value = 0.001;
		if($dama == 'true'){
			$DAMA_evalue = 1e-10;
			//$name_file = 'http://localhost:8888/MetaCLADE_webserver/data/examplewithDAMA.csv';
			$name_file = 'http://localhost/MetaCLADE_webserver/MyCLADE/jobs/example_withDAMA/testDataSet/results/3_arch/testDataSet.arch.txt';
			$db_table = 'Example_withDAMA';}
		else if($dama == 'false'){
			//$name_file = 'http://localhost:8888/MetaCLADE_webserver/data/examplewithoutDAMA.csv';
			$name_file = 'http://localhost/MetaCLADE_webserver/MyCLADE/jobs/example_withoutDAMA/testDataSet/results/3_arch/testDataSet.arch.txt';
			$db_table = 'Example_withoutDAMA';}}
	$data = array();
	$domain_list = array();
	$sql = "SELECT SeqID, DomainID, Seq_start FROM ". $db_table . " ORDER BY SeqID, Seq_start";
	$result = $conn->query($sql);
	if ($result -> num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$seq_id = $row["SeqID"];
			$domain_id = $row["DomainID"];
			array_push($domain_list, $domain_id);
			if(array_key_exists($seq_id, $data)){
				array_push($data[$seq_id], $domain_id);}
			else{
				$data[$seq_id]=array($domain_id);}}}
	if($form=='small'){
		//print_r($domain_list);
		echo "<div id = 'pfam_selection'>";
		foreach($domain_list as $domain_id){
			echo "<input type='radio' id=".$domain_id." name=".$domain_id." value=".$domain_id.">";
			echo "<label for=".$domain_id.">".$domain_id."</label>";}
		echo "</div>";}

	//Button that allows the user to download the text files with the results
	echo "<a id = 'dl_link' href=".$name_file." download=results.csv><i class='fa fa-download'></i>Download the CSV resulting file</a>";
		?>
		<!-- Table with the results -->
		<div class='table_container'>
		<table>
		<thead>
			<tr>
			<th class='table_header'>Sequence ID <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Click on the sequence ID to see the architecture.</span></span></th>
			<th class='table_header'>Domain Id</th>
			</tr>
		</thead>
		<tbody>
		<?php

	foreach($data as $seq_id => $domain_list){
		echo "<tr><td><a class='table_link' href='architecture.php?id=" . $seq_id . "&db=" . $db_table . "'>" . $seq_id . "</a></td>";
		echo "<td>";
		foreach($domain_list as $domain_id){
			$link_id = 'http://pfam.xfam.org/family/' . $domain_id;
			echo "<a class = 'table_link' href=".$link_id." target='_blank'>  " . $domain_id . "  </a>";
		}
		echo "</td></tr>";
	}
	echo "</tbody></table>";
	$conn->close();
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
	if($dama == 'true'){
		echo 'You choose to use DAMA to your dataset with a cut-off e-value equal to: ' . $DAMA_evalue;}
	else if($dama == 'false'){
		echo 'DAMA was not used to determine the architecture.';}
	echo '<br><br>';
	?>
	Output explanation:
	If the model species is 'unavailable', it is because the most reliable model was HMMer-3
	</div>
	</div>
	</section>
<?php include("./includes/footer.php"); ?>
