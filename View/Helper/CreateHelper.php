<?php 
class CreateHelper extends AppHelper{

    //Remplace les caractères spéciaux et les espaces d'une chaîne de caractères
    function slug($str,$tolower=NULL,$charset='utf-8'){

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

}?>