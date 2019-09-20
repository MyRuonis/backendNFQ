<?php
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbdoc as dbdoc;
 
try {
    $pdo = Connection::get()->connect();

    $stockDB = new dbdoc($pdo);

    $stocks = $stockDB->all();
} catch (\PDOException $e) {
    echo $e->getMessage();
}
?>

<body>
 
<form action="/sukurtiKlienta.php" method="post" id="forma">
  Vardas: <input type="text" name="vardas"><br>
    <br>
  <input type="submit" value="Submit">
</form>

Daktaras: <select name='specialistas' from="forma">
            <?php foreach($stocks as $stock) :
                  $value=htmlspecialchars($stock['name']);
                  echo "<option value=". $value . ">" . $value . "</option>";
              endforeach;
            ?>
          </select> 

<body>