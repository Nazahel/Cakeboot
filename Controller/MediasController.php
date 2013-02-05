<?php App::uses('AppController', 'Controller');

class MediasController extends AppController {

	public $components = array('Cakeboot.Zimage');

    /**
    * Permet de cropper les images
    **/
    function imageCrop($file, $format){
        $this->autoRender = false;

    	//Security
        if(file_exists($file)){ die(); }

        $size  = explode('x',$format);
        $root  = ROOT.'/app/webroot/img/';
        $dest  = $root.$file.'_'.$format.'.jpg';
        $f     = str_replace('/thumbs','',$file);
        $fileo = $root.$f.'.jpg';
        $file  = $root.$file.'_'.$format.'.jpg';

		//Création thumbs
        if(file_exists($fileo) && end(explode('/',dirname($file))) == 'thumbs'){

            //Création du dossier thumbs s'il n'exite pas
    		if(!file_exists(dirname($file))){
    			mkdir(dirname($file),0777);
    		}

            //Création de la miniature
            if($this->Zimage->resizing($fileo, $dest, $size[0], $size[1])){
                header("Content-type: image/jpg");
                echo file_get_contents($dest);
                exit();
            }
        }
    }
}
