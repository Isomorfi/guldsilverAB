<?php
session_start();

include("db_connection.php");

if(isset($_SESSION['signedin']) && $_SESSION['signedin'] == true && $_SESSION['username'] === "Admin") {	
	echo "Inloggad som " . $_SESSION['username'] . ".";
} else {
	echo " Endast admin har tillgång till denna sida.";
	header("Location: home.php");	
	die;
	
}


if($_SERVER['REQUEST_METHOD'] == "POST") {
	if(isset($_POST['create'])){
		$name = $_POST['productname']; 
		$price = $_POST['price'];
		$url = $_POST['url'];
		$info = $_POST['info'];
                 $unit = $_POST['pricetype'];
		$stock = $_POST['stock'];
		if(strlen($name) > 0 && strlen($price) > 0 && strlen($url) > 0 && strlen($info) > 0 && strlen($stock) >= 0) {
			

			$sql = "INSERT INTO Products (ProductName, Price, PicSrc, Stock, Description, Unit) VALUES ('$name', '$price', '$url', '$stock', '$info', '$unit')";
			if ($conn->query($sql) === TRUE) {
				echo '<script>alert("Ny produkt skapad!")</script>';
			}
                        else {
                            echo '<script>alert("Produkt kan inte skapas just nu! Kontakta IT-avdelningen!")</script>';
                        }
		}
		else {
			echo '<script>alert("Fält kan inte lämnas tomma!")</script>';
		}
	}

}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <style>
body {background-color: powderblue;}
h1   {color: #020764;}
p    {color: #020764;}
</style>
</head>
<body>

<h1>Guld och silver AB - Skapa ny produkt</h1>

<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="mypages.php"><button type="submit" value="Submit">Beställningar</button></a>

<a href="store.php"><button type="submit" value="Submit">Produkter</button></a>

<fieldset>


<center><style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
	text-align:left;
    }
</style>

    <fieldset class="fieldset-auto-width">
<form name="form" method="POST">

<div style="width:450px;">
  <div style="width:150px; float:left;">
<p><label for="productname">Produktnamn:</label></p><br>
<p><label for="price">Pris:</label></p><br>
<p><label for="url">URL:</label></p><br><br>
<p><label for="url">Lagersaldo:</label></p><br>
<p><label for="info">Produktinfo:</label></p>
</div>
  <div style="width:250px; float:left;">
<p><input type="text" id="productname" name="productname"></p><br>
<p><input type="text" id="price" name="price"><label for="pricetype"></label>
  <select name="pricetype" id="pricetype">
    <option value="gram">gram</option>
    <option value="kg">kg</option>
    <option value="st">st</option>
  </select>
  <br></p><br>
<p><input type="url" id="text" name="url"><label></p><br>
<p><input type="text" id="text" name="stock"><label></p><br>
<p><textarea name="info" cols="40" rows="5"></textarea></p>
</div>
</div>
<div style="clear: both;"></div>








       <p style="text-align:center;"><button type="submit" name="create" value="Submit">Skapa</button></p>
     </form></fieldset>
</fieldset>


</body>
</html>