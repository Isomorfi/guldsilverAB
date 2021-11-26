<?php
session_start();

include("db_connection.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = $_POST['username'];
	$password = $_POST['password'];


	$sql = "SELECT Password FROM db19880310.Customers WHERE Username='$username'";
	$res = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($res);
	
	$hash_pwd = sha1($password);

    	if($hash_pwd === $data['Password'] || $password == $data['Password']) {
		$_SESSION['signedin'] = true;
		$_SESSION['username'] = $username;
		header("Location: store.php");
		die;
	} else {
		echo "Felaktigt användarnamn eller lösenord.";
	}
	
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <style>
body {background-color: powderblue;}
h1   {color: #020764;}
p    {color: #020764;}
</style>
</head>
<body>

<h1>Guld och silver AB - Logga in</h1>

<a href="home.php"><button type="submit" value="Submit">Hem</button></a>

<fieldset>
<form name="form" method="POST">
<p style="text-align:center;"><label for="username">Användarnamn:</label></p>
<p style="text-align:center;"><input type="text" id="username" name="username"></p><br>
<p style="text-align:center;"><label for="password">Lösenord:</label></p>
<p style="text-align:center;"><input type="password" id="password" name="password"></p><br>


       <p style="text-align:center;"><button type="submit" value="Submit">Logga in</button></p>
     </form>
</fieldset>
<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>