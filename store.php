
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
$productID = '';





if($_SERVER['REQUEST_METHOD'] == "POST") {

	foreach ($_POST as $name => $value) {
   		$quantity = $value; 
   		$productID = $name;
	}

	$sql = "SELECT OrderID FROM db19880310.Orders WHERE Username='$username' AND Status='Basket'";
	$res = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($res);
	
	if(!isset($data['OrderID'])) {
		$_SESSION['status'] = 'Ordered';
	}
	else {
		$orderID = $data['OrderID'];
		$_SESSION['status'] = 'Basket';
	}
	

	if($_SESSION['status'] == 'Ordered') {
		$_SESSION['status'] = 'Basket';
		
		$sql = "INSERT INTO db19880310.Orders (Username, Status)
			VALUES ('$username', '".$_SESSION['status']."')";
		$conn->query($sql);

		$sql = "SELECT OrderID FROM db19880310.Orders WHERE Username='$username' AND Status='Basket'";
		$conn->query($sql);
		$res = mysqli_query($conn, $sql);
		$data = mysqli_fetch_assoc($res);
		$orderID = $data['OrderID'];
			
	} 

	

	
	
	$sql = "INSERT INTO db19880310.OrderItems (OrderID, ProductID, Quantity)
		VALUES ('$orderID', '$productID', '$quantity')";
	if ($conn->query($sql) === TRUE) {
		echo " Vara tillagd i varukorg.";
	}

	
}

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

<h1>Guld och silver AB</h1>

<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a>

<?php

if($_SESSION['username'] === "Admin") {?>

<a href="createProduct.php"><button type="submit" value="Submit">Skapa produkt</button></a>
<?php
}
?>
<br>
<a href="basket.php"><input type="image" src="https://purepng.com/public/uploads/large/purepng.com-shopping-cartshoppingcarttrolleycarriagebuggysupermarkets-1421526532323sy0um.png" name="submit" width="60" height="60"/></a>

<?php
$link = 'products.php';
$sql = "SELECT * FROM db19880310.Products";
$res = mysqli_query($conn, $sql);
while($data = mysqli_fetch_assoc($res)){
    $prodid = $data['ProductID'];
    $src = $data['PicSrc'];
    $prodname = $data['ProductName'];
    $pricee = $data['Price'];
    $unit = $data['Unit'];?>
    <fieldset>
    <?php
    echo "<p style=\"text-align:center;\"><a href=\"$link?ProductID=$prodid\"><input type=\"image\" src=\"$src\" 
    name=\"submit\" width=\"250\" height=\"200\"/></a></p>";

    echo "<p style=\"text-align:center;\"><a href=\"$link?ProductID=$prodid\">$prodname" . "<br><br>" . "Pris: " . $pricee . "kr/" . $unit . "</a></p>";
    ?>

</fieldset>
<?php
}


?>





</body>
</html>