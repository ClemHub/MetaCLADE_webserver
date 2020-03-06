<?php include("./includes/header.php"); ?>
<?php include("./includes/menu.php"); ?>

	<section id = 'contact'>
	<h2> Architecture </h2>
	<?php
	$seq_id = $_GET['id'];
	echo "<h4> Sequence ID: " . $seq_id . " <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Move your mouse over the colored domain to show more detailed information about it.</span></span></h4>";
	$username = "root"; 
	$password = "myclade"; 
	$database = "METACLADE"; 
	$mysqli = new mysqli("localhost", $username, $password, $database);
	if (!$mysqli) {
		die("Connection failed: " . mysqli_connect_error());
	}
	$database = $_GET['db'];
	$sql = "SELECT * FROM ". $database ." WHERE SeqID='".$seq_id."'";
	$result = mysqli_query($mysqli, $sql);
	echo "<svg height='30' width='2000' style='border:1px dashed #ccc' overflow='scroll'>";
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$length = $row['Seq_length'];
			$start = $row['Seq_start'];
			$stop = $row['Seq_stop'];
			$pfam = $row['DomainID'];
			$width = (($stop-$start)*2000)/$length;
			$start = (2000/$length)*$start;
			$color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
			//echo 'Length:'.$length.'<br>';
			//echo 'Width:'.$width.'<br>';
			//echo 'Start:'.$start.'<br>';
			//echo 'Stop:'.$stop.'<br><br>';
			echo "<g><a xlink:href='http://pfam.xfam.org/family/".$pfam."' target='_blank'><rect x='".$start."' y='5' width='". $width ."' height='20' style='fill:".$color.";fill-opacity:0.7;stroke-width:1;stroke:;'/>";
			echo "<text x='".$start."' y='20' font-size='15'fill='black'>".$pfam."</text></a></g>";
		}
	} else {
		echo "0 results";
	}
	echo "</svg>";
	
	echo "<svg height='40' width='2050'><line x1='1' y1='1' x2='2000' y2='1' stroke='#D8D8D8' stroke-width='20' stroke-linecap='butt' />";

	echo "<text x='3' y='25' fill='black'>1</text><line x1='1' y1='0' x2='1' y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=". round(2005/5) ." y='25' fill='black'>". round($length/5) ."</text><line x1=". round(2000/5) . " y1='0' x2=". round(2000/5) . " y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=". round(2*(2005/5)) ." y='25' fill='black'>". round(2*($length/5)) ."</text><line x1=". round(2*(2000/5)) ." y1='0' x2=". round(2*(2000/5)) ." y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=". round(3*(2005/5)) ."  y='25' fill='black'>". round(3*($length/5)) ."</text><line x1=". round(3*(2000/5)) ."  y1='0' x2=". round(3*(2000/5)) ."  y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=". round(4*(2005/5)) ." y='25' fill='black'>". round(4*($length/5)) ."</text><line x1='". round(4*(2000/5)) ."' y1='0' x2='". round(4*(2000/5)) ."' y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";

	echo "<text x='2005' y='25' fill='black'>".$length."</text><line x1='2000' y1='0' x2='2000' y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/></svg>";

	$mysqli -> close();

	$dama = $_POST['dama'];
	$e_value = $_POST['evalue_range'];
	$DAMA_evalue = $_POST['dama_evalue_range'];
	echo '<br>Dama: '.$dama.'<br/>';
	echo 'E-value: '.$e_value.'<br/>';
	echo 'DAMA e-value: '.$DAMA_evalue.'<br/>';

	?>

	</section>

<?php include("./includes/footer.php"); ?>