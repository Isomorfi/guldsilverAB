<?php

session_start();
include("db_connection.php");


if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}



$username = $_SESSION['username'];
$orderID = '';
$total = 0;
$weight = 0;
$shippingcost = 0;

$sql = "DELETE FROM OrderItems WHERE Quantity='0'";
$conn->query($sql);



if($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['Betala'])) {
        if(isset($_POST['pickup']) ^ isset($_POST['ship'])) { // ena eller andra
		$orderID = $_POST['Betala'];
		$_SESSION['orderID'] = $orderID;
		$total = $_POST['tot'];

                
                $sql = "SELECT Balance FROM Wallet WHERE Username='$username'";
                $res = mysqli_query($conn, $sql);
		$data = mysqli_fetch_assoc($res);
		$balance = $data['Balance'];
                  
                if($balance >= $total) {
                    
                    if(isset($_POST['ship'])) {
                        $shippingcost = $_POST['shipcost'];
                        $balance = $balance - $total - $shippingcost;
                        $sql = "UPDATE db19880310.Orders SET Status='Ordered', ShippingCost='$shippingcost', Delivery='Shipping', orderDate=CURRENT_TIMESTAMP, TotalCost='$total' WHERE Username='$username' AND OrderID='$orderID'"; 
                        $conn->query($sql);
                    }
                    else {
                        $shippingcost = 0;
                        $balance = $balance - $total;
                        $sql = "UPDATE db19880310.Orders SET Status='Ordered', ShippingCost='$shippingcost', Delivery='Pick up', orderDate=CURRENT_TIMESTAMP, TotalCost='$total' WHERE Username='$username' AND OrderID='$orderID'"; 
                        $conn->query($sql);
                    }
                
                    $_SESSION['balance'] = $balance;

                    $sql = "UPDATE db19880310.Wallet SET Balance='$balance' WHERE Username='$username'"; 
                    $conn->query($sql);
                    
                    header("Location: checkout.php");	
                    die;
                }
                else {
                    echo '<script>alert("Du har inte nog med pengar för att kunna handla.")</script>';
                }
        }
        else {
            echo '<script>alert("Du måste välja ett leveransalternativ.")</script>';
        }
    }


	
	if (isset($_POST['change'])) {
		
		$orderID = $_POST['order'];
		$_SESSION['OrderID'] = $orderID;
		$newquantity = $_POST['changegold'];
		$prodid = $_POST['change'];


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

    <link rel="stylesheet" href="style.css">
    <title>     
        Sverige-mineralen AB
    </title>  

</head>
<body>

    <header>
        <center><label>&#10004; Snabb leverans  &#10004; Låga priser  &#10004; Miljöcertifierade produkter</label></center>
        <div class="topnav">

            <a href="store.php">
                <h1>Sverige-mineralen AB - Kundvagn</h1>
            </a>

            <div id="topnav-right">

                <?php
                if(isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {?>

                    <fieldset class="fieldset-auto-width">
                    <?php
                    echo "<p>" . "Inloggad: " . $_SESSION['username'] . "." . "<br>" . "Kontobalans: " . $_SESSION['balance'] . " kr." . "</p>";
                    ?>
                    </fieldset>
                <?php
                }
                ?>
 
                <a href="mypages.php">
                    <h2>Mina sidor</h2>
                </a>
                <a href="home.php">
                    <h2>Logga ut</h2>
                </a>
                <a href="store.php">
                    <h2>Produkter</h2>
                </a>
            </div>
        </div>
    </header>

<br><br>


<?php

$sql = "SELECT * FROM Orders
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
        $weight += $data['Weight'] * $quantity;

        $total = $total + $totprice;

        $costupdate = "UPDATE OrderItems SET TotalCost='$totprice' WHERE OrderID='$orderID' AND ProductID='$prodid'";
        $conn->query($costupdate);

    ?>
    <fieldset>
        <center>
        <div style="width:500px;">
        <div class="a"><p style="text-align:center;"><?php echo "<a href=\"$link?ProductID=$prodid\">";?><input type="image" 
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

<fieldset>
     <p style="text-align:center;"><label>Fraktkostnad:</label></p>

    <?php
    $shippingcost = ceil($weight/1000) * 49; 
    echo "<p style=\"text-align:center;\">" . $shippingcost . " kr." . "</p>"; 
    ?>
</fieldset>



<!-- totalpris och betala -->
<fieldset>
	
	
	<form name="form" method="POST">

<?php
if($total != 0) {
?>
            
            <p style="text-align:center;"><label>Välj ett alternativ:</label></p><br>
        <p style="text-align:center;"><input type="checkbox" name="pickup" value="Hämta på lager">Hämta på lager i Kolsva.</input></p>
        <?php echo "<p style=\"text-align:center;\">" . "Totalt: " . $total . " kr" . "</p>"; ?>
        <br><p style="text-align:center;"><input type="checkbox" name="ship" value="Skickas">Frakta med PostNord.</input></p>
	<?php
            $totwithship = $total + $shippingcost;
            echo "<p style=\"text-align:center;\">" . "Totalt: " . $totwithship . " kr." . "</p>";
            ?>
        <input type="hidden" name="tot" value="<?php echo $total;?>">
        <input type="hidden" name="shipcost" value="<?php echo $shippingcost;?>">
        <br>
        
        
	<p style="text-align:center;"><button type="submit" value="<?php echo $orderID?>" name="Betala">Betala</button></p>
<?php
}
?>

</form>
</fieldset>
<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>