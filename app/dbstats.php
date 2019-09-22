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
        $galimasNuo = "08:00:00";
        $galimasIki = "17:00:00";

        $lastLaikas = "";

        foreach ($stocks as $stock){
            if($galimasNuo == "08:00:00" && $stock['laikasnuo'] != $galimasNuo){
                $laikai[] = $galimasNuo;
                $laikai[] = $stock['laikasnuo'];
            }

            else if (strtotime($stock['laikasnuo']) > strtotime($galimasNuo)){
                $laikai[] = $galimasNuo;
                $laikai[] = $stock['laikasnuo'];
            }

            $galimasNuo = $stock['laikasiki'];
        }

        if(strtotime($galimasNuo) < strtotime($galimasIki)) {
            $laikai[] = $galimasNuo;
            $laikai[] = $galimasIki;
        }

        for($i=0;$i<sizeof($laikai);$i+=2){
            echo $laikai[$i] . " - " . $laikai[$i+1] . "<br>";
        }

        if(sizeof($laikai) == 0) { echo "Siūlome ateiti iš anksto, nes dažnai būna užimta.";}
    }
}

?>