<?php
 
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\Sqlinsert as Sqlinsert;

try {
    $vard = $_GET['vardas'];
    $pdo = Connection::get()->connect();

    //echo 'A connection to the PostgreSQL database sever has been established successfully.';

    $insertDemo = new Sqlinsert($pdo);

    $insertDemo->insertLine($vard);

    echo "Sėkmingai įvykdyta.<br>";
    echo "<a href='svieslente.php'>Svieslentė</a>";


} catch (\PDOException $e) {
    echo $e->getMessage();
}