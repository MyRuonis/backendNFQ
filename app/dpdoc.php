<?php

namespace namesql;

class dbdoc {
    protected $pdo = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        $stmt = $this->pdo->query('SELECT name '
                . 'FROM docs '
                . 'ORDER BY name ');
        $stocks = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $aptarnautiSnd = 0;
            $aptarnautiIsViso = 0;

            $stmt1 = $this->pdo->query('SELECT docs.name as name, stats.diena as diena, docs.aptarnautiklientai as klientai FROM docs '
            . 'INNER JOIN stats ON docs.name=stats.specialistas;');

            while ($row2 = $stmt1->fetch(\PDO::FETCH_ASSOC)) {
                echo $row2['diena'] . " " . $row2['name'] . " " . $row['klientai'] . "<br>";
                if($row2['diena'] == date("Y/m/d") && $row['name'] == $row2['klientai']){
                    $aptarnautiSnd += 1;
                }
                $aptarnautiIsViso = $row['docs.aptarnautiklientai'];
            }

            $stocks[] = [
                'name' => $row['name'] . "(Iš viso aptarnavo - " . $aptarnautiIsViso . ", šiandien - " . $aptarnautiSnd . ")"
            ];
        }
        return $stocks;
    }
}

?>