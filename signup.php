<?php
session_start();

include("db_connection.php");
$stat = "Active";
$_SESSION['username'] = '';
	$_SESSION['firstname'] = '';
	$_SESSION['lastname'] = '';
	$_SESSION['address'] = '';
	$_SESSION['zip'] = '';
	$_SESSION['city'] = '';
	$_SESSION['country'] = '';
	$_SESSION['email'] = '';
	$_SESSION['phone'] = '';

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = $_POST['username'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$ssn = $_POST['persnr'];
	

	$_SESSION['username'] = $_POST['username'];
	$_SESSION['firstname'] = $_POST['firstname'];
	$_SESSION['lastname'] = $_POST['lastname'];
	$_SESSION['address'] = $_POST['address'];
	$_SESSION['zip'] = $_POST['zip'];
	$_SESSION['city'] = $_POST['city'];
	$_SESSION['country'] = $_POST['country'];
	$_SESSION['email'] = $_POST['email'];
	$_SESSION['phone'] = $_POST['phone'];
	


        
	if(strlen($username) > 0 && strlen($firstname) > 0 && strlen($lastname) > 0 && strlen($password) > 0 && 
		strlen($password2) > 0 && strlen($_SESSION['address']) > 0 && strlen($_SESSION['zip']) > 0 && strlen($_SESSION['city']) > 0 
		&& strlen($_SESSION['country']) > 0 && strlen($_SESSION['email']) > 0 && strlen($_SESSION['phone']) > 0) {
		if($password === $password2) {
            if(is_numeric($ssn) && strlen($ssn) == 10) {
                if(is_numeric($_SESSION['zip'])) {
                    if(is_numeric($_SESSION['phone'])) {
                        if(isset($_POST['checkbox_name'])) {
							$hash_pwd = sha1($password);
                                                
							$sql = "INSERT INTO Customers (	Username, Firstname, Lastname, Password, SSN, 
															Address, ZIP, City, Country, Email, Phone, UserStatus)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

							$stmt = $conn->prepare($sql);
							$stmt->bind_param("ssssssssssss", $username, $firstname, $lastname, 
												$hash_pwd, $ssn, $_SESSION['address'], $_SESSION['zip'], 
												$_SESSION['city'], $_SESSION['country'], $_SESSION['email'], 
												$_SESSION['phone'], $stat);

							if($res = $stmt->execute()) {

								// Skapan en plånbok för ny användare
								$sql = "INSERT INTO Wallet (Username, Balance) VALUES (?, ?)";
								$stmt = $conn->prepare($sql);
								$balance = 0;
								$stmt->bind_param("sd", $username, $balance);
								$stmt->execute();
								

								$_SESSION['signedin'] = true;
    							$_SESSION['username'] = $username;
								$_SESSION['balance'] = $balance;

								

							}
							
                            else {
								echo "Användarnamnet är redan upptaget. Välj ett annat.";
                            }
							$stmt->close();
							header("Location: wallet.php");
							die;
                        } 
						else {
                            echo "Felaktigt angivet telefonnummer.";
                        }
					} 
					else {
						echo "Du måste godkänna villkoren.";
					}          
                } 
				else {
                    echo "Felaktigt angivet postnummer.";
                }       
            } 
			else {
                echo "Felaktigt angivet personnummer.";
            }	
		} 
		else {
			echo "Lösenord överenstämmer inte.";
		}
	} 
	else {
		echo "Fält kan ej lämnas tomma!";
	}
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
</style>
</head>
<body>
<header>

    <center><label>&#10004; Snabb leverans  &#10004; Låga priser  &#10004; Miljöcertifierade produkter</label></center>
    <div class="topnav">

        <a href="home.php">
            <h1>Sverige-mineralen AB</h1>
        </a>

        <div id="topnav-right">
            <a href="home.php">
                <h2>Hem</h2>
            </a>
            <a href="login.php">
                <h2>Logga in</h2>
            </a>
                       
            </div>
        </div>

</header>

<br>
<center><h1>Skapa konto</h1></center><br>

<fieldset>
<form name="form" method="POST">
<center>
<div style="width:800px;">
  <div style="width:300px; float:left;">
<p style="text-align:center;"><label for="username">Användarnamn:</label></p>
<p style="text-align:center;"><input type="text" id="username" value="<?php echo $_SESSION['username']?>" name="username"></p>
<p style="text-align:center;"><label for="password">Personnummer (ååmmddnnnn):</label></p>
<p style="text-align:center;"><input type="text" id="persnr" name="persnr"></p>
<p style="text-align:center;"><label for="firstname">Förnamn:</label></p>
<p style="text-align:center;"><input type="text" id="firstname" value="<?php echo $_SESSION['firstname']?>" name="firstname"></p>
<p style="text-align:center;"><label for="lastname">Efternamn:</label></p>
<p style="text-align:center;"><input type="text" id="lastname" value="<?php echo $_SESSION['lastname']?>" name="lastname"></p>

<p style="text-align:center;"><label for="username">Adress:</label></p>
<p style="text-align:center;"><input type="text" id="address" value="<?php echo $_SESSION['address']?>" name="address"></p>
<p style="text-align:center;"><label for="username">Postnummer:</label></p>
<p style="text-align:center;"><input type="text" id="zip" value="<?php echo $_SESSION['zip']?>" name="zip"></p>

</div>
  <div style="width:400px; float:right;">

<p style="text-align:center;"><label for="username">Ort:</label></p>
<p style="text-align:center;"><input type="text" id="city" value="<?php echo $_SESSION['city']?>" name="city"></p>
<p style="text-align:center;"><label for="username">Land:</label></p>
<p style="text-align:center;"><input type="text" id="country" value="<?php echo $_SESSION['country']?>" name="country"></p>
<p style="text-align:center;"><label for="username">E-post:</label></p>
<p style="text-align:center;"><input type="text" id="email" value="<?php echo $_SESSION['email']?>" name="email"></p>
<p style="text-align:center;"><label for="username">Telefonnummer:</label></p>
<p style="text-align:center;"><input type="text" id="phone" value="<?php echo $_SESSION['phone']?>" name="phone"></p>

<p style="text-align:center;"><label for="password">Lösenord:</label></p>
<p style="text-align:center;"><input type="password" id="password" name="password"></p>
<p style="text-align:center;"><label for="password2">Upprepa lösenord:</label></p>
<p style="text-align:center;"><input type="password" id="password2" name="password2"></p>
</div>
</div>
<div style="clear: both;"></div></center>

<p style="text-align:center;"><input type="checkbox" name="checkbox_name" value="checkox_value">Jag lovar och svär att jag verkligen försökte ge mina riktiga personuppgifter.</input></p>

       <p style="text-align:center;"><button type="submit" value="Submit">Skapa konto</button></p>
     </form>
</fieldset>
<center>
<p>
&copy; <?php echo date ('Y') . " Sverige-mineralen AB. All rights reserved."; ?></p></center>
</body>
</html>