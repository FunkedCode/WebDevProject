<?php 
	require 'vendor/autoload.php';
	include './lib/ImageResize.php';
	use \Gumlet\ImageResize;
	
	function uploadEventImage($fileName,$eventName)
	{
		$newDirectory = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' .DIRECTORY_SEPARATOR. 'events'.DIRECTORY_SEPARATOR.$eventName;
		if(!file_exists($newDirectory))
		{
			mkdir($newDirectory, 0700,true);
			echo $newDirectory;
		}

		$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

		$imageLocation = $newDirectory.DIRECTORY_SEPARATOR.'event.'.$fileExtension;

		$acceptedTypes = ['JPEG','JPG','PNG','GIF'];

		if(in_array(strtoupper($fileExtension),$acceptedTypes))
		{
			move_uploaded_file($_FILES['eventPicture']['tmp_name'], $imageLocation);

			$imageMedium = new ImageResize($imageLocation);
			$imageMedium->scale(50);
			$imageMedium->save($newDirectory.DIRECTORY_SEPARATOR.'event.'.$fileExtension);

			return 'images' . DIRECTORY_SEPARATOR. 'events'.DIRECTORY_SEPARATOR. $eventName.DIRECTORY_SEPARATOR.'event.'.$fileExtension;
		}
		else
		{
			return null;
		}

	}
?>