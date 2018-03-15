<?php
	require("php/connection.php");

	session_start();

	if(isset($_POST['submit']))
	{

		$email = filter_var(filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL),FILTER_VALIDATE_EMAIL);
		//To be hashed!

		$query = "SELECT email, password FROM users WHERE email = :email;";
		$statement = $db->prepare($query);
		$statement->bindValue(':email',$email);
		$statement->execute();
		$result = $statement->fetch();

		if(empty($result) || !password_verify($_POST['password'],$result ['password'] ))
		{
			$_SESSION['message'] = 'Invalid Email or Password.';
		}
	
		print_r($result);
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
		<h1>Login</h1>
	</div>
	<div class="row align-items-center justify-content-center">
		<form method="post">
			<div class="form-group-row" id="login">
				<label for="email" class="col-sm-2 col-form-label">Email</label>
				<div class="col-sm-10">
					<input class="form-control" type="email" name="email" required>
				</div>
				<label for="password" class="col-sm-2 col-form-label">Password</label>
				<div class="col-sm-10">
					<input class="form-control" type="password" name="password" required>
				</div>	
			</div>
			<div class="col-lg-1 col-offset-6 centered">
			<button type="submit" class="btn btn-primary" name="submit">
			Submit</button>
			<?php if(isset($_SESSION['message']))	:?>
				<p>Invalid Email or Password.</p>
			<?php unset($_SESSION['message']); endif ?>
			</div>
			<p>New user? Come join us!</p>
			<a href="register.php">Sign up</a>
		</form>
	</div>
</body>
</html>