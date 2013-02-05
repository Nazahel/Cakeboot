<?php App::uses('Component', 'Controller');

class ImagineComponent extends Component{

	public $uses   = array('Html');
	private $Box   = 'Imagine\Image\Box';
	private $Point = 'Imagine\Image\Point';

	public function cropping($url, $dest, $width, $height, $options=array()){

		$fileinfo           = pathinfo($url);
		$filesize           = getimagesize($url);
		$filesize['width']  = $filesize[0];
		$filesize['height'] = $filesize[1];

		//On bloque la création d'image si le fichier existe ou si l'option d'overwrite est à false
		if(file_exists($dest) && !$options['overwrite']){ return false; }

		App::import('Vendor', 'Cakeboot.Imagine', array('file'=>'imagine.phar'));

		$options['mode'] = (!isset($options['mode'])) ? 'outbound' : $options['mode'];

		//Instance Imagine
		$imagine = new Imagine\Gd\Imagine();

		if($width > 0 && $height > 0){
					
			$image = $imagine->open($url)
							 //->crop(new $this->Point(($filesize['width']-$width)/2, 0), new $this->Box($width, $height))
							 ->thumbnail(new $this->Box($width, $height), $options['mode'])
							 ->save($dest);

			return true;
		}
		else{
			debug(); die();
		}
	}

	public function upload($url){
		
	}

	public function grayscale($url){
		require_once('imagine.phar');

		$imagine = new Imagine\Gd\Imagine();
		$image = $imagine->open($url);

		$image->effects()->grayscale()->save('img/grayscale_test.jpg');

		return true;
	}

}