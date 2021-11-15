<?php

session_start();
include("db_connection.php");


if(isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {	
	echo "Inloggad som " . $_SESSION['username'] . ".";
} else {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");	
	die;
	
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

<h1>Guld och silver AB - Mina sidor</h1>


<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="store.php"><button type="submit" value="Submit">Produkter</button></a><br>
<center>

<br>
<br>
<a href="myOrders.php"><button type="submit" value="Submit">Mina beställningar</button></a>
<br>
<br>
<br>
<a href="changePassword.php"><button type="submit" value="Submit">Ändra lösenord</button></a>

</center>

</body>
</html>