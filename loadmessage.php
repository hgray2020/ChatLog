<?php
session_start();
?>
<style>
	profile {
		boder-radius:50%;
	}
	* {
			box-sizing: border-box;
			font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; 
   font-weight: 30;
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

//echo"<body onmousemove = 'loadConvo();'>";

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
	
	<a href = 'logoutForm.php'>Logout</a>
	<p></p>
	
	</div>
	<div class='row'>
		<div class = 'column2'>";
	echo "<div id='userlist'></div>";
	$getUsers = "SELECT * FROM users WHERE userID != " . $id;
	$userStmt = $conn -> query($getUsers);
	$users = $userStmt -> fetchAll();
	foreach($users as $user){
		
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
				
			}
			if(isset($_SESSION['toID']) && isset($_SESSION['selected'])){
					echo "<span id = 'showbody' style='display:none;'>" . $_SESSION['toID'] . "</span>";
					echo "<span id = 'showbody2' style='display:none;'>" . $_SESSION['selected'] . "</span>";
			} else {
				echo "<script>alert('hi!');</script>";
					echo "<span id = 'showbody' style='display:none;'>0</span>";
					echo "<span id = 'showbody2' style='display:none;'>' '</span>";
			}
		}

	
			
} else {
	echo "<p><a href='login.php'>Login</a> to see messages</p>";
}
//have seperate php that sets an invisible span in topbar with some id and stores the parameters for show body to be updated.

?>

<script>
loadConvo();

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
	document.getElementById('tuser').value = document.getElementById('showbody2').innerHTML;
	
	httpGetAsync("setselected.php?s=" +user+"&i="+n, hey);
	//httpGetAsync("newmessage.php", processPage);
    httpGetAsync("getconvo.php?i="+n, showConvo);
	//document.getElementById('tuser').value = user;
	

	
}
function hey(n){
	
}
//setInterval(loadConvo, 2000);
function loadConvo(n){
	n = document.getElementById('showbody').innerHTML;
	//document.getElementById('tuser').value = document.getElementById('showbody2').innerHTML;
	httpGetAsync("getconvo.php?i="+n, showConvo);
	document.getElementById('bodyDiv').scrollTop = 500000;
	httpGetAsync("getusers.php", showUsers);
	
}

function showUsers(responseText){
	document.getElementById('userlist').innerHTML = responseText;
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




