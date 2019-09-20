<?php

namespace namesql;

class dbpatient
{
    protected $pdo = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function delete($ID) {
        $sql = 'DELETE FROM patients '
            . 'WHERE ID = :ID ';
 
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':ID', $ID);
 
        $stmt->execute();
    }

    public function insertLine($name, $specialistas) {

        date_default_timezone_set('Europe/Vilnius');

        $regTime = date("H:i:s");

        $aptarnautas = 'f';
        $sql = 'INSERT INTO patients(name, regTime, endTime, aptarnautas, specialistas) VALUES(:name, :regTime, :endTime, :aptarnautas, :specialistas);';
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':regTime', $regTime);
        $stmt->bindValue(':endTime', $regTime);
        $stmt->bindValue(':aptarnautas', $aptarnautas); // PDO::PARAM_BOOL
        $stmt->bindValue(':specialistas', $specialistas);
        
        $stmt->execute();
    }

    public function all() {
        $stmt = $this->pdo->query('SELECT name, regtime '
                . 'FROM patients '
                . 'ORDER BY regTime '
                . 'LIMIT 10;');
        $stocks = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $stocks[] = [
                'name' => $row['name'],
                'regtime' => $row['regtime']
            ];
        }
        return $stocks;
    }
}

?>