<?php

$vard = $_GET["vardas"];
$time = date("H:i:s");

include 'connect.php';

$query = "INSERT INTO patients VALUES($vard, $time)";

echo "HERE1";

pg_prepare(, "prepare1", $query) or die ("Cannot prepare statement\n"); 

echo "HERE2";

pg_execute($conn, "prepare1", array($vard, $time)) or die ("Cannot execute statement\n"); 

echo "Row successfully inserted\n";

pg_close();

?>