<?php App::uses('Component', 'Controller');

/**
 * Work image with Zebra Image
 * http://stefangabos.ro/wp-content/docs/Zebra_Image/Zebra_Image/Zebra_Image.html
 * ////////////////////////
 * ZEBRA_IMAGE_CROP_TOPLEFT
 * ZEBRA_IMAGE_CROP_TOPCENTER
 * ZEBRA_IMAGE_CROP_TOPRIGHT
 * ZEBRA_IMAGE_CROP_MIDDLELEFT
 * ZEBRA_IMAGE_CROP_CENTER
 * ZEBRA_IMAGE_CROP_MIDDLERIGHT
 * ZEBRA_IMAGE_CROP_BOTTOMLEFT
 * ZEBRA_IMAGE_CROP_BOTTOMCENTER
 * ZEBRA_IMAGE_CROP_BOTTOMRIGHT
 */
class ZimageComponent extends Component{

	public function resizing($url, $dest, $width, $height, $options=array()){

		//On bloque la création d'image si le fichier existe ou si l'option d'overwrite est à false
		if(file_exists($dest) && $options['overwrite'] === false){ return false; }

		App::import('Vendor', 'Cakeboot.ZebraImage', array('file'=>'ZebraImage/ZebraImage.php'));

		$fileinfo           = pathinfo($url);
		$filesize           = getimagesize($url);
		$filesize['width']  = $filesize[0];
		$filesize['height'] = $filesize[1];
		
		//Création de l'image croppée
		$image = new Zebra_Image();
		$image->source_path = $url;
		$image->target_path = $dest;
		$image->resize($width, $height, ZEBRA_IMAGE_CROP_TOPCENTER);

		return true;
	}

}