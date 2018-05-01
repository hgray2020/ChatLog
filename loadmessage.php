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
		overflow: auto;
		
	}
	.column3 {
		float: left;
		width: 48%;
		padding:1%;
		margin:1%;
		height:53%;
		border:1px solid black;
		overflow: auto;
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
if(!isset($_SESSION['toID'])){
	$_SESSION['toID'] = 0;
}

echo"<body onmousemove = 'loadConvo();'>";

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
	
	
		if(isset($_SESSION['toID'])){
	echo "
	</div>
	
			<div id='bodyDiv' class = 'column3'>
			
			</div>
			<div style = 'height:30%;' id='sendmessage' style = border:1px solid black>
			<form method='POST'>
				<h2>New Message</h2>
				<label for='tuser'>To:</label>
				<input type='text' id='tuser' name='toUser' required>
				
				<label for='inSubject'>Subject:</label>
				<input type='text' id='inSubject' name='subject' placeholder='Subject'>
				<br>
				
				<textarea rows='4' cols='50' name='body' placeholder='Body'></textarea>";
				
				$tempstr = '"getconvo.php?i=' . $_SESSION['toID'] . '"';
				echo "<input type='submit' onclick='httpGetAsync(" . $tempstr . ");' value='Send' >
			</form>
			</div>
			</div>
			";
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
				$_SESSION['nID'] = $user['userID'];
				echo "<span id = 'showbody' style='display:none;'>" . $_SESSION['toID'] . "</span>";
				echo "<span id = 'showbody2' style='display:none;'>" . $_SESSION['selected'] . "</span>";
			}
		}

	
			
} else {
	echo "<p><a href='login.php'>Login</a> to see messages</p>";
}
//have seperate php that sets an invisible span in topbar with some id and stores the parameters for show body to be updated.

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

function showBody(n, user, id){
	
	document.getElementById('showbody').innerHTML = n;
	document.getElementById('showbody2').innerHTML = user;
	
	httpGetAsync("setselected.php?s=" +user+"&i="+n);
	//httpGetAsync("newmessage.php", processPage);
    httpGetAsync("getconvo.php?i="+n, showConvo);
	//document.getElementById('tuser').value = user;
	

	
}

function loadConvo(n){
	n = document.getElementById('showbody').innerHTML;
	document.getElementById('tuser').value = document.getElementById('showbody2').innerHTML;
	httpGetAsync("getconvo.php?i="+n, showConvo);
	
}



function processPage(responseText) {
	//document.getElementById("sendmessage").innerHTML = responseText;
	//alert(responseText);//just here for testing...always a good idea
}

function showConvo(responseText) {
	document.getElementById("bodyDiv").innerHTML = responseText;
	//alert(responseText);//just here for testing...always a good idea
}
</script>




