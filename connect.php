 <?php
 
 $host = "ec2-107-20-243-220.compute-1.amazonaws.com";
 $port = "5432";
 $dbname = "dcv3lsp8t8oabi";
 $user = "hkslzztxngudie";
 $password = "0bb0602d3e75e1a53cccef4ace38264961bc94fa4c58a1678a5a26bad1380b0f";

$conn = pg_connect ("host=" . $host . " port=" . $port . " dbname=" . $dbname . " user=" . $user . " password=" . $password) or die("Įvyko klaida, kreipkitės telefonu.");
?>