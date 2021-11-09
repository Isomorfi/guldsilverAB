<?php

session_start();

if(isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {	
	echo "Inloggad som " . $_SESSION['username'] . ".";
} else {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");	
	die;
	
}

include("db_connection.php");

	$username = $_SESSION['username'];
	$orderID = '';
	$quantity = '';
	
	// Hämta orderId
	$sql = "SELECT OrderID FROM db19880310.Orders WHERE Username='$username' AND Status='Basket'";
	$res = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($res);

	$orderID = $data['OrderID'];
	
	// Hämpa alla produkter som hör till orderId
	$sql = "SELECT * FROM db19880310.OrderItems WHERE OrderID='$orderID'";
	$res = mysqli_query($conn, $sql);

	$goldCount = 0;
	$silverCount = 0;
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

	$priceGold = '';
	$priceSilver = '';

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

?>


<!DOCTYPE html>
<html>
<head>
<style>
body {background-color: powderblue;}
h1   {color: blue;}
p    {color: blue;}
</style>
</head>
<body>

<h1>Guld och silver AB - Min Kundvagn</h1>

<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>
<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a>
<a href="store.php"><button type="submit" value="Submit">Produkter</button></a>


<p style="text-align:center;"><label for="fname">99,9% rent guld. Pris 244kr/g.</label></p>
<p style="text-align:center;">
<img src="https://cdn-3d.niceshops.com/upload/image/product/large/default/fiberlogy-fibersilk-metallic-gold-326274-sv.jpg" alt="Logo" width="150" height="100"></p>

<p style="text-align:center;"><label id="guld"></label></p>



<p style="text-align:center;"><label for="fname">99,9% rent silver. Pris 3.20kr/g.</label></p>
<p style="text-align:center;">
<img src="https://th.bing.com/th/id/R.4647e7752887fe3122b9e7036a0e68ce?rik=nDnCb7zPvrhJXw&pid=ImgRaw&r=0" alt="Logo" width="150" height="100"></p>

<p style="text-align:center;"><label id="silver"></label></p>


<p style="text-align:center;">
<p style="text-align:center;"><label id="totalpris"></label></p>


<script>
	getGoldcount();
	getSilvercount();
	getTotalprice();
    function getGoldcount() {
        var guld = <?php echo $goldCount ?>;
		document.getElementById('guld').innerHTML = "Antal guld: " + guld;
    }
	function getSilvercount() {
		var silver = <?php echo $silverCount ?>;
		document.getElementById('silver').innerHTML = "Antal silver: " + silver;
	}
	function getTotalprice() {
		var total = <?php echo $totalPrice ?>;
		document.getElementById('totalpris').innerHTML = "Totalt pris: " + total + "kr";
	}
</script>


</body>
</html>