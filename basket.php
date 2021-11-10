<?php

session_start();
include("db_connection.php");


if(isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {	
	echo "Inloggad som " . $_SESSION['username'] . ".";
	// GÖRA SÖKNING OCH RADERA ALLA ORDRAR SOM HAR 0 ???
} else {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");	
	die;
	
}

if($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['Betala'])) {
		$username = $_SESSION['username'];
		$orderID = $_SESSION['OrderID'];
        $sql = "UPDATE db19880310.Orders SET Status='Ordered' WHERE Username='$username' AND OrderID='$orderID'"; 
		$res = mysqli_query($conn, $sql);
		header("Location: basket.php");	
		die;
    }
	//Gör funktion av detta?
    if (isset($_POST['ChangeGold'])) {
	$orderID = $_SESSION['OrderID'];
	$quantity = $_POST['changegold'];
	echo $quantity;
        $sql = "UPDATE db19880310.OrderItems SET Quantity='$quantity' WHERE ProductID='1' AND OrderID='$orderID'"; 
	$res = mysqli_query($conn, $sql);
	header("Location: basket.php");	
	die;
    }
    if (isset($_POST['ChangeSilver'])) {
	$orderID = $_SESSION['OrderID'];
	$quantity = $_POST['changesilver'];
        $sql = "UPDATE db19880310.OrderItems SET Quantity='$quantity' WHERE ProductID='2' AND OrderID='$orderID'"; 
	$res = mysqli_query($conn, $sql);
	header("Location: basket.php");	
	die;
    }
}
	


	$username = $_SESSION['username'];
	$orderID = '';
	$quantity = '';
	
	// Hämta orderId
	$sql = "SELECT OrderID FROM db19880310.Orders WHERE Username='$username' AND Status='Basket'";
	$res = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($res);

	$goldCount = 0;
	$silverCount = 0;
	$totalPrice = 0;
	$priceGold = '';
	$priceSilver = '';

