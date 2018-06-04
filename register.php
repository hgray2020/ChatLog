<style>
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
$id = 0;
$sql = "SELECT * FROM users";


if($loggedIn == false){
	echo "<form method='POST' action='register.php'>
	<h2>New Account</h2>
	<label for='inUsername'>Username</label>
	<input type='text' id='inUsername' name='username' placeholder='Username' required>
	
	
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
	<label for 'profilepicture'>Profile Picture Url</label>
	<input type='text' id='profilePic' name='profilePic' placeHolder='Profile Picture Url'>
	<br>
	
	<input type='submit' value='Register'>
	</form>";
}

if(isset($_POST['username'])){
	$input = "INSERT INTO users (username, password, email, firstName, lastName, image) values ('" . $_POST['username'] . "', '" . $_POST['password'] . "', '" . $_POST['email'] . "', '" . $_POST['firstName'] . "', '" . $_POST['lastName'] . "', '" . $_POST['profilePic'] . "')";
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