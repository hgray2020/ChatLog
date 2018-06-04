
<style>
	div {
		border: 1px solid black;
	}
	*{
		font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; 
   font-weight: 30;
	}
	input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus
input:-webkit-autofill, 
textarea:-webkit-autofill,
textarea:-webkit-autofill:hover
textarea:-webkit-autofill:focus,
select:-webkit-autofill,
select:-webkit-autofill:hover,
select:-webkit-autofill:focus {
  -webkit-box-shadow: 0 0 0px 1000px #0ef inset;
 
}
input, select {
	background-color: #0ef;
}
input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
input[type=email], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
input[type=password], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit] {
    width: 100%;
    background-color: #3333ef;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #1010c6;
}

div {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 20px;
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