<?php

session_start();
include("db_connection.php");


if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}

$username = $_SESSION['username'];
if($_SERVER['REQUEST_METHOD'] == "POST") {
        
	if (isset($_POST['searchbtn'])) {
		$searchinput = $_POST['searchinput'];
                $searchtype = $_POST['searchtype'];
             if($_SESSION['username'] !== "Admin") {   
                
                $sql = "SELECT * FROM db19880310.Orders WHERE Status='Ordered' AND Username='$username' AND $searchtype LIKE '$searchinput%' ORDER BY orderdate desc";
             }
             else {
                 $sql = "SELECT * FROM db19880310.Orders WHERE Status='Ordered' AND $searchtype LIKE '$searchinput%' ORDER BY orderdate desc";
             }
        }
}
else {
    
    if($_SESSION['username'] !== "Admin") {
        
        $sql = "SELECT * FROM db19880310.Orders WHERE Username='$username' AND Status='Ordered' ORDER BY orderdate desc";
    } else {
        $sql = "SELECT * FROM db19880310.Orders WHERE Status='Ordered' ORDER BY orderdate desc";
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
                <h1>Sverige-mineralen AB - Beställningar</h1>
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


<form name="form" method="POST">
<p style="text-align:center;"><label>Sök order:</label><input type="text" id="searchinput" name="searchinput">
<select name="searchtype" id="searchtype">
    <option value="orderid">Ordernummer</option>
    <option value="orderdate">Datum</option>
</select><button type="submit" name="searchbtn" value="Submit">Sök</button>
  </p>
</form>
<center>
<?php
$username = $_SESSION['username'];

if($username === "Admin") {

$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
$link = 'checkout.php';
echo "<br>";

echo '<table>';
echo "<table border='1'>";
echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Ordernummer" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Orderdatum" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Produktkostnad" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Status" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Leverans" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Leveranskostnad" . "</p>";
    echo '</td></tr>';

while ($row = mysqli_fetch_assoc($result)) {

    $order = $row['OrderID'];
    echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p><a href=\"$link?OrderID=$order\">" . $row['OrderID'] . "</a></p>";

    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['orderDate'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['TotalCost'] . " kr" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Status'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['DELIVERY'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['ShippingCost'] . " kr" . "</p>";
    echo '</td></tr>';

    }

}



if($_SESSION['username'] !== "Admin") {
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
$link = 'checkout.php';
echo "<br>";

echo '<table>';
echo "<table border='1'>";
echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Ordernummer" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Orderdatum" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Totalsumma" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Status" . "</p>";
        echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Leverans" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Leveranskostnad" . "</p>";
    echo '</td></tr>';
if($result) {
while ($row = mysqli_fetch_assoc($result)) {

    $order = $row['OrderID'];
    echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p><a href=\"$link?OrderID=$order\">" . $row['OrderID'] . "</a></p>";

    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['orderDate'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['TotalCost'] . " kr" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Status'] . "</p>";
        echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['DELIVERY'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['ShippingCost'] . " kr" . "</p>";
    echo '</td></tr>';

    }
}
}
?>

</center>
<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>