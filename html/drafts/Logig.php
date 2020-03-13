<body>
	
	<form method="post" action="Logig.php">
		  
	<table border="1" align="center">
		   
	<tr>
			
	<td>Enter your name</td>
	
	<td><input type="text" name="n"/></td>
	
	</tr>
	
	<tr>
	
	<td colspan="2" align="center">
	
	<input type="submit" name="sub" value="SHOW MY NAME"/>
	
	</td>
	
	</tr>
	
	</table>
	
	</form>
	<?php
  
  $name=$_POST['n'];
  
  echo "Welcome ".$name;
  
  ?>
	</body>	