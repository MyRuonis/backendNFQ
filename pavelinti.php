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
    $stockDB->pavelinti($name, $time, $specialistas);

    header('Location: laukimoLangas.php?vard=' . $name . '&regtime=' . $time . '&spec=' . $specialistas);
} catch (\PDOException $e) {
    echo $e->getMessage();
}
?>