<?php

require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbpatient as dbpatient;

try {
    $name = $_GET['name'];
    $regTime = $_GET['regtime'];
    $specialistas = $_GET['specialistas'];

    $pdo = Connection::get()->connect();
    $stockDB = new dbpatient($pdo);
    $stockDB->pavelinti($name, $regTime, $specialistas);

    $str = 'Location: laukimoLangas.php?id=' . $_GET['id'];

    //header($str);
} catch (\PDOException $e) {
    echo $e->getMessage();
}
?>