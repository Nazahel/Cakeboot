<?php
App::uses('AppHelper', 'View/Helper');

class DropfileHelper extends AppHelper{

    public $helpers = array('Html');

    /**
     *
     *
     *
     **/
    function uploadlist($dir, $model, $type='image'){

        //$type = ($type == 'image') ? IMAGES : FILES;

        $ext_array = array(
            'image' => array('jpg','jpeg','png','gif')
        );
        $str = '<div class="dropfile-container">';
        $str .= '<div class="dropfile" data-folder="'.$dir.'"></div>';

        

        foreach(glob(IMAGES.$dir.'*.jpg') as $file):

            $f = end(explode('/',$file));

            $str .= '<div class="dropfile" data-value="'.$f.'" data-folder="'.$dir.'">
                        '.$this->Html->image($dir.$f).'
                     </div>';
        endforeach;

        $str .= '</div>';

        $this->Html->css('uploader',null,array('inline'=>false));
        $this->Html->script('dropfile',array('inline'=>false));
        $this->Html->scriptStart(array('inline'=>false));
        ?>
        jQuery(function($){
           $('.dropfile').dropfile({
                script: '/nazahel-net/admin/medias/upload/<?php echo strtolower($model);; ?>'
           });  
        });
        <?php
        $this->Html->scriptEnd();

        return $str;
    }
    
}
?>