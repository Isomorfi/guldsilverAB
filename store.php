

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
            
		$search = $_POST['search'] . '%';
                $sql10 = "SELECT * FROM db19880310.Products WHERE ProductName LIKE ?";
                $stmt = $conn->prepare($sql10); 
                $stmt->bind_param("s", $search);
		
		
		
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
    $stmt = $conn->prepare($sql10);
    
    
}

?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
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
    
	echo "<p>" . "Inloggad: " . $_SESSION['username'] . "." . "<br>" . "Kontobalans: " . $_SESSION['balance'] . " kr." . "</p>";
?>
    </fieldset>
<?php
}

?>
                       <?php

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
                       <a href="basket.php"><input type="image" src="https://purepng.com/public/uploads/large/purepng.com-shopping-cartshoppingcarttrolleycarriagebuggysupermarkets-1421526532323sy0um.png" name="submit" width="60" height="60"/>
                       </a>
                       
                   </div>
                </div>

      </header>


    <br><br>
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
while ($data = $result->fetch_assoc()) {
    $prodid = $data['ProductID'];
    $src = $data['PicSrc'];
    $prodname = $data['ProductName'];
    $pricee = $data['Price'];
    $unit = $data['Unit'];

	
    $status = $data['Available'];
    $counter++;
    
    ?>
     <div class="a">
         <fieldset class="fieldset-auto-width3">
         <?php
    if($_SESSION['username'] === "Admin") {
        ?>

    
    
    
        
        <form name="form" method="POST">
       
           <?php
                if($status) { ?>
                   <p style="text-align:center;">
                       <label for="fname">
                           <p style="text-decoration: underline; text-align:center;">Tillgänglig produkt<br><br>
                       </label>
                   </p></p>

               <?php } else { ?>
                   <p style="text-align:center;">
                       <label for="fname">
                           <p style="text-decoration: underline; text-align:center; color: red;">Otillgänglig produkt<br><br>
                       </label>
                   </p></p>
               <?php } ?>

               <p style="text-align:center;"><button type="submit" name="status" value="<?php echo $prodid ?>">Uppdatera</button></p>

               </form>

           
       <?php
       
    }
           
           if(!$status && $_SESSION['username'] != "Admin") {
               continue;
           }

           ?>

                   <?php echo "<p style=\"text-align:center;\"><a href=\"$link?ProductID=$prodid\"><input type=\"image\" src=\"$src\" 
           name=\"submit\" width=\"200\" height=\"200\"/></a></p>";?>
               <label><?php echo "<p style=\"text-align:center;\"><a href=\"$link?ProductID=$prodid\">$prodname" . "<br><br>" . "Pris: " . $pricee . "kr/" . $unit . "</a></p>";
               ?></label></fieldset></div>
           <?php if($counter == 4) {
               ?> </center> <?php $counter = 0; ?><br> <center> <?php
           }

    
    
           

           }
$stmt->close();
$conn->close();
?>


 </center>
<center>
<p>
    <br><br>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>