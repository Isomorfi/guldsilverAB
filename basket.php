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
        if(isset($_POST['pickup']) ^ isset($_POST['ship'])) { // Ena eller det andra
		    $orderID = $_POST['Betala'];
		    $_SESSION['orderID'] = $orderID;
		    $total = $_POST['tot'];

            $sql = "SELECT Balance FROM Wallet WHERE Username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            if($stmt->execute()) {
                $res = $stmt->get_result();
                $data = $res->fetch_assoc();
                $stmt->close();
                $balance = $data['Balance'];
            } 
                  
            if($balance >= $total) {
                    
                if(isset($_POST['ship'])) {
                    $shippingcost = $_POST['shipcost'];
                    $balance = $balance - $total - $shippingcost;
                    $inclship = $total + $shippingcost;

                    $sql = "UPDATE Orders 
                            SET Status=?, CostInclShip=?, ShippingCost=?, Delivery=?, orderDate=CURRENT_TIMESTAMP, TotalCost=? 
                            WHERE Username=? AND OrderID=?"; 
                    $stmt = $conn->prepare($sql);
                    $status = 'Ordered';
                    $delivery = 'Shipping';
                    $stmt->bind_param("ssisssi", $status, $inclship, $shippingcost, $delivery, $total, $username, $orderID);
                    
                }
                else {
                    $shippingcost = 0;
                    $balance = $balance - $total;
                    $inclship = $total + $shippingcost;

                    $sql = "UPDATE db19880310.Orders 
                            SET Status=?, CostInclShip=?, ShippingCost=?, Delivery=?, orderDate=CURRENT_TIMESTAMP, TotalCost=? 
                            WHERE Username=? AND OrderID=?"; 
                    $stmt = $conn->prepare($sql);
                    $status = 'Ordered';
                    $delivery = 'Pick up';
                    $stmt->bind_param("ssisssi", $status, $inclship, $shippingcost, $delivery, $total, $username, $orderID);
                }

                if($stmt->execute()) {
                    $_SESSION['balance'] = $balance;

                    $sql = "UPDATE Wallet SET Balance=? WHERE Username=?"; 
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ds", $balance, $username);
                    $stmt->execute();
                    
                    $stmt->close();
                    header("Location: checkout.php");	
                    die;
                }
                else {
                    $stmt->close();
                    header("Location: basket.php");
                    die;
                }
                
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

        $conn->begin_transaction();

        $sql = "SELECT Stock FROM Products WHERE ProductID=?";
		$stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $prodid);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        $stmt->close();

		$stock = $data['Stock'];

        $sql = "SELECT Quantity FROM OrderItems WHERE ProductID=? AND OrderID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $prodid, $orderID);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        $stmt->close();

		$quantity = $data['Quantity'];

		if($newquantity >= 0 && $stock >= $newquantity) {

			$diff = $quantity - $newquantity;
			$newStock = $stock + $diff;

            $sql = "UPDATE Products 
                    SET Stock=?
                    WHERE ProductID=?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $newStock, $prodid);
            $stmt->execute();

            $sql = "UPDATE OrderItems 
                    SET Quantity=?
                    WHERE ProductID=? AND OrderID=?"; 
			$stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $newquantity, $prodid, $orderID);
            $stmt->execute();

            $stmt->close();
            
            $conn->commit();
			header("Location: basket.php");	
			die;
		} else {
            $conn->commit();
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
            <h1>Sverige-mineralen AB</h1>
        </a>

        <div id="topnav-right">

            <?php
            if(isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {?>

                <fieldset class="fieldset-auto-width">
                <?php
                echo "<p>" . "Inloggad: " . $_SESSION['username'] . "<br>" . "Kontobalans: " . number_format($_SESSION['balance'], 2, '.', ',') . " kr" . "</p>";
                ?>
                </fieldset>
            <?php
            }
            ?>
            <a href="store.php">
                <h2>Produkter</h2>
            </a>
            <a href="mypages.php">
                <h2>Mina sidor</h2>
            </a>
            <a href="home.php">
                <h2>Logga ut</h2>
            </a>
                
        </div>
    </div>
</header>

<br><center><h1>Kundvagn</h1></center><br>

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
        
        <center><div style="width:700px;">
            <div style="width:300px; float:left;">
                <p style="text-align:center;"><?php echo "<a href=\"$link?ProductID=$prodid\">";?><input type="image" 
	        src="<?php echo $src ?>" 
	        name="submit" width="200" height="150"/></p>
            </div></center>
            <div style="width:400px; float:left;">
                <p><?php echo "<a href=\"$link?ProductID=$prodid\">";?><?php echo $prodname?></a></p><p style="text-decoration: underline;"><label>Produkter i varukorgen: <?php echo $quantity . " " . $unit . " " . $prodname?></label></p>
    <p style="text-decoration: underline;"><label>Kostnad: <?php echo $quantity . " " . $unit . " á " . number_format($price, 2, '.', ',') . " kr/" . $unit . ": " . number_format($totprice, 2, '.', ',') . " kr"?></label></p>

    <form name="form" method="POST">
        <p>
	        <label for="username">Ändra varukorgen? Skriv in nytt önskat<br> antal produkter: </label>
	        <input type="text" id="changegold" name="changegold">
	        <input type="hidden" name="order" value="<?php echo $orderID;?>">
	        <button type="submit" value="<?php echo $data['ProductID']?>" name="change">Ändra varukorg</button></p>
    </form>
            </div>
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
    echo "<p style=\"text-align:center;\">" . number_format($shippingcost, 2, '.', ',') . " kr" . "</p>"; 
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
        <?php echo "<p style=\"text-align:center;\">" . "Totalt: " . number_format($total, 2, '.', ',') . " kr" . "</p>"; ?>
        <br><p style="text-align:center;"><input type="checkbox" name="ship" value="Skickas">Leverera med PostNord.</input></p>
	<?php
            $totwithship = $total + $shippingcost;
            echo "<p style=\"text-align:center;\">" . "Totalt: " . number_format($totwithship, 2, '.', ',') . " kr" . "</p>";
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
&copy; <?php echo date ('Y') . " Sverige-mineralen AB. All rights reserved."; ?></p></center>
</body>
</html>