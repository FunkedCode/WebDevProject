<?php
require("php/connection.php");

session_start();

if(isset($_SESSION['email']))
{
	//User Info
	$queryId = "SELECT userId FROM users WHERE email = :email;";
	$statementId = $db->prepare($queryId);
	$statementId->bindValue(':email',$_SESSION['email']);
	$statementId->execute();
	$userId = $statementId->fetch();

	$queryUserInfo = "SELECT color, profilePicture FROM userPages WHERE creatorId = :userId;";
	$statementUserInfo = $db->prepare($queryUserInfo);
	$statementUserInfo->bindValue(':userId', $userId['userId']);
	$statementUserInfo->execute();
	$userInfo = $statementUserInfo->fetch();

	$_SESSION['theme'] = $userInfo['color'].'theme.css';
	$_SESSION['profilePicture'] = $userInfo['profilePicture'];

	//Posts
	$queryEvents = "SELECT date,description, eventName,pictureDirectory, approved,firstName,lastName FROM events,users WHERE userId = creatorId;";
	$statementEvents = $db->prepare($queryEvents);
	$statementEvents->execute();
	$posts = $statementEvents->fetchAll();

	//print_r($posts);

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
	<div class='container'>
		<div class="row mx-auto">
    		<div class="col-md">
				<h3 class="pb-3">Login was a success!</h3>
				<?php if(empty($posts)): ?>
				<p>Hmm, nothing is here.</p>
			<?php else: 
				  foreach ($posts as $post):?>
				  <div class="card p-2">
				  	<h4><?=$post['eventName']?></h4>
				  	<h5>Description</h5>
				  	<p><?=$post['description']?></p>
				  	<strong><?=$post['date']?></strong>
				  	<small>Proposed by: <?=$post['firstName'].' '.$post['lastName']?></small>
				  </div>
				 <?php endforeach?>
			<?php endif ?>
    		</div>
  		</div>
	</div>
<?php else: header('Location: index.php');?>
<?php endif?>

</body>
</html>