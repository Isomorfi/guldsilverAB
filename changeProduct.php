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


echo $_SESSION['productname'];

if($_SERVER['REQUEST_METHOD'] == "POST") {
	if(isset($_POST['create'])){
		$name = $_POST['productname']; 
		$price = $_POST['price'];
		$url = $_POST['url'];
		$info = $_POST['info'];
                 $unit = $_POST['pricetype'];
		if(strlen($name) > 0 && strlen($price) > 0 && strlen($url) > 0 && strlen($info) > 0) {
			

			$sql = "INSERT INTO Products (ProductName, Price, PicSrc, Stock, Description, Unit) VALUES ('$name', '$price', '$url', '0', '$info', '$unit')";
			if ($conn->query($sql) === TRUE) {
				echo " Ny produkt skapad.";
			}
                        else {
                            echo " Produkt kan inte skapas just nu.";
                        }
		}
		else {
			echo " Fält får ej lämnas tomma.";
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
<p><label for="url">URL:</label></p><br>
<p><label for="info">Produktinfo:</label></p>
</div>
  <div style="width:250px; float:left;">
<p><input type="text" id="productname" value="<?php echo $_SESSION['productname']?>"name="productname"></p><br>
<p><input type="text" id="price" value="<?php echo $_SESSION['price']?>" name="price"><label for="pricetype"></label>
  <select name="pricetype" value="<?php echo $_SESSION['unit']?>" id="pricetype">
    <option value="gram">gram</option>
    <option value="kilo">kilo</option>
    <option value="styck">styck</option>
  </select>
  <br></p><br>
<p><input type="url" id="text" value="<?php echo $_SESSION['url']?>" name="url"><label></p><br>
<p><textarea name="info" value="<?php echo $_SESSION['info']?>" cols="40" rows="5"></textarea></p>
</div>
</div>
<div style="clear: both;"></div>








       <p style="text-align:center;"><button type="submit" name="create" value="Submit">Skapa</button></p>
     </form></fieldset>
</fieldset>


</body>
</html>