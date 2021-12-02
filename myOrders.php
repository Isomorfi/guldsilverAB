<?php

session_start();
include("db_connection.php");




if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}

$offset = 0;
$username = $_SESSION['username'];
if($_SERVER['REQUEST_METHOD'] == "POST") {

    
	if (isset($_POST['prev'])) {
            $_SESSION['value'] += -1;
            $offset = 10 * $_SESSION['value'];
            
        }
        if (isset($_POST['next'])) {
            $_SESSION['value'] += 1;
            $offset = 10 * $_SESSION['value'];
        }
        
        
        if($_SESSION['username'] !== "Admin") {
        
        $sql = "SELECT * FROM db19880310.Orders WHERE Username='$username' AND Status='Ordered' ORDER BY orderdate desc LIMIT ".$offset.", 10";
    } else {
        $sql = "SELECT * FROM db19880310.Orders WHERE Status='Ordered' ORDER BY orderdate desc LIMIT ".$offset.", 10";
    }




        
	if (isset($_POST['searchbtn'])) {
		$searchinput = $_POST['searchinput'];
                $searchtype = $_POST['searchtype'];
               

                
             if($_SESSION['username'] !== "Admin") {   
                
                $sql = "SELECT * FROM db19880310.Orders WHERE Status='Ordered' AND Username='$username' AND $searchtype LIKE '$searchinput%' ORDER BY orderdate desc LIMIT ".$offset.", 10";
             }
             else {
                 $sql = "SELECT * FROM db19880310.Orders WHERE Status='Ordered' AND $searchtype LIKE '$searchinput%' ORDER BY orderdate desc LIMIT ".$offset.", 10";
             }
             
        }
}
else {

    if($_SESSION['username'] !== "Admin") {
        
        $sql = "SELECT * FROM db19880310.Orders WHERE Username='$username' AND Status='Ordered' ORDER BY orderdate desc LIMIT ".$offset.", 10";
    } else {
        $sql = "SELECT * FROM db19880310.Orders WHERE Status='Ordered' ORDER BY orderdate desc LIMIT ".$offset.", 10";
    }
    $_SESSION['value'] = 0;

        
   
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


<center><br>
    <h1>Beställningar</h1></center>
<br>
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

$countrow = 0;
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
    echo "<p>" . "Status" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Leverans" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Leveranskostnad" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Produktkostnad" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Total kostnad" . "</p>";
    echo '</td></tr>';
if (!$result){
   die();
}

while ($row = mysqli_fetch_assoc($result)) {
    $countrow++;
    $order = $row['OrderID'];
    echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p><a href=\"$link?OrderID=$order\">" . $row['OrderID'] . "</a></p>";

    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['orderDate'] . "</p>";
    
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Status'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['DELIVERY'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . number_format($row['ShippingCost'], 2, '.', ',') . " kr" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . number_format($row['TotalCost'], 2, '.', ',') . " kr" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . number_format($row['CostInclShip'], 2, '.', ',') . " kr" . "</p>";
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
    echo "<p>" . "Status" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Leverans" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Leveranskostnad" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Produktkostnad" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Total kostnad" . "</p>";
    echo '</td></tr>';
if($result) {
while ($row = mysqli_fetch_assoc($result)) {
    $countrow++;
    $order = $row['OrderID'];
    echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p><a href=\"$link?OrderID=$order\">" . $row['OrderID'] . "</a></p>";

    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['orderDate'] . "</p>";
    
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Status'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['DELIVERY'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . number_format($row['ShippingCost'], 2, '.', ',') . " kr" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . number_format($row['TotalCost'], 2, '.', ',') . " kr" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . number_format($row['CostInclShip'], 2, '.', ',') . " kr" . "</p>";
    echo '</td></tr>';

    }
}
}
if (!isset($_SESSION['value'])) {
    $_SESSION['value'] = 0;
}
?>

    <form name="form" method="POST">
        
<?php
if($_SESSION['value'] > 0) { ?>
    <button type="submit" name="prev" value="<?php echo $low - 10;?>">Föregående</button><?php
    
}
?>
    <?php
if($countrow == 10 ) { ?>
<button type="submit" name="next" value="<?php echo $high + 10;?>">Nästa</button>
<?php
    
}
?>
  </p>
</form>

    
</center>
<center>
<p>
&copy; <?php echo date ('Y') . " Sverige-mineralen AB. All rights reserved."; ?></p></center>
</body>
</html>