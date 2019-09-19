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
                                <?php $value1=htmlspecialchars($stock['name']); $value2=htmlspecialchars($stock['time']);?>
                                <td><input type="hidden" name="name" value="<?php echo $value1; ?>"><?php echo $value1; ?><br></td>
                                <td><input type="hidden" name="time" value="<?php echo $value2; ?>"><?php echo $value2; ?><br></td>
                                <td><input type="submit" value="Aptarnautas"></td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>