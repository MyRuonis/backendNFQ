<?php

$vard = $_GET["vardas"];
$time = date("H:i:s");

include 'connect.php';

$query = "INSERT INTO patients VALUES($vard, $time)";

pg_prepare(, "prepare1", $query) or die ("Cannot prepare statement\n"); 

pg_execute(, "prepare1", array($vard, $time)) or die ("Cannot execute statement\n"); 

echo "Row successfully inserted\n";

pg_close();

?>