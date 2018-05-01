<?php
session_start();
?>
<style>
	* {
			box-sizing: border-box;
		}
	div {
		//border: 1px solid black;
	}
	.column2 {
		
		float: left;
		width: 48%;
		padding:1%;
		margin:1%;
		height:78%;
		border:1px solid black;
		
	}
	.column3 {
		float: left;
		width: 48%;
		padding:1%;
		margin:1%;
		height:53%;
		border:1px solid black;
	}
	.column1 {
			background-color: #f1f1f1;
			float: left;
			width: 100%;
			padding:20px;
			margin:20px;
		}
	
	
	.row:after {
		
		content: "";
		display: table;
		clear: both;
		
	}
</style>


<?php

require('connect.php');
$loggedIn = false;
if(isset($_SESSION['loggedIn'])){
	$loggedIn = true;
}
if(isset($_SESSION['id'])){
	$id = $_SESSION['id'];
}
$hasConvo = array();
$initHasConvo = true;
$messages;
$getMax = "SELECT MAX(messageID) AS lastID FROM messages";
$maxStmt = $conn -> query($getMax);
$maxMessageIDStmt = $maxStmt -> fetchAll();
$maxID = 0;
foreach($maxMessageIDStmt as $row){
	$maxID = $row['lastID'];
}

$str = '"newmessage.php", processPage';
if($loggedIn){
	echo "<div style='height:10%;'>
	<p>Hello, " . $_SESSION['username'] . "</p>
	<a href='#' onclick='httpGetAsync(" . $str . ");' style = 'padding:10px'>New Message</a>
	<a href = 'logoutForm.php'>Logout</a>
	<p></p>
	
	</div>
	<div class='row'>
		<div class = 'column2'>";
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
			echo "<div style = 'border:1px solid black;' onclick='showBody(" . $user['userID'] . ", " . $temp . ");'>
			<h3>" . $user['username'] . "</h3>";
		}
		
		foreach($messages as $message){
			if($message['fromUserID'] == $id){
				echo "<span class ='body" . $user['userID'] . "' style='display: none;'>You: " . $message['body'] . "</span>";
			} else {
				echo "<span class ='body" . $user['userID'] . "' style='display: none;'>" . $user['username'] . ": " . $message['body'] . "</span>";
			}
		}
		
		
		if(count($messages) > 0 ){
			echo "</div> <br />";
		}
	}
	
	
	
	echo "
	</div>
	
			<div id='bodyDiv' class = 'column3'>
			
			</div>
			<div style = 'height:30%;' id='sendmessage' style = border:1px solid black></div>
			</div>
			";
	
} else {
	echo "<p><a href='login.php'>Login</a> to see messages</p>";
}


?>

<script>
function httpGetAsync(theUrl, callbackWhenPageLoaded) {
		
		var xmlHttp = new XMLHttpRequest();
		
		xmlHttp.onreadystatechange = function() { 
			if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
				callbackWhenPageLoaded(xmlHttp.responseText);
			}
		}	
		xmlHttp.open("GET", theUrl, true); 
		xmlHttp.send(null);
	}

function showBody(n, user){
	
	httpGetAsync("setselected.php?s=" +user);
	httpGetAsync("newmessage.php", processPage);
    

     
	var original = document.getElementsByClassName('body'+n);
	var string = "";
	for(i = 0; i < original.length; i++){
		string+=original[i].innerHTML + "<br>";
	}
    
	document.getElementById('bodyDiv').innerHTML = string;
	
	
	
}
function processPage(responseText) {
	document.getElementById("sendmessage").innerHTML = responseText;
	//alert(responseText);//just here for testing...always a good idea
}
</script>




