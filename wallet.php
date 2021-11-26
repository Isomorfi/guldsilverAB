<?php
session_start();



if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}
include("db_connection.php");

$username = $_SESSION['username'];




$sql = "SELECT * FROM Wallet WHERE Username='$username'";
$res = mysqli_query($conn, $sql);



// Kolla om användare redan har en plånka, om inte skapa en ny
if(!$data = mysqli_fetch_assoc($res)) {

	$sql = "INSERT INTO Wallet (Username, Balance) VALUES ('$username', '0')";	// Skapa ny plånka
	$conn->query($sql);

	$sql = "SELECT * FROM Wallet WHERE Username='$username'";	// Hämta plånka
	$res = mysqli_query($conn, $sql);

	if(!$data = mysqli_fetch_assoc($res)) {	// Om något är fel återgå till mypages
		header("Location: mypages.php");
		die;
	}
}


$id = $data['WalletID'];
$uname = $data['Username'];
$balance = $data['Balance'];


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <style>
body {background-color: powderblue;}
h1   {color: #020764;}
h4   {color: #020764;}
p    {color: #020764;}
</style>
</head>
<body>

<h1>Guld och silver AB - Plånbok</h1>

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
    
	echo "<p>" . "Inloggad: " . $_SESSION['username'] . "." . "<br>" . "Kontobalans: " . $balance . " kr." . "</p>";
?>
    </fieldset>
<?php
}

?>
<br>
<br>
<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="store.php"><button type="submit" value="Submit">Produkter</button></a>

<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a><br>


<fieldset>
	<p style="text-align:center;"><label for="sum"><?php echo "Summa: $balance kr" ?> </label></p>

	<form name="insert" method="POST">
		<p style="text-align:center;"><button type="submit" value="Submit" name=insert>Sätt in pengar</button></p>
                <p style="text-align:center;"><button type="submit" value="Submit" name=takeout>Ta ut pengar</button></p>
	</form>

</fieldset>


<?php
if($_SERVER['REQUEST_METHOD'] == "POST") {
	if(isset($_POST['insert'])) {
		?>
		 <fieldset>
			 <form name="form" method="POST">

				<p style="text-align:center;">
					<label for="grade">Typ av kort:</label><br><br>
						<select name="grade" id="grade">
						<option value="1">Visa</option>
						<option value="2">Mastercard</option>
						<option value="3">Maestro</option>
						</select>
					<br>
				</p> 

				<p style="text-align:center;"><label for="num">Kortnummer:</label></p>
                <p style="text-align:center;"><input type="text" id="num" name="num"></p>
                <p style="text-align:center;"><label for="date">Utg. datum:</label></p>
                <p style="text-align:center;"><input type="text" id="date" size="1" name="dateMonth"><label> / </label><input type="text" size="1" id="date" name="dateYear"></p>
                <p style="text-align:center;"><label for="cvc">CVC:</label></p>
                <p style="text-align:center;"><input type="text" size="2" id="cvc" name="cvc"></p>

                <p style="text-align:center;"><label for="sum">Summa: (max 100.000)</label></p>
                <p style="text-align:center;"><input type="text" id="sum" name="sum"></p>


                <a href="wallet.php"><p style="text-align:center;"><button type="submit" value="Submit" name='insertmoney'>Sätt in</button></p>
			</form> 

		</fieldset> 
		<?php	
	}
        
        if(isset($_POST['takeout'])) {
            ?>
		 <fieldset>
			 <form name="form" method="POST">

                <p style="text-align:center;"><label for="sum">Summa:</label></p>
                <p style="text-align:center;"><input type="text" id="sum" name="sum"></p>
                
                <p style="text-align:center;"><input type="checkbox" name="checkbox_name" value="checkox_value">Jag godkänner en postgiro-utbetalning till kontoregistrerad adress.</input></p>


                <a href="wallet.php"><p style="text-align:center;"><button type="submit" value="Submit" name='takeoutmoney'>Ta ut</button></p>
			</form> 

		</fieldset> 
		<?php	
        }
        
        if(isset($_POST['takeoutmoney'])) {
            $sum = $_POST['sum'];
            if(is_numeric($sum) && $sum > 0 && $sum <= $balance) {
                if(isset($_POST['checkbox_name'])) {
                    $balance = $balance - $sum;
                    $sql = "UPDATE Wallet SET Balance='$balance'";
                    if($conn->query($sql)) {
                        header("Location: wallet.php");
                        die;
                    }
                    else {
                       echo '<script>alert("Något gick galet. Kontakta IT-avdelningen.")</script>'; 
                    }

                }
                else {
                    echo '<script>alert("Du måste godkänna villkoren.")</script>'; 
                }
            }
            echo '<script>alert("Du kan inte ta ut detta belopp.")</script>';
        }
        

	if(isset($_POST['insertmoney'])) {
		$number = $_POST['num'];
		$dateMonth = $_POST['dateMonth'];
		$dateYear = $_POST['dateYear'];
		$cvc = $_POST['cvc'];
		$sum = $_POST['sum'];

		if(is_numeric($number) && strlen($number) == 16) {
			if(is_numeric($dateMonth) && strlen($dateMonth) == 2) {
				if(is_numeric($dateYear) && strlen($dateYear) == 4) {
					if(is_numeric($cvc) && strlen($cvc) == 3) {
						if(is_numeric($sum) && $sum > 0 && $sum <= 100000) {
							
							$balance = $balance + $sum;
							$sql = "UPDATE Wallet SET Balance='$balance'";
							if($conn->query($sql)) {
								header("Location: wallet.php");
								die;
							}
							echo '<script>alert("Något gick galet. Kontakta IT-avdelningen.")</script>'; 
						} 
						else {
							echo '<script>alert("Du måste sätta in ett positivt belopp.")</script>'; 
						}
					}
					else {
						echo '<script>alert("CVC måste vara tre siffror.")</script>'; 
					}
				}
				else {
					echo '<script>alert("Fel angett år, 20xx.")</script>'; 
				}	
			}
			else {
				echo '<script>alert("Fel angiven månad, xx.")</script>'; 
			}
		}
		else {
			echo '<script>alert("Fel angett kortnummer. Det måste vara 16 siffror.")</script>'; 
		}
	}
}
?>

<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>