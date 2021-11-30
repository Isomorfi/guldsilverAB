<?php
session_start();

include("db_connection.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = $_POST['username'];
	$password = $_POST['password'];


	$sql = "SELECT Password FROM db19880310.Customers WHERE Username='$username'";
	$res = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($res);
        
        if(!isset($data)) {
            echo '<script>alert("Felaktigt användarnamn.")</script>';
        }
	else {
            $hash_pwd = sha1($password);

            if($hash_pwd === $data['Password']) {
                    $_SESSION['signedin'] = true;
                    $_SESSION['username'] = $username;
                    header("Location: store.php");
                    die;
            } else {
                    echo '<script>alert("Felaktigt lösenord.")</script>';
            }
        }
	
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>

                <center><label>&#10004; Snabb leverans  &#10004; Låga priser  &#10004; Miljöcertifierade produkter</label></center>
                <div class="topnav">

                   <a href="#">
                       <h1>Sverige-mineralen AB</h1>
                   </a>

                   <div id="topnav-right">
                      <a href="home.php">
                         <h2>Hem</h2>
                      </a>
                      <a href="signup.php">
                         <h2>Skapa konto</h2>
                      </a>
                       
                   </div>
                </div>

      </header>
    <br><br><br><br><br><br>
<fieldset>
<form name="form" method="POST">
<p style="text-align:center;"><label for="username">Användarnamn:</label></p>
<p style="text-align:center;"><input type="text" id="username" name="username"></p><br>
<p style="text-align:center;"><label for="password">Lösenord:</label></p>
<p style="text-align:center;"><input type="password" id="password" name="password"></p><br>


       <p style="text-align:center;"><button type="submit" value="Submit">Logga in</button></p>
     </form>
</fieldset>
<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>