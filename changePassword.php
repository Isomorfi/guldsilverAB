<?php

session_start();
include("db_connection.php");


if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}

 


if($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = $_SESSION['username'];
	$oldpassword = $_POST['oldpassword'];
	$newpassword = $_POST['newpassword'];
	$newpassword2 = $_POST['newpassword2'];
	

	$sql = "SELECT Password FROM db19880310.Customers WHERE Username='$username'";
	$res = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($res);
	
	$hash_pwd = sha1($oldpassword);

    	if($hash_pwd === $data['Password'] || $oldpassword == $data['Password']) {
		if($newpassword === $newpassword2) {
			$hash_pwd = sha1($newpassword);
			$sql = "UPDATE db19880310.Customers SET Password='$hash_pwd' WHERE Username='$username'";
			$res = mysqli_query($conn, $sql);
		} else {
			echo "Lösenord överensstämmer inte.";
		}
		header("Location: changePassword.php");
		die;
	} else {
		echo "Felaktigt lösenord.";
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
                <h1>Sverige-mineralen AB - Ändra lösenord</h1>
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

<br><br>

<fieldset>
<form name="form" method="POST">
<center>
<p style="text-align:center;"><label for="username">Nuvarande lösenord:</label></p>
<p style="text-align:center;"><input type="password" id="oldpassword" name="oldpassword"></p>
<p style="text-align:center;"><label for="password">Nytt lösenord:</label></p>
<p style="text-align:center;"><input type="password" id="newpassword" name="newpassword"></p>
<p style="text-align:center;"><label for="firstname">Nytt lösenord:</label></p>
<p style="text-align:center;"><input type="password" id="newpassword2" name="newpassword2"></p>
</center>


       <p style="text-align:center;"><button type="submit" value="Submit">Byt lösenord</button></p>
     </form>
</fieldset>
<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>