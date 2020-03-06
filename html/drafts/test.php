/*convert_txtTOcsv('/var/www/html/php_code/results/results.csv');
		

		$username = "blachon"; 
		$password = "myclade"; 
		$database = "METACLADE"; 
		$conn = mysqli_connect("localhost", $username, $password, $database);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);}

		$sql = "DELETE FROM MetaCLADE_results";
		$request = $conn->query($sql);

		$sql = "LOAD DATA INFILE '/var/www/html/php_code/results/results.csv' INTO TABLE MetaCLADE_results FIELDS TERMINATED BY ' ' LINES TERMINATED BY '\\n'";
		$request = $conn->query($sql);*/
