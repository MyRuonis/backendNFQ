<?php

require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbpatient as dbpatient;

try {
    $name = $_GET['name'];
    $time = $_GET['regtime'];
    $specialistas = $_GET['specialistas'];

    $pdo = Connection::get()->connect();
    $stockDB = new dbpatient($pdo);
    $stockDB->delete($name, $time, $specialistas);

    header('Location: specialistas.php');
} catch (\PDOException $e) {
    //echo $e->getMessage();
    echo "Klaida.";
}
?>