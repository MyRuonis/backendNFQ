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

        date_default_timezone_set('Europe/London');

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

        $time =  strtotime($time2) - strtotime($time);
        
        $hours = floor($time / 3600);
        $mins = floor($time / 60 % 60);
        $secs = floor($time % 60);

        $time = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

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

        $time = 0;

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

        $aptarnaujamasKlientas = true;
        $time2 = 0;

        $stmt = $this->pdo->query('SELECT name, specialistas, regtime '
                . 'FROM patients '
                . 'WHERE aptarnautas = false;');
        $klientuKiekis = 0;
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if($row['specialistas'] == $specialistas && strtotime($row['regtime']) < strtotime($regTime))
            {
                $klientuKiekis += 1;
                if ($aptarnaujamasKlientas)
                {
                    $ptarnaujamasKlientas = false;

                    date_default_timezone_set('Europe/Vilnius');
                    $timenow = date("H:i:s");
                    date_default_timezone_set('Europe/London');

                    $time2 = strtotime($timenow) - strtotime($row['regtime']);

                    if($time2 > $time)
                    {
                        $time2 = $time;
                    }
                }
            }
        }

        if ($klientuKiekis == 0) $time = 0;
        else $time = $time * $klientuKiekis - $time2;

        return date("H:i:s", $time);
    }

    public function pavelinti($name, $regTime, $specialistas){
        $sql = 'SELECT id, name, regtime FROM patients WHERE regtime >= :regtime AND specialistas = :specialistas AND aptarnautas = false;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':regtime', $regTime);
        $stmt->bindValue(':specialistas', $specialistas);
        $stmt->execute();

        $smtToSwap = false;
        $helpid1 = $helpid2 = 0;

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $smtToSwap = true;

            if($regTime == $row['regtime']){ $helpid1 = $row['id']; }
            elseif($regTime < $row['regtime']) 
            {
                $helpid2 = $row['id'];
                break;
            }
        }

        if(!$smtToSwap) { return; }

        //WORKS TILL HERE

        $duomenys1 = $duomenys2 = 0;

        echo "1<br>";

        $stmt = $this->pdo->query('SELECT * '
                . 'FROM patients '
                . 'WHERE id = :id');
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $helpid1);
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $duomenys1 = array($row['name'],$row['regtime'],$row['endtime'],$row['aptarnautas'],$row['specialistas']);
        }

        echo "1<br>";
        $stmt = $this->pdo->query('SELECT * '
                . 'FROM patients '
                . 'WHERE id = :id');
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $helpid2);
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $duomenys2 = array($row['name'],$row['regtime'],$row['endtime'],$row['aptarnautas'],$row['specialistas']);
        }

        echo "1<br>";

        $sql = 'UPDATE patients '
        . 'SET name = :name, '
        . 'regtime = :regtime, '
        . 'endtime = :endtime, '
        . 'aptarnautas = :aptarnautas, '
        . 'specialistas = :specialistas, '
        . 'WHERE id = :id;';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':name', $duomenys1[0]);
        $stmt->bindValue(':regtime', $duomenys1[1]);
        $stmt->bindValue(':endtime', $duomenys1[2]);
        $stmt->bindValue(':aptarnautas', $duomenys1[3]);
        $stmt->bindValue(':specialistas', $duomenys1[4]);
        $stmt->bindValue(':id', $helpid2);

        $stmt->execute();

        echo "1<br>";

        $sql = 'UPDATE patients '
        . 'SET name = :name, '
        . 'regtime = :regtime, '
        . 'endtime = :endtime, '
        . 'aptarnautas = :aptarnautas, '
        . 'specialistas = :specialistas, '
        . 'WHERE id = :id;';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':name', $duomenys2[0]);
        $stmt->bindValue(':regtime', $duomenys2[1]);
        $stmt->bindValue(':endtime', $duomenys2[2]);
        $stmt->bindValue(':aptarnautas', $duomenys2[3]);
        $stmt->bindValue(':specialistas', $duomenys2[4]);
        $stmt->bindValue(':id', $helpid1);

        $stmt->execute();

        return $duomenys2[1];
    }

    public function atsaukti($name, $regTime, $specialistas){
        $sql = 'DELETE FROM patients WHERE name = :name AND regtime =:regtime AND specialistas = :specialistas; ';
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':regtime', $regTime);
        $stmt->bindValue(':specialistas', $specialistas);
        
        $stmt->execute();
    }
}
?>