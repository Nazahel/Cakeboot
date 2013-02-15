<?php
App::uses('AppHelper', 'View/Helper');

class SkillHelper extends AppHelper{

    //Définit le lien courrant
    public function currentLink($page,$comp,$addClass=0){
        $pages = explode('|',$page);
        foreach($pages as $p):

            if($p == $comp):
                if($addClass == 0){
                    return ' class="active"';
                }
                elseif($addClass == 1){
                    return 'active';
                }
                elseif($addClass == 2){
                    return ' active';
                }
            endif;
            
        endforeach;
    }

    /**
     * Dossiers
     **/
    //Vide le dossier ciblé
    function VoidFiles($dir,$ext,$age=NULL){
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

    //Unset
    function unvar($d=array()){
        foreach($d as $v){
            unset($v);	
        }
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
    function slug($str,$tolower=1,$charset='utf-8'){

        $str = htmlentities($str,ENT_NOQUOTES,$charset);

        if($tolower = 1){ $str = strtolower($str); }
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

    /**
     * Convertit les points en level
     *
     **/
    function level($Pts){
        $grid = array(
            1 => 50,
            2 => 200,
            3 => 600,
            4 => 1000,
            5 => 2500
        );
        $lvl = 0;
        foreach($grid as $k=>$v):
            if($Pts >= $v){
                $lvl = $k;
            }
        endforeach;
        return 'lvl '.$lvl;
    }

    /**
     * Upload une image
     *
     *
     **/
    function uploadImg($array_FILES,$dos,$imgName){ global $Message;

        $app_message = NULL;

        //Upload de l'image dans un dossier
        $img = $array_FILES;
        $ext = strtolower(substr($img['name'],-3));
        $extAut = array('jpg','png','gif');

        //On vérifie si le champs de l'image est vide
        if($img['name'] == NULL){
            $erreur = 'Vous devez choisir une photo';
        }
        else{
            //On vérifie sir le fichier est bien téléchargé
            if(is_uploaded_file($img['tmp_name'])){

                $app_message .= $Message->valid("Fichier ".$imgName.".".$ext." téléchargé avec succès");

                //On vérifie les extensions
                if(in_array($ext,$extAut)){

                    move_uploaded_file($img['tmp_name'],'img/'.$dos.'/'.$imgName.'.'.$ext);

                    $app_message .= $Message->valid("Fichier ".$imgName.".".$ext." déplacé avec succès");

                    //on recréer l'image en .jpg si différent
                    if($ext != 'jpg'){
                        include('class/RedImg.class.php');
                        RedImg::convertirImg('img/'.$dos.'/'.$imgName.'.'.$ext);

                        $app_message .= $Message->valid("Fichier ".$imgName.".".$ext." convertit avec succès");
                    }

                }
                else{
                    $app_message .= 'Les fichiers autorisés sont les .jpg, .png et .gif';
                }
            }
            else{
                $app_message .= "Attaque possible par téléchargement de fichier : ";
                $app_message .= "Nom du fichier : '". $img['tmp_name'] . "'.";
            }
        }
        return $app_message;		
    }

    /**
     * Nettoie les prefixes dans les liens
     */
    function cleanPrefixes($array){
        return array_merge($array, Configure::read('appli.CleanPrefixes'));
    }

    /**
     * Retourne la taille plus l'unité arrondie
     *
     * @param mixed $bytes taille en octets
     * @param string $format formatage (http://www.php.net/manual/fr/function.sprintf.php)
     * @param string $lang indique la langue des unités de taille
     * @return string chaine de caractères formatées
     */
    function formatSize($bytes,$format = '%.2f',$lang = 'fr')
    {
        static $units = array(
            'fr' => array(
                'o',
                'Ko',
                'Mo',
                'Go',
                'To'
            ),
            'en' => array(
                'B',
                'KB',
                'MB',
                'GB',
                'TB'
            )
        );
        $translatedUnits = &$units[$lang];
        if(isset($translatedUnits)  === false)
        {
            $translatedUnits = &$units['en'];
        }
        $b = (double)$bytes;
        /*On gére le cas des tailles de fichier négatives*/
        if($b > 0)
        {
            $e = (int)(log($b,1024));
            /**Si on a pas l'unité on retourne en To*/
            if(isset($translatedUnits[$e]) === false)
            {
                $e = 4;
            }
            $b = $b/pow(1024,$e);
        }
        else
        {
            $b = 0;
            $e = 0;
        }
        return sprintf($format.' %s',$b,$translatedUnits[$e]);
    }

}
?>