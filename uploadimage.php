<?php 
	require 'vendor/autoload.php';
	include './lib/ImageResize.php';
	use \Gumlet\ImageResize;
	
	function uploadImage($fileName,$usersName)
	{
		$newDirectory = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR. 'userprofile'.DIRECTORY_SEPARATOR. $usersName;

		if(!file_exists($newDirectory))
		{
			mkdir($newDirectory, 0700,true);
		}

		$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

		$imageLocation = $newDirectory.DIRECTORY_SEPARATOR.'profile.'.$fileExtension;

		$acceptedTypes = ['JPEG','JPG','PNG','GIF'];

		if(in_array(strtoupper($fileExtension),$acceptedTypes))
		{
			move_uploaded_file($_FILES['profilePic']['tmp_name'], $imageLocation);

			$imageMedium = new ImageResize($imageLocation);
			$imageMedium->resizeToBestFit(200,200);
			$imageMedium->save($newDirectory.DIRECTORY_SEPARATOR.'profile.'.$fileExtension);

			return 'images' . DIRECTORY_SEPARATOR. 'userprofile'.DIRECTORY_SEPARATOR. $usersName.DIRECTORY_SEPARATOR.'profile.'.$fileExtension;
		}
		else
		{
			return null;
		}

	}
?>