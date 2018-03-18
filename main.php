<?php
require("php/connection.php");

session_start();

if(isset($_SESSION['email']))
{
	$queryId = "SELECT userId FROM users WHERE email = :email;";
	$statementId = $db->prepare($queryId);
	$statementId->bindValue(':email',$_SESSION['email']);
	$statementId->execute();
	$userId = $statementId->fetch();

	$query = "SELECT color, profilePicture FROM userPages WHERE creatorId = :userId;";
	$statement = $db->prepare($query);
	$statement->bindValue(':userId', $userId['userId']);
	$statement->execute();
	$result = $statement->fetch();

	$_SESSION['theme'] = $result['color'].'theme.css';
	$_SESSION['profilePicture'] = $result['profilePicture'];


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
	<title>Lets Make Plans :) </title>
</head>
<body>
	<?php if (isset($_SESSION['email'])) :?>
	<div class="jumbotron">
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
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
		</div>
		<h1><?=$_SESSION['usersName']?></h1>
    </div>
</div>
	<div class="container">
		<h3>Login was a success!</h3>
		<p>A users main feed will go here, links to their profile as well as admin tasks if admin will also appear here in future milestones.</p>
	</div>
<?php else: header('Location: index.php');?>
<?php endif?>

</body>
</html>