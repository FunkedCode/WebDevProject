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

		print_r($email);
		print_r($lastName);
		print_r($firstName);
		print_r($password);
		print_r($confirmPassword);

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

					header("Location: login.php");
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
	<title>Lets Make Plans :) </title>
</head>
<body>
	<div class="jumbotron text-center">
		<h1>Sign Up!</h1>
	</div>
	<div class="row align-items-center justify-content-center">
		<form method="post">
			<div class="form-group-row" id="login">
				<label for="firstName" class="col-lg-10 col-form-label">First Name:</label>
				<div class="col-lg-10">
					<input class="form-control" type="text" name="firstName" required>
				</div>
				<label for="lastName" class="col-lg-10 col-form-label">Last Name:</label>
				<div class="col-lg-10">
					<input class="form-control" type="text" name="lastName" required>
				</div>
				<label for="email" class="col-lg-10 col-form-label">Email</label>
				<div class="col-lg-10">
					<input class="form-control" type="email" name="email" required>
				</div>
				<label for="password" class="col-lg-10  col-form-label">Password</label>
				<div class="col-lg-10">
					<input class="form-control" type="password" name="password" required>
				</div>
				<label for="confirm-password" class="col-lg-10  col-form-label">Confirm Password</label>
				<div class="col-lg-10">
					<input class="form-control" type="password" name="confirm-password" required>
				</div>	
			</div>
			<div class="col-lg-1 col-offset-6 centered">
			<button type="submit" class="btn btn-primary" name="submit">
			Sign up</button>
			</div>
		</form>
		<?php if(isset($_SESSION['errorMessage']))	:?>
				<p><?=$_SESSION['errorMessage']?></p>
		<?php unset($_SESSION['errorMessage']); endif ?>
	</div>
</body>
</html>