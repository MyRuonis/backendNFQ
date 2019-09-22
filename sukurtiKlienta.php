<?php
 
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbpatient as dbpatient;

$boolean = true;

if (empty($_POST["vardas"]) || empty($_POST["specialistas"])) {
    $boolean = false;
} else {
    $name = test_input($_POST["vardas"]);
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
        $boolean = false;
    }
}

if($boolean)
{
    try {
        $vard = $_POST['vardas'];
        $specialistas = $_POST['specialistas'];
    
        $pdo = Connection::get()->connect();
    
        //echo 'A connection to the PostgreSQL database sever has been established successfully.';
    
        $insertDemo = new dbpatient($pdo);

        date_default_timezone_set('Europe/Vilnius');
        $time = date("H:i:s");
    
        $id = $insertDemo->insertLine($vard, $specialistas, $time);
    
        echo "Užregistruota sėkmingai.<br>";
        echo "<a href='laukimoLangas.php?id=" . $id . "'>Laukimo langas</a>";
    
    } catch (\PDOException $e) {
        //echo $e->getMessage();
        echo "Įvyko klaida, kreipkitės telefonu";
    }
} else {
    echo "Įvyko klaida, kreipkitės telefonu";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

?>