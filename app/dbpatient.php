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

        $time = date("H:i:s", strtotime($time2) - strtotime($time));

        $sql = 'UPDATE docs '
        . 'SET aptarnautiklientai = aptarnautiklientai + 1, '
        . 'bendrassugaistaslaikas = bendrassugaistaslaikas + :time '
        . 'WHERE name = :name;';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':time', $time);
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

    public function kiekLaukti($name, $regTime, $specialistas){
        $stmt = $this->pdo->query('SELECT name, bendrasSugaistasLaikas, aptarnautiKlientai '
                . 'FROM docs;');
        $time = date("H:i:s");

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if($row['name'] == $specialistas)
            {
                $str_time = $row['bendrassugaistaslaikas'];
                $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
                sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
                $time_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                $time = $time_seconds / $row['aptarnautiklientai'];
            }
        }

        $stmt = $this->pdo->query('SELECT name, specialistas, regTime '
                . 'FROM patients '
                . 'WHERE aptarnautas = false;');
        $klientuKiekis = 0;
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if($row['specialistas'] == $specialistas && $row['regTime'] < $regTime && $row['name'] != $name)
            {
                $klientuKiekis += 1;
            }
        }

        $time = $time * $klientuKiekis;

        return date("H:i:s", $time);
    }
}

?>