<?php

session_start();
include("db_connection.php");


if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}

if(isset($_GET['OrderID'])){
    $_SESSION['orderID'] = $_GET['OrderID'];
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

<br>
<center><br>
    <h1>Order</h1></center><br>


<?php
$totPrice = 0;
$orderID = $_SESSION['orderID'];

$username = $_SESSION['username'];

$sql = "SELECT * FROM Customers
    INNER JOIN Orders ON Customers.Username=Orders.Username WHERE OrderID='$orderID'";
$result = mysqli_query($conn, $sql); 
echo "<br>";
while ($row = mysqli_fetch_assoc($result)) {
?>

<center>

<fieldset class="fieldset-auto-width2">
    
<?php
    echo "<b>Ordernummer</b>";
    echo "<p>" . $_SESSION['orderID'] . "</p>";
    echo "<br>";
    echo "<b>Leveransadress</b>";
    echo "<p>" . $row['Firstname'] . " " . $row['Lastname'] . "</p>";
    echo "<p>" . $row['Address'] . "</p>";
    echo "<p>" . $row['ZIP'] . " " . $row['City'] . "</p>";
    echo "<p>" . $row['Country'] . "</p>";
    echo "<br>";
    echo "<b>Kontaktuppgifter</b>";
    echo "<p>" . "E-post: " . $row['Email'] . "</p>";
    echo "<p>" . "Telefon: " . $row['Phone'] . "</p>";
    echo "<br>";
    echo "<b>Leveranssätt</b>";
    echo "<p>" . $row['DELIVERY'] . "</p>";
    echo "<p>" . "Leveranskostnad: " . $row['ShippingCost'] . " kr" . "</p>";
    echo "<br>";
    echo "<b>Din beställning</b>";
}
?>


<?php
$ordquery = "SELECT * FROM db19880310.OrderItems WHERE OrderID='$orderID'";
$res = mysqli_query($conn, $ordquery); 
while ($data = mysqli_fetch_assoc($res)) {


    if(isset($data['ProductID'])) {
    $productID = $data['ProductID'];
    $sql3 = "SELECT ProductName FROM Products WHERE ProductID='$productID'";
    $res3 = mysqli_query($conn, $sql3);
    $data3 = mysqli_fetch_assoc($res3);

    ?>
    <fieldset>
    <br>
    <div class="width:600px;">
  	    <div style="width:240px; float:left;"><input type="image" 
  	    <?php
	    // Hämtar bild source från databas
		    $sql1 = "SELECT * FROM db19880310.Products WHERE ProductID='$productID'";
		    $res1 = mysqli_query($conn, $sql1);
		    $data2 = mysqli_fetch_assoc($res1);
		    $unit = $data2['Unit'];
	    ?>
	    src="<?php echo $data2['PicSrc'] ?>" 
  	    name="submit" width="200" height="150"/></p>
    </div>

    <div style="width:350px; float:left;"><p><label id="silver"><br><?php echo $data3['ProductName']?> <br><br>Antal <?php echo $unit;?>: <?php echo $data['Quantity']?> <br><br>Kostnad: <?php echo number_format($data['TotalCost'], 2, '.', ',')?> kr</label></p>

    </div>
    </div>
    <div style="clear: both;"></div>
    </fieldset>

    <?php
    }
}

$sql3 = "SELECT * FROM Orders WHERE OrderID='$orderID'";
$res3 = mysqli_query($conn, $sql3);
$data3 = mysqli_fetch_assoc($res3);
?>
<br>
    <b><p style="text-align:center;">Orderkostnad:</p></b>
    
    <?php echo "<p style=\"text-align:center;\">" . number_format($data3['TotalCost'], 2, '.', ',') . " kr + " . number_format($data3['ShippingCost'], 2, '.', ',') . " kr frakt" . "</p>";
    echo "<br>";
    $tot = $data3['TotalCost'] + $data3['ShippingCost'];
    echo "<p style=\"text-align:center;\">" . "<b>Total kostnad: </b><br><br>" . number_format($tot, 2, '.', ',') . " kr" . "</p>";
    
   ?>
</fieldset></center>
<br>



</fieldset>
<center>
<p>
&copy; <?php echo date ('Y') . " Sverige-mineralen AB. All rights reserved."; ?>
</p>
</center>
</body>
</html>