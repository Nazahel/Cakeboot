<?php App::uses('AppHelper', 'View/Helper');

class LibraryHelper extends AppHelper{

    public $helpers = array('Html');

    function activityTextTransform($string, $options=array()){
        $c = array(
            '\[link\](.*?)\[\/link\]' => '<a href="'.$this->Html->url($options['link']).'">$1</a>',
            '(\[id\])' => $options['id']
        );
        foreach($c as $k=>$v){
            $string = preg_replace('/'.$k.'/', $v, $string);
        }
        debug($string);
        return $string;
    }
}
