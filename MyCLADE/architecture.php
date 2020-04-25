<?php include("./includes/header.php"); ?>
<?php include("./includes/menu.php"); ?>

	<section id = 'architecture_section'>
	<div id='architecture'>
	<h2> Architecture </h2>
	<?php
	$seq_id = $_GET['id'];
	$job_id = $_GET['job_id'];

	$db_name = $approot.'/data/MetaCLADE.db';
	$db = new SQLite3($approot.'/data/MetaCLADE.db');

	$name_file = $approot."/MyCLADE/jobs/".$job_id."/".$job_id."/results/3_arch/".$job_id.".arch.txt";
	echo "<h4> Sequence ID: " . $seq_id . " <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Move your mouse over the colored domain to show more detailed information about it.</span></span></h4>";
	$pfam_list = array();
	echo "<svg height='40' width='100%' style='border:1px dashed #ccc' overflow='scroll'>";
	$file_content = fopen($name_file, "r");
	while(!feof($file_content)){
		$line = fgets($file_content);
		$exploded_line = explode("\t", $line);
		if($exploded_line[0]==$seq_id){{
			$length = $exploded_line[3];
			$start = $exploded_line[1];
			$stop = $exploded_line[2];
			$pfam = $exploded_line[4];
			$row = $db->query("SELECT DISTINCT PFAM32.PFAM_acc_nb, PFAM32.Family, PFAM32.Clan_acc_nb, PFAM32.Clan FROM PFAM32 WHERE PFAM32.PFAM_acc_nb='".$pfam."'");
			$row = $row->fetchArray();
			$nb_aa = ($stop-$start);
			$width = ($nb_aa*100)/$length;
			$scaled_start = ($start*100)/$length;
			$scaled_stop = ($stop*100)/$length;
			$color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
			echo "<g><a xlink:href='http://pfam.xfam.org/family/".$pfam."' target='_blank'><rect x='".$scaled_start."%' y='5' width='". $width ."%' height='30' style=' fill:".$color."; fill-opacity:0.7; stroke-width:1; stroke:3'>";
			echo "<title>PFAM Acc Number: ".$pfam."\nFamily: ".$row['Family']."\n\nPosition: ".$start."-".$stop." (".$nb_aa."aa)\n\nClan Acc Number: ".$row['Clan_acc_nb']."\nClan: ".$row['Clan']."\n\nModel species: ".$exploded_line[12]."\nE-value: ".$exploded_line[9]."\nBitscore: ".$exploded_line[10]."\nAccuracy: ".$exploded_line[11]."</title></rect>";
			echo "<title>PFAM Acc Number: ".$pfam."\n\nPosition: ".$start."-".$stop." (".$nb_aa."aa)\n\nModel species: ".$exploded_line[12]."\nE-value: ".$exploded_line[9]."\nBitscore: ".$exploded_line[10]."\nAccuracy: ".$exploded_line[11]."</title></rect>";
			echo "<text x='". $scaled_start ."%' y='25' style='font-size:15px; font-size-adjust: 0.5; fill:white; font-weight:bold; mix-blend-mode: exclusion;' >".$pfam."</text></a></g>";
			$pfam_list[$pfam]=$exploded_line;}}
		}
	echo "</svg>";
	
	echo "<svg height='40' width='100%'><line x1='1' y1='1' x2='100%' y2='1' stroke='#D8D8D8' stroke-width='20' stroke-linecap='butt' />";

	echo "<text x='0.1%' y='25' fill='black'>1</text><line x1='1' y1='0' x2='1' y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=20.1% y='25' fill='black'>". round($length/5) ."</text><line x1=20% y1='0' x2=20% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=40.1% y='25' fill='black'>". round(2*($length/5)) ."</text><line x1=40% y1='0' x2=40% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=60.1%  y='25' fill='black'>". round(3*($length/5)) ."</text><line x1=60%  y1='0' x2=60% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=80.1% y='25' fill='black'>". round(4*($length/5)) ."</text><line x1=80% y1='0' x2=80% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";

	echo "<text x='98%' y='25' fill='black'>".$length."</text><line x1='99.9%' y1='0' x2='99.9%' y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/></svg>";

	?>
	</div>
	<div class='info'>
	<input type='button' class='bouton_info' value='Results informations:' onclick='close_open_info(this);' />
	<div class='contenu_info'>
	<div class='table_container' id='architecture_data'>
	<table id='data_table'>
	<thead>
		<tr>
		<th class='table_header'>Domain ID</th>
		<th class='table_header'>Family</th>
		<th class='table_header'>Domain position<br>along the sequence</th>
		<!--<th class='table_header'>Model Id</th>-->
		<th class='table_header'>Model species <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>If the model species is 'unavailable', it is because the most reliable model was HMMer-3.</span></span></h4></th>	
		<th class='table_header'>E-Value <button class='sort_button' onclick='sortTable(4)'><i class='fas fa-caret-down'></i></button></th>
		<th class='table_header'>Bitscore <button class='sort_button' onclick='sortTable(5)'><i class='fas fa-caret-down'></i></button></th>
		<th class='table_header'>Accuracy <button class='sort_button' onclick='sortTable(6)'><i class='fas fa-caret-down'></i></button></th>
		</tr>
	</thead>
	
	<?php
	//$test=Array('PF00001 ' => Array('SeqID' => 'tr|A0A072NB93|A0A072NB93_9DEIO', 'Seq_start' => 591, 'Seq_stop' => 753, 'Seq_length' => 766, 'DomainID' => 'PF00001', 'ModelID' => 'A0A0G0AUB5_9BACT_52-217', 'Model_start' => 1, 'Model_stop' => 162, 'Model_size' => 163, 'e_value' => 5.6e-75, 'Bitscore' => 238.8, 'Accuracy' => 0.99, 'Model species' => 'Candidatus Roizmanbacteria bacterium GW2011_GWC2_34_23'), 'PF00004' => Array ( 'SeqID' => 'tr|A0A072NB93|A0A072NB93_9DEIO', 'Seq_start' => 12, 'Seq_stop' => 141, 'Seq_length' => 766, 'DomainID' => 'PF00004', 'ModelID' => 'A6FVP5_9RHOB_1-137', 'Model_start' => 1, 'Model_stop' => 129, 'Model_size' => 130, 'e_value' => 8.2e-36, 'Bitscore' => 111.3, 'Accuracy' => 0.97, 'Model species' => 'Roseobacter sp. AzwK-3b'), 'PF03441' => Array ( 'SeqID' => 'tr|A0A072NB93|A0A072NB93_9DEIO', 'Seq_start' => 314, 'Seq_stop' => 465, 'Seq_length' => 766, 'DomainID' => 'PF03441', 'ModelID' => 'F3L3D9_9GAMM_269-463', 'Model_start' => 4, 'Model_stop' => 153, 'Model_size' => 155, 'e_value' => 6.2e-49, 'Bitscore' => 153.4, 'Accuracy' => 0.94, 'Model species' => 'Halieaceae bacterium IMCC3088'));
	
	foreach($pfam_list as $pfam => $data){
		echo '<tbody>';
		$link_id = 'http://pfam.xfam.org/family/' . $pfam;
		//$nb=0;
        echo "<tr><td><a class = 'table_link' href=" . $link_id . " target='_blank'>".$pfam."</a></td>";
        $row = $db->query("SELECT DISTINCT PFAM32.Family FROM PFAM32 WHERE PFAM32.PFAM_acc_nb='".$pfam."'");
        $row = $row->fetchArray();
        echo "<td>" . $row['Family']."</td>";
		echo "<td>" . $data[1] . " - " . $data[2]. "</td>";
		echo "<td class='species_name'>" . $data[12]. "</td>";
		echo "<td>".$data[9]."</td>";
		echo "<td>" . $data[10]. "</td>";
		echo "<td>" . $data[11]. "</td></tr>";
		echo '</tbody>';}
	echo '</table>';
	?>
	</div>
	</div>
	</div>
	<div class='info'>
	<input type='button' class='bouton_info' value='GO-terms:' onclick='close_open_info(this);' />
	<div class='contenu_info'>
	<div class='table_container' id='goterms'>
	<table id='go_terms_table'>
	<thead>
		<tr>
		<th class='table_header'>Domain ID</th>
		<th class='table_header'>Family</th>
		<th class='table_header'>GO Terms</th>
		</tr>
	</thead>
	
	<?php
	
	foreach($pfam_list as $pfam => $data){
		echo '<tbody>';
		$link_id = 'http://pfam.xfam.org/family/' . $pfam;
		$row = $db->query("SELECT * FROM GO_terms WHERE Domain='".$pfam."'");
		$row = $row->fetchArray();
		$nb = mysqli_num_rows($rowspan);
		echo "<tr><td rowspan=".$nb."><a class = 'table_link' href=" . $link_id . " target='_blank'>".$pfam."</a></td>";
		if ($nb > 0) {
			$i = 0;
			while($row = mysqli_fetch_assoc($rowspan)){
				if($i==0){
					echo "<td rowspan=".$nb.">" . $row['Family']."</td>";}
				echo "<td>" . $row['GO_term'] . '</td></tr>';
				$i++;
			}}
		else if ($nb == 0){
			$row2 = $db->query("SELECT DISTINCT PFAM32.Family FROM PFAM32 WHERE PFAM32.PFAM_acc_nb='".$pfam."'");
			$row2 = $row2->fetchArray();
			echo "<td>" . $row2['Family']."</td>";
			echo "<td>Not available</td></tr>";}
		echo '</tbody>';
		}
	
	echo '</table>';
	$database->close();
	?>
	</div>
	</div>
	</div>
	</section>
<?php include("./includes/footer.php"); ?>
