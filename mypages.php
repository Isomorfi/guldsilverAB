<?php

session_start();
include("db_connection.php");


if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
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
                <h1>Sverige-mineralen AB - Mina sidor</h1>
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
                <a href="home.php">
                    <h2>Logga ut</h2>
                </a>
                
            </div>
        </div>
    </header>

<br><br>


<center>
<a href="myOrders.php"><button type="submit" value="Submit">Beställningar</button></a>
<br>
<br>
<br>
<a href="changeInfo.php"><button type="submit" value="Submit">Ändra konto</button></a>
<br>
<br>
<br>
<a href="changePassword.php"><button type="submit" value="Submit">Ändra lösenord</button></a>
<br>
<br>
<br>
<a href="wallet.php"><button type="submit" value="Submit">Hantera plånbok</button></a>
</center>
<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>