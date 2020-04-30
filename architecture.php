<?php include("./includes/header.php"); ?>
<?php include("./includes/menu.php"); ?>

	<section id = 'architecture_section'>
	<div id='previous_page'><i class="fa fa-arrow-left"></i><a class="table_link" href="javascript:history.back()"> Main results page</a></div>
	<div id='architecture'>
	<h2> Architecture </h2>
	<?php
	$form = $_GET['form'];
	$seq_id = $_GET['id'];
	$job_id = $_GET['job_id'];

	$db = new SQLite3($approot.'/data/MetaCLADE.db');

	$name_file = $approot."/jobs/".$job_id."/".$job_id."/results/3_arch/".$job_id.".arch.txt";
	echo "<h4> Sequence ID: " . $seq_id . " <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>Move your mouse over the colored domain to show more detailed information about it.</span></span></h4>";
	$pfam_list = array();
	$pfam_name = array();
	$pfam_fam = array();
	$pfam_clan_nb = array();
	$pfam_clan = array();
	$model_species = array();
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
			$request = $db->query("SELECT * FROM GO_terms WHERE Domain='".$pfam."'");
			$go_terms = array();
			while($data = $request->fetchArray()){
				array_push($go_terms, $data);}
			$nb_aa = ($stop-$start);
			$width = ($nb_aa*100)/$length;
			$scaled_start = ($start*100)/$length;
			$scaled_stop = ($stop*100)/$length;
			$color = "rgb(".rand(150,255).",".rand(150,255).",".rand(150,255).")";
			echo "<g><a xlink:href='http://pfam.xfam.org/family/".$pfam."' target='_blank'><rect x='".$scaled_start."%' y='5' width='". $width ."%' height='30' style=' fill:".$color."; fill-opacity:0.7; stroke-width:1; stroke:3'>";
			echo "<title>PFAM Acc Number: ".$pfam."\nFamily: ".$row['Family']."\n\nPosition: ".$start."-".$stop." (".$nb_aa."aa)\n\nClan Acc Number: ".$row['Clan_acc_nb']."\nClan: ".$row['Clan']."\n\nModel species: ".$exploded_line[12]."\nE-value: ".$exploded_line[9]."\nBitscore: ".$exploded_line[10]."\nAccuracy: ".$exploded_line[11]."</title></rect>";
			echo "<text x='". $scaled_start ."%' y='25' style='font-size:15px; font-size-adjust: 0.5; fill:white; font-weight:bold; mix-blend-mode: exclusion;' >".$pfam."</text></a></g>";
			array_push($pfam_name, $pfam);
			array_push($pfam_fam, $row['Family']);
			array_push($pfam_clan_nb, $row['Clan_acc_nb']);
			array_push($pfam_clan, $row['Clan']);
			array_push($model_species, substr($exploded_line[12], 0, -1));
			array_push($pfam_list, $exploded_line);}}}

	echo "</svg>";
	
	echo "<svg height='40' width='100%'><line x1='1' y1='1' x2='100%' y2='1' stroke='#D8D8D8' stroke-width='20' stroke-linecap='butt' />";

	echo "<text x='0.1%' y='25' fill='black'>1</text><line x1='1' y1='0' x2='1' y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=20.1% y='25' fill='black'>". round($length/5) ."</text><line x1=20% y1='0' x2=20% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=40.1% y='25' fill='black'>". round(2*($length/5)) ."</text><line x1=40% y1='0' x2=40% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=60.1%  y='25' fill='black'>". round(3*($length/5)) ."</text><line x1=60%  y1='0' x2=60% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";
	
	echo "<text x=80.1% y='25' fill='black'>". round(4*($length/5)) ."</text><line x1=80% y1='0' x2=80% y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/>";

	echo "<text x='98%' y='25' fill='black'>".$length."</text><line x1='99.9%' y1='0' x2='99.9%' y2='20' style='stroke:rgb(0,0,0);stroke-width:2'/></svg></div>";

	?>

	<div class='info'>
	<input type='button' class='bouton_info' value='Results informations' onclick='close_open_info(this);' />
	<div class='contenu_info'>

	<div class='table_container' id='architecture_data'>
	<table id='data_table'>
	<thead>
		<tr>
		<th class='table_header'>Domain ID</th>
		<th class='table_header'>Family</th>
		<th class='table_header'>Domain position<br>along the sequence</th>
		<th class='table_header'>Model species <span class='tooltip'><i class='far fa-question-circle'></i><span class='tooltiptext'>If the model species is 'unavailable', it is because the most reliable model was HMMer-3.</span></span></h4></th>	
		<th class='table_header'>E-Value</th>
		<th class='table_header'>Bitscore</th>
		<th class='table_header'>Accuracy</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
		<?php
		echo "<th class='table_header'>";
		echo "<select id='domain-filter'>";
		echo "<option value=''>All</option>";
		foreach(array_unique($pfam_name) as $pfam){
			echo "<option value='".$pfam."'>".$pfam."</option>";}
		echo "</select></th>";

		echo "<th class='table_header'>";
		echo "<select id='family-filter'>";
		echo "<option value=''>All</option>";
		foreach(array_unique($pfam_fam)  as $fam){
			echo "<option value='".$fam."'>".$fam."</option>";}
		echo "</select></th>";

		echo "<th class='table_header'></th>";

		echo "<th class='table_header'>";
		echo "<select id='species-filter'>";
		echo "<option value=''>All</option>";
		foreach(array_unique($model_species) as $species){
			echo "<option value='".$species."'>".$species."</option>";}
		echo "</select></th>";	
		?>
		<th class='table_header'><input id='e-value_max' type='text' placeholder='E-value max'/></th>
		<th class='table_header'><input id='bitscore_min' type='text' placeholder='Bitscore min'/></th>
		<th class='table_header'><input id='acc_min' type='text' placeholder='Accuracy min'/></th>
		</tr>
	</tfoot>	
	<?php
	echo '<tbody>';
	foreach($pfam_list as $data){
		$link_id = 'http://pfam.xfam.org/family/' . $data[4];
        echo "<tr><td><a class = 'table_link' href=" . $link_id . " target='_blank'>".$data[4]."</a></td>";
        $row = $db->query("SELECT DISTINCT PFAM32.Family FROM PFAM32 WHERE PFAM32.PFAM_acc_nb='".$data[4]."'");
        $row = $row->fetchArray();
        echo "<td>" . $row['Family']."</td>";
		echo "<td>" . $data[1] . " - " . $data[2]. "</td>";
		echo "<td class='species_name'>" . $data[12]. "</td>";
		echo "<td>".$data[9]."</td>";
		echo "<td>" . $data[10]. "</td>";
		echo "<td>" . $data[11]. "</td></tr>";}
		echo '</tbody>';
	echo '</table>';
	?>

	</div>
	</div>
	</div>
	<div class='info'>
	<input type='button' class='bouton_info' value='GO-terms' onclick='close_open_info(this);' />
	<div class='contenu_info'>
	<div class='table_container' id='goterms'>
	<table id='go_terms_table'>
	<thead>
		<tr>
		<th class='table_header'>Domain ID</th>
		<th class='table_header'>Family</th>
		<th class='table_header'>Clan ID</th>
		<th class='table_header'>Clan Family</th>
		<th class='table_header'>GO Terms</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
		<?php
		echo "<th class='table_header'>";
		echo "<select id='go_domain-filter'>";
		echo "<option value=''>All</option>";
		foreach(array_unique($pfam_name) as $pfam){
			echo "<option value='".$pfam."'>".$pfam."</option>";}
		echo "</select></th>";

		echo "<th class='table_header'>";
		echo "<select id='go_family-filter'>";
		echo "<option value=''>All</option>";
		foreach(array_unique($pfam_fam)  as $fam){
			echo "<option value='".$fam."'>".$fam."</option>";}
		echo "</select></th>";

		echo "<th class='table_header'>";
		echo "<select id='clan-nb-filter'>";
		echo "<option value=''>All</option>";
		echo "<option value='NA'>NA</option>";
		$pfam_clan_nb = array_filter($pfam_clan_nb);
		foreach(array_unique($pfam_clan_nb) as $clan_nb){
			echo "<option value='".$clan_nb."'>".$clan_nb."</option>";}
		echo "</select></th>";

		echo "<th class='table_header'>";
		echo "<select id='clan-filter'>";
		echo "<option value=''>All</option>";
		echo "<option value='NA'>NA</option>";
		$pfam_clan = array_filter($pfam_clan);
		foreach(array_unique($pfam_clan)  as $clan){
			echo "<option value='".$clan."'>".$clan."</option>";}
		echo "</select></th>";

		echo "<th class='table_header'>";
		echo "<select id='clan-filter'>";
		echo "<option value=''>All</option>";
		echo "<option value='NA'>NA</option>";
		$go_terms = array_filter($go_terms);
		foreach(array_unique($go_terms)  as $go){
			echo "<option value='".$go."'>".$go."</option>";}
		echo "</select></th>";
		?>
		</tr>
	</tfoot>
	<?php
	echo '<tbody>';
	array_push($pfam_name, 'PF00001');

	foreach($pfam_name as $data){
		$link_id = 'http://pfam.xfam.org/family/' . $data;
		$link_clan = 'https://pfam.xfam.org/clan/';
		$pfam_row = $db->query("SELECT DISTINCT PFAM32.Family, PFAM32.Clan_acc_nb, PFAM32.Clan FROM PFAM32 WHERE PFAM32.PFAM_acc_nb='".$data."'");
		$pfam_row = $pfam_row->fetchArray();
		if($pfam_row['Clan_acc_nb']==""){
			$Clan_acc_nb="NA";
			$Clan="NA";}
		else{
			$Clan_acc_nb="<a class = 'table_link' href=" . $link_clan.$pfam_row['Clan_acc_nb'] . " target='_blank'>".$pfam_row['Clan_acc_nb']."</a>";
			$Clan="<a class = 'table_link' href=" . $link_clan.$pfam_row['Clan'] . " target='_blank'>".$pfam_row['Clan']."</a>";}
		if(empty($go_terms)){
			echo "<tr><td><a class = 'table_link' href=" . $link_id . " target='_blank'>".$data."</a></td>";
			echo "<td>" . $pfam_row['Family']."</td>";
			echo "<td>" . $Clan_acc_nb."</td>";
			echo "<td>" . $Clan."</td>";
			echo "<td>NA</td></tr>";}
		else{
			$i = 0;
			echo "<tr><td><a class = 'table_link' href=" . $link_id . " target='_blank'>".$data."</a></td>";
			echo "<td>" . $pfam_row['Family']."</td>";
			echo "<td>" . $Clan_acc_nb."</td>";
			echo "<td>" . $Clan."</td>";
			echo "<td>";
			foreach($go_terms as $go){
				echo '<br>'.$go['GO_term'].'<br>';}
			echo "</td></tr>";}}
	echo '</tbody>';
	echo '</table>';
	$db->close();
	?>
	</div>
	</div>
	</div>
	</section>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>

