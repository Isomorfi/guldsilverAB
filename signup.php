<?php
session_start();

include("db_connection.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = $_POST['username'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];

	if(strlen($username) > 0 && strlen($firstname) > 0 && strlen($lastname) > 0 &&
	strlen($password) > 0 && strlen($password2) > 0) {
		if($password === $password2) {
			$sql = "INSERT INTO db19880310.Customers (Username, Firstname, Lastname, Password)
			VALUES ('$username', '$firstname', '$lastname', '$password')";

			if ($conn->query($sql) === TRUE) {
				header("Location: store.php");
				die;
			} else {
				echo "Användarnamnet är redan upptaget. Välj ett annat.";
			}
		} else {
			echo "Lösenord överenstämmer inte.";
		}
	} else {
		echo "Fält kan ej lämnas tomma!";
	}
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <style>
body {background-color: powderblue;}
h1   {color: blue;}
p    {color: blue;}
</style>
</head>
<body>

<h1>Guld och silver AB</h1>

<fieldset>
<form name="form" method="POST">
<p style="text-align:center;"><label for="username">Användarnamn:</label></p>
<p style="text-align:center;"><input type="text" id="username" name="username"></p><br>
<p style="text-align:center;"><label for="firstname">Förnamn:</label></p>
<p style="text-align:center;"><input type="text" id="firstname" name="firstname"></p><br>
<p style="text-align:center;"><label for="lastname">Efternamn:</label></p>
<p style="text-align:center;"><input type="text" id="lastname" name="lastname"></p><br>
<p style="text-align:center;"><label for="password">Lösenord:</label></p>
<p style="text-align:center;"><input type="password" id="password" name="password"></p><br>
<p style="text-align:center;"><label for="password2">Upprepa lösenord:</label></p>
<p style="text-align:center;"><input type="password" id="password2" name="password2"></p><br>


       <p style="text-align:center;"><button type="submit" value="Submit">Skapa konto</button></p>
     </form>
</fieldset>


</body>
</html>