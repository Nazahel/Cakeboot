<?php App::uses('AppHelper', 'View/Helper');

class CakebootHelper extends AppHelper{

    public $helpers = array('Html');


    //METHODS FOR CAKE
    /**
     * Spécial Cake :)
     * Créer un lien et rajoutes la classe "active" si c'est le lien courrant
     */
    function currentLink($name, $options=array()){
        //debug($options);
        $class   = (!empty($options['class'])) ? $options['class'].' ' : '';
        $current = false;
        $active  = null;

        //Assign
        if($options['assign'] == 'controller' || $options['assign'] == 'action'){
            $assign  = $this->request->params[$options['assign']];
            $current = true;
        }
        elseif($options['assign'] == 'pass' && !empty($this->request->params[$options['assign']])){
            $assign  = $this->request->params[$options['assign']][$options['value']];
            $current = true;
        }
        //----------
        if($current){
            foreach($options['pages'] as $p):
                $active = (isset($assign) && $assign == $p) ? 'active' : null;
                if($active != null){ break; }
            endforeach;
        }
        //Class
        $cls = (!empty($class) || !empty($active)) ? ' class="'.$class.$active.'"' : '';
        //---
        return '<a href="'.$this->Html->url($options['link']).'"'.$cls.'>'.$name.'</a>';
    }

    /**
     * Nettoie les prefixes dans les liens
     * Fusionne le tableau du lien avec le tableau des préfixs à nettoyer
     * Ajouter Configure::write('appli.CleanPrefixes') dans le bootstrap
     */
    function cleanPrefixes($array){
        return array_merge($array, Configure::read('appli.CleanPrefixes'));
    }

    /**
     * Retourne true si le membre est en ligne
     */
    function userOnline($time, $min=5){
        if(time()-strtotime($time) < 60*$min){
            return true;
        }else{
            return false;
        }
    }

    //-------------------------------------------------------
    //-------------------------------------------------------

    //IMAGES
    /**
     * Retourne l'image cropée
     */
    function imageCrop($img, $w, $h){
        return $this->Html->image(sprintf($img, $w, $h));
    }

    //-------------------------------------------------------
    //-------------------------------------------------------

    //STR
    /**
     * MARKDOWN
     */
    function Markdown($string){
        App::import('Vendor', 'Cakeboot.Markdown', array('file'=>'Markdown/markdown.php'));
        return Markdown($string);
    }

    /**
     * Limite le nombre de caractères d'une chaîne
     * @param $chaine = la chaine à limiter
     * @param $max = le nombre maximal de caractères
     * @param $word => 0 = coupe le dernier mot
     * @param $word => 1 = garde le dernier mot en entier
     * @param $end = rajouter un string à la fin
     **/
    function StrLimit($chaine,$max,$word=true,$end=null){
        if(mb_strlen($chaine) >= $max):
            $chaine = substr($chaine,0,$max);
            if($word){
                //Garde le mot en entier
                $espace = strrpos($chaine,' ');
                $chaine = substr($chaine,0,$espace);
            }
            if($end){
                $chaine .= $end;
            }
        endif;
        return $chaine;
    }

    /**
     * Retourne le singulier/pluriel d'un string
     *
     *
     **/
    function pluralize($nb=1, $string=null, $values=array()) {
        // remplace {#} par le chiffre
        $string = str_replace("{#}", $nb, $string);
        // cherche toutes les occurences de {...}
        preg_match_all("/\{(.*?)\}/", $string, $matches);
        foreach($matches[1] as $k=>$v) {
            // on coupe l'occurence à |
            $part = explode("|", $v);
            // si aucun
            if ($nb == 0) {
                $mod = (count($part) == 1) ? "" : $part[0];
            // si singulier
            } else if ($nb == 1) {
                $mod = (count($part) == 1) ? "" : $part[1];
            // sinon pluriel
            } else {
                $mod = (count($part) == 1) ? $part[0] : ((count($part) == 2) ? $part[1] : $part[2]);
            }
            // je remplace les occurences trouvées par le bon résultat.
            $string = str_replace($matches[0][$k], $mod , $string);
        }
        // retourne le résultat en y incluant éventuellement les valeurs passées
        return vsprintf($string, $values);
    }

