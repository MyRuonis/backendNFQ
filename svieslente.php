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
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
    </head>
    <body>
        <div class="container">
            <h1>Stock List</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>TimeRegistered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stocks as $stock) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($stock['name']); ?></td>
                            <td><?php echo htmlspecialchars($stock['regTime']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>