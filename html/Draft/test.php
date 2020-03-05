<html>
<body>
<?php 
$username = "blachon"; 
$password = "myclade"; 
$database = "METACLADE"; 
$mysqli = new mysqli("localhost", $username, $password, $database); 
$query = "SELECT * FROM `TABLE_2` ORDER BY `TABLE_2`.`COL 1` DESC ";
 
 
echo '<table border="0" cellspacing="2" cellpadding="2"> 
      <tr> 
          <td> <font face="Arial">Value1</font> </td> 
          <td> <font face="Arial">Value2</font> </td> 
          <td> <font face="Arial">Value3</font> </td> 
          <td> <font face="Arial">Value4</font> </td> 
		  <td> <font face="Arial">Value5</font> </td>
		  <td> <font face="Arial">Value6</font> </td>
		  <td> <font face="Arial">Value7</font> </td>
		  <td> <font face="Arial">Value8</font> </td>
		  <td> <font face="Arial">Value9</font> </td>
		  <td> <font face="Arial">Value10</font> </td>
		  <td> <font face="Arial">Value11</font> </td>
		  <td> <font face="Arial">Value12</font> </td>
      </tr>';
 
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $field1name = $row["COL 1"];
        $field2name = $row["COL 2"];
        $field3name = $row["COL 3"];
        $field4name = $row["COL 4"];
        $field5name = $row["COL 5"]; 
		$field6name = $row["COL 6"];
		$field7name = $row["COL 7"];
		$field8name = $row["COL 8"];
		$field9name = $row["COL 9"];
		$field10name = $row["COL 10"];
		$field11name = $row["COL 11"];
		$field12name = $row["COL 12"];
        echo '<tr> 
                  <td>'.$field1name.'</td> 
                  <td>'.$field2name.'</td> 
                  <td>'.$field3name.'</td> 
                  <td>'.$field4name.'</td> 
				  <td>'.$field5name.'</td> 
				  <td>'.$field6name.'</td> 
				  <td>'.$field7name.'</td> 
				  <td>'.$field8name.'</td> 
				  <td>'.$field9name.'</td> 
				  <td>'.$field10name.'</td> 
				  <td>'.$field11name.'</td> 
				  <td>'.$field12name.'</td> 
              </tr>';
    }
    $result->free();
} 
?>
</body>
</html>