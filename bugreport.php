<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\Exception;
use League\OAuth2\Client\Provider\Google;

require("php/connection.php");
require 'uploadEventImage.php';

require 'vendor/autoload.php';

session_start();

date_default_timezone_set('America/Winnipeg');

// //Create a new PHPMailer instance
// $mail = new PHPMailer;
// //Tell PHPMailer to use SMTP
// $mail->isSMTP();
// //Enable SMTP debugging
// // 0 = off (for production use)
// // 1 = client messages
// // 2 = client and server messages
// $mail->SMTPDebug = 2;
// //Set the hostname of the mail server
// $mail->Host = 'smtp.gmail.com';
// //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
// $mail->Port = 587;
// //Set the encryption system to use - ssl (deprecated) or tls
// $mail->SMTPSecure = 'tls';
// //Whether to use SMTP authentication
// $mail->SMTPAuth = true;
// //Set AuthType to use XOAUTH2
// $mail->AuthType = 'XOAUTH2';
// //Fill in authentication details here
// //Either the gmail account owner, or the user that gave consent
// $email = 'eventhelpff@gmail.com';
// $clientId = '257050439-aa9dni4lemur7stfk7k4gfftjieat68k.apps.googleusercontent.com';
// $clientSecret = 'hXGLBwdb-pfCusU2YwMfp1WM';
// //Obtained by configuring and running get_oauth_token.php
// //after setting up an app in Google Developer Console.
// $refreshToken = '4/AABBsHiuYT6xvyIxhollQLY--O-WDcJNOVj5uWzAW8m1eLXRhGjpLrGm9B3U45OKM1nyWw08dx08E5e0S7oyyTk';
// //Create a new OAuth2 provider instance
// $provider = new Google(
//     [
//         'clientId' => $clientId,
//         'clientSecret' => $clientSecret,
//     ]
// );
// //Pass the OAuth provider instance to PHPMailer
// $mail->setOAuth(
//     new OAuth(
//         [
//             'provider' => $provider,
//             'clientId' => $clientId,
//             'clientSecret' => $clientSecret,
//             'refreshToken' => $refreshToken,
//             'userName' => $email,
//         ]
//     )
// );
// //Set who the message is to be sent from
// //For gmail, this generally needs to be the same as the user you logged in as
// $mail->setFrom($email, 'First Last');
// //Set who the message is to be sent to
// $mail->addAddress('sorenff@gmail.com', 'Soren Funk-Froese');
// //Set the subject line
// $mail->Subject = 'PHPMailer GMail XOAUTH2 SMTP test';
// //Read an HTML message body from an external file, convert referenced images to embedded,
// //convert HTML into a basic plain-text alternative body
// $mail->CharSet = 'utf-8';
// $mail->Body = 'TEST!';
// //Replace the plain text body with one created manually
// $mail->AltBody = 'This is a plain-text message body';

// //send the message, check for errors
// if (!$mail->send()) {
//     echo "Mailer Error: " . $mail->ErrorInfo;
// } else {
//     echo "Message sent!";
// }

if(isset($_SESSION['email']) && isset($_GET['event']))
{
	//User Info
	$queryId = "SELECT userId,isAdmin FROM users WHERE email = :email;";
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
	$queryEvents = "SELECT creatorId, description, eventName,pictureDirectory, approved,firstName,lastName 
				    FROM events,users 
				    WHERE userId = creatorId AND eventId = :selectedEvent ORDER BY eventId DESC;";
	$statementEvents = $db->prepare($queryEvents);
	$statementEvents->bindValue(':selectedEvent', $_GET['event']);
	$statementEvents->execute();
	$post = $statementEvents->fetch();

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
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=fr921eolm9c0bjm0ahtxlm3wjkysiro1w4mxzn0jbw1s1bej"></script>
  	<script>tinymce.init({ selector:'textarea' });</script>
	<title>Lets Make Plans :) </title>
</head>
<body>
	<?php if (isset($_SESSION['email'])):?>
	<div class="jumbotron">
		<div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">
			<!-- https://miketricking.github.io/bootstrap-image-hover -->
    		<div class="hovereffect">
        		<img class="img-responsive" src="<?=$_SESSION['profilePicture']?>" alt="profilePicture">
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
	<div class="col-lg-5 mr-1 border mx-auto">
    	<form enctype="multipart/form-data" method="post" id="eventForm">
    		<textarea>Next, start a free trial!</textarea>
		</form>
	</div>
</div>
<?php else: header('Location: main.php');?>
<?php endif?>
</body>
</html>