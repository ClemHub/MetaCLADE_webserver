$sql2 = "SELECT DISTINCT PFAM32.Family FROM PFAM32 WHERE PFAM32.PFAM_acc_nb='".$domain_id."'";
$domain_result = mysqli_query($mysqli, $sql2);
$family = mysqli_fetch_assoc($domain_result);