<?php
class CurrentHelper extends AppHelper{
    
    //Url de la page active
    function realUrl(){
        return "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    }
    
    //Date actuelle
    function date(){
        $date = new DateTime();
        return $date->format('Y-m-d H:i:s');
    }
    
}
?>