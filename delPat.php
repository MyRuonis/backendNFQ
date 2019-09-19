<?php

require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbaction as dbaction;

    $name = $_POST['name'];
    $time = $_POST['time'];

    $pdo = Connection::get()->connect();
    $stockDB = new dbaction($pdo);
    $stockDB->delete($name, $time);

    header('Location: specialistas.php');
?>