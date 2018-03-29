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

$deleteQuery = "DELETE FROM events WHERE eventId = :eventId AND creatorId = :userId;";
$deleteStatement = $db->prepare($deleteQuery);
$deleteStatement->bindValue(':eventId',$eventId);
$deleteStatement->bindValue(':userId', $userId);
$deleteStatement->execute();

header('Location: main.php');


?>