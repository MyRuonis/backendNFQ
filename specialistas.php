<?php
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbpatient as dbpatient;
 
try {
    $pdo = Connection::get()->connect();

    $stockDB = new dbpatient($pdo);

    $stocks = $stockDB->all();
} catch (\PDOException $e) {
    echo $e->getMessage();
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
    <h1>Klientai</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Time Registered</th>
                <th>Specialistas</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($stocks as $stock) : ?>
                    <tr>
                    <form action="/delPat.php" method="get">
                        <?php $value1=htmlspecialchars($stock['name']); $value2=htmlspecialchars($stock['regtime']); $value3=htmlspecialchars($stock['specialistas']);?>
                        <td><input type="hidden" name="name" value="<?php echo $value1; ?>"><?php echo $value1; ?><br></td>
                        <td><input type="hidden" name="regtime" value="<?php echo $value2; ?>"><?php echo $value2; ?><br></td>
                        <td><input type="hidden" name="specialistas" value="<?php echo $value3; ?>"><?php echo $value3; ?><br></td>
                        <input type="hidden" name="page" value="specialistas">
                        <td><input type="submit" value="Aptarnautas"></td>
                    </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
    </table>
</div>
</body>