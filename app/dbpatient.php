<?php

namespace namesql;

class dbpatient
{
    protected $pdo = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function delete($vard, $time, $specialistas) {
        date_default_timezone_set('Europe/Vilnius');

        $time2 = date("H:i:s");

        $sql = 'UPDATE patients '
            . 'SET endtime = :time, '
            . 'aptarnautas = true '
            . 'WHERE name = :name '
            . 'AND regtime = :time2;';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':time', $time2);
        $stmt->bindValue(':name', $vard);
        $stmt->bindValue(':time2', $time);
 
        $stmt->execute();

        $sql = 'UPDATE docs '
        . 'SET aptarnautiklientai = aptarnautiKlientai + 1, '
        . 'bendrassugaistaslaikas = bendrasSugaistasLaikas + :time '
        . 'WHERE name = :name;';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':time', $time2 - $time);
        $stmt->bindValue(':name', $specialistas);

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
                . 'WHERE aptarnautas = false '
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