<?php

namespace namesql;

class dbaction
{
    protected $pdo = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function delete($name, $time) {
        $sql = 'DELETE FROM patients '
            . 'WHERE name = :name '
            . 'WHERE time = :time';
 
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':time', $time);
 
        $stmt->execute();
    }

    public function insertLine($name) {

        date_default_timezone_set('Europe/Vilnius');

        $time = date("H:i:s");
        
        $sql = 'INSERT INTO patients(name,time) VALUES(:name,:time)';
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':time', $time);
        
        $stmt->execute();
    }

    public function all() {
        $stmt = $this->pdo->query('SELECT name, time '
                . 'FROM patients '
                . 'ORDER BY time '
                . 'LIMIT 10;');
        $stocks = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $stocks[] = [
                'name' => $row['name'],
                'time' => $row['time']
            ];
        }
        return $stocks;
    }
}

?>