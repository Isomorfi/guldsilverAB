

<?php


session_start();


if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}

include("db_connection.php");



$username = $_SESSION['username'];
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


	if (isset($_POST['searchP'])) {
		$search = $_POST['search'];
                $sql10 = "SELECT * FROM db19880310.Products WHERE ProductName LIKE '$search%'";
		
		
		
	}


	if (isset($_POST['status'])) {
        		$prodid = $_POST['status'];

        		$conn->begin_transaction();
        		$sql = "SELECT Available From db19880310.Products WHERE ProductID='$prodid'";
        		$res = mysqli_query($conn, $sql);
        		$status = mysqli_fetch_assoc($res)['Available'] == 1 ? 0 : 1;

        		$sql = "UPDATE Products SET Available='$status' WHERE ProductID='$prodid'";
        		$conn->query($sql);
        		$conn->commit();
                        $sql10 = "SELECT * FROM db19880310.Products";
	
    	}
	
}
else {
    $sql10 = "SELECT * FROM db19880310.Products";
    
}

?>


<!DOCTYPE html>
<html>
<head>
<style>
body {background-color: powderblue;}
h1   {color: #020764;}
p    {color: #020764;}
</style>
</head>
<body>

<h1>Guld och silver AB - Produkter</h1>

<?php

if(isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {?>
<style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
	text-align:left;
    }
</style>

    <fieldset class="fieldset-auto-width">
        <?php
    
	echo "<p>" . "Inloggad: " . $_SESSION['username'] . "." . "<br>" . "Kontobalans: " . $_SESSION['balance'] . " kr." . "</p>";
?>
    </fieldset>
<?php
}

?>
<br>
<br>

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

<!-- nytt -->
<form name="form" method="POST">
<p style="text-align:center;"><label for="search">Sök produkt: </label><input type="text" id="search" name="search">
<button type="submit" name="searchP" value="SearchP">Sök</button></p>

     </form>
<!-- nytt -->

<?php
$link = 'products.php';

$res = mysqli_query($conn, $sql10);
while($data = mysqli_fetch_assoc($res)){
    $prodid = $data['ProductID'];
    $src = $data['PicSrc'];
    $prodname = $data['ProductName'];
    $pricee = $data['Price'];
    $unit = $data['Unit'];

	
    $status = $data['Available'];

    if($_SESSION['username'] === "Admin") {
        ?>

        <fieldset>
        <form name="form" method="POST">
        <div style="width:300px; display: block; margin-left: auto; margin-right: auto;">

        <?php if($status) { ?>
            <p style="text-align:center;">
                <label for="fname">
                    <p style="text-decoration: underline; text-align:center;">Tillgänglig produkt<br><br>
                </label>
            </p></p></div>

        <?php } else { ?>
            <p style="text-align:center;">
                <label for="fname">
                    <p style="text-decoration: underline; text-align:center;">Ej tillgänglig produkt<br><br>
                </label>
            </p></p></div>
        <?php } ?>

        <p style="text-align:center;"><button type="submit" name="status" value="<?php echo $prodid ?>">Uppdatera</button></p>

        </form>

    <?php
    }
    if(!$status && $_SESSION['username'] != "Admin") {
        continue;
    }

    echo "<p style=\"text-align:center;\"><a href=\"$link?ProductID=$prodid\"><input type=\"image\" src=\"$src\" 
    name=\"submit\" width=\"250\" height=\"200\"/></a></p>";

    echo "<p style=\"text-align:center;\"><a href=\"$link?ProductID=$prodid\">$prodname" . "<br><br>" . "Pris: " . $pricee . "kr/" . $unit . "</a></p>";
    ?>

</fieldset>
<?php
}


?>



<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>