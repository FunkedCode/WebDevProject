<?php
//TO DO:
//Add pretty links
//Delete profile picture (reset to default remove pic in directory)
require("php/connection.php");
require 'uploadEventImage.php';

session_start();

$_SESSION['error'] = null;

$orderBy = 'eventId DESC';

//Set order by
if(isset($_POST['orderBy']))
{
		if ($_POST['orderBy'] == 'name') 
		{
			$orderBy = 'eventName';
		}
		else if($_POST['orderBy'] == 'creator')
		{
			$orderBy = 'creatorId DESC';
		}
}

if(isset($_SESSION['email']))
{
	//User Info
	$queryId = "SELECT userId, isAdmin FROM users WHERE email = :email;";
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
	$queryEvents = "SELECT eventId,creatorId, description, eventName,pictureDirectory, approved,firstName,lastName, isAdmin
				    FROM events,users 
				    WHERE userId = creatorId
				    AND userId = :userId
				    ORDER BY $orderBy;";
	$statementEvents = $db->prepare($queryEvents);
	$statementEvents->bindValue(':userId', $userId['userId']);
	$statementEvents->execute();
	$posts = $statementEvents->fetchAll();

	//Votes
	$userVotes = "SELECT event,user,votes FROM votes;";
	$statementUserVotes = $db->prepare($userVotes);
	$statementUserVotes->execute();
	$votes = $statementUserVotes->fetchAll();

}

//Create a new event
if(isset($_POST['submitEvent']))
{
	$eventName = filter_var($_POST['eventName'],FILTER_SANITIZE_STRING);
	$description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
	
	$queryEventInsert = "INSERT INTO events (creatorId,eventName,description,approved) VALUES (:userId,:eventName,:description,0);";
	$statementEventInsert = $db->prepare($queryEventInsert);
	$statementEventInsert->bindValue(':userId',$userId['userId']);
	$statementEventInsert->bindValue(':eventName',$eventName);
	$statementEventInsert->bindValue(':description',$description);
	$statementEventInsert->execute();

	$eventId = $db->lastInsertId();

	$imageDirectory = 'images' . DIRECTORY_SEPARATOR.'events'.DIRECTORY_SEPARATOR.'default.png';

	if($_FILES['eventPicture']['name'] != '' && $_FILES['eventPicture']['error'] == 0)
	{
		$fileName = basename($_FILES['eventPicture']['name']);
		$imageDirectory = uploadEventImage($fileName ,$eventId,$db);
	}

	$updateImage = "UPDATE events 
					SET pictureDirectory = :image
					WHERE eventId = :selectedEvent;";

	$updateStatement = $db->prepare($updateImage);
	$updateStatement->bindValue(':image', $imageDirectory);
	$updateStatement->bindValue(':selectedEvent', $eventId);
	$updateStatement->execute();

	//Add event to voteing table
	$queryVote = "INSERT INTO votes (event,user,votes) VALUES (:event,:userId,1);";
	$statementVote = $db->prepare($queryVote);
	$statementVote->bindValue(':userId',$userId['userId']);
	$statementVote->bindValue(':event',$eventId);
	$statementVote->execute();

	header('Location: userEvents.php');

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
	<script type="text/javascript" src="js/main.js"></script>
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
	<div class="section col-lg-3 mx-auto border rounded">
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
			<div class="form-group-row">
				<label for="eventPicture" class="pl-0 mb-5 float-left col-md-12 col-form-label">Picture<input class="form-control-file" type="file" name="eventPicture" id="eventPicture"></label>					
			</div>
			<div class="form-group-row mt-3 mb-3">
				<button type="submit" id="submitEvent" class="m-auto btn btn-primary" name="submitEvent">Submit</button>
			</div>
		</form>
	</div>
	<div class='col-lg-6 border rounded'> 		
		<?php if(empty($posts)): ?>
			<p>Hmm, nothing is here.</p>
		<?php else: ?>
			<div class="m-3">
				<form method="post" class="float-right form-inline">
					<label>Sort By: </label>
					<div class="form-group">
				 	<select class="form-control ml-3" size="1" name="orderBy" onchange="this.form.submit()">
				 		<option value="date" <?php if($orderBy == 'eventId DESC'):?> selected <?php endif?> >Date Proposed</option>
				 		<option value="name" <?php if($orderBy == 'eventName') :?> selected <?php endif?> >Name</option>
				 		<option value="creator" <?php if($orderBy == 'creatorId DESC') :?> selected <?php endif?> >Host</option>	
				 	</select>
				 </div>
				 </form>
				<h2>Share and Vote.</h2>
				<?php if (isset($_SESSION['error'] )) :?>				 
				  <div class="alert alert-warning">
  					<strong><?=$_SESSION['error']?></strong>
				 </div>
				 <?php endif?>
				 
				 <?php foreach ($posts as $post):?>
				  	<div class="bg-light card p-3 mb-3">
				  		<h5><?=$post['eventName']?></h5>
				  		<div class="p-3">
				  			<img class="img-fluid" src="<?=$post['pictureDirectory']?>">
				  		</div>
				  		<h6>Description</h6>
				  		<p><?=$post['description']?></p>		  		
				  		<div class="mb-3">	  			
				  			<?php $voteSum = 0; foreach ($votes as $vote) :?>
				  				<?php if ($vote['event'] == $post['eventId']) :?>
				  					<?php $voteSum++;?>
								<?php endif ?>
				  			<?php endforeach?>	
				  		</div>
				  		<div class="container pl-0">
							<div class="float-left">		  		
				  				<small>Proposed by: <?=$post['firstName'].' '.$post['lastName']?></small>
				  				<?php if ($post['creatorId'] == $userId['userId'] || $userId['isAdmin'] == 1) :?>		  			
				  					<p><a href="editPost.php?event=<?=$post['eventId']?>">edit</a>
				  				<?php endif?>
				  			</div>
				  			<div class="float-right">
				  				<h6>Want to do this?</h6>
				  				<form method="post" class="float-right">
				  					<input type="submit" class="btn btn-primary input-sm" value="Yes!" name="yes" />
				  					<input type="hidden" name="eventVoted" id="hiddenField" value="<?=$post['eventId']?>" />
				  				</form>
				  			</div>
				  		</div>
				  	</div>
				 <?php endforeach?>
			</div>
		<?php endif ?>
    </div>
    <div class="section col-lg-2 mx-auto border rounded float-right">
    	<h3>Even More!</h3>
    	<ul class="list-group">
  			<a href="#" class="btn disabled"><li class="list-group-item mt-3">Your Events</li></a>
  			<a href="bugreport.php" class="btn"><li class="list-group-item mt-3">Report a Bug</li></a>
		</ul>
    </div>
</div>
<?php else: header('Location: index.php');?>
<?php endif?>
</body>
</html>