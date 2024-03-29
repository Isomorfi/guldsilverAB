<?php
session_start();

include("db_connection.php");

if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}
if(isset($_SESSION['message']) && $_SESSION['message'] == true) {
    echo '<script>alert("Produkt tillagd.")</script>';
    $_SESSION['message'] = false;
}

if(isset($_GET['ProductID'])){
    $_SESSION['ProductID'] = $_GET['ProductID'];
    $prodid = $_SESSION['ProductID'];
    
    $sql10 = "SELECT * FROM Products WHERE ProductID='$prodid'";
    $res10 = mysqli_query($conn, $sql10);
    $data10 = mysqli_fetch_assoc($res10);
    
    $name = $data10['ProductName'];
    $price = $data10['Price'];
	$url = $data10['PicSrc'];
	$info = $data10['Description'];
	$stock = $data10['Stock'];
	$unit = $data10['Unit'];
        $weight = $data10['Weight'];


}
else {
	$prodid = 0;
	$name = '';
	$price = '';
	$url = '';
	$info = '';
	$stock = '';
	$unit = '';
        $weight = '';
}


if($_SERVER['REQUEST_METHOD'] == "POST") {
	if(isset($_POST['create'])){
		$name = $_POST['productname']; 
		$price = $_POST['price'];
		$url = $_POST['url'];
		$info = $_POST['info'];
                $weight = $_POST['weight'];
		if(isset($_POST['pricetype'])) {
                		$unit = $_POST['pricetype'];
		}
		else {
			$unit = '';
		}
		$stock = $_POST['stock'];
		
		if(strlen($name) > 0 && $price > 0 && strlen($url) > 0 && strlen($weight) > 0 && strlen($info) > 0 && $stock >= 0 && strlen($unit) > 0) {
			
			if($prodid == 0) {
				$sql = "INSERT INTO Products (ProductName, Price, PicSrc, Stock, Description, Unit, Weight) VALUES (?, ?, ?, ?, ?, ?, ?)";
				
                                $stmt = $conn->prepare($sql); 
                                $stmt->bind_param("sdsissi", $name, $price, $url, $stock, $info, $unit, $weight);
                                $stmt->execute();
                                $stmt->close();
                                
                                $_SESSION['message'] = true;
                                header("Location: createProduct.php");
                                die;
      
			}
			else {
				$sql = "UPDATE Products SET Productname=?, Price=?, PicSrc=?, Stock=?, Description=?, Unit=?, Weight=? WHERE ProductID=?";	
				$stmt = $conn->prepare($sql); 
                                $stmt->bind_param("sdsissii", $name, $price, $url, $stock, $info, $unit, $weight, $prodid);
                                $stmt->execute();
                                $stmt->close();
                                
                                $_SESSION['message'] = true;
                                header("Location: createProduct.php");
                                die;
			}
                      
                }
		else {
			echo '<script>alert("Något gick galet, kontrollera fält!")</script>';
		}
	}

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
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
<center><h1>Skapa produkt</h1></center>
<br>


<fieldset>


<center><style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
	text-align:left;
    }
</style>

    <fieldset class="fieldset-auto-width">
<form name="form" method="POST">

<div style="width:500px;">
  <div style="width:150px; float:left;">
<p><label for="productname">Produktnamn:</label></p><br>
<p><label for="price">Pris:</label></p><br>
<p><label for="url">URL:</label></p><br><br>
<p><label for="bal">Lagersaldo:</label></p><br>
<p><label for="weight">Vikt (gram):</label></p><br>
<p><label for="info">Produktinfo:</label></p>
</div>
  <div style="width:350px; float:left;">
<p><input type="text" id="productname" value="<?php echo $name?>" name="productname"></p><br>
<p><input type="text" id="price" value="<?php echo $price?>" name="price"><label for="pricetype"></label>

  <select name="pricetype" id="pricetype">
    <option value="" selected disabled hidden>Välj enheten</option>
    <option value="gram">gram</option>
    <option value="kg">kg</option>
    <option value="st">st</option>
</select>
  <br></p><br>
<p><input type="url" id="text" value="<?php echo $url?>" name="url"><label></p><br>
<p><input type="text" id="text" value="<?php echo $stock?>" name="stock"><label></p><br>
<p><input type="text" id="text" value="<?php echo $weight?>" name="weight"><label></p><br>
<p><textarea name="info" cols="40" rows="5"><?php echo $info?></textarea></p>
</div>
</div>
<div style="clear: both;"></div>



       <p style="text-align:center;"><button type="submit" name="create" value="Submit">Skicka</button></p>
     </form></fieldset>
</fieldset>
<center>
<p>
&copy; <?php echo date ('Y') . " Sverige-mineralen AB. All rights reserved."; ?></p></center>
</body>
</html>