<?php

namespace namesql;

class Connection {

    private static $conn;
 
    public function connect() {

        $params = parse_ini_file('db.ini');
        if ($params === false) {
            throw new \Exception("Error reading database configuration file");
        }
        
        $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s", 
                $params['host'], 
                $params['port'], 
                $params['database'], 
                $params['user'], 
                $params['password']);
 
        $pdo = new \PDO($conStr);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
 
        return $pdo;
    }

    public static function get() {
        if (null === static::$conn) {
            static::$conn = new static();
        }
 
        return static::$conn;
    }
 
    protected function __construct() {
        
    }
 
    private function __clone() {
        
    }
 
    private function __wakeup() {
        
    }

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