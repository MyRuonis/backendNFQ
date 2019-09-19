<?php
require 'vendor/autoload.php';
 
use namesql\Connection as Connection;
use namesql\dbaction as dbaction;
 
try {
    $pdo = Connection::get()->connect();

    $stockDB = new dbaction($pdo);

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
                        <th>Time Registered</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stocks as $stock) : ?>
                        <tr>
                            <form action="/delPat.php" method="post">
                                <td><input type="hidden" name="name" value="<?php echo htmlspecialchars($stock['name']); ?>"><br></td>
                                <td><input type="hidden" name="time"><?php echo htmlspecialchars($stock['time']); ?><br></td>
                                <td><input type="submit" value="Aptarnautas"></td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>