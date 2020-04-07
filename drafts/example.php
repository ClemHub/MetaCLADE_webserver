<?php include("./includes/cookies.php"); ?>
<?php include("./includes/header.php"); ?>

	<section id = 'example'>
	<h2> Example </h2>
	<?php 
	$dama = $_SESSION['dama'];
	if($dama == 'true'){
		$DAMA_evalue = '1e<sup>-10</sup>';
		//$name_file = 'http://localhost:8888/MetaCLADE_webserver/data/examplewithDAMA.csv';
		$name_file = $appurl.'/MyCLADE/jobs/example_withDAMA/testDataSet/results/3_arch/testDataSet.arch.txt';
		$db_table = 'Example_withDAMA';}
	else {
		//$name_file = 'http://localhost:8888/MetaCLADE_webserver/data/examplewithoutDAMA.csv';
		$name_file = $appurl.'/MyCLADE/jobs/example_withoutDAMA/testDataSet/results/3_arch/testDataSet.arch.txt';
		$db_table = 'Example_withoutDAMA';}
	$e_value = '1e<sup>-3</sup>';
	$username = "blachon"; 
	$password = "myclade"; 
	$database = "METACLADE"; 
	$conn = mysqli_connect($hostname, $username, $password, $database);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "SELECT * FROM ". $db_table . " ORDER BY SeqID, Seq_start";
	$result = $conn->query($sql);

	//Button that allows the user to download the text files with the results
	echo "<a id = 'dl_link' href=".$name_file." download=results.csv><i class='fa fa-download'></i>Download the CSV resulting file</a>";
	?>

	<!-- Table with the results -->	
	<div class='table_container'>
	<table>
	<thead>
		<tr>
		<th class='table_header'>Sequence ID <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Click on the sequence ID to see the architecture.</span></span></th>
		<th class='table_header'>Domain position<br>along the sequence</th>
		<th class='table_header'>Domain Id</th>
		<!--<th class='table_header'>Model Id</th>-->
		<th class='table_header'>Model species</th>
		<th class='table_header'>E-Value</th>
		<th class='table_header'>Bitscore</th>
		<th class='table_header'>Accuracy</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$old_seq_id = '';
	if ($result -> num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$new_seq_id = $row["SeqID"];
			$domain_id = $row["DomainID"];
			$sql2 = "SELECT DISTINCT PFAM32.Family FROM PFAM32 WHERE PFAM32.PFAM_acc_nb='".$domain_id."'";
			$domain_result = mysqli_query($conn, $sql2);
			$family = mysqli_fetch_assoc($domain_result);
			$family = $family['Family'];
			$link_id = 'http://pfam.xfam.org/family/' . $domain_id;
			if($old_seq_id == ''){
				$request = "SELECT COUNT(1) FROM " . $db_table . " WHERE SeqID='" . $new_seq_id . "'";
				$rowspan = $conn->query($request);
				$rowspan = $rowspan->fetch_assoc();
				echo "<tr><td rowspan=" . $rowspan['COUNT(1)'] . "><a class='table_link' href='architecture.php?id=" . $new_seq_id . "&db=" . $db_table . "'>" . $new_seq_id . "</a></td>";
				$old_seq_id = $new_seq_id;}
			else if($new_seq_id != $old_seq_id){
				echo '</tbody>';
				echo '<tbody><tr>';
				$request = "SELECT COUNT(1) FROM " . $db_table . " WHERE SeqID='" . $new_seq_id . "'";
				$rowspan = $conn->query($request);
				$rowspan = $rowspan->fetch_assoc();
				echo "<td rowspan=".$rowspan['COUNT(1)']."><a class='table_link' href='architecture.php?id=" . $new_seq_id . "&db=". $db_table . "'>" . $new_seq_id . "</a></td>";
				$old_seq_id = $new_seq_id;}
			else{echo '<tr>';}
			echo "<td>" . $row["Seq_start"] . " - " . $row["Seq_stop"]. "</td>";
			echo "<td><a class = 'table_link' href=" . $link_id . " target='_blank'>" . $family . "<br>(" . $domain_id . ")</a></td>";
			//echo "<td>" . $row["ModelID"]. "</td>";
			echo "<td class='species_name'>" . $row["Model species"]. "</td>";
			echo "<td>" . $row["e_value"]. "</td>";
			echo "<td>" . $row["Bitscore"]. "</td>";
			echo "<td>" . $row["Accuracy"]. "</td></tr>";}
		echo "</tbody></table>";}
	$conn->close();
	?>
	</div>

	<!--Information button--> 
	<div class='info'>
	<input type='button' class='bouton_info' value='Info' onclick='close_open_info(this);' />
	<div class='contenu_info'>
	Parameters of the test:
	<?php
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