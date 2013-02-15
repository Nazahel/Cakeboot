<?php
class ConvertHelper extends AppHelper{

    /**
     * Convertit une date
     * On met par défaut le format de la date : 00/00/0000
     * $data par défaut -> affiche la date sous le format : 00 janvier 0000
     * $data = complete -> affiche la date sous le format : 00 janvier 0000 à 00h00
     **/
    function date($dateVal,$data='date'){ 
        date_default_timezone_set('Europe/Paris');
        // jour
        $j = date("d",strtotime($dateVal));
        // mois
        $mois = array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
        $m = date("m",strtotime($dateVal));
        $m = $m-1;
        // année
        $a = date("Y",strtotime($dateVal));
        // heure
        $h = date("H",strtotime($dateVal));
        // minute
        $i = date("i",strtotime($dateVal));
        // secondes
        $s = date("s",strtotime($dateVal));

        if($data == "complete"){ return $j.' '.$mois[$m].' '.$a.' à '.$h.':'.$i; }
                elseif($data == "date"){ return $j.' '.$mois[$m].' '.$a; }
    }

}
?>