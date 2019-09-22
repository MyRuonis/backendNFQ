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

        //date_default_timezone_set('Europe/London');

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

    public function insertLine($name, $specialistas, $time) {

        $sql = 'INSERT INTO patients(name, regTime, endTime, aptarnautas, specialistas) VALUES (:name, :regTime, :endTime, FALSE, :specialistas);';
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':regTime', $time);
        $stmt->bindValue(':endTime', $time);
        $stmt->bindValue(':specialistas', $specialistas);
        
        $stmt->execute();

        $sql = 'SELECT id FROM patients WHERE name=:name AND regtime=:regtime;';

        $stmt1 = $this->pdo->prepare($sql);

        $stmt1->bindValue(':name', $name);
        $stmt1->bindValue(':regtime', $time);

        $stmt1->execute();

        $id = 0;

        while ($row = $stmt1->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }

        return $id;
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
                    //date_default_timezone_set('Europe/London');

                    $timenow = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $timenow);
                    sscanf($timenow, "%d:%d:%d", $hours, $minutes, $seconds);
                    $timenow = $hours * 3600 + $minutes * 60 + $seconds;

                    $regtime = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $row['regtime']);
                    sscanf($regtime, "%d:%d:%d", $hours, $minutes, $seconds);
                    $regtime = $hours * 3600 + $minutes * 60 + $seconds;

                    $time2 = $timenow - $regtime;

                    if($time2 > $time)
                    {
                        $time2 = $time;
                    }
                }
            }
        }

        if ($klientuKiekis == 0) $time = 0;
        else $time = $time * $klientuKiekis - $time2;

        $hours = floor($time / 3600);
        $mins = floor($time / 60 % 60);
        $secs = floor($time % 60);

        $time = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

        return $time;
    }

    public function pavelinti($name, $regTime, $specialistas){
        $sql = 'SELECT id, regtime FROM patients WHERE specialistas = :specialistas AND aptarnautas = false;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':specialistas', $specialistas);
        $stmt->execute();

        echo "LINE<br>";

        $smtToSwap = false;
        $helpid1 = $helpid2 = 0;

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if(strtotime($regTime) <= strtotime($row['regtime']))
            {
                $smtToSwap = true;

                if($regTime == $row['regtime']){ $helpid1 = $row['id']; }
                elseif(strtotime($regTime) < strtotime($row['regtime'])) 
                {
                    $helpid2 = $row['id'];
                    break;
                }
            }
        }

        if(!$smtToSwap) { return; }

        echo "LINE<br>";

        $duomenys1 = $duomenys2 = 0;

        $sql = 'SELECT regtime FROM patients WHERE id=:id;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $helpid1);
        $stmt->execute();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $duomenys1 = $row['regtime'];
        }

        $sql ='SELECT regtime FROM patients WHERE id = :id;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $helpid2);
        $stmt->execute();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $duomenys2 = $row['regtime'];
        }

        echo "LINE<br>";

        $sql = 'UPDATE patients '
        . 'SET regtime = :regtime '
        . 'WHERE id = :id;';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':regtime', $duomenys1);
        $stmt->bindValue(':id', $helpid2);

        $stmt->execute();

        $sql = 'UPDATE patients '
        . 'SET regtime = :regtime '
        . 'WHERE id = :id;';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':regtime', $duomenys2);
        $stmt->bindValue(':id', $helpid1);

        $stmt->execute();
    }

    public function atsaukti($name, $time, $specialistas){
        $sql = 'DELETE FROM patients WHERE name = :name AND regtime =:regtime AND specialistas = :specialistas; ';
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':regtime', $time);
        $stmt->bindValue(':specialistas', $specialistas);
        
        $stmt->execute();
    }

    public function readLine($id){
        $sql = 'SELECT name, regtime, specialistas FROM patients WHERE id=:id;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $arr = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $arr = array($row['name'],$row['regtime'],$row['specialistas']);
        }

        return $arr;
    }
}
?>