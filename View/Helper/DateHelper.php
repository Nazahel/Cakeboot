<?php
/**
 * Edition : 15/04/2012
 */
class DateHelper extends AppHelper{

    //Retourne la date actuelle
    function current(){
        $date = new DateTime();
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Convertit une date
     * On met par défaut le format de la date : 00/00/0000
     * $data par défaut -> affiche la date sous le format : 00 janvier 0000
     * $data = complete -> affiche la date sous le format : 00 janvier 0000 à 00h00
     **/
    function convert($dateVal,$format='date'){ 

        if($dateVal == '0000-00-00 00:00:00'):
            return false;
        endif;

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
        // return
        if($format == "complete"){ return 'le '.$j.' '.$mois[$m].' '.$a.' à '.$h.':'.$i; }
        elseif($format == "date"){ return 'le '.$j.' '.$mois[$m].' '.$a; }
    }

    /**
     * Retourne un temps relatif
     *
     *
     **/
    function relativeTime($time,$format='complete'){
        $t = time() - strtotime($time);
        //Secondes
        if($t < 60){
             $lastVisit = 'il y a '.$t.' secondes';
        }
        //Minutes
        elseif($t < 3600){
            $t = ceil($t/60);
            $lastVisit = 'il y a '.$t.' minutes';
        }
        //Heures
        elseif($t < 86400){
            $t = ceil($t/3600);
            $lastVisit = 'il y a '.$t.' heures';
        }
        //Si plus de 1 jour
        else{
            $lastVisit = $this->convert($time,$format);
        }
        return $lastVisit;

    }
    
    /**
     * Retourne une date relative
     *
     *
     **/
    function relativeDate($date,$h=1){
        // Les paramètres locaux sont basés sur la France
        setlocale(LC_ALL, 'fr_FR.utf8','fra');
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
                $relative = htmlentities(strftime("%A", $time))." dernier";
            }
        // sinon on retourne une date complète.
        } else {
            $relative = htmlentities(strftime("%A %d %B %Y", $time));
        }
        // si l'heure est présente dans la date originale, on l'ajoute
        if (preg_match('#[0-9]{2}:[0-9]{2}#', $date) && $h==1) {
            $relative .= ' à '.date('H:i', $time);
        }
        return $relative;
    }

    /**
     * Transorme la date donnée au format : jj/mm/aaaa
     */
    function transformDate($date){

        $date =  strtotime($date);
        
        return 'le '.date('d',$date).'/'.date('m',$date).'/'.date('Y',$date);
    }

    /**
     * Transforme le nombre de min en temps
     */
    function transformTime($time,$str=array()){
        $t = '';
        if($time >= 60){
            $t .= floor($time/60);
            $t .= 'h';
            $t .= $time%60;
        }else{
            $t .= $time;
            $t .= 'm';
        }
        return $t;

    }

    /**
     * Retourne l'âge à partir d'une date
     *
     *
     **/
    function transformAge($date=NULL){
        $age = date('Y') - $date;
        if(date('md') < date('md', strtotime($date))):
            return $age - 1;
        endif;
        return $age;
    }

    /**
     * Vérifie si un utilisateur est actif
     */
    function online($time,$min=5){
        return (time()-strtotime($time) < 60*$min) ? 'En ligne' : ucfirst($this->relativeTime($time,'date'));
    }

}
?>