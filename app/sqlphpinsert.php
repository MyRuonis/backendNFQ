<?php

namespace namesql;

class Sqlinsert
{
    protected $pdo = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function insertLine($name) {

        date_default_timezone_set(Europe/Vilnius);
        
        $time = date("H:i:s");
        
        $sql = 'INSERT INTO patients(name,time) VALUES(:name,:time)';
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':time', $time);
        
        $stmt->execute();
    }
}

?>