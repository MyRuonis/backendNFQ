<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">SVEIKI!</a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
  </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="administravimas.php">Registracija<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="svieslente.php">Švieslentė<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="specialistas.php">Specialistas<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="statistika.php?spec=Dantistas">Statistika<span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>
</nav>

<table class="table table-dark">
  <thead>
    <tr>
      <th scope="col">Siūloma ateiti Nuo - Iki</th>
      <th scope="col">Specialistas</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <?php
        require 'vendor/autoload.php';
        
        use namesql\Connection as Connection;
        use namesql\dbstats as dbstats;
        
        try {
            $pdo = Connection::get()->connect();

            $stockDB = new dbstats($pdo);

            $specialistas = $_GET['spec'];

            $stocks = $stockDB->laisva($specialistas);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        ?>
      </td>
      <td>
        <?php echo $specialistas; ?>
      </td>
    </tr>
  </tbody>
</table>

<?php

use namesql\dbdoc as dbdoc;

try {
    $pdo = Connection::get()->connect();

    $stockDB = new dbdoc($pdo);

    $stocks = $stockDB->all();

    foreach($stocks as $stock){
      echo "<a href='statistika.php?spec=" . $stock['name'] . "' class='btn btn-dark'>" . $stock['name'] . "</a>";
    }
} catch (\PDOException $e) {
    echo $e->getMessage();
}

?>

</body>