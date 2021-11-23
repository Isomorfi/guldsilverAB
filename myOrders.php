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
h1   {color: #020764;}
p    {color: #020764;}
</style>
</head>
<body>

<h1>Guld och silver AB - Beställningar</h1>
<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="store.php"><button type="submit" value="Submit">Produkter</button></a>

<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a><br>
<center>
<?php
$username = $_SESSION['username'];

if($username === "Admin") {
$sql = "SELECT OrderID, Status, orderDate, TotalCost FROM db19880310.Orders WHERE Status='Ordered'";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
$link = 'checkout.php';
echo "<br>";

echo '<table>';
echo "<table border='1'>";
echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Ordernummer" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Orderdatum" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Totalsumma" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Status" . "</p>";
    echo '</td></tr>';

while ($row = mysqli_fetch_assoc($result)) {

    $order = $row['OrderID'];
    echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p><a href=\"$link?OrderID=$order\">" . $row['OrderID'] . "</a></p>";
    //if(isset($_GET['OrderID'])){

    //$_SESSION['orderID'] = $_GET['OrderID'];
//}
   
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['orderDate'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['TotalCost'] . " kr" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Status'] . "</p>";
    echo '</td></tr>';

    }

}




if($_SESSION['username'] !== "Admin") {
$sql = "SELECT OrderID, Status, orderDate, TotalCost FROM db19880310.Orders WHERE Username='$username' AND Status='Ordered'";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
$link = 'checkout.php';
echo "<br>";

echo '<table>';
echo "<table border='1'>";
echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Ordernummer" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Orderdatum" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Totalsumma" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Status" . "</p>";
    echo '</td></tr>';

while ($row = mysqli_fetch_assoc($result)) {

    $order = $row['OrderID'];
    echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p><a href=\"$link?OrderID=$order\">" . $row['OrderID'] . "</a></p>";
    //if(isset($_GET['OrderID'])){

    //$_SESSION['orderID'] = $_GET['OrderID'];
//}
   
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['orderDate'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['TotalCost'] . " kr" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Status'] . "</p>";
    echo '</td></tr>';

    }
}
?>

</center>

</body>
</html>