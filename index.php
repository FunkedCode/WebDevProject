<?php
require("php/connection.php");

session_start();

if(isset($_POST['submit']))
{

	$email = filter_var(filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL),FILTER_VALIDATE_EMAIL);

	$query = "SELECT email, password, firstName, lastName FROM users WHERE email = :email;";
	$statement = $db->prepare($query);
	$statement->bindValue(':email',$email);
	$statement->execute();
	$result = $statement->fetch();

	if(empty($result) || !password_verify($_POST['password'],$result['password'] ))
	{
		$_SESSION['message'] = 'Invalid Email or Password.';
	}
	else
	{
		$_SESSION['usersName'] = $result['firstName'].' '.$result['lastName'];
		$_SESSION['email'] = $result['email'];

		header("Location: main.php");
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
		<h1>Login</h1>
	</div>
	<div class="container">
		<div class="row align-items-center justify-content-center">
			<div class="col-md-4">
				<form method="post">
					<div class="form-group-row">
						<label for="email" class="col-form-label">Email</label>
						<input class="col-md-12 m-auto form-control" type="email" name="email" required>
					</div>
					<div class="form-group-row">
						<label for="password" class=" col-form-label">Password</label>
						<input class="m-auto col-md form-control" type="password" name="password" required>
					</div>
					<div class="form-group-row mt-3">
						<button type="submit" class="m-auto btn btn-primary" name="submit">
						Login</button>
					</div>
					<div class="m-auto form-group-row">
						<div id="newUser" class="centered">
							<?php if(isset($_SESSION['message']))	:?>
									<strong>Invalid Email or Password.</strong>
							<?php unset($_SESSION['message']); endif ?>
							<p class="pt-5">New user? Come join us!</p>
							<a href="register.php">Sign up</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>