<?php

session_start();
include("db_connection.php");


if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true && $_SESSION['username'] === 'Admin') {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}


$active = 'Active';
$inactive = 'Inactive';
$offset = 0;
$username = $_SESSION['username'];
$U = 'Username';
if($_SERVER['REQUEST_METHOD'] == "POST") {

    
	if (isset($_POST['prev'])) {
            $_SESSION['value'] += -1;
            $offset = 10 * $_SESSION['value'];
            
        }
        if (isset($_POST['next'])) {
            $_SESSION['value'] += 1;
            $offset = 10 * $_SESSION['value'];
        }
        
        
        $sql = "SELECT * FROM db19880310.Customers ORDER BY ? desc LIMIT ".$offset.", 10";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $U);
        
        
	if (isset($_POST['searchbtn'])) {
		$searchinput = $_POST['searchinput']  . '%';
                $searchtype = $_POST['searchtype'];

                
                $sql = "SELECT * FROM db19880310.Customers WHERE $searchtype LIKE ? ORDER BY ? desc LIMIT ".$offset.", 10";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("ss", $searchinput, $U);
                
             
             
        }
        
        if (isset($_POST['status'])) {
           $usernamestat = $_POST['status'];
            
         
            $sql = "SELECT UserStatus FROM Customers WHERE Username='$usernamestat'";
            $res = mysqli_query($conn, $sql);
            $status = mysqli_fetch_assoc($res)['UserStatus'] == "Active" ? "Inactive" : "Active";

            $sql = "UPDATE Customers SET UserStatus='$status' WHERE Username='$usernamestat'";
            $conn->query($sql);
            
  
            
            $sql = "SELECT * FROM db19880310.Customers ORDER BY ? desc LIMIT ".$offset.", 10";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $U);
    

        }
        
}
else {

    
        
        $sql = "SELECT * FROM db19880310.Customers ORDER BY ? desc LIMIT ".$offset.", 10";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $U);
    
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
    <h1>Användardata</h1></center>
<br>
<form name="form" method="POST">
<p style="text-align:center;"><label>Sök order:</label><input type="text" id="searchinput" name="searchinput">
<select name="searchtype" id="searchtype">
    <option value="Username">Användarnamn</option>
    <option value="Firstname">Förnamn</option>
    <option value="Lastname">Efternamn</option>
    <option value="SSN">Personnummer</option>
    <option value="Address">Adress</option>
    <option value="ZIP">Postnummer</option>
    <option value="City">Stad</option>
    <option value="Country">Land</option>
    <option value="Email">E-post</option>
    <option value="Phone">Telefonnummer</option>
    <option value="UserStatus">Status</option>
    
</select><button type="submit" name="searchbtn" value="Submit">Sök</button>
  </p>
</form>
<center>
<?php
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$stmt->close();
$countrow = 0;


echo "<br>";

echo '<table>';
echo "<table border='1'>";
echo '<tr><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Användarnamn" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Förnamn" . "</p>";
    
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Efternamn" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Personnummer" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Adress" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Postnummer" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Stad" . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Land" . "</p>";
        echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "E-post" . "</p>";
        echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Telefonnummer" . "</p>";
        echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . "Status" . "</p>";
    echo '</td></tr>';
if($result) {
while ($row = $result->fetch_assoc()) {
    $countrow++;
    

    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Username'] . "</p>";
    
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Firstname'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Lastname'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['SSN'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Address'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['ZIP'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['City'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Country'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Email'] . "</p>";
    echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['Phone'] . "</p>";
    if($row['UserStatus'] == "Inactive") {
        echo '</td><td style="text-align: center; vertical-align: middle; color: red;">';
    echo "<p>" . $row['UserStatus'] . "</p>";
    }
    else {
       echo '</td><td style="text-align: center; vertical-align: middle;">';
    echo "<p>" . $row['UserStatus'] . "</p>"; 
    }
    
    ?>
    <form name="form" method="POST">
        <button type="submit" name="status" value="<?php echo $row['Username'];?>">Ändra status</button>
        </form>
    <?php
    echo '</td></tr>';

    
}
if (!isset($_SESSION['value'])) {
    $_SESSION['value'] = 0;
}
?>

    <form name="form" method="POST">
        
<?php
if($_SESSION['value'] > 0) { ?>
    <button type="submit" name="prev" value="<?php echo $low - 10;?>">Tidigare användare</button><?php
    
}
?>
    <?php
if($countrow == 10 ) { ?>
<button type="submit" name="next" value="<?php echo $high + 10;?>">Fler användare</button>
<?php
    
}
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