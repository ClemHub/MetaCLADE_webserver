<?php include("./includes/header.php"); ?>
<?php include("./includes/menu.php"); ?>

	<section id = 'example'>
	<h2> Example </h2>
	
	<?php 

	$username = "blachon"; 
	$password = "myclade"; 
	$database = "METACLADE"; 
	$conn = mysqli_connect("localhost", $username, $password, $database);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);}?>

	<a id = 'dl_link' href=".$name_file." download=results.csv><i class='fa fa-download'></i>Download the CSV resulting file</a>
	<div class='table_container'>
	<table>
	<thead>
		<tr>
		<th class='table_header'>Sequence ID <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Click on the sequence ID to see the architecture.</span></span></th>
		<th class='table_header'>Sequence start</th>
		<th class='table_header'>Sequence End</th>
		<th class='table_header'>Domain Id</th>
		<th class='table_header'>Model Id</th>
		<th class='table_header'>E-Value</th>
		</tr>
	</thead>
	<tbody>

	<?php
	$dama = $_POST['dama'];
	if($dama == 'true'){
		$database = 'Example_withDAMA';}
	else {
		$database = 'Example_withoutDAMA';}
	$sql = "SELECT * FROM ". $database;
	$result = $conn->query($sql);

	if ($result -> num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$seq_id = $row["SeqID"];
			$link = 'http://pfam.xfam.org/family/'. $row["DomainID"];
			echo "<tr><td><a href='architecture.php?id=" . $seq_id . "&db=". $database ."'>" . $seq_id . "</a></td>";
			echo "<td>" . $row["Seq_start"] . "</td>";
			echo "<td>" . $row["Seq_stop"]. "</td>";
			echo "<td><a class = 'pfam_link' href=" . $link . " target='_blank'>" . $row["DomainID"] . "</a></td>";
			echo "<td>" . $row["ModelID"]. "</td>";
			echo "<td>" . $row["e_value"]. "</td></tr>";
		}
		echo "</tbody></table>";}

	$conn->close();
	?>

	<div class='info'>
	<input type='button' class='bouton_info' value='Info' onclick='close_open_info(this);' />
	<div class='contenu_info'>
	Informations test:<br/>
	
	</div>
	</div>
	</div>
	</section>

<?php include("./includes/footer.php"); ?>