<?php

require("php/connection.php");
require 'uploadimage.php';
use Gregwar\Captcha\CaptchaBuilder;

	
session_start();

$builder = new CaptchaBuilder;
$builder->build();

if(!isset($_SESSION['firstName']))
{
	$_SESSION['firstName'] = "First name";
	$_SESSION['lastName'] = "Last name";
	$_SESSION['email'] = "Email";
}


if(!isset($_POST['captcha']))
{
	$_SESSION['phrase'] = $builder->getPhrase();
}
	

if(isset($_POST['submit']))
{
	$firstName = filter_input(INPUT_POST, 'firstName',FILTER_SANITIZE_STRING);
	$lastName = filter_input(INPUT_POST, 'lastName',FILTER_SANITIZE_STRING);
	$password = filter_input(INPUT_POST, 'password',FILTER_SANITIZE_STRING);
	$confirmPassword = filter_input(INPUT_POST, 'confirm-password',FILTER_SANITIZE_STRING);
	$email = filter_var(filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL),FILTER_VALIDATE_EMAIL);

	$validData = ($email && $confirmPassword && $password && $lastName && $firstName);

	$emailExists = false;

	if($_SESSION['phrase'] == $_POST['captcha']) 
	{
		if($validData)
		{
			$emailCheckQuery = "SELECT email FROM users WHERE email = :email;";
			$emailStatement = $db->prepare($emailCheckQuery);
			$emailStatement->bindValue(':email',$email);
			$emailStatement->execute();
			$emailResult = $emailStatement->fetch();

			if(empty($emailResult))
			{
				if($password == $confirmPassword)
				{
						
						$imageDirectory = 'images' . DIRECTORY_SEPARATOR. 'userprofile'.DIRECTORY_SEPARATOR.'default.png';

						if($_FILES['profilePic']['name'] != '' && $_FILES['profilePic']['error'] == 0)
						{
							$fileName = basename($_FILES['profilePic']['name']);
							$imageDirectory = uploadImage($fileName,$lastName);
						}
		
						if($imageDirectory != "")
						{
							$hash = password_hash($password,PASSWORD_BCRYPT);

							$query = "INSERT INTO users (email,password,firstName,lastName,isAdmin) VALUES (:email,:password,:firstName,:lastName,0);";
							$statement = $db->prepare($query);
							$statement->bindValue(':firstName',$firstName);
							$statement->bindValue(':lastName',$lastName);
							$statement->bindValue(':password',$hash);
							$statement->bindValue(':email',$email);
							$statement->execute();

							$userId = $db->lastInsertId();
	
							$imageQuery = "INSERT INTO userpages (creatorId,color,profilePicture) VALUES (:userId,:color,:image);";
							$imageStatement = $db->prepare($imageQuery);
							$imageStatement->bindValue(':userId',$userId);
							$imageStatement->bindValue(':color',$_POST['color']);
							$imageStatement->bindValue(':image',$imageDirectory);
							$imageStatement->execute();

							header("Location: index.php");
						}
						else
						{
							$_SESSION['errorMessage'] = "Unhelpful Robot: Somthing went wrong with your profile picture. Try another one.";

							setUserSession();
						}
					
				}
				else
				{
					$_SESSION['errorMessage'] = "Unhelpful Robot: Your password does not match 'Confirm Password', try again.";

					setUserSession();
				}
				
			}
			else
			{
				$_SESSION['errorMessage'] = "Unhelpful Robot: It seems this email is in use.";

				setUserSession();
			}
		}
		else
		{
			$_SESSION['errorMessage'] = "Unhelpful Robot: Invalid input detected, beep boop";

			setUserSession();
		}
	}
	else
	{
		$_SESSION['errorMessage'] = "Unhelpful Robot: Invalid Captcha detected";

		$_SESSION['phrase'] = $builder->getPhrase();
		
		unset($_POST['captcha']);

		setUserSession();
	}

}

function setUserSession()
{
	$_SESSION['firstName'] = "First name";
	$_SESSION['lastName'] = "Last name";
	$_SESSION['email'] = "Email";
}

	

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="styles/login.css">
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/register.js"></script>
	<title>Lets Make Plans :) </title>
</head>
<body id="background">
	<div class="jumbotron text-center" id="header">
		<h1>Sign Up!</h1>
	</div>
	<div class="container">
		<div class="row align-items-center justify-content-center">
		<div class="col-md-6">
				<form method="post" enctype="multipart/form-data">
					<div class="form-group-row">
						<label for="firstName" class="pl-1 col-form-label">First Name</label>
						<input id="firstName" class="pl-1 m-auto col-md form-control" type="text" name="firstName" required value="<?=$_SESSION['firstName']?>">
					</div>
					<div class="form-group-row">
						<label for="lastName" class="pl-1 col-form-label">Last Name</label>
						<input id="lastName" class="pl-1 m-auto col-md form-control" type="text" name="lastName" value="<?=$_SESSION['lastName']?>" required>
					</div>
					<div class="form-group-row">
						<label for="email" class="pl-1 col-form-label">Email</label>
						<input id="email" class="pl-1 m-auto col-md form-control" type="email" name="email" value="<?=$_SESSION['email']?>" required>
					</div>
					<div class="form-group-row">
						<label for="password" class="pl-1 col-form-label">Password</label>
						<input id="password" class="pl-1 m-auto col-md form-control" type="password" name="password" required>
					</div>
					<div class="form-group-row">
						<label for="confirm-password" class="pl-1 col-form-label">Confirm Password</label>
						<input id="confirm-password" class="m-auto col-md form-control" type="password" name="confirm-password" required>
					</div>
					<div class="form-group-row">
						<h3 class="pt-5">lets Customize!</h3>
						<label for="profilePic" class="pl-1 mb-5 float-left col-md-12 col-form-label">Profile Pic<input class="form-control-file" type="file" name="profilePic" id="profilePic"><img class="pt-3" src="images/userprofile/default.png" alt="default image"></label>					
					</div>
					<div class="form-group-row mt-3">
						<div class="radio">
  							<label><input type="radio" name="color" value="white" checked>Classic White</label>
						</div>
						<div class="radio">
  							<label><input type="radio" name="color" value="black" selected>Night</label>
						</div>
					</div>
					<div class="form-group-row mt-3">
						<label for="captcha">No Robots Allowed!</label>
						<div class="form-group-row mt-3 mb-3">
							<img src="<?=$builder->inline(); ?>" alt="Captcha" />
						</div>
						<input id="captcha" type="text" name="captcha" placeholder="Enter Captcha">
					</div>
					<div class="form-group-row pt-5">
						<button type="submit" class=" m-auto btn btn-primary" name="submit">
						Sign up</button>
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
			</div>
		</div>
</body>
</html>