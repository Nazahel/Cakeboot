<?php
class StrHelper extends AppHelper{

    /**
     * Limite le nombre de caractères d'une chaîne
     * @param $chaine = la chaine à limiter
     * @param $max = le nombre maximal de caractères
     * @param $word => 0 = coupe le dernier mot
     * @param $word => 1 = garde le dernier mot en entier
     * @param $end = rajouter un string à la fin
     **/
    function limit($chaine,$max,$word=true,$end=null){
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
        return nl2br($data);        
    } 
}
?>