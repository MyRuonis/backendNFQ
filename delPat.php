<?php

require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbaction as dbaction;

try {
    $name = $_POST['name'];
    $time = $_POST['time'];

    echo "$name<br>$time<br>";

    $pdo = Connection::get()->connect();
    $stockDB = new dbaction($pdo);
    $stockDB->delete($name, $time);

    //header('Location: specialistas.php');
} catch (\PDOException $e) {
    echo $e->getMessage();
}
?>