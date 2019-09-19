<?php
 
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;

 
try {
    $vard = $_GET['vardas'];
    $pdo = Connection::get()->connect();
    
    //echo 'A connection to the PostgreSQL database sever has been established successfully.';

    $insertDemo = new Sqlhphpinsert($pdo);

    $id = $insertDemo->insertLine($vard);
    echo 'The stock has been inserted with the id ' . $id . '<br>';

} catch (\PDOException $e) {
    echo $e->getMessage();
}