if(isset($data)) {
	
	$orderID = $data['OrderID'];
	$_SESSION['OrderID'] = $orderID;
	
	// Hämta alla produkter som hör till orderId
	$sql = "SELECT * FROM db19880310.OrderItems WHERE OrderID='$orderID'";
	$res = mysqli_query($conn, $sql);

	
	$productID = '';
	$quantity = '';

	while($data = mysqli_fetch_assoc($res)) {
		$productID = $data['ProductID'];
		$quantity = $data['Quantity'];

		if($productID == '1') {
			$goldCount += $quantity;
		}

		if($productID == '2') {
			$silverCount += $quantity;
		}
	}


	// Hämta pris på produkterna
	$sql = "SELECT ProductID, Price FROM db19880310.Products WHERE ProductID='1' OR ProductID='2'";
	$res = mysqli_query($conn, $sql);


	while($data = mysqli_fetch_assoc($res)) {
		if($data['ProductID'] == '1') {
			$priceGold = $data['Price'];
		}
		if($data['ProductID'] == '2') {
			$priceSilver = $data['Price'];
		}
	}

	if($goldCount != '0' || $priceGold != '') {
		$priceGold = $priceGold * $goldCount;
	} else {
		$priceGold = '0';
	}
	if($silverCount != '0' || $priceSilver != '') {
		$priceSilver = $priceSilver * $silverCount;
	} else {
		$priceSilver = '0';
	}

	$totalPrice = $priceGold + $priceSilver;


	$sql = "DELETE FROM db19880310.OrderItems WHERE OrderID='$orderID'";
	$res = mysqli_query($conn, $sql);


	$sql = "INSERT INTO db19880310.OrderItems (OrderID, ProductID, Quantity)
			VALUES ('$orderID', '1', $goldCount)";
	$res = mysqli_query($conn, $sql);	


	$sql = "INSERT INTO db19880310.OrderItems (OrderID, ProductID, Quantity)
			VALUES ('$orderID', '2', $silverCount)";
	$res = mysqli_query($conn, $sql);	
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

<h1>Guld och silver AB - Min Kundvagn</h1>

<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>
<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a>
<a href="store.php"><button type="submit" value="Submit">Produkter</button></a>


<?php
if($goldCount > 0) {
?>



<fieldset>
<center>
<div style="width:500px;">
  <div style="width:200px; float:left;"><p style="text-align:center;">
<a href="gold.php"><input type="image" src="https://cdn-3d.niceshops.com/upload/image/product/large/default/fiberlogy-fibersilk-metallic-gold-326274-sv.jpg" name="submit" width="200" height="150"/></a></p>
</div></center>
  <div style="width:700px; float:right;"><p><a href="gold.php">99,9% rent guld.</a></p><p style="text-decoration: underline;"><label id="guld"></label></p>
<p style="text-decoration: underline;"><label id="guldPris"></label></p>



<form name="form" method="POST">
    <p>
	<label for="username">Ändra varukorgen? Skriv in nytt önskat<br> antal produkter: </label>
	<input type="text" id="changegold" name="changegold">
	<button type="submit" name="ChangeGold" value="Submit">Ändra varukorg</button></p>
</form></div>
</div>
<div style="clear: both;"></div>
</fieldset>
<?php
}
?>



<?php
if($silverCount > 0) {
?>
<fieldset>
<center>
<div style="width:500px;">
  <div style="width:200px; float:left;"><a href="silver.php"><input type="image" src="https://th.bing.com/th/id/R.4647e7752887fe3122b9e7036a0e68ce?rik=nDnCb7zPvrhJXw&pid=ImgRaw&r=0" name="submit" width="200" height="150"/></a></p>
</div></center>
  <div style="width:700px; float:right;"><p><a href="silver.php">99,9% rent silver.</a></p><p style="text-decoration: underline;"><label id="silver"></label></p>
<p style="text-decoration: underline;"><label id="silverPris"></label></p>



<form name="form" method="POST">
    <p>
	<label for="username">Ändra varukorgen? Skriv in nytt önskat<br> antal produkter: </label>
	<input type="text" id="changesilver" name="changesilver">
	<button type="submit" name="ChangeSilver" value="Submit">Ändra varukorg</button></p>
</form></div>
</div>
<div style="clear: both;"></div>
</fieldset>


<?php
}
?>




<!-- totalpris och betala -->
<fieldset>
	<p style="text-align:center;">
	<p style="text-align:center;"><label id="totalpris"></label></p>

	<form name="form" method="POST">

<?php
if($totalPrice != 0) {
?>
	<p style="text-align:center;"><button type="submit" onclick="alert('Köp genomfört')"value="Submit" name="Betala">Betala</button></p>
<?php
}
?>

</form>
</fieldset>





<script>
	getTotalprice();
	<?php
	if($goldCount > 0) {
	?>
		getGoldcount();
		getGoldprice();
	<?php	
	}
	?>
	
	<?php
	if($silverCount > 0) {
	?>
		getSilvercount();
		getSilverprice();
	<?php
	}
	?>

       function getGoldcount() {
        var guld = <?php echo $goldCount ?>;
		document.getElementById('guld').innerHTML = "Produkter i varukorgen: " + guld + " gram guld.";	
    }
	function getGoldprice() {
		var price = <?php echo $priceGold ?>;
		var guld = <?php echo $goldCount ?>;
		document.getElementById('guldPris').innerHTML = "Kostnad: " + guld + " gram á 244 kr/gram: " + price + " kr.";
	}
	function getSilvercount() {
		var silver = <?php echo $silverCount ?>;
		document.getElementById('silver').innerHTML = "Produkter i varukorgen: " + silver + " gram silver.";
	}
	function getSilverprice() {
		var price = <?php echo $priceSilver ?>;
		var silver = <?php echo $silverCount ?>;
		document.getElementById('silverPris').innerHTML = "Kostnad: " + silver + " gram á 3.20 kr/gram: " + price + " kr.";
	}
	function getTotalprice() {
		var total = <?php echo $totalPrice ?>;
		document.getElementById('totalpris').innerHTML = "Totalt pris: " + total + "kr";
	}
</script>


</body>
</html>