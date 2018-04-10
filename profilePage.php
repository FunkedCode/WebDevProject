<?php

require("php/connection.php");
require 'uploadImage.php';

session_start();

date_default_timezone_set('America/Winnipeg');
$date = date("Y-m-d");
$successMessage = "";

if(isset($_SESSION['email']))
{
	//User Info
	$queryId = "SELECT userId, lastName FROM users WHERE email = :email;";
	$statementId = $db->prepare($queryId);
	$statementId->bindValue(':email',$_SESSION['email']);
	$statementId->execute();
	$userId = $statementId->fetch();

}

if(isset($_POST['addDate']))
{
	$date = preg_replace("([^0-9/])", "", $_POST['date']);

	if($date != null)
	{
		$addDate = "INSERT INTO availabledays (creatorId,date) VALUES(:userId, :date);";
		$addDateStatement = $db->prepare($addDate);
		$addDateStatement->bindValue(':userId',$userId['userId']);
		$addDateStatement->bindValue(':date',$date);
		$addDateStatement->execute();

		$successMessage = "Date Added!";
	}
}

if(isset($_POST['changeProfile']))
{
	$imageDirectory = 'images' . DIRECTORY_SEPARATOR. 'userprofile'.DIRECTORY_SEPARATOR.'default.png';
	
	if($_FILES['profilePic']['name'] != '' && $_FILES['profilePic']['error'] == 0)
	{
		$fileName = basename($_FILES['profilePic']['name']);
		$imageDirectory = uploadImage($fileName ,$userId['lastName']);
	}

	$updateProfile = "UPDATE userpages 
					SET color = :color,
					profilePicture = :picture
					WHERE creatorId = :userId;";
	$updateProfileStatement = $db->prepare($updateProfile);
	$updateProfileStatement->bindValue(':color', $_POST['color']);
	$updateProfileStatement->bindValue(':picture', $imageDirectory);
	$updateProfileStatement->bindValue(':userId', $userId['userId']);
	$updateProfileStatement->execute();

	header('Location: main.php');

}


?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="styles/<?=$_SESSION['theme']?>">
	<link rel="stylesheet" type="text/css" href="styles/main.css">
	<script src="js/profile.js"></script>
	<title>Lets Make Plans :) </title>
</head>
<body>
	<?php if (isset($_SESSION['email'])) :?>
		<div class="jumbotron" id="header">
			<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">
				<!-- https://miketricking.github.io/bootstrap-image-hover -->
				<div class="hovereffect">
					<img class="img-responsive" src="<?=$_SESSION['profilePicture']?>" alt="">
					<div class="overlay">
						<p>
							<a href="main.php">Home</a>
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
	<div class="row">
		<div class="ml-5 col-lg-6">
			<form method="post" enctype="multipart/form-data">
				<div class="form-group-row">
					<h3 class="">Change it up!</h3>
					<label for="profilePic" class="pl-1 mb-5 float-left col-md-12 col-form-label">Profile Pic<input class="form-control-file" type="file" name="profilePic" id="profilePic"><img class="pt-3" src="images\userprofile\default.png"></label>					
				</div>
				<div class="form-group-row mt-3">
					<div class="radio">
						<label><input type="radio" name="color" value="white" checked>Classic White</label>
					</div>
					<div class="radio">
						<label><input type="radio" name="color" value="black" selected>Night</label>
					</div>
				</div>
				<div class="form-group-row pt-5">
					<button type="submit" class=" m-auto btn btn-primary" name="changeProfile">Change Profile</button>
				</div>
				<div class="m-auto form-group-row">
					<div id="newUser" class="centered">
						<?php if(isset($_SESSION['errorMessage']))	:?>
							<p><?=$_SESSION['errorMessage']?></p>
							<?php unset($_SESSION['errorMessage']); endif ?>
						</div>
					</div>
				</form>
			</div>
			<div class=" col-lg-5">
				<h2 class="pb-3 pt-3">When are you Available?</h2>
				<form method="post">
					<input id="date" name="date" type="date" min="<?=$date?>" value="<?=$date?>">
					<button type="submit" class=" m-auto btn btn-primary" name="addDate">Add Availablity</button>
				</form>
				<?php if ($successMessage != "") :?>
					<p><?=$successMessage?></p>
				<?php endif ?>
			</div>
		</div>
	<?php else: header('Location: index.php');?>
	<?php endif?>
</body>
</html>