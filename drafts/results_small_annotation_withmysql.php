<?php include("./includes/header.php"); ?>

	<section>
	<h2>Results</h2>
	<?php
		$sequences = $_POST['sequences'];
		$erreur = 'Everything worked';
		$e_value = $_POST['evalue_range'];
		$DAMA_evalue = $_POST['dama_evalue_range'];
		$sequences = $_POST['sequences'];
		if($sequences == ""){
			$directory = './uploads/';
			$file = basename($_FILES['fasta_file']['name']);
			$taille_maxi = 10000000;
			$taille = filesize($_FILES['fasta_file']['tmp_name']);
			$extensions = array('.txt', '.fsa', '.fasta', '.fa');
			$extension = strrchr($_FILES['fasta_file']['name'], '.'); 
			if(!in_array($extension, $extensions))
				{$erreur = 'We only accept .fsa, .fasta or .txt files.';}
			if($taille>$taille_maxi)
				{$erreur = 'The file is too big.';}
			if(!isset($erreur)){
				if(move_uploaded_file($_FILES['fasta_file']['tmp_name'], $directory . 'fasta_tmp.fa'))
					{$data_type = 'File: upload.<br/>';}
				else
					{$data_type = 'File: error - not uploaded.<br/>';}}}
		else{
			$data_type = 'Sequences entered manually.<br/>';
			file_put_contents('./uploads/fasta_tmp.fa', $sequences);}
		$dama = $_POST['dama'];
		$pfam = $_POST['pfam_domains'];

		/* appel du script metaclade
		if($dama){./metaclade2/metaclade2 -i ./uploads/fasta_temp.fa -N results -d $pfam -W ../ -j 2}
		else{./metaclade2/metaclade2 -i ./uploads/fasta_temp.fa -a -N results -d $pfam -W ../ -j 2}
		*/

		
		//Presentation des resultats sous forme de tableau
		if($dama == 'true'){
			$name_file = './metaclade2/output/results/3_arch/test_withDAMA.arch.txt';
			$data = file($name_file);}
		else {
			$name_file = './metaclade2/output/results/3_arch/test_withoutDAMA.arch.txt';
			$data = file($name_file);}
		natsort($data);

		$username = "blachon"; 
		$password = "myclade"; 
		$database = "METACLADE"; 
		$mysqli = new mysqli("localhost", $username, $password, $database); 
		$sql = "DELETE FROM Results";
		if ($mysqli->query($sql) === TRUE) {
			$rm_msg = "Table MyGuests removed successfully";}
		else{
			$rm_msg =  "Error removing table: " . $mysqli->error;}
		$sql = "ALTER TABLE Results AUTO_INCREMENT = 1";
		if ($mysqli->query($sql) === TRUE) {
			$auto_incr = "Auto increment put to 1";}
		else{
			$auto_incr =  "Error to the autoincrement " . $mysqli->error;}		
		$column_names = array('SeqID', 'Seq_start', 'Seq_stop', 'Seq_length', 'DomainID', 'ModelID', 'Model_start', 'Model_stop', 'Model_size', 'e_value', 'Bitscore', 'Accuracy');

		if ($data){
			echo "<a id = 'dl_link' href=".$name_file." download=results.csv><i class='fa fa-download'></i>Download the CSV resulting file</a>";
			echo "<div style='overflow-x:auto;'>";
			echo "<table>";
			
			echo "<thead>
			<tr>
			<th class='table_header'>Sequence ID</th>
			<th class='table_header'>Sequence start</th>
			<th class='table_header'>Sequence End</th>
			<th class='table_header'>Sequence Length</th>
			<th class='table_header'>Domain Id</th>
			<th class='table_header'>Model Id</th>
			<th class='table_header'>E-Value</th>
			</tr>
			</thead>
			<tbody>";
			$seq_id = array();
			foreach($data as $line)
				{$line = preg_split("/[\s,]+/", $line);
				array_push($seq_id, $line[0]);}
			$count_id = array_count_values($seq_id);
			$c = 1;
			foreach($data as $line){
				$s_line = preg_split("/[\s,]+/", $line);
				$s_line = array_slice($s_line, 0, 12);
				echo "<tr>";
				$i = 0;
				foreach($s_line as $item){
					$item_name = $column_names[$i];

					if($i == 0 and $count_id[$item]>0)
						{
						echo "<td rowspan='".$count_id[$item]."'>" . $item . "</td>";
						$count_id[$item]=0;}
					else if(in_array($i, array(1, 2, 3, 5)))
						{echo "<td>" . $item . "</td>";}
					else if($i == 4){
						$link = 'http://pfam.xfam.org/family/'.$item;
						echo "<td> <a class = 'pfam_link' href=".$link." target='_blank'>".$item."</a> </td>";}
					else if ($i == 9)
						{echo "<td>" . $item . "</td>";}
					if($i==0){
						$sql = "INSERT INTO `Results` (`id`, `SeqID`, `Seq_start`, `Seq_stop`, `Seq_length`, `DomainID`, `ModelID`, `Model_start`, `Model_stop`, `Model_size`, `e_value`, `Bitscore`, `Accuracy`) VALUES (NULL, '$item', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);";}
					else{
						$sql = "UPDATE `Results` SET `$item_name` = '$item' WHERE `Results`.`id` = $c";}
					if ($mysqli->query($sql) === TRUE) {
						$new_rec = "New record created successfully<br>";
					} else {
						$new_rec = "Error: " . $sql . "<br>" . $mysqli->error. "<br>";
					}
					$i++;}
				echo "</tr>";
				$c++;
			}		
			echo "</tbody>
			</table>";
			fclose($results_file);}
	

			echo "<div class='info'>";
			echo "<input type='button' class='bouton_info' value='Info' onclick='close_open_info(this);' />";
			echo "<div class='contenu_info'>";
			echo 'Informations entered by the user:<br/>';
			echo 'PFAM domains selected: '.$pfam.'<br/>';
			echo 'Dama: '.$dama.'<br/>';
			echo 'Erreur: '.$erreur.'<br/>';
			echo 'Data: '.$data_type.'<br/>';
			echo 'E-value: '.$e_value.'<br/>';
			echo 'DAMA e-value: '.$DAMA_evalue.'<br/>';
			echo 'Remove: '.$rm_msg.'<br/>';
			echo 'Increment: '.$auto_incr.'<br/>';
			echo 'Creation of the table: '.$msg.'<br/>';
			echo 'New record: '.$new_rec.'<br/>';
			echo "</div>";
			echo "</div>";
			echo "</div>";
			$mysqli -> close();
	?>
	</section>
<?php include("./includes/footer.php"); ?>