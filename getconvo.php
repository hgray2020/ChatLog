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
				echo "<div style = 'color:#999;margin-right:5%;margin-left:95%'><strong>You</strong></div>";
				echo "<div style = 'margin-left:35%; position:relative; padding:5px; border:1px solid #3333ef; border-radius:5px;  background:#3333ef; color:#fdfdfd' class ='body" . $user['userID'] . "'>" . $message['body'] . "</div><br>";
			} else {
				echo "<div style = 'color:#999;font-weight:20;'><strong>" . $user['username'] . "</strong></div>";
				echo "<div style = 'margin-right:35%; position:relative; padding:5px; border:1px solid #ccc; border-radius:5px; background:#ccc;' class ='body" . $user['userID'] . "'>" . $message['body'] . "</div><br>";
			}
		}
		
		
		
	}
	
?>