    //Remplace les caractères spéciaux et les espaces d'une chaîne de caractères
    function slug($str,$tolower=true,$charset='utf-8'){

        $str = htmlentities($str,ENT_NOQUOTES,$charset);

        if($tolower){ $str = strtolower($str); }
        $str = trim($str);

        //Caractères alacon
        $str = preg_replace(array('#[.]#','#&(lt|gt)#'),'', $str);
        //Accents
        $str = preg_replace('#&([a-zA-Z]+)(amp|acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml)#','$1',$str);
        $str = preg_replace('#&([a-zA-Z]{2})(?:lig)#','$1', $str); // pour les ligatures e.g. '&oelig;'
        //On ne garde que les caractères alpha-numériques
        $str = preg_replace(array('#[\s]#','#[^a-zA-Z0-9\-]#'),'-', $str);
        //On nettoie les tirets
        $str = preg_replace(array('#[\-]{2,}#'),'-', $str);
        $str = preg_replace(array('#^[\-]#','#[\-]$#'),'', $str);

        return $str;
    }

    function unslug($string){
        $string = str_replace('-', ' ', $string);
        return $string;
    }

    function BBCode($data){
    
        $data = htmlentities($data,ENT_NOQUOTES,'UTF-8');
        $conv = array(
            '\[g\](.*?)\[\/g\]' => '<strong>$1</strong>',
            '\[i\](.*?)\[\/i\]' => '<em>$1</em>',
            '\[s\](.*?)\[\/s\]' => '<u>$1</u>',
            // - - - - - - - - - - - - - - - - - - - -
            '\[taille=(1|2|3|4|5|6)\](.*?)\[\/taille\]' => '<font size="$1">$2</font>', 
            '\[couleur=(red|blue|green|orange)\](.*?)\[\/couleur\]' => '<font color=$1>$2</font>',  
            '\[separation\]' => '<hr />',
            // - - - - - - - - - - - - - - - - - - - -
            '\[code=html\](.*?)\[\/code\]' => '<span class="syntaxColor"><h4 class="titleCode">Code html :</h4>$1</span>',
            '\[code=css\](.*?)\[\/code\]' => '<span class="syntaxColor"><h4 class="titleCode">Code css :</h4>$1</span>',
            '\[code=php\](.*?)\[\/code\]' => '<span class="syntaxColor"><h4 class="titleCode">Code php :</h4><span></span><span>?php </span>$1<span> ?></span></span>',
            // - - - - - - - - - - - - - - - - - - - -
            '\[img\](.*?)\[\/img\]' => '<a href="$1" class="zoombox zgallery1 thumbs"><img src="$1"/></a>',
            '\[lien=([^\]]*)\](.*)\[\/lien\]' => '<a href="$1">$2</a>', 
            '\[youtube\](.*?)\[\/youtube\]' => '<object width="700" height="400"><param name="movie" value="$1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="$1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="700" height="400"></embed></object>'
        );
        foreach($conv as $k => $v){
            $data = preg_replace('/'.$k.'/',$v,$data);
        }
        //---
        //debug($data);
        return nl2br($data);        
    } 

    //-------------------------------------------------------
    //-------------------------------------------------------

    //TIME
    //Retourne la date actuelle
    function currentDate(){
        $date = new DateTime();
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Convertit une date
     * On met par défaut le format de la date : 00/00/0000
     * $data par défaut -> affiche la date sous le format : 00 janvier 0000
     * $data = complete -> affiche la date sous le format : 00 janvier 0000 à 00h00
     **/
    function convertDate($dateVal, $format='date'){ 

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

    //-------------------------------------------------------
    //-------------------------------------------------------

    //Directories
    /**
     * Vide le dossier ciblé
     **/
    function voidFiles($dir,$ext,$age=NULL){
        // On ouvre le dossier.
        $rep = opendir($dir);

        // On lance notre boucle qui lira les fichiers un par un
        while(false !== ($file = readdir($rep))){
            // On met le chemin du fichier dans une variable simple
            $road = $dir."/".$file;

            // Les variables qui contiennent toutes les infos nécessaires
            $infos = pathinfo($road);
            $extFile = $infos['extension'];
            $ageFile = time() - filemtime($road);

            // On n'oublie pas LA condition sous peine d'avoir quelques surprises
            if($file!="." && $file!=".." && !is_dir($file) && $extFile == $ext && $ageFile > 3600*$age){
                unlink($road);
            }
        }
        closedir($rep);
    }

}