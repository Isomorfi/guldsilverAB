<?php
session_start();
include("db_connection.php");
unset($_SESSION['signedin']);
unset($_SESSION['username']);
unset($_SESSION['firstname']);
unset($_SESSION['lastname']);
unset($_SESSION['address']);
unset($_SESSION['zip']);
unset($_SESSION['city']);
unset($_SESSION['country']);
unset($_SESSION['email']);
unset($_SESSION['phone']);
unset($_SESSION['balance']);


$sql = "SELECT Orders.OrderID, OrderItems.ProductID, OrderItems.Quantity, Orders.OrderDate, Orders.Status
FROM Orders
INNER JOIN OrderItems ON Orders.OrderID=OrderItems.OrderID WHERE OrderDate < NOW() - INTERVAL 1 day AND Status='Basket' ORDER BY ProductID ASC";
$res = mysqli_query($conn, $sql);
$gold = 0;


$current = 0;
$previous = 0;
$counter = 0;

// Radera alla kundvagnar som är äldre än ett dygn och har status basket. Återställ lagersaldo.

if (mysqli_num_rows($res) > 0) {
while($data = mysqli_fetch_assoc($res)) {
	$ord = $data['OrderID'];
	$current = $data['ProductID'];
	if($counter > 0) {
		if($data['ProductID'] == $previous) {
			$previous = $current;
			$gold = $gold + (int)$data['Quantity'];
		
		}
		else {
			$sql2 = "SELECT Stock FROM Products WHERE ProductID='$previous'";
			$res2 = mysqli_query($conn, $sql2);
			$data2 = mysqli_fetch_assoc($res2);
			$stock = (int)$data2['Stock'] + $gold;
			$sql1 = "UPDATE Products SET Stock='$stock' WHERE ProductID='$previous'";
			$res1 = mysqli_query($conn, $sql1);
			$gold = (int)$data['Quantity'];
			$counter = 0;
			$previous = $current;
		}


	}
	else {
		$previous = $current;
		$gold = $gold + (int)$data['Quantity'];

	}
	$counter = $counter + 1;
	

}

$sql2 = "SELECT Stock FROM Products WHERE ProductID='$previous'";
$res2 = mysqli_query($conn, $sql2);
$data2 = mysqli_fetch_assoc($res2);
$stock = (int)$data2['Stock'] + $gold;
$sql1 = "UPDATE Products SET Stock='$stock' WHERE ProductID='$previous'";

$conn->query($sql1);



$sql4 = "DELETE OrderItems FROM OrderItems INNER JOIN Orders ON Orders.OrderID=OrderItems.OrderID WHERE OrderDate < NOW() - INTERVAL 1 day AND Status='Basket'";
$conn->query($sql4);

$sql3 = "DELETE Orders FROM Orders WHERE OrderDate < NOW() - INTERVAL 1 day AND Status='Basket'";
$conn->query($sql3);

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

<?php



$sql = "SELECT COUNT(Username) as users FROM Customers";
$res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($res);
$num = $data['users'];

$sql = "SELECT Quantity FROM OrderItems";
$res = mysqli_query($conn, $sql);
$Quan = 0;
while($data = mysqli_fetch_assoc($res)) {


$Quan = $Quan + $data['Quantity'];

}
?>

<h1>Guld och silver AB</h1>





<a href="signup.php"><button type="submit" value="Submit">Skapa konto</button></a>

<a href="login.php"><button type="submit" value="Submit">Logga in</button></a>

<p style="text-align:center;"><label for="fname">Välkommen till Sveriges minsta e-handelsbutik för guld och silver.<br> Våra produkter består av 99,9% rent guld och silver och utvinns i våra <br> egna gruvor på Tallvik i Överkalix i norr respektive Kolsva i söder.<br> Att produkterna utvinns i Sverige och har en så pass hög halt av guld och silver<br> i kombination med vårat låga pris, som är 50% under marknadsvärde,<br> gör våra produkter helt unika. <br><br> För att ta del av våra erbjudanden krävs det att du registrerar ett konto hos oss.</label></p>



<p style="text-align:center;"><img src="https://packbud.com/sites/packbud/files/field/gmapimagei154428739gmapimage106949.png" alt="Logo" width="350" height="300"></p>


<p style="text-align:center;"><label for="fname"><b>Några av våra produkter:</b></label></p>


<p style="text-align:center;">
<img <?php
    $sql = "SELECT Price, PicSrc FROM db19880310.Products WHERE ProductID='1'";
    $res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($res);
    ?>
src="<?php echo $data['PicSrc'] ?>" 
alt="Logo" width="250" height="200"></p>

<p style="text-align:center;"><label for="fname">99,9% rent guld. Utvunnet och producerat i Överkalix.
    <br> Pris: <?php echo $data['Price'] ?>kr/g.</label></p>
<br>


<p style="text-align:center;">
<img <?php
    $sql = "SELECT Price, PicSrc FROM db19880310.Products WHERE ProductID='2'";
    $res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($res); 
    ?>
src="<?php echo $data['PicSrc'] ?>"
alt="Logo" width="250" height="200"></p>

<p style="text-align:center;"><form action="/action_page.php"></p>
<p style="text-align:center;"><label for="fname">99,9% rent silver. Utvunnet och producerat i Kolsva.
    <br> Pris: <?php echo $data['Price'] ?>kr/g.</label></p>
</form></p><br><br>
<br><br>
<p style="text-align:center;"><?php echo "Sedan 2021-11-09 : " . $num . " nya användare - " . $Quan . " sålda produkter."?></p>
<br>
<center>
<p>

&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>