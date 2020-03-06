<?php include("./includes/header.php"); ?>
	
	<section>
	<h2>Results</h2>	
	<?php

		$sequences = $_POST['sequences'];
		$erreur = 'Everything worked';
		$e_value = $_POST['evalue_range'];
		$DAMA_evalue = $_POST['dama_evalue_range'];
		if($sequences == ""){
			$directory = './fasta_file/';
			$file = basename($_FILES['fasta_file']['name']);
			$taille_maxi = 10000000;
			$taille = filesize($_FILES['fasta_file']['tmp_name']);
			$extensions = array('.txt', '.fsa', '.fasta', '.fa');
			$extension = strrchr($_FILES['fasta_file']['name'], '.'); 

			if(!in_array($extension, $extensions))
				{$erreur = 'We only accept .fsa, .fasta .fa or .txt files.';}
			if($taille>$taille_maxi)
				{$erreur = 'The file is too big.';}
			if(!isset($erreur)){	
				if(move_uploaded_file($_FILES['fasta_file']['tmp_name'], $directory . 'fasta_tmp.fa'))
					{$data_type = 'File: upload.<br/>';}
				else
					{$data_type = 'File: error - not uploaded.<br/>';}}}
		else{
			$data_type = 'Sequences entered manually.<br/>';
			file_put_contents('./fasta_file/fasta_tmp.fa', $sequences);}

		$dama = $_POST['dama'];


		/* appel du script metaclade
		if($dama){./metaclade2/metaclade2 -i ./fasta_file/fasta_temp.fa -N results -W ./ -j 2}
		else{./metaclade2/metaclade2 -i ./fasta_file/fasta_temp.fa -a -N results -W ./ -j 2}
		*/
		
		//Presentation des resultats sous forme de tableau
		if($dama == 'true'){
			$name_file = './metaclade2/output/results/3_arch/test_withDAMA.arch.txt';
			$data = file($name_file);}
		else {
			$name_file = './metaclade2/output/results/3_arch/test_withoutDAMA.arch.txt';
			$data = file($name_file);}

		$username = "blachon"; 
		$password = "myclade"; 
		$database = "METACLADE"; 
		$conn = mysqli_connect("localhost", $username, $password, $database);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);}
	
		$sql = "DELETE FROM MetaCLADE_results";
		$request = $conn->query($sql);
		
		convert_txtTOcsv($conn, $name_file);
		
		$sql = "LOAD DATA LOCAL INFILE '/var/www/html/php_code/results/results.csv' INTO TABLE MetaCLADE_results";
		$request = $conn->query($sql);

		
		if ($data){
			echo "<a id = 'dl_link' href=".$name_file." download=results.csv><i class='fa fa-download'></i>Download the CSV resulting file</a>";
			?>
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
			$seq_id = array();
			foreach($data as $line)
				{$line = preg_split("/[\s,]+/", $line);
				array_push($seq_id, $line[0]);}
			$count_id = array_count_values($seq_id);

			foreach($data as $line){
				$s_line = preg_split("/[\s,]+/", $line);
				echo "<tr>";
				$i = 0;
				foreach($s_line as $item){
					if($i == 0 and $count_id[$item]>0)
					
						{echo "<td rowspan='".$count_id[$item]."'><a href='architecture.php?id=".$item."&db=MetaCLADE_results'>". $item ."</a></td>";
						$count_id[$item]=0;}
					else if(in_array($i, array(1, 2, 5)))
						{echo "<td>" . $item . "</td>";}
					else if($i == 4){
						$link = 'http://pfam.xfam.org/family/'.$item;
						echo "<td> <a class = 'pfam_link' href=".$link." target='_blank'>".$item."</a> </td>";}
					else if ($i == 9)
						{echo "<td>" . $item . "</td>";}
					$i++;}
				echo "</tr>";
			}		
			echo "</tbody>";
			echo "</table>";
			fclose($results_file);}
	echo "<div class='info'>";
	echo "<input type='button' class='bouton_info' value='Info' onclick='close_open_info(this);' />";
	echo "<div class='contenu_info'>";
	echo 'Informations entered by the user:<br/>';
	echo 'Dama: '.$dama.'<br/>';
	echo 'Erreur: '.$erreur.'<br/>';
	echo 'Data: '.$data_type.'<br/>';
	echo 'E-value: '.$e_value.'<br/>';
	echo 'DAMA e-value: '.$DAMA_evalue.'<br/>';
	echo "</div>";
	echo "</div>";
	echo "</div>";
	?>
	</section>

<?php include("./includes/footer.php"); ?>