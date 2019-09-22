<?php
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbpatient as dbpatient;
 
try {
    $pdo = Connection::get()->connect();

    $stockDB = new dbpatient($pdo);

    $id = $_GET['id'];

    $duom = $stockDB->readLine($id);

} catch (\PDOException $e) {
    //echo $e->getMessage();
    echo "Klaida";
}

?>
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
        </ul>
    </div>
</nav>
<div class="container">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Vardas</th>
                <th>~Laukti</th>
                <th>Specialistas</th>
                <th>Atšaukimas</th>
                <th>Pavėlinimas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <form>
                <td><?php echo $duom[0]; ?></td>
                <td><?php echo $stockDB->kiekLaukti($duom[0], $duom[1], $duom[2]);?></td>
                <td><?php echo $duom[2]; ?></td>
                <td><?php echo "<a href='/atsaukti.php?name=" . htmlspecialchars($duom[0]) . "&regtime=" . htmlspecialchars($duom[1]) . "&specialistas=" . htmlspecialchars($duom[2]) . "' class='btn btn-dark'>Atšaukti</a>"; ?></td>
                <td><?php echo "<a href='/pavelinti.php?id=" . $id . "&name=" . htmlspecialchars($duom[0]) . "&regtime=" . htmlspecialchars($duom[1]) . "&specialistas=" . htmlspecialchars($duom[2]) . "' class='btn btn-dark'>Pavėlinti</a>"; ?></td>
            </tr>
        </tbody>
    </table>
</div>

<meta http-equiv="refresh" content="5"/>
</body>