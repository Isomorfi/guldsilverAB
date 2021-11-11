
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

<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a><br>

<a href="basket.php"><input type="image" src="https://purepng.com/public/uploads/large/purepng.com-shopping-cartshoppingcarttrolleycarriagebuggysupermarkets-1421526532323sy0um.png" name="submit" width="60" height="60"/></a>



<fieldset>
<p style="text-align:center;"><a href="gold.php"><input type="image" 
<?php
$sql = "SELECT PicSrc FROM db19880310.Products WHERE ProductID='1'";
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);
?>
src="<?php echo $data['PicSrc'] ?>" 
name="submit" width="250" height="200"/></a></p>

<p style="text-align:center;"><a href="gold.php">99,9% rent guld. Pris 244kr/g.</a></p>

</fieldset>


<fieldset>
<p style="text-align:center;"><a href="silver.php"><input type="image" 
<?php
$sql = "SELECT PicSrc FROM db19880310.Products WHERE ProductID='2'";
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);
?>
src="<?php echo $data['PicSrc'] ?>" 
name="submit" width="250" height="200"/></a></p>

<p style="text-align:center;"><a href="silver.php">99,9% rent silver. Pris 6,40kr/g.</a></p>

</fieldset>





</body>
</html>