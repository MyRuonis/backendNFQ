<?php

require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbpatient as dbpatient;

try {
    $name = $_GET['name'];
    $time = $_GET['regtime'];
    $specialistas = $_GET['specialistas'];

    echo "1";
    $pdo = Connection::get()->connect();echo "2";
    $stockDB = new dbpatient($pdo);echo "3";
    $stockDB->delete($name, $time, $specialistas);

    //header('Location: specialistas.php');
} catch (\PDOException $e) {
    echo $e->getMessage();
}
?>