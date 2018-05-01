<?php
session_start();
require('connect.php');


$loggedIn = false;
$id = 0;
$sql = "SELECT * FROM users";


if($loggedIn == false){
	echo "<form method='POST' action='register.php'>
	<h2>Login</h2>
	<label for='inUsername'>Username</label>
	<input type='text' id='inUsername' name='username' placeholder='Username' required>
	<br>
	
	<label for='inPass'>Password</label>
	<input type='password' id='inPass' name='password' placeholder='Password' required>
	<br>
	
	<label for='inEMail'>eMail</label>
	<input type='email' id='inEMail' name='email' placeholder='eMail' required>
	<br>
	
	<label for='firstName'>First Name</label>
	<input type='text' id='firstName' name='firstName' placeholder='First Name' required>
	<br>
	
	<label for='lastName'>Last Name</label>
	<input type='text' id='lastName' name='lastName' placeholder='Last Name' required>
	<br>
	
	<input type='submit' value='Register'>
	</form>";
}

if(isset($_POST['username'])){
	$input = "INSERT INTO users (username, password, email, firstName, lastName) values ('" . $_POST['username'] . "', '" . $_POST['password'] . "', '" . $_POST['email'] . "', '" . $_POST['firstName'] . "', '" . $_POST['lastName'] . "')";
	echo $input;
	$stmt = $conn -> query($input);
}




$statement = $conn -> query($sql);
$results = $statement->fetchAll();  
if(isset($_POST['username'])){
	foreach($results as $row){
		
		if($_POST['username'] == $row['username']){
			$loggedIn = true;
			$_SESSION['loggedIn'] = true;
			$_SESSION['id'] = $id;
			$_SESSION['username'] = $row['username'];
			$id = $row['userID'];
			break;
		}
	}
	
}

if($loggedIn == true){
	header('Location: loadmessage.php');
}




?>