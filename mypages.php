<?php

session_start();
include("db_connection.php");


if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
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
h1   {color: #020764;}
p    {color: #020764;}
</style>
</head>
<body>

<h1>Guld och silver AB - Mina sidor</h1>

<?php

if(isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {?>
<style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
	text-align:left;
    }
</style>

    <fieldset class="fieldset-auto-width">
        <?php
    
	echo "<p>" . "Inloggad: " . $_SESSION['username'] . "." . "<br>" . "Kontobalans: " . $_SESSION['balance'] . " kr." . "</p>";
?>
    </fieldset>
<?php
}

?>
<br>
<br>


<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="store.php"><button type="submit" value="Submit">Produkter</button></a><br>
<center>

<br>
<br>
<a href="myOrders.php"><button type="submit" value="Submit">Beställningar</button></a>
<br>
<br>
<br>
<a href="changeInfo.php"><button type="submit" value="Submit">Ändra konto</button></a>
<br>
<br>
<br>
<a href="changePassword.php"><button type="submit" value="Submit">Ändra lösenord</button></a>
<br>
<br>
<br>
<a href="wallet.php"><button type="submit" value="Submit">Hantera plånbok</button></a>
</center>
<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>