<?php
	session_start();
	require('connect.php');
	if(isset($_SESSION['id'])){
		$id = $_SESSION['id'];
	}
	$getUsers = "SELECT * FROM users WHERE userID != " . $id;
	$userStmt = $conn -> query($getUsers);
	$users = $userStmt -> fetchAll();
	foreach($users as $user){
		$getMessagesTo = "SELECT * FROM messagerecipients WHERE toUserID = " . $user['userID'];
		$messageToStmt = $conn -> query($getMessagesTo);
		$messagesRTo = $messageToStmt -> fetchAll();
		
		$getMessagesFrom = "SELECT * FROM `messages` JOIN messagerecipients ON messages.messageID = messagerecipients.messageID WHERE (messages.fromUserID = " . $id . " AND messagerecipients.toUserID = " . $user['userID'] .") OR (messages.fromUserID = " . $user['userID'] ." AND messagerecipients.toUserID = " . $id .")";
		$messageFromStmt = $conn -> query($getMessagesFrom);
		$messages = $messageFromStmt -> fetchAll();
		//$messageToGet = "SELECT * FROM messages WHERE messageID = " . $messagesRTo[0]['messageID'];
		for($i = 1; $i < sizeOf($messagesRTo); $i++){
			//$messageToGet .= " OR messageID = " . $messagesRTo[$i]['messageID'];
		}
		
		
		
		
		
		
		
		$temp = '"' . $user['username'] . '"';
		if(count($messages) > 0 ){
			
			if(isset($user['image'])){
				if(strlen($user['image']) > 0){
					$src = $user['image'];
				} else {
					$src = "profile.jpg";
				}
			} else {
				$src = "profile.jpg";
			}
			echo "<div style = 'border:1px solid #bbb;' onclick='showBody(" . $user['userID'] . ", " . $temp . ");'>
			<span style='block;'><img style='border-radius:50%;float:left; margin-top:2.5%; margin-left:2%; border:1px solid #bbb;' width='30vw' height='30vh' src='" . $src . "'></img><h3 style = 'margin-left:6%;padding-left:3%;'>" . $user['username'] . "</h3></span>";
		}
		
		foreach($messages as $message){
			if($message['fromUserID'] == $id){
				echo "<span class ='body" . $user['userID'] . "' style='display: none;'>You: " . $message['body'] . "</span>";
			} else {
				echo "<span class ='body" . $user['userID'] . "' style='display: none;'>" . $user['username'] . ": " . $message['body'] . "</span>";
			}
		}
		
		
		if(count($messages) > 0 ){
			echo "</div> ";
		}
	}
?>