$.fn.dataTable.ext.search.push(
	function( settings, data, dataIndex ) {
		var max = Number($('#e-value_max').val()) || 1;
		var e_value = Number(data[4]) || 0;
		if ((isNaN(max)) || (e_value <= max)){
			return true;}
		return false;});
$.fn.dataTable.ext.search.push(		
	function( settings, data, dataIndex ) {
		var min = Number($('#bitscore_min').val()) || 0;
		var bitscore = Number(data[5]) || 0;
		if ((isNaN(min)) || (bitscore >= min)){
			return true;}
		return false;});
$.fn.dataTable.ext.search.push(		
	function( settings, data, dataIndex ) {
		var min = Number($('#acc_min').val()) || 0;
		var accuracy = Number(data[6]) || 0;
		if ((isNaN(min)) || (accuracy >= min)){
			return true;}
		return false;});

$(document).ready(function() {
	var table = $('#data_table').DataTable( {
		dom: 'lrtip',
		"pageLength": 10,
		"order": [[ 2, "desc" ]],
		"lengthMenu": [ [5, 10, 20, 50, -1], [5, 10, 20, 50, "All"] ],} );

	$('#e-value_max').keyup( function() {
		table.draw();} );
	$('#bitscore_min').keyup( function() {
		table.draw();} );
	$('#acc_min').keyup( function() {
		table.draw();} );
	$('#domain-filter').on('change', function(){
		table.search(this.value).draw();});
	$('#family-filter').on('change', function(){
		table.search(this.value).draw();});
	$('#species-filter').on('change', function(){
		table.search(this.value).draw();});
});

$(document).ready(function() {
	var go_termstable = $('#go_terms_table').DataTable( {
		dom: 'lrtip',
		"pageLength": 10,
		"order": [[ 2, "desc" ]],
		"lengthMenu": [ [5, 10, 20, 50, -1], [5, 10, 20, 50, "All"] ],
	} );

	$('#go_domain-filter').on('change', function(){
		go_termstable.search(this.value).draw();});
	$('#go_family-filter').on('change', function(){
		go_termstable.search(this.value).draw();});
	$('#clan-nb-filter').on('change', function(){
		go_termstable.search(this.value).draw();});
	$('#clan-filter').on('change', function(){
		go_termstable.search(this.value).draw();});
	$('#goterm-filter').on('change', function(){
		go_termstable.search(this.value).draw();});
});
</script>
<?php include("./includes/footer.php"); ?>
