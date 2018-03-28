<?php

require("php/connection.php");
require 'uploadEventImage.php';

session_start();

date_default_timezone_set('America/Winnipeg');
$date = strftime('%Y-%m-%dT%H:%M:%S', time());

if(isset($_SESSION['email']))
{
	//User Info
	$queryId = "SELECT userId FROM users WHERE email = :email;";
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
	$queryEvents = "SELECT description, eventName,pictureDirectory, approved,firstName,lastName FROM events,users WHERE userId = creatorId ORDER BY eventId DESC;";
	$statementEvents = $db->prepare($queryEvents);
	$statementEvents->execute();
	$posts = $statementEvents->fetchAll();

}

if(isset($_POST['submitEvent']))
{
	$eventName = filter_var($_POST['eventName'],FILTER_SANITIZE_STRING);
	$description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);

	$imageDirectory = 'images' . DIRECTORY_SEPARATOR. 'events'.DIRECTORY_SEPARATOR.'default.png';

	if($_FILES['eventPicture']['name'] != '' && $_FILES['eventPicture']['error'] == 0)
	{
		$fileName = basename($_FILES['eventPicture']['name']);
		$imageDirectory = uploadEventImage($fileName ,$eventName);
	}

	$queryEventInsert = "INSERT INTO events (creatorId,eventName,description,pictureDirectory,approved) VALUES (:userId,:eventName,:description,:image,0);";
	$statementEventInsert = $db->prepare($queryEventInsert);
	$statementEventInsert->bindValue(':userId',$userId['userId']);
	$statementEventInsert->bindValue(':eventName',$eventName);
	$statementEventInsert->bindValue(':description',$description);
	$statementEventInsert->bindValue(':image',$imageDirectory);
	$statementEventInsert->execute();

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
	<?php if (isset($_SESSION['email'])) :?>
	<div class="jumbotron">
		<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">
    		<div class="hovereffect">
        		<img class="img-responsive" src="<?=$_SESSION['profilePicture']?>" alt="">
            	<div class="overlay">
						<p>
							<a href="#">Profile</a>
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
	<div class="col-lg-3 mr-1 border">
    			<h4 class="mb-3">Make a new Event.</h4>
    			<button class="btn btn-primary mb-3" id="makePlan">Make Plans!</button>
    			<form enctype="multipart/form-data" method="post" style="display: none;" id="eventForm">
    				<h2>Whats the Plan?</h2>
					<div class="form-group-row">
						<label for="eventName" class="col-form-label">Name of Event</label>
						<input class="col-md-12 m-auto form-control" type="text" name="eventName" required>
					</div>
					<div class="form-group-row">
						<label for="description" class=" col-form-label">Description</label>
						<textarea class="m-auto col-md form-control" rows="5" name="description" id="description"></textarea>
					</div>
					<!-- <div class="form-group-row">
						<label for="date" class="col-form-label">Date</label>
						<input class="col-md-12 m-auto form-control" value="<?=$date?>" type="datetime-local" name="date" required>
					</div> -->
					<div class="form-group-row">
						<label for="eventPicture" class="pl-0 mb-5 float-left col-md-12 col-form-label">Picture<input class="form-control-file" type="file" name="eventPicture" id="eventPicture"></label>					
					</div>
					<div class="form-group-row mt-3 mb-3">
						<button type="submit" id="submitEvent" class="m-auto btn btn-primary" name="submitEvent">Submit</button>
					</div>
				</form>
			</div>
	<div class='col-lg-6 border'> 		
				<?php if(empty($posts)): ?>
				<p>Hmm, nothing is here.</p>
			<?php else: ?>
				<div class="m-3">
				<h2>Share and Vote.</h2>
				 <?php foreach ($posts as $post):?>
				  <div class="card p-3 mb-3">
				  	<h5><?=$post['eventName']?></h5>
				  	<div class="p-3">
				  		<img class="img-fluid" src="<?=$post['pictureDirectory']?>">
				  	</div>
				  	<h6>Description</h6>
				  	<p><?=$post['description']?></p>
				  	<small>Proposed by: <?=$post['firstName'].' '.$post['lastName']?></small>
				  </div>
				 <?php endforeach?>
				</div>
			<?php endif ?>
    		</div>
  		</div>
	</div>
<?php else: header('Location: index.php');?>
<?php endif?>

</body>
</html>