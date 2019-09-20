<?php
 
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbpatient as dbpatient;

try {
    $vard = $_POST['vardas'];
    $specialistas = $_POST['specialistas'];

    echo $vard . " " . $specialistas . "<br>";
    $pdo = Connection::get()->connect();

    //echo 'A connection to the PostgreSQL database sever has been established successfully.';

    $insertDemo = new dbpatient($pdo);

    $insertDemo->insertLine($vard, $specialistas);

    echo "Užregistruota sėkmingai.<br>";
    echo "<a href='svieslente.php'>Svieslentė</a>";


} catch (\PDOException $e) {
    //echo $e->getMessage();
    echo "Įvyko klaida, kreipkitės telefonu";
}