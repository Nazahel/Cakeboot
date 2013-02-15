<?php
class RelativeHelper extends AppHelper{

    /**
     * Retourne un temps relative
     *
     *
     **/
    function time($time){

            $time = time() - strtotime($time);

            //Secondes
            if($time < 60){
                    $lastVisit = 'il y a '.$time.' secondes';
            }
            //Minutes
            elseif($time < 3600){

                    $time = ceil($time/60);
                    $lastVisit = 'il y a '.$time.' minutes';
            }
            //Heures
            elseif($time < 3600*24){

                    $time = ceil($time/3600);
                    $lastVisit = 'il y a '.$time.' heures';
            }
            else{
                    $lastVisit = 'le '.$this->DateConv($date,'complete');
            }

            return $lastVisit;
            unset($time); unset($lastVisit);

    }
    
    /**
     * Retourne une date relative
     *
     *
     **/
    function date($date,$h=1) {
        // Les paramètres locaux sont basés sur la France
        setlocale (LC_TIME, 'fr_FR.utf8','fra');
        // On prend divers points de repère dans le temps
        $time            = strtotime($date);
        $after           = strtotime("+7 day 00:00");
        $afterTomorrow   = strtotime("+2 day 00:00");
        $tomorrow        = strtotime("+1 day 00:00");
        $today           = strtotime("today 00:00");
        $yesterday       = strtotime("-1 day 00:00");
        $beforeYesterday = strtotime("-2 day 00:00");
        $before          = strtotime("-7 day 00:00");
        // On compare les repères à la date actuelle
        // si elle est proche alors on retourne une date relative...
        if ($time < $after && $time > $before) {
            if ($time >= $after) {
                $relative = strftime("%A", $date)." prochain";
            } else if ($time >= $afterTomorrow) {
                $relative = "après demain";
            } else if ($time >= $tomorrow) {
                $relative = "demain";
            } else if ($time >= $today) {
                $relative = "aujourd'hui";
            } else if ($time >= $yesterday) {
                $relative = "hier";
            } else if ($time >= $beforeYesterday) {
                $relative = "avant hier";
            } else if ($time >= $before) {
                $relative = strftime("%A", $time)." dernier";
            }
        // sinon on retourne une date complète.
        } else {
            $relative = strftime("%A %d %B %Y", $time);
        }
        // si l'heure est présente dans la date originale, on l'ajoute
        if (preg_match('#[0-9]{2}:[0-9]{2}#', $date) && $h==1) {
            $relative .= ' à '.date('H:i', $time);
        }
        return $relative;
    }

}
?>