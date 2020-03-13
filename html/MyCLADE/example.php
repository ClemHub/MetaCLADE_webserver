<?php include("./includes/cookies.php"); ?>
<?php include("./includes/header.php"); ?>

	<section id = 'example'>
	<h2> Example </h2>
	
	<?php 
	$dama = $_SESSION['dama'];
	if($dama == 'true'){
		$DAMA_evalue = '1e<sup>-10</sup>';
		$name_file = 'http://localhost/data/examplewithDAMA.csv';
		$db_table = 'Example_withDAMA';}
	else {
		$name_file = 'http://localhost/data/examplewithoutDAMA.csv';
		$db_table = 'Example_withoutDAMA';}
	$e_value = '1e<sup>-3</sup>';

	$username = "blachon"; 
	$password = "myclade"; 
	$database = "METACLADE"; 
	$conn = mysqli_connect("localhost", $username, $password, $database);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);}

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
			$link = 'http://pfam.xfam.org/family/' . $row["DomainID"];
			if($old_seq_id == ''){
				$request = "SELECT COUNT(1) FROM " . $db_table . " WHERE SeqID='" . $new_seq_id . "'";
				$rowspan = $conn->query($request);
				$rowspan = $rowspan->fetch_assoc();
				echo "<tr><td rowspan=" . $rowspan['COUNT(1)'] . "><a class='table_link' href='architecture.php?id=" . $new_seq_id . "&db=" . $db_table . "'>" . $new_seq_id . "</a></td>";
				$old_seq_id = $new_seq_id;}
			else if($new_seq_id != $old_seq_id){
				echo '</tbody>';
				echo '<tbody>';
				$request = "SELECT COUNT(1) FROM " . $db_table . " WHERE SeqID='" . $new_seq_id . "'";
				$rowspan = $conn->query($request);
				$rowspan = $rowspan->fetch_assoc();
				echo "<tr><td rowspan=".$rowspan['COUNT(1)']."><a class='table_link' href='architecture.php?id=" . $new_seq_id . "&db=". $db_table . "'>" . $new_seq_id . "</a></td>";
				$old_seq_id = $new_seq_id;}
			echo "<td>" . $row["Seq_start"] . " - " . $row["Seq_stop"]. "</td>";
			echo "<td><a class = 'table_link' href=" . $link . " target='_blank'>" . $row["DomainID"] . "</a></td>";
			//echo "<td>" . $row["ModelID"]. "</td>";
			echo "<td id='species_name'>" . $row["Model species"]. "</td>";
			echo "<td>" . $row["e_value"]. "</td>";
			echo "<td>" . $row["Bitscore"]. "</td>";
			echo "<td>" . $row["Accuracy"]. "</td></tr>";}
		echo "</tbody></table>";}
	$conn->close();
	?>
	</div>

	<!--Information button--> 
	<div class='info'>
	<input type='button' class='bouton_info' value='Info' onclick='close_open_info(this);' /></legend>
	<div class='contenu_info'>
	<dd>Parameters of the test:</dd>
	<?php
	echo 'The MetaCLADE e-value applied: ' . $e_value . '<br>';
	if($dama == 'true'){
		echo 'You choose to use DAMA to your dataset with a cut-off e-value equal to: ' . $DAMA_evalue;}
	else if($dama == 'false'){
		echo 'DAMA was not used to determine the architecture.';}
	echo '<br><br>';
	?>
	<dd>Output explanation:</dd>
	If the model species is 'unavailable', it is because the most reliable model wa HMMer-3
	</div>
	</div>
	</section>
<?php include("./includes/footer.php"); ?>