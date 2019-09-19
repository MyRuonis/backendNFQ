<?php

namespace namesql;

class Sqlsvies
{
    protected $pdo = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        $stmt = $this->pdo->query('SELECT name, time '
                . 'FROM patients '
                . 'ORDER BY time');
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