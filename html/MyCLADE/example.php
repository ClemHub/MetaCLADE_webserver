<?php include("./includes/header.php"); ?>

	<section id = 'example'>
	<h2> Example </h2>
	
	<?php 
	$dama = $_POST['dama'];
	if($dama == 'true'){
		$name_file = 'http://localhost/data/examplewithDAMA.csv';
		$db_table = 'Example_withDAMA';}
	else {
		$name_file = 'http://localhost/data/examplewithoutDAMA.csv';
		$db_table = 'Example_withoutDAMA';}

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
		<th class='table_header'>Sequence start</th>
		<th class='table_header'>Sequence End</th>
		<th class='table_header'>Domain Id</th>
		<th class='table_header'>Model Id</th>
		<th class='table_header'>Model species</th>
		<th class='table_header'>E-Value</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$old_seq_id = '';
	if ($result -> num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$new_seq_id = $row["SeqID"];
			$link = 'http://pfam.xfam.org/family/' . $row["DomainID"];
			if($new_seq_id != $old_seq_id){
				$request = "SELECT COUNT(1) FROM " . $db_table . " WHERE SeqID='" . $new_seq_id . "'";
				$rowspan = $conn->query($request);
				$rowspan = $rowspan->fetch_assoc();
				echo "<tr><td rowspan=".$rowspan['COUNT(1)']."><a href='architecture.php?id=" . $new_seq_id . "&db=". $db_table . "'>" . $new_seq_id . "</a></td>";
				$old_seq_id = $new_seq_id;}
			echo "<td>" . $row["Seq_start"] . "</td>";
			echo "<td>" . $row["Seq_stop"]. "</td>";
			echo "<td><a class = 'pfam_link' href=" . $link . " target='_blank'>" . $row["DomainID"] . "</a></td>";
			echo "<td>" . $row["ModelID"]. "</td>";
			echo "<td>" . $row["Model species"]. "</td>";
			echo "<td>" . $row["e_value"]. "</td></tr>";}
		echo "</tbody></table>";}
	$conn->close();
	?>

	<!--Information button--> 
	<div class='info'>
	<input type='button' class='bouton_info' value='Info' onclick='close_open_info(this);' />
	<div class='contenu_info'>
	Informations test:<br/>
	<?php echo $name_file?>
	</div>
	</div>
	</div>
	</section>
<?php include("./includes/footer.php"); ?>