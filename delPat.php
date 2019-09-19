<?php
    $name = $_POST['name'];
    $time = $_POST['time'];

    $pdo = Connection::get()->connect();
    $stockDB = new StockDB($pdo);
    $stockDB->delete($name, $time);

    header('Location: specialistas.php');
?>