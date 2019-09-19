<?php

namespace namesql;

class Sqlhphpinsert
{
    public function insertLine($vard) {

        $time = date("H:i:s");
        
        $sql = 'INSERT INTO patients(name,time) VALUES(:vard,:time)';
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':name', $vard);
        $stmt->bindValue(':time', $time);
        
        $stmt->execute();
        
        return $this->pdo->lastInsertId('stocks_id_seq');
    }
}

?>