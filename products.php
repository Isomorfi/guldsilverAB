<?php
session_start();
include("db_connection.php");


if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}

if(isset($_GET['ProductID'])){
    $_SESSION['ProductID'] = $_GET['ProductID'];
}


$username = $_SESSION['username'];
$orderID = '';
$quantity = '';

$comment = '';
$prodid = $_SESSION['ProductID'];


if($_SERVER['REQUEST_METHOD'] == "POST") {

	if (isset($_POST['update'])) {
		$stockvalue = $_POST['stock'];
                
		$sql = "UPDATE Products SET Stock=? WHERE ProductID=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $stockvalue, $prodid);
                $ress = $stmt->execute();
                
		if($ress){
			$_SESSION['postdata'] = $_POST;
			unset($_POST);
			echo '<script>alert("Fält kan inte lämnas tomma!")</script>';
			header("Location: products.php");
			die;
		}
		else {
			echo '<script>alert("Något gick fel vid påfyllning av saldo. Kontakta IT-avdelningen.")</script>';
		}
                $stmt->close();
	}


	if (isset($_POST['Delete'])) {
		$commentid = $_POST['Delete'];
		$sql = "DELETE FROM Comments WHERE CommentID='$commentid'";
		if($conn->query($sql)){
			$_SESSION['postdata'] = $_POST;
			unset($_POST);
			header("Location: products.php");
			die;
		}
		else {
			echo '<script>alert("Kommentaren kunde inte tas bort just nu.")</script>';
		}
	}

	if (isset($_POST['Answer'])) {
		$commentid = $_POST['Answer'];
		$answer = $_POST['Ans'];

		$sql = "UPDATE Comments SET Answers=?, Author=?, AnswerDate=current_timestamp WHERE CommentID=?";
                $stmt = $conn->prepare($sql);
                $author='Admin';
                //$answerDate=current_timestamp;
                $stmt->bind_param("ssi", $answer, $author, $commentid);
                $stmt->execute();
                $stmt->close();
                
                $_SESSION['postdata'] = $_POST;
		unset($_POST);
		header("Location: products.php");
                die;
		
	}


	if (isset($_POST['removeprod'])) {
		$sql = "DELETE FROM Products WHERE ProductID='$prodid'";
		if($conn->query($sql)){
			$_SESSION['postdata'] = $_POST;
			unset($_POST);
			echo '<script>alert("Produkten tas bort.")</script>';
			header("Location: store.php");
			die;
		}
		else {
			echo '<script>alert("Någon har handlat av denna produkt och kan ej längre tas bort. Du kan däremot inaktivera den.")</script>';
		}
	}

	// kolla om recensionsknapp nedtryck. Lägg in kommentar i databas.
	if (isset($_POST['Recension'])) {
		$username = $_SESSION['username'];
		$rating = $_POST['grade'];
	
		$comment = $_POST['comment'];
		

		$sql = "INSERT INTO db19880310.Comments (Comment, Username, ProductID, Rating) VALUES (?, ?, ?, ?)";  
		
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssid", $comment, $username, $prodid, $rating);
                $stmt->execute();
                $stmt->close();
		$_SESSION['postdata'] = $_POST;
		unset($_POST);
		header("Location: products.php");
		die;
		
	}

        
    	if (isset($_POST['buy'])) {
		$quantity = $_POST['1'];
                
		if($quantity > 0 && $_SESSION['Stock'] >= $quantity) {
                    //$conn->begin_transaction();
                    $newStock = $_SESSION['Stock'] - $quantity;
                        $stmt = $conn->prepare("UPDATE db19880310.Products SET Stock=? WHERE ProductID=?");
                        $stmt->bind_param("ii", $newStock, $prodid);
                        $stmt->execute();
                        $stmt->close();
			//$conn->commit();
                        
                        $basket = 'Basket';
                        $sql = "SELECT OrderID FROM db19880310.Orders WHERE Username=? AND Status=?"; // SQL with parameters
                        $stmt = $conn->prepare($sql); 
                        $stmt->bind_param("ss", $username, $basket);
                        $stmt->execute();
                        $result = $stmt->get_result(); // get the mysqli result
                        $stmt->close();
                        $data = $result->fetch_assoc(); // fetch data  

			if(!isset($data['OrderID'])) {
				$_SESSION['status'] = 'Ordered';
			}
			else {
				$orderID = $data['OrderID'];
				$_SESSION['status'] = 'Basket';
			}
	

			if($_SESSION['status'] == 'Ordered') {
				$_SESSION['status'] = 'Basket';
		
				$sql = "INSERT INTO db19880310.Orders (Username, Status)
				VALUES (?, ?)";

                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ss", $username, $_SESSION['status']);
                                $stmt->execute();
                                $stmt->close();
                                
                                $sql = "SELECT OrderID FROM db19880310.Orders WHERE Username=? AND Status=?";
                                $stmt = $conn->prepare($sql); 
                                $stmt->bind_param("ss", $username, $basket);
                                $stmt->execute();
                                $result = $stmt->get_result(); // get the mysqli result
                                $stmt->close();
                                $data = $result->fetch_assoc(); // fetch data 
				$orderID = $data['OrderID'];
			
			} 
			
			$sql = "SELECT * FROM OrderItems WHERE ProductID=? AND OrderID=?";
			$stmt = $conn->prepare($sql); 
                                $stmt->bind_param("ii", $prodid, $orderID);
                                $stmt->execute();
                                $result = $stmt->get_result(); // get the mysqli result
                                $stmt->close();
                                $data = $result->fetch_assoc(); // fetch data
			if(!isset($data['OrderID'])) {
                                //$conn->begin_transaction();
				$sql = "INSERT INTO db19880310.OrderItems (OrderID, ProductID, Quantity)
				VALUES (?, ?, ?)";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("iii", $orderID, $prodid, $quantity);
                                $stmt->execute();
                                $stmt->close();
                                //$conn->commit();
				
			}
			else {
                            //$conn->begin_transaction();
				$insquan = $data['Quantity'];
				$quantity = $quantity + $insquan;
				$sql = "Update db19880310.OrderItems SET Quantity=? WHERE OrderID=? AND ProductID=?";
				$stmt = $conn->prepare($sql);
                                $stmt->bind_param("iii", $quantity, $orderID, $prodid);
                                $stmt->execute();
                                $stmt->close();
                                //$conn->commit();
			}
                        
			$_SESSION['postdata'] = $_POST;
                        unset($_POST);
                        header("Location: basket.php");
                        die;
		}
                else {
                    echo '<script>alert("Det finns inte nog många enheter av denna produkt eller så har du inte nog mycket pengar.")</script>';
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
                
				<a href="basket.php"><input type="image" src="https://purepng.com/public/uploads/large/purepng.com-shopping-cartshoppingcarttrolleycarriagebuggysupermarkets-1421526532323sy0um.png" name="submit" width="60" height="60"/>
                </a>
            </div>
        </div>
    </header>

<br>
<br>


<?php

$sql = "SELECT * FROM db19880310.Products WHERE ProductID='$prodid'";
$conn->query($sql);
$res = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($res);
$_SESSION['Stock'] = $data['Stock'];
$pricegold = $data['Price'];
$prodname = $data['ProductName'];
$url = $data['PicSrc'];
$desc = $data['Description'];
$unit =$data['Unit'];
?>

<fieldset>
<center><h1><?php echo $prodname?></h1></center>
<p style="text-align:center;"><img src="<?php echo $url?>" alt="Logo" width="250" height="200"></p>

<?php

$ratingquery = "SELECT Rating FROM Comments WHERE ProductID='$prodid'";
$conn->query($ratingquery);
$resu = mysqli_query($conn, $ratingquery);
$avgrat = 0;
$count = 0;
$link2 = 'createProduct.php';
while($ratingdata = mysqli_fetch_assoc($resu)) {
	$avgrat = $avgrat + $ratingdata['Rating'];
	$count = $count + 1;
}
if($count > 0){
$avgrat = $avgrat/$count;

echo "<h4 style='text-align:center;'>" . "Betyg: " . number_format($avgrat, 1) . " av 5.0" . "<br><br>" . "Antal omdömen: " . $count . "</h4>"; 
} else {
	echo "<h4 style='text-align:center;'>" . "Det finns inga betyg för den här produkten." . "</h4>";
}
?>



<form name="form" method="POST">
<div style="width:300px; display: block; margin-left: auto; margin-right: auto;">
<p style="text-align:center;"><label for="fname"><?php echo $desc?> <br><br><p style="text-decoration: underline; text-align:center;">Antal i lager: <?php echo number_format($_SESSION['Stock'], 0, '', ','), " " . $unit; ?><br><br>Pris: <?php echo number_format($pricegold, 2, '.', ',')?> kr/<?php echo $unit?></label></p></p></div>

<?php
if($_SESSION['username'] === "Admin") {?>
<p style="text-align:center;"><label for="stock">Nytt lagersaldo: </label><input type="text" id="stock" name="stock">
<button type="submit" name="update" value="Submit">Uppdatera</button></p>
     </form>
<?php
echo "<p style=\"text-align:center;\"><a href=\"$link2?ProductID=$prodid\"><button type=\"submit\" name=\"change\" value=\"Submit\">Ändra produkt</button></a></p>";
?>
<form name="form" method="POST">
<p style="text-align:center;"><button type="submit" name="removeprod" value="Submit">Ta bort produkt</button></p>
<?php
}


if($quantity > 0 && $_SESSION['Stock'] >= $quantity) {

    echo '<script>alert($quantity . " " . $unit . " finns nu i varukorgen!")</script>';

}

if($_SESSION['username'] !== "Admin") {?>
<p style="text-align:center;"><label for="Guld">Antal <?php echo $unit?>: </label><input type="text" id="1" name="1">
<button type="submit" name="buy" value="Submit">Köp</button></p>
     </form>
</fieldset>
<?php
}
?>
<fieldset>
<center><h1>Kundrecensioner</h1></center>
<?php
if($_SESSION['username'] !== "Admin") {?>
<form name="form" method="POST">
<p style="text-align:center;"><textarea name="comment" cols="40" rows="5"></textarea></p>

<p style="text-align:center;">
<label for="grade">Välj ett betyg:</label>
  <select name="grade" id="grade">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
  </select>
  <br></p>

<p style="text-align:center;">
<button type="submit" name="Recension" value="Submit">Skicka recension</button></p>
     </form>
<?php
}
?>
<br>


<?php

$sql = "SELECT * FROM db19880310.Comments WHERE ProductID='$prodid' ORDER BY CommentDate DESC";
$result = mysqli_query($conn, $sql); 

echo "<br>";


while ($row = mysqli_fetch_assoc($result)) {
?>
<center>

    <fieldset class="fieldset-auto-width2">

<div style="width:400px; display: block; margin-left: auto; margin-right: auto; border: 15px black;">
    <p>
        
<?php
    echo "<h4>" . $row['Username'] . "&nbsp;" . "(" . $row['CommentDate'] . ")" . "&nbsp;" . "Betyg: " . $row['Rating'] . " av 5" ."</h4>";
    echo "<p>" . $row['Comment'] . "</p>";

if(isset($row['Answers'])) { ?>
        <fieldset class="fieldset-auto-width2">
<div style="width:350px; display: block; margin-left: auto; margin-right: auto; border: 10px black;">
<?php
    echo "<h4 style=\"color:black;\">" . $row['Author'] . "&nbsp;" . "(" . $row['AnswerDate'] . ")" . "</h4>";
    echo "<p style=\"color:black;\">" . $row['Answers'] . "</p>"; 
}
?> 
</div></fieldset>
<?php
if(($_SESSION['username'] === $row['Username'] && (!isset($row['Answers']))) || $_SESSION['username'] === "Admin") {?>
<form name="form" method="POST">
    <p  style="text-align:center;">
	<button type="submit" name="Delete" value="<?php echo $row['CommentID']?>">Ta bort</button></p>
	<?php if(!isset($row['Answers']) && $_SESSION['username'] === "Admin") {?>
		<p style="text-align:center;"><textarea name="Ans" cols="40" rows="5"></textarea></p>
		<p style="text-align:center;"><button type="submit" name="Answer" value="<?php echo $row['CommentID']?>">Svara</button></p>
	<?php } ?>
</form>
<?php
}
    
?>
   </p>
</div>
   </fieldset></center>
<br>
<?php

}

?>

</fieldset>
<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>