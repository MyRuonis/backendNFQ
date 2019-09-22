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
            $stocks[] = [
                'name' => $row['name']
            ];
        }
        return $stocks;
    }
}

?>