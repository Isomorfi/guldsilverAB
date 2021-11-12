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
<style>
body {background-color: powderblue;}
h1   {color: blue;}
p    {color: blue;}
h3   {color: red;}
</style>
</head>
<body>

<h1>Guld och silver AB - Order</h1>

<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>
<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a>
<a href="store.php"><button type="submit" value="Submit">Produkter</button></a>


<?php
$username = $_SESSION['username'];



$sql = "SELECT Firstname, Lastname, Address, ZIP, City, Country FROM db19880310.Customers WHERE Username='$username'";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
//echo "Kundrecensioner: ";
echo "<br>";
//echo "<table border='1'>";
while ($row = mysqli_fetch_assoc($result)) {
?>
<center><style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
	text-align:left;
    }
</style>

    <fieldset class="fieldset-auto-width">
    <p>
<?php
    echo "<p>" . "Ordernummer: " . $_SESSION['orderID'] . "</p>";
    echo "<br>";
    echo "Leveransadress";
    echo "<p>" . "Förnamn: " . $row['Firstname'] . "</p>";
    echo "<p>" . "Efternamn: " . $row['Lastname'] . "</p>";
    echo "<p>" . "Adress: " . $row['Address'] . "</p>";
    echo "<p>" . "Postnummer: " . $row['ZIP'] . "</p>";
    echo "<p>" . "Postort: " . $row['City'] . "</p>";
    echo "<p>" . "Land: " . $row['Country'] . "</p>";
    echo "<br>";
    
?>
   </p>
   </fieldset></center>
<br>
<?php
}

?>

</fieldset>

</body>
</html>