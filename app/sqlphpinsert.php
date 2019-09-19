<?php

namespace namesql;

class Sqlhphpinsert
{
    protected $pdo = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function insertLine($name) {

        $time = date("H:i:s");
        
        $sql = 'INSERT INTO patients(name,time) VALUES(:name,:time)';
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':time', $time);
        
        $stmt->execute();
        
        return 0;
        //return $this->pdo->lastInsertId('stocks_id_seq');
    }
}

?>