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
    $newTime = $stockDB->pavelinti($name, $time, $specialistas);

    echo "<br>" . $newTime . "<br>";

    //header('Location: laukimoLangas.php?vard=' . $name . '&regtime=' . $newTime . '&spec=' . $specialistas);
} catch (\PDOException $e) {
    echo $e->getMessage();
}
?>