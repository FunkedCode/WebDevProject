<?php
	require("php/connection.php");

	session_start();

	if(isset($_POST['submit']))
	{
		$firstName = filter_input(INPUT_POST, 'firstName',FILTER_SANITIZE_STRING);
		$lastName = filter_input(INPUT_POST, 'lastName',FILTER_SANITIZE_STRING);
		$password = filter_input(INPUT_POST, 'password',FILTER_SANITIZE_STRING);
		$confirmPassword = filter_input(INPUT_POST, 'confirm-password',FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, 'email',FILTER_SANITIZE_EMAIL);

		$validData = ($email && $confirmPassword && $password && $lastName && $firstName)? true:false;

		$emailExists = false;

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
					$hash = password_hash($password,PASSWORD_BCRYPT);

					$query = "INSERT INTO users (email,password,firstName,lastName,isAdmin) VALUES (:email,:password,:firstName,:lastName,0);";
					$statement = $db->prepare($query);
					$statement->bindValue(':firstName',$firstName);
					$statement->bindValue(':lastName',$lastName);
					$statement->bindValue(':password',$hash);
					$statement->bindValue(':email',$email);
					$statement->execute();

					header("Location: index.php");
				}
				else
				{
					$_SESSION['errorMessage'] = "Unhelpful Robot: Your password does not match 'Confirm Password', try again.";
				}
				
			}
			else
			{
				$_SESSION['errorMessage'] = "Unhelpful Robot: It seems this email is in use.";
			}
		}
		else
		{
			$_SESSION['errorMessage'] = "Unhelpful Robot: Invalid input detected, beep boop";
		}

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
	<div class="jumbotron text-center">
		<h1>Sign Up!</h1>
	</div>
	<div class="container">
		<div class="row align-items-center justify-content-center">
		<div class="col-md">
				<form method="post">
					<div class="form-group-row">
						<label for="firstName" class="pl-1 float-left col-form-label col-md-2">First Name</label>
						<input class="float-left col-md-4 m-auto form-control" type="text" name="firstName" required>
						<label for="lastName" class=" float-left col-md-2 col-form-label">Last Name</label>
						<input class="float-left col-md-4 m-auto form-control" type="text" name="lastName" required>
					</div>
					<div class="form-group-row">
						<label for="email" class="pl-1 col-form-label">Email</label>
						<input class="col-md-12 m-auto form-control" type="email" name="email" required>
					</div>
					<div class="form-group-row">
						<label for="password" class="pl-1 col-form-label">Password</label>
						<input class="pl-1 m-auto col-md form-control" type="password" name="password" required>
					</div>
					<div class="form-group-row">
						<label for="confirm-password" class="pl-1 col-form-label">Confirm Password</label>
						<input class="m-auto col-md form-control" type="password" name="confirm-password" required>
					</div>
					<div class="form-group-row">
						<h3 class="pt-5">lets Customize!</h3>
						<label for="profilePic" class="pl-0 float-left col-md-1 col-form-label">Profile Pic</label>
						<input class="float-left col-md-3 m-auto form-control-file" type="file" name="profilePic">
						
					</div>
					<p>Can't decide? No worries we got a default pic you can use in the mean time.</p>
					<div class="form-group-row">
						<label class="col-form-label" for="color">Theme!</label>
						<div class="radio">
  							<label><input type="radio" name="color" value="1">Classic White</label>
						</div>
						<div class="radio">
  							<label><input type="radio" name="color" value="2">Night</label>
						</div>
					</div>
					<div class="form-group-row pt-5">
						<button type="submit" class=" m-auto btn btn-primary" name="submit">
						Submit</button>
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