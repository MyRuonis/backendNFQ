<?php
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbdoc as dbaction;
 
try {
    $pdo = Connection::get()->connect();

    $stockDB = new dbdoc($pdo);

    $stocks = $stockDB->all();
} catch (\PDOException $e) {
    echo $e->getMessage();
}
?>

<body>
 
 <form action="/sukurtiKlienta.php" method="post">
  Vardas: <input type="text" name="vardas"><br>
  Daktaras: <select>
              <?php foreach($stocks as $stock) :
                  $value=htmlspecialchars($stock['name']);
                  echo "<option value=" . $value . ">" . $value . "</option>";
              endforeach;
              ?>
            </select> 
  <input type="submit" value="Submit">
</form>

<body>