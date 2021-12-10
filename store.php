
<?php
session_start();

if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}

include("db_connection.php");

$username = $_SESSION['username'];
$sql = "SELECT OrderItems.ProductID
FROM Orders
INNER JOIN OrderItems ON Orders.OrderID=OrderItems.OrderID WHERE Status='Basket' And Username='$username'";
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);

if(!isset($data['ProductID'])) {
    $bask = "empty.png";
}
else {
    $bask = "full.png";
}

$offset = 0;
$orderID = '';
$quantity = '';
$productID = '';
$sql10 = '';

$sql = "SELECT Balance FROM Wallet WHERE Username='$username'";
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);
if(isset($data['Balance'])){
    $_SESSION['balance'] = $data['Balance'];
}

if($_SERVER['REQUEST_METHOD'] == "POST") {

        if (isset($_POST['prev'])) {
            $_SESSION['value'] += -1;
            $offset = 8 * $_SESSION['value'];
            
        }
        if (isset($_POST['next'])) {
            $_SESSION['value'] += 1;
            $offset = 8 * $_SESSION['value'];
        }
    
    
        $sql10 = "SELECT * FROM db19880310.Products LIMIT ".$offset.", 8";
        $stmt = $conn->prepare($sql10); 
        
	if (isset($_POST['searchP'])) {       
		$search = $_POST['search'] . '%';
        $sql10 = "SELECT * FROM db19880310.Products WHERE ProductName LIKE ? LIMIT ".$offset.", 8";
        $stmt = $conn->prepare($sql10); 
        $stmt->bind_param("s", $search);	
	}

	if (isset($_POST['status'])) {
        $prodid = $_POST['status'];
		
        $sql = "SELECT Available From db19880310.Products WHERE ProductID='$prodid'";
        $res = mysqli_query($conn, $sql);
        $status = mysqli_fetch_assoc($res)['Available'] == 1 ? 0 : 1;

        $sql = "UPDATE Products SET Available='$status' WHERE ProductID='$prodid'";
        $stmt = $conn->prepare($sql);  
        $stmt->execute();
        		
        $sql10 = "SELECT * FROM db19880310.Products LIMIT ".$offset.", 8";
        $stmt = $conn->prepare($sql10); 
        $_SESSION['value'] = 0;
    }
}

else {
    $sql10 = "SELECT * FROM db19880310.Products LIMIT ".$offset.", 8";
    $_SESSION['value'] = 0;
    $stmt = $conn->prepare($sql10);  
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

        <a href="#">
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

            if($_SESSION['username'] === "Admin") {?>
                <a href="createProduct.php"><h2>Skapa produkt</h2></a>
            <?php
            }
            ?>
            <a href="mypages.php">
                <h2>Mina sidor</h2>
            </a>
            <a href="home.php">
                <h2>Logga ut</h2>
            </a>
                <a href="basket.php"><input type="image" src="<?php echo $bask?>" name="submit" width="60" height="60"/>
            </a>
                       
        </div>
    </div>
</header>

<br>
<center>
    <h1>Produkter</h1></center><br>


<form name="form" method="POST">
<p style="text-align:center;"><label for="search">Sök produkt: </label><input type="text" id="search" name="search">
<button type="submit" name="searchP" value="SearchP">Sök</button></p>
</form>


<center>
<?php

$link = 'products.php';
$counter = 0;
$stmt->execute();
$result = $stmt->get_result();
$countrow = 0;
while ($data = $result->fetch_assoc()) {

    $countrow++;
    $prodid = $data['ProductID'];
    $src = $data['PicSrc'];
    $prodname = $data['ProductName'];
    $pricee = $data['Price'];
    $unit = $data['Unit'];

    $status = $data['Available'];
    
    $isAdmin = $_SESSION['username'] === "Admin";
    $showForCustomer = ($status && !$isAdmin);

    if($showForCustomer || $isAdmin) {
    ?>
        <div class="a">
            <fieldset class="fieldset-auto-width3">
            <?php
            if($isAdmin) {
            ?>
                <form name="form" method="POST">
                <?php
                if($status) { ?>
                    <p style="text-align:center;">
                    <label for="fname">
                        <p style="text-decoration: underline; text-align:center;">Tillgänglig produkt<br><br></p>
                    </label>
                    </p>
                <?php 
                } else { ?>
                    <p style="text-align:center;">
                    <label for="fname">
                        <p style="text-decoration: underline; text-align:center; color: red;">Otillgänglig produkt<br><br></p>
                    </label>
                    </p>
                <?php 
                } ?>

                <p style="text-align:center;"><button type="submit" name="status" value="<?php echo $prodid ?>">Uppdatera</button></p>
                </form>      
            <?php 
            }

            echo "<p style=\"text-align:center;\"><a href=\"$link?ProductID=$prodid\"><input type=\"image\" src=\"$src\" 
                name=\"submit\" width=\"200\" height=\"200\"/></a></p>";?>
            <label><?php echo "<p style=\"text-align:center;\"><a href=\"$link?ProductID=$prodid\">$prodname" . "<br><br>" . "Pris: " . number_format($pricee, 2, '.', ',') . " kr/" . $unit . "</a></p>";?></label>

            </fieldset>
        </div>

    <?php
    }

    if($counter == 3) {
        ?> 
        </center> <?php $counter = 0; ?><br> <center> 
        <?php
    } 
    else {
        $counter++;  
    }
}

$stmt->close();
$conn->close();


if (!isset($_SESSION['value'])) {
    $_SESSION['value'] = 0;
}
?>
            
    <form name="form" method="POST">
        
<?php
if($_SESSION['value'] > 0) { ?>
        <br><br><br>
    <button type="submit" name="prev" value="<?php echo $low - 10;?>">Föregående produkter</button><?php
    
}
?>
    <?php
if($countrow == 8 ) { ?>
    <br><br>
<button type="submit" name="next" value="<?php echo $high + 10;?>">Fler produkter</button>
<?php
    
}
?>
  </p>
</form>


</center>


<center>
    <p><br><br> &copy; <?php echo date ('Y') . " Sverige-mineralen AB. All rights reserved."; ?></p>
</center>
</body>
</html>