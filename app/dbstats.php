<?php

namespace namesql;

class dbstats
{
    protected $pdo = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function laisva($specialistas) {
        $sql = 'SELECT * FROM stats WHERE specialistas=:specialistas ORDER BY laikasnuo;';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':specialistas', $specialistas);

        $stmt->execute();

        $stocks = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $stocks[] = [
                'laikasnuo' => $row['laikasnuo'],
                'laikasiki' => $row['laikasiki']
            ];
        }

        $laikai = array();
        $galimasNuo = "";

        foreach ($stocks as $stock){
            if ($galimasNuo != "" && strtotime($stock['laikasnuo']) > strtotime($galimasNuo)){
                $laikai[] = $galimasNuo;
                $laikai[] = $stock['laikasnuo'];
            }

            $galimasNuo = $stock['laikasiki'];
        }

        foreach($laikai as $laikas){
            echo $laikas . " ";
        }
    }
}

?>