<?php
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbstats as dbstats;
 
try {
    $pdo = Connection::get()->connect();

    $stockDB = new dbstats($pdo);

    $stocks = $stockDB->laisva("Dantistas");
} catch (\PDOException $e) {
    echo $e->getMessage();
}
?>