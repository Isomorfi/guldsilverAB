
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
$quantity = '';
$productID = '1';
$comment = '';

if($_SERVER['REQUEST_METHOD'] == "POST") {
	// kolla om recensionsknapp nedtryck. Lägg in kommentar i databas.
	if (isset($_POST['Recension'])) {
		$username = $_SESSION['username'];
		//$orderID = $_SESSION['OrderID'];
	
		$comment = $_POST['comment'];
		

		$sql = "INSERT INTO db19880310.Comments (Comment, Username, ProductID) VALUES ('$comment', '$username', '$productID')";  
		$conn->query($sql);
		
	}

        
    	if (isset($_POST['buy'])) {
		$quantity = $_POST['1'];
		if($quantity > 0 && $_SESSION['Stock'] >= $quantity) {
		
			$newStock = $_SESSION['Stock'] - $quantity;
			
			$sql = "UPDATE db19880310.Products SET Stock='$newStock' WHERE ProductID='1'";
			$conn->query($sql);
			
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
			$conn->query($sql);
	
			}
		
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
h4   {color: red;}
</style>
</head>
<body>

<h1>Guld och silver AB</h1>

<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a>

<a href="store.php"><button type="submit" value="Submit">Produkter</button></a><br>

<a href="basket.php"><input type="image" src="https://purepng.com/public/uploads/large/purepng.com-shopping-cartshoppingcarttrolleycarriagebuggysupermarkets-1421526532323sy0um.png" name="submit" width="60" height="60"/></a>

<?php
$sql = "SELECT Stock FROM db19880310.Products WHERE ProductID='1'";
$conn->query($sql);
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);
$_SESSION['Stock'] = $data['Stock'];
?>

<fieldset>
<center><h1>Guld</h1></center>
<p style="text-align:center;"><img src="https://cdn-3d.niceshops.com/upload/image/product/large/default/fiberlogy-fibersilk-metallic-gold-326274-sv.jpg" alt="Logo" width="250" height="200"></p>
<form name="form" method="POST">
<p style="text-align:center;"><label for="fname">99,9% rent guld. Utvunnet och producerat i de norrländska <br> skogarna i Överkalix där Kalixälven porlande älvar och <br> tidens tand har slipat till vårat guld i tusentals år. <br><br>Antal i lager: <?php echo $_SESSION['Stock'], " gram."; ?><br><br>Pris: 244kr/gram.</label></p>

<?php
if($quantity > 0 && $_SESSION['Stock'] >= $quantity) {
?>
    <h4 style="text-align:center;"><label><?php echo $quantity, " gram är tillagt i varukorgen!"; ?></label></h4>
<?php
}
?>

<p style="text-align:center;"><label for="Guld">Antal gram: </label><input type="text" id="1" name="1">
<button type="submit" name="buy" value="Submit">Köp</button></p>
     </form>
</fieldset>

<fieldset>
<center><h1>Kundrecensioner</h1></center>
<form name="form" method="POST">
<p style="text-align:center;"><textarea name="comment" cols="40" rows="5"></textarea></p>

<p style="text-align:center;">
<button type="submit" name="Recension" value="Submit">Skicka recension</button></p>
     </form>
<br>

<?php
$sql = "SELECT * FROM db19880310.Comments WHERE ProductID='1' ORDER BY CommentDate DESC";
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
    echo "<h4>" . $row['Username'] . "&nbsp;" . $row['CommentDate'] . "</h4>";
    echo "<p>" . $row['Comment'] . "</p>";
    
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