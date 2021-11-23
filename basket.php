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



$username = $_SESSION['username'];
$orderID = '';
$total = 0;

$sql = "DELETE FROM OrderItems WHERE Quantity='0'";
$conn->query($sql);



if($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['Betala'])) {
		$orderID = $_POST['Betala'];
		$_SESSION['orderID'] = $orderID;
		$total = $_POST['tot'];


        $sql = "UPDATE db19880310.Orders SET Status='Ordered' WHERE Username='$username' AND OrderID='$orderID'"; 
		$conn->query($sql);

		$sql = "UPDATE db19880310.Orders SET orderDate=CURRENT_TIMESTAMP, TotalCost='$total' WHERE Username='$username' AND OrderID='$orderID'"; 
		$conn->query($sql);

		header("Location: checkout.php");	
		die;
    }


	//Gör funktion av detta?
	if (isset($_POST['change'])) {
		echo "hej";
		$orderID = $_POST['order'];
		$_SESSION['OrderID'] = $orderID;
		$newquantity = $_POST['changegold'];
		$prodid = $_POST['change'];

		echo "prodid = " . $prodid;
		echo "orderid = " . $orderID;

		
		$sql = "SELECT Stock FROM db19880310.Products WHERE ProductID='$prodid'";
		
		$res = mysqli_query($conn, $sql);
		$data = mysqli_fetch_assoc($res);
		$stock = $data['Stock'];

		$sql1 = "SELECT Quantity FROM db19880310.OrderItems WHERE ProductID='$prodid' AND OrderID='$orderID'";
		
		$res1 = mysqli_query($conn, $sql1);
		$data1 = mysqli_fetch_assoc($res1);
		$quantity = $data1['Quantity'];


		if($newquantity >= 0 && $stock >= $newquantity) {

			$diff = $quantity - $newquantity;
			
			$newStock = $stock + $diff;

			echo "newstock = " . $newstock;
			
			$sql = "UPDATE db19880310.Products SET Stock='$newStock' WHERE ProductID='$prodid'";
			$conn->query($sql);

        	$sql = "UPDATE db19880310.OrderItems SET Quantity='$newquantity' WHERE ProductID='$prodid' AND OrderID='$orderID'"; 
			$res = mysqli_query($conn, $sql);
			header("Location: basket.php");	
			die;
		}
    }
   
}


?>

<!DOCTYPE html>
<html>
<head>
<style>
body {background-color: powderblue;}
h1   {color: #020764;}
p    {color: #020764;}
h3   {color: #020764;}
</style>
</head>
<body>

<h1>Guld och silver AB - Min Kundvagn</h1>

<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>
<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a>
<a href="store.php"><button type="submit" value="Submit">Produkter</button></a>

<?php

$sql = "SELECT *
FROM Orders
INNER JOIN OrderItems ON Orders.OrderID=OrderItems.OrderID
INNER JOIN Products ON OrderItems.ProductID=Products.ProductID WHERE Username='$username' AND Status='Basket'";
$res = mysqli_query($conn, $sql);
$link = 'products.php';
$total = 0;

if($res) {
while($data = mysqli_fetch_assoc($res)){

$quantity = $data['Quantity'];
$price = $data['Price'];
$totprice = $quantity * $price;
$unit = $data['Unit'];
$prodname = $data['ProductName'];
$src = $data['PicSrc'];
$prodid = $data['ProductID'];
$orderID = $data['OrderID'];



$total = $total + $totprice;

$costupdate = "UPDATE OrderItems SET TotalCost='$totprice' WHERE OrderID='$orderID' AND ProductID='$prodid'";
$conn->query($costupdate);

?>
<fieldset>
<center>
<div style="width:500px;">
  <div style="width:200px; float:left;"><p style="text-align:center;"><?php echo "<a href=\"$link?ProductID=$prodid\">";?><input type="image" 
	
	
	src="<?php echo $src ?>" 
	name="submit" width="200" height="150"/></a></p>
</div></center>
  <div style="width:700px; float:right;"><p><?php echo "<a href=\"$link?ProductID=$prodid\">";?><?php echo $prodname?></a></p><p style="text-decoration: underline;"><label>Produkter i varukorgen: <?php echo $quantity . " " . $unit . " " . $prodname . "."?></label></p>
<p style="text-decoration: underline;"><label>Kostnad: <?php echo $quantity . " " . $unit . " á " . $price . " kr/" . $unit . ": " . $totprice . " kr."?></label></p>



<form name="form" method="POST">
    <p>
	<label for="username">Ändra varukorgen? Skriv in nytt önskat<br> antal produkter: </label>
	<input type="text" id="changegold" name="changegold">
	<input type="hidden" name="order" value="<?php echo $orderID;?>">
	<button type="submit" value="<?php echo $data['ProductID']?>" name="change">Ändra varukorg</button></p>
</form></div>
</div>
<div style="clear: both;"></div>
</fieldset>

<?php
}
}
?>





<!-- totalpris och betala -->
<fieldset>
	<p style="text-align:center;">
	<p style="text-align:center;"><label>Totalt pris: <?php echo $total . " kr."?></label></p>
	
	<form name="form" method="POST">

<?php
if($total != 0) {
?>
	<input type="hidden" name="tot" value="<?php echo $total;?>">
	<p style="text-align:center;"><button type="submit" onclick="alert('Köp genomfört')"value="<?php echo $orderID?>" name="Betala">Betala</button></p>
<?php
}
?>

</form>
</fieldset>


</body>
</html>