
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

	if (isset($_POST['update'])) {
		$stockvalue = $_POST['stock'];

		$sql = "UPDATE Products SET Stock='$stockvalue' WHERE ProductID='1'";
		if($conn->query($sql)){
			header("Location: gold.php");
			die;
		}
		else {
			echo "Kan inte fylla på saldo just nu.";
		}
	}

	if (isset($_POST['Delete'])) {
		$commentid = $_POST['Delete'];
		$sql = "DELETE FROM Comments WHERE CommentID='$commentid'";
		if($conn->query($sql)){
			header("Location: gold.php");
			die;
		}
		else {
			echo "Kunde inte ta bort kommentaren just nu.";
		}
	}

	// kolla om recensionsknapp nedtryck. Lägg in kommentar i databas.
	if (isset($_POST['Recension'])) {
		$username = $_SESSION['username'];
		//$orderID = $_SESSION['OrderID'];
		$rating = $_POST['grade'];
	
		$comment = $_POST['comment'];
		

		$sql = "INSERT INTO db19880310.Comments (Comment, Username, ProductID, Rating) VALUES ('$comment', '$username', '$productID', $rating)";  
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
h1   {color: #020764;}
p    {color: #020764;}
h4   {color: #020764;}
</style>
</head>
<body>

<h1>Guld och silver AB</h1>

<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a>

<a href="store.php"><button type="submit" value="Submit">Produkter</button></a><br>

<a href="basket.php"><input type="image" src="https://purepng.com/public/uploads/large/purepng.com-shopping-cartshoppingcarttrolleycarriagebuggysupermarkets-1421526532323sy0um.png" name="submit" width="60" height="60"/></a>

<?php
$sql = "SELECT * FROM db19880310.Products WHERE ProductID='1'";
$conn->query($sql);
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);
$_SESSION['Stock'] = $data['Stock'];
$pricegold = $data['Price'];
?>

<fieldset>
<center><h1>Guld</h1></center>
<p style="text-align:center;"><img src="https://cdn-3d.niceshops.com/upload/image/product/large/default/fiberlogy-fibersilk-metallic-gold-326274-sv.jpg" alt="Logo" width="250" height="200"></p>

<?php

$ratingquery = "SELECT Rating FROM Comments WHERE ProductID='1'";
$conn->query($ratingquery);
$resu = mysqli_query($conn, $ratingquery);
$avgrat = 0;
$count = 0;
while($ratingdata = mysqli_fetch_assoc($resu)) {
	$avgrat = $avgrat + $ratingdata['Rating'];
	$count = $count + 1;
}
if($count > 0){
$avgrat = $avgrat/$count;

echo "<h4 style='text-align:center;'>" . "Betyg: " . number_format($avgrat, 1) . " av 5. Antal omdömen: " . $count . "." . "</h4>"; 
} else {
	echo "<h4 style='text-align:center;'>" . "Det finns inga betyg för den här produkten." . "</h4>";
}
?>


<form name="form" method="POST">
<p style="text-align:center;"><label for="fname">99,9% rent guld. Utvunnet och producerat i de norrländska <br> skogarna i Överkalix där Kalixälvens porlande vatten och <br> tidens tand har slipat till vårat guld i tusentals år. <br><br><p style="text-decoration: underline; text-align:center;">Antal i lager: <?php echo $_SESSION['Stock'], " gram."; ?><br><br>Pris: <?php echo $pricegold?> kr/gram.</label></p></p>

<?php
if($_SESSION['username'] === "Admin") {?>
<p style="text-align:center;"><label for="stock">Nytt lagersaldo: </label><input type="text" id="stock" name="stock">
<button type="submit" name="update" value="Submit">Uppdatera</button></p>
     </form>
<?php
}


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
<label for="grade">Välj ett betyg:</label>
  <select name="grade" id="grade">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
  </select>
  <br></p>

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
    echo "<h4>" . $row['Username'] . "&nbsp;" . "(" . $row['CommentDate'] . ")" . "&nbsp;" . "Betyg: " . $row['Rating'] . " av 5" ."</h4>";
    echo "<p>" . $row['Comment'] . "</p>";

if(($_SESSION['username'] === $row['Username']) || $_SESSION['username'] === "Admin") {?>
<form name="form" method="POST">
    <p>
	<button type="submit" name="Delete" value="<?php echo $row['CommentID']?>">Ta bort</button></p>
</form>
<?php
}
    
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