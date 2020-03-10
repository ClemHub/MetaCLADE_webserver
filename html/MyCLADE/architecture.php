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
			$sql = "SELECT DISTINCT PFAM32.PFAM_acc_nb, PFAM32.Family, PFAM32.Clan_acc_nb, PFAM32.Clan FROM PFAM32 WHERE PFAM32.PFAM_acc_nb='".$pfam."'";
			$result2 = mysqli_query($mysqli, $sql);
			$row2 = mysqli_fetch_assoc($result2);
			$nb_aa = ($stop-$start);
			$width = ($nb_aa*2000)/$length;
			$scaled_start = (2000/$length)*$start;
			$color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
			echo "<g><a xlink:href='http://pfam.xfam.org/family/".$pfam."' target='_blank'><rect x='".$scaled_start."' y='5' width='". $width ."' height='20' style='fill:".$color.";fill-opacity:0.7;stroke-width:1;stroke:;'><title>PFAM Acc Number: ".$pfam."\nFamily: ".$row2['Family']."\n\nPosition: ".$start."-".$stop." (".$nb_aa."aa)\n\nClan Acc Number: ".$row2['Clan_acc_nb']."\nClan: ".$row2['Clan']."\n\nE-value: ".$row['e_value']."</title></rect>";
			echo "<text x='".$scaled_start."' y='20' font-size='15'fill='black'>".$pfam."</text></a></g>";
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
	?>


	<div class='info'>
	<input type='button' class='bouton_info' value='Info' onclick='close_open_info(this);' />
	<div class='contenu_info'>
	Informations test:<br/>
	<?php
	echo 'E-value: '.$_COOKIE['evalue'].'<br/>';
	if($_COOKIE['dama']){
		echo 'DAMA e-value: '.$_COOKIE['dama-evalue'].'<br/>'; 
	}
	else{echo 'DAMA was not to determine the architecture.';}
	
	
	?>
	</div>
	</div>


	

	</section>

<?php include("./includes/footer.php"); ?>