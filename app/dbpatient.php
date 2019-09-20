<?php

namespace namesql;

class dbpatient
{
    protected $pdo = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function delete($vard, $time) {
        $sql = 'DELETE FROM patients '
            . 'WHERE name = :vard '
            . 'AND regtime = :time';
 
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':vard', $vard);
        $stmt->bindValue(':time', $time);
 
        $stmt->execute();
    }

    public function insertLine($name, $specialistas) {

        date_default_timezone_set('Europe/Vilnius');

        $regTime = date("H:i:s");

        $sql = 'INSERT INTO patients(name, regTime, endTime, aptarnautas, specialistas) VALUES (:name, :regTime, :endTime, FALSE, :specialistas);';
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':regTime', $regTime);
        $stmt->bindValue(':endTime', $regTime);
        //$stmt->bindValue(':aptarnautas', 0, PDO::PARAM_INT); // PDO::PARAM_BOOL
        $stmt->bindValue(':specialistas', $specialistas);
        
        $stmt->execute();
    }

    public function all() {
        $stmt = $this->pdo->query('SELECT name, regtime, specialistas '
                . 'FROM patients '
                . 'ORDER BY regtime '
                . 'LIMIT 10;');
        $stocks = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $stocks[] = [
                'name' => $row['name'],
                'regtime' => $row['regtime'],
                'specialistas' => $row['specialistas']
            ];
        }
        return $stocks;
    }
}

?>