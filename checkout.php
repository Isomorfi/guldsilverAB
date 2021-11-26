<?php

session_start();
include("db_connection.php");


if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}

if(isset($_GET['OrderID'])){
    $_SESSION['orderID'] = $_GET['OrderID'];
}




?>


<!DOCTYPE html>
<html>
<head>
<style>
body {background-color: powderblue;}
h1   {color: #020764;}
p    {color: #020764;}
h3   {color: red;}
</style>
</head>
<body>

<h1>Guld och silver AB - Order</h1>

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
<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a>
<a href="store.php"><button type="submit" value="Submit">Produkter</button></a>


<?php
$totPrice = 0;
$orderID = $_SESSION['orderID'];


$username = $_SESSION['username'];


$sql = "SELECT *
FROM Customers
INNER JOIN Orders ON Customers.Username=Orders.Username WHERE OrderID='$orderID'";
$result = mysqli_query($conn, $sql); 
echo "<br>";
while ($row = mysqli_fetch_assoc($result)) {
?>
<center><style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
	text-align:left;
    }
</style>

    <fieldset class="fieldset-auto-width">
    
<?php
    echo "Ordernummer";
    echo "<p>" . $_SESSION['orderID'] . "</p>";
    echo "<br>";
    echo "Leveransadress";
    echo "<p>" . $row['Firstname'] . " " . $row['Lastname'] . "</p>";
    echo "<p>" . $row['Address'] . "</p>";
    echo "<p>" . $row['ZIP'] . " " . $row['City'] . "</p>";
    echo "<p>" . $row['Country'] . "</p>";
    echo "<br>";
    echo "Kontaktuppgifter";
    echo "<p>" . "E-post " . $row['Email'] . "</p>";
    echo "<p>" . "Telefon " . $row['Phone'] . "</p>";
    echo "<br>";
    echo "Leveranssätt";
    echo "<p>" . $row['DELIVERY'] . "</p>";
    echo "<p>" . "Leveranskostnad: " . $row['ShippingCost'] . " kr" . "</p>";
    echo "<br>";
    echo "Din beställning";
}
?>


<?php
$ordquery = "SELECT * FROM db19880310.OrderItems WHERE OrderID='$orderID'";
$res = mysqli_query($conn, $ordquery); // First parameter is just return of "mysqli_connect()" function
while ($data = mysqli_fetch_assoc($res)) {


if(isset($data['ProductID'])) {
$productID = $data['ProductID'];
$sql3 = "SELECT ProductName FROM Products WHERE ProductID='$productID'";
$res3 = mysqli_query($conn, $sql3);
$data3 = mysqli_fetch_assoc($res3);


?>
<fieldset>
<br>
<div style="width:600px;">
  	<div style="width:240px; float:left;"><input type="image" 
  	<?php
	// Hämtar bild source från databas
		$sql1 = "SELECT * FROM db19880310.Products WHERE ProductID='$productID'";
		$res1 = mysqli_query($conn, $sql1);
		$data2 = mysqli_fetch_assoc($res1);
		$unit = $data2['Unit'];
	?>
	src="<?php echo $data2['PicSrc'] ?>" 
  	name="submit" width="200" height="150"/></p>
</div>

<div style="width:350px; float:left;"><p><label id="silver"><br>99,9% rent <?php echo $data3['ProductName']?>. <br><br>Antal <?php echo $unit;?>: <?php echo $data['Quantity']?>. <br><br>Kostnad: <?php echo $data['TotalCost']?> kr.</label></p>



</div>
</div>
<div style="clear: both;"></div>
</fieldset>


<?php
}
}
$sql3 = "SELECT * FROM Orders WHERE OrderID='$orderID'";
$res3 = mysqli_query($conn, $sql3);
$data3 = mysqli_fetch_assoc($res3);
?>
<p style="text-align:center;">Orderkostnad:</p>
<?php echo "<p style=\"text-align:center;\">" . $data3['TotalCost'] . " kr + " . $data3['ShippingCost'] . " kr frakt." . "</p>";
echo "<br>";
$tot = $data3['TotalCost'] + $data3['ShippingCost'];
echo "<p style=\"text-align:center;\">" . "Total kostnad: " . $tot . " kr." . "</p>";
   ?>
   </fieldset></center>
<br>
<?php


?>

</fieldset>
<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>