<?php
//TO DO:
//User can delete their events 5 DONE
//Link to edit event Link to unique Profile 5
//Add composer project 5
//Add captcha 5
require("php/connection.php");
require 'uploadEventImage.php';

session_start();

date_default_timezone_set('America/Winnipeg');
$date = strftime('%Y-%m-%dT%H:%M:%S', time());

if(isset($_SESSION['email']) && isset($_GET['event']))
{
	//User Info
	$queryId = "SELECT userId,isAdmin FROM users WHERE email = :email;";
	$statementId = $db->prepare($queryId);
	$statementId->bindValue(':email',$_SESSION['email']);
	$statementId->execute();
	$userId = $statementId->fetch();

	//Page info
	$queryUserInfo = "SELECT color, profilePicture FROM userPages WHERE creatorId = :userId;";
	$statementUserInfo = $db->prepare($queryUserInfo);
	$statementUserInfo->bindValue(':userId', $userId['userId']);
	$statementUserInfo->execute();
	$userInfo = $statementUserInfo->fetch();

	$_SESSION['theme'] = $userInfo['color'].'theme.css';
	$_SESSION['profilePicture'] = $userInfo['profilePicture'];

	//Posts
	$queryEvents = "SELECT creatorId, description, eventName,pictureDirectory, approved,firstName,lastName 
				    FROM events,users 
				    WHERE userId = creatorId AND eventId = :selectedEvent ORDER BY eventId DESC;";
	$statementEvents = $db->prepare($queryEvents);
	$statementEvents->bindValue(':selectedEvent', $_GET['event']);
	$statementEvents->execute();
	$post = $statementEvents->fetch();

}

if(isset($_POST['updateEvent']))
{

	$eventName = filter_var($_POST['eventName'],FILTER_SANITIZE_STRING);
	$description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);

	$updateEvent = "UPDATE events 
					SET eventName = :eventName,
					description = :description
					WHERE eventId = :selectedEvent;";
	$updateStatement = $db->prepare($updateEvent);
	$updateStatement->bindValue(':eventName', $eventName);
	$updateStatement->bindValue(':description', $description);
	$updateStatement->bindValue(':selectedEvent', $_GET['event']);
	$updateStatement->execute();

	if($_FILES['eventPicture']['name'] != '' && $_FILES['eventPicture']['error'] == 0)
	{
		$fileName = basename($_FILES['eventPicture']['name']);
		$imageDirectory = uploadEventImage($fileName ,$_GET['event'],$db);
	}

	header('Location: main.php');

}



?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="styles/<?=$_SESSION['theme']?>">
	<link rel="stylesheet" type="text/css" href="styles/main.css">
	<script type="text/javascript" src="js/main.js"></script>
	<title>Lets Make Plans :) </title>
</head>
<body>
	<?php if (isset($_SESSION['email']) && ($userId['userId'] == $post['creatorId'] || $userId['isAdmin']))  :?>
	<div class="jumbotron">
		<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">
			<!-- https://miketricking.github.io/bootstrap-image-hover -->
    		<div class="hovereffect">
        		<img class="img-responsive" src="<?=$_SESSION['profilePicture']?>" alt="profilePicture">
            	<div class="overlay">
            		<p>
							<a href="main.php">Home</a>
						</p>
						<p>
							<a href="profilePage.php">Profile</a>
						</p>
						<p>
							<a href="logout.php">Logout</a>
						</p>
            	</div>
    		</div>
		
		<h1><?=$_SESSION['usersName']?></h1>
		<h2 class="display-1 text-muted display-4" >Lets make plans...</h2>
	</div>
    </div>
</div>
<div class="row mx-auto">
	<div class="col-lg-5 mr-1 border mx-auto">
    	<form enctype="multipart/form-data" method="post" id="eventForm">
    		<h2><?=$post['eventName']?></h2>
			<div class="form-group-row">
				<label for="eventName" class="col-form-label">Name of Event</label>
				<input class="col-md-12 m-auto form-control" type="text" name="eventName" required value="<?=$post['eventName']?>">
			</div>
			<div class="form-group-row">
				<label for="description" class=" col-form-label">Description</label>
				<textarea class="m-auto col-md form-control" rows="5" name="description" id="description"><?=$post['description']?></textarea>
			</div>
			<div class="form-group-row">
				<label for="eventPicture" class="pl-0 mb-5 float-left col-md-12 col-form-label">Picture<input class="form-control-file" type="file" name="eventPicture" id="eventPicture"></label>					
			</div>
			<div class="form-group-row">
				<img src="<?=$post['pictureDirectory']?>">			
			</div>
			<div class="form-group-row mt-3 mb-3">
				<button type="submit" id="updateEvent" class="m-auto btn btn-primary" name="updateEvent">Update</button>
				<a class="float-right" href="deletePost.php?event=<?=$_GET['event']?>">delete</a>
			</div>
		</form>
	</div>
</div>
<?php else: header('Location: main.php');?>
<?php endif?>
</body>
</html>