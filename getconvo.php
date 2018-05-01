<?php
	session_start();
	require('connect.php');
	if(isset($_SESSION['id'])){
		$id = $_SESSION['id'];
	}
	$usr = $_REQUEST['i'];
	
	$getUsers = "SELECT * FROM users WHERE userID = " . $usr;
	$userStmt = $conn -> query($getUsers);
	$users = $userStmt -> fetchAll();
	foreach($users as $user){
		
		$getMessagesFrom = "SELECT * FROM `messages` JOIN messagerecipients ON messages.messageID = messagerecipients.messageID WHERE (messages.fromUserID = " . $id . " AND messagerecipients.toUserID = " . $user['userID'] .") OR (messages.fromUserID = " . $user['userID'] ." AND messagerecipients.toUserID = " . $id .")";
		$messageStmt = $conn -> query($getMessagesFrom);
		$messages = $messageStmt -> fetchAll();
		$temp = '"' . $user['username'] . '"';
		
		
		foreach($messages as $message){
			if($message['fromUserID'] == $id){
				echo "<span style = 'float:right;' class ='body" . $user['userID'] . "'>You: " . $message['body'] . "</span><br>";
			} else {
				echo "<span class ='body" . $user['userID'] . "'>" . $user['username'] . ": " . $message['body'] . "</span><br>";
			}
		}
		
		
		
	}
	
?>