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
	echo "<svg height='40' width='100%' style='border:1px dashed #ccc' overflow='scroll'>";
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$length = $row['Seq_length'];
			$start = $row['Seq_start'];
			$stop = $row['Seq_stop'];
			$pfam = $row['DomainID'];
			$sql = "SELECT DISTINCT PFAM32.PFAM_acc_nb, PFAM32.Family, PFAM32.Clan_acc_nb, PFAM32.Clan FROM PFAM32 WHERE PFAM32.PFAM_acc_nb='".$pfam."'";
			$result2 = mysqli_query($mysqli, $sql);
			$row2 = mysqli_fetch_assoc($result2);
			$nb_aa = ($stop-$start);
			$width = ($nb_aa*100)/$length;
			$scaled_start = ($start*100)/$length;
			$scaled_stop = ($stop*100)/$length;
			$color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
			echo "<g><a xlink:href='http://pfam.xfam.org/family/".$pfam."' target='_blank'><rect x='".$scaled_start."%' y='5' width='". $width ."%' height='30' style=' fill:".$color."; fill-opacity:0.7;stroke-width:1;stroke:;'><title>PFAM Acc Number: ".$pfam."\nFamily: ".$row2['Family']."\n\nPosition: ".$start."-".$stop." (".$nb_aa."aa)\n\nClan Acc Number: ".$row2['Clan_acc_nb']."\nClan: ".$row2['Clan']."\n\nModel species: ".$row['Model species']."\nE-value: ".$row['e_value']."\nBitscore: ".$row['Bitscore']."\nAccuracy: ".$row['Accuracy']."</title></rect>";
			echo "<text class='pfam_link' x='". $scaled_start ."%' y='25' font-size='15' fill='black'>".$pfam."</text></a></g>";

		}
	} else {
		echo "0 results";
	}
	echo "</svg>";
	
	echo "<svg height='40' width='100%'><line x1='1' y1='1' x2='100%' y2='1' stroke='#D8D8D8' stroke-width='20' stroke-linecap='butt' />";

	echo "<text x='0.1%' y='25' fill='black'>1</text><line x1='1' y1='0' x2='1' y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=20.1% y='25' fill='black'>". round($length/5) ."</text><line x1=20% y1='0' x2=20% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=40.1% y='25' fill='black'>". round(2*($length/5)) ."</text><line x1=40% y1='0' x2=40% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=60.1%  y='25' fill='black'>". round(3*($length/5)) ."</text><line x1=60%  y1='0' x2=60% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=80.1% y='25' fill='black'>". round(4*($length/5)) ."</text><line x1=80% y1='0' x2=80% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";

	echo "<text x='98%' y='25' fill='black'>".$length."</text><line x1='99.9%' y1='0' x2='99.9%' y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/></svg>";

	$mysqli -> close();
	?>
	</section>
<?php include("./includes/footer.php"); ?>