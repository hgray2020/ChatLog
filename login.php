
<style>
	div {
		border: 1px solid black;
	}
</style>


<?php
session_start();
require('connect.php');

$loggedIn = false;
if(isset($_SESSION['loggedIn'])){
	$loggedIn = true;
}
$id = 0;
$sql = "SELECT * FROM users";
$statement = $conn -> query( $sql );

if($loggedIn == false){
	echo "<form method='POST' action='login.php'>
	<h2>Login</h2>
	<label for='inUsername'>Username</label>
	<input type='text' id='inUsername' name='username' placeholder='Username' required>
	<br>
	<label for='inPassword'>Password</label>
	<input type='password' id='inPassword' name='password' placeholder='Password' required>
	<input type='submit' value='Login'>
	</form>
	<p>Don't have an account? <a href='register.php'>Register</a>";
}
$results = $statement->fetchAll();  
if(isset($_POST['username'])){
	foreach($results as $row){
		
		if($_POST['username'] == $row['username'] && $_POST['password'] == $row['password']){
			$loggedIn = true;
			$id = $row['userID'];
			$_SESSION['loggedIn'] = true;
			$_SESSION['id'] = $id;
			$_SESSION['username'] = $row['username'];
			break;
		}
	}
	if($loggedIn == false){
		echo "<p>Login Failed</p>";
	}
}

if($loggedIn == true){
	
	foreach($results as $row){
		
		if($id == $row['userID']){
			echo "<p>Hello, " . $row['firstName'] . "</p><br><a href='logoutForm.php'>Logout</a><br><a href='newmessage.php'>New Message</a>";
			$_SESSION['id'] = $id;
			header('Location: loadmessage.php');
		}
	}
}




?>