<?php

require("php/connection.php");

session_start();

$queryId = "SELECT userId FROM users WHERE email = :email;";
$statementId = $db->prepare($queryId);
$statementId->bindValue(':email', $_SESSION['email']);
$statementId->execute();
$userId = $statementId->fetch();

$userId = $userId['userId'];
$eventId = $_GET['event'];

$eventPictureQuery = "SELECT pictureDirectory FROM events WHERE eventId = :eventId;";
$eventPicture = $db->prepare($eventPictureQuery);
$eventPicture->bindValue(':eventId',$eventId);
$eventPicture->execute();
$pictureLocation = $eventPicture->fetch();


if(file_exists($pictureLocation['pictureDirectory']))
{
	unlink($pictureLocation['pictureDirectory']);
	rmdir(str_replace("event.jpg","", $pictureLocation['pictureDirectory']));
}

$deleteVotes = "DELETE FROM votes WHERE event = :eventId;";
$deleteVotesStatement = $db->prepare($deleteVotes);
$deleteVotesStatement->bindValue(':eventId',$eventId);
$deleteVotesStatement->execute();


$deleteQuery = "DELETE FROM events WHERE eventId = :eventId;";
$deleteStatement = $db->prepare($deleteQuery);
$deleteStatement->bindValue(':eventId',$eventId);
$delete = $deleteStatement->execute();

header('Location: main.php');


?>