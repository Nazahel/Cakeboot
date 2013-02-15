<?php
App::uses('AppHelper', 'View/Helper');

class UploadHelper extends AppHelper{

    public $helpers = array('Html');

    /**
     *
     *
     *
     **/
    function modalgroup($dir, $model, $files, $id=null){

        $str  = '<div id="uploadfile-container">';
        $str .= '<div class="dropfile"><a href="'.$this->Html->url(array('controller'=>'medias','action'=>'upload', $model, $id)).'" class="button primary modalicious" title="Ajouter un fichier | Administration">Ajouter une image</a></div>';

        foreach($files as $file):
            $f = end(explode('/',$file));
            $str .= '<div class="dropfile" data-value="'.$f.'" data-folder="'.$dir.'">
                        '.$this->Html->image($dir.'/'.$f).'
                     </div>';
        endforeach;

        $str .= '</div>';

        $this->Html->css('uploader',null,array('inline'=>false));
        return $str;
    }
    
}
?>