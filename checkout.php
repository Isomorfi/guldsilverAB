<?php
session_start();

include("db_connection.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = $_POST['username'];
	$password = $_POST['password'];


	$sql = "SELECT losenord FROM db19880310.Kunder WHERE användarnamn='$username'";
	$res = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($res);
	
	
	if($password === $data['losenord']) {
		header("Location: store.php");
		die;
	} else {
		echo "Wrong password or username";
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
<p style="text-align:center;"><label for="password">Lösenord:</label></p>
<p style="text-align:center;"><input type="text" id="password" name="password"></p><br>


       <p style="text-align:center;"><button type="submit" value="Submit">Logga in</button></p>
     </form>
</fieldset>


</body>
</html>