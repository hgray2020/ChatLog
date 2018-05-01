<?php
session_start();
require('connect.php');

$loggedIn = false;
if(isset($_SESSION['loggedIn'])){
	$loggedIn = true;
}
if(isset($_SESSION['id'])){
	$id = $_SESSION['id'];
}


if($loggedIn == true){
	
	$tempStr = '"newmessage.php?b="';
	echo "<form method='POST' action='newmessage.php'>
	<h2>New Message</h2>
	<label for='tuser'>To:</label>
	<input type='text' id='tuser' name='toUser' value='" . $_SESSION['selected'] . "' required>
	
	<label for='inSubject'>Subject:</label>
	<input type='text' id='inSubject' name='subject' placeholder='Subject'>
	<br>
	
	<textarea rows='4' cols='50' name='body' placeholder='Body'></textarea>
	<input type='submit' value='Send' >
	</form>";
} else {
	echo "<p><a href='login.php'>Login</a> to send messages</p>";
}
if(isset($_POST['toUser'])){
	$sql = "INSERT INTO messages (subject, body, fromUserID) VALUES ('" . $_POST['subject'] . "', '" . $_POST['body'] . "', " . $id . ")";
	$stmt =  $conn -> query($sql);
	$sql2 = "SELECT userID FROM users WHERE username = '" . $_POST['toUser'] . "'";
	
	$stmt2 = $conn -> query($sql2);
	$results = $stmt2->fetchAll();
	$toUserID = 0;
	foreach($results as $row){
		$toUserID = $row['userID'];
	}
	$sql3 = "SELECT messageID FROM messages WHERE fromUserID = " . $id . " AND subject = '" . $_POST['subject'] . "' AND body = '" . $_POST['body'] . "'";
	$stmt3 = $conn -> query($sql3);
	$results2 = $stmt3->fetchAll();
	$mID = 0;
	foreach($results2 as $row2){
		$mID = $row2['messageID'];
	}
	$sql4 = "INSERT INTO messagerecipients (messageID, toUserID) VALUES (" . $mID . ", " . $toUserID . ")";
	$stmt4 = $conn -> query($sql4);
}

?>