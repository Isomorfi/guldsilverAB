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
h1   {color: blue;}
p    {color: blue;}
</style>
</head>
<body>

<h1>Guld och silver AB</h1>

<fieldset>
<form name="form" method="POST">

<p style="text-align:center;">
<label for="grade">Typ av kort:</label>
  <select name="grade" id="grade">
    <option value="1">Visa</option>
    <option value="2">Mastercard</option>
    <option value="3">Maestro</option>
  </select>
  <br></p>

<p style="text-align:center;"><label for="num">Kortnummer:</label></p>
<p style="text-align:center;"><input type="text" id="num" name="num"></p><br>
<p style="text-align:center;"><label for="date">Utg. datum:</label></p>
<p style="text-align:center;"><input type="text" id="date" name="date"></p><br>
<p style="text-align:center;"><label for="cvc">CVC:</label></p>
<p style="text-align:center;"><input type="text" id="cvc" name="cvc"></p><br>

<p style="text-align:center;"><label for="sum">Summa:</label></p>
<p style="text-align:center;"><input type="text" id="sum" name="sum"></p><br>


       <p style="text-align:center;"><button type="submit" value="Submit">Sätt in</button></p>
     </form>
</fieldset>


</body>
</html>