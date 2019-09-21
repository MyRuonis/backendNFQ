<?php
 
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbpatient as dbpatient;

try {
    $vard = $_POST['vardas'];
    $specialistas = $_POST['specialistas'];

    $pdo = Connection::get()->connect();

    //echo 'A connection to the PostgreSQL database sever has been established successfully.';

    $insertDemo = new dbpatient($pdo);

    $insertDemo->insertLine($vard, $specialistas);

    echo "Užregistruota sėkmingai.<br>";
    date_default_timezone_set('Europe/Vilnius');
    echo "<a href='laukimoLangas.php?vard=" . $vard . "&regtime=" . date("H:i:s") . "&spec=" . $specialistas . "'>Laukimo langas</a>";
    date_default_timezone_set('Europe/London');


} catch (\PDOException $e) {
    //echo $e->getMessage();
    echo "Įvyko klaida, kreipkitės telefonu";
}