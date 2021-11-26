<?php
session_start();

include("db_connection.php");

if(!isset($_SESSION['signedin']) && $_SESSION['signedin'] !== true) {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");
	die;
	
}


if($_SERVER['REQUEST_METHOD'] == "POST") {

    if(isset($_POST['change'])) {
        $username = $_SESSION['username'];

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        $zip = $_POST['zip'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $hash_pwd = sha1($password);

        $sql = "SELECT * FROM Customers WHERE Username='$username'";
        $res = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($res);

        $storedPwd = $data['Password'];
        $ssn = $data['SSN'];
            
        if(strlen($firstname) > 0 && strlen($lastname) > 0 && strlen($password) > 0 && 
            strlen($address) > 0 && strlen($zip) > 0 && strlen($city) > 0 &&
            strlen($country) > 0 && strlen($email) > 0 && strlen($phone) > 0) {

            if($hash_pwd === $storedPwd || $password == $storedPwd) {
                if(is_numeric($ssn)) {
                    if(is_numeric($zip)) {
                        if(is_numeric($phone)) {
                            
                            $sql = "UPDATE Customers SET Firstname='$firstname',
                                                        Lastname='$lastname',
                                                        Address='$address',
                                                        ZIP='$zip',
                                                        City='$city',
                                                        Country='$country',
                                                        Email='$email',
                                                        Phone='$phone' 
                                                        WHERE Username='$username'";

                            if ($conn->query($sql) === TRUE) {
                                echo '<script>alert("Kontouppgifter uppdaterade!")</script>';
                                header("Location: store.php");
                                die;
                            }
                                    
                        } else {
                            echo '<script>alert("Felaktigt angivet telefonnummer.")</script>';
                        }                                
                    } else {
                        echo '<script>alert("Felaktigt angivet postnummer.")</script>';
                    }            
                } else {
                    echo '<script>alert("Felaktigt angivet personnummer.")</script>';
                } 
            } else {
                echo '<script>alert("Felaktigt lösenord.")</script>';
            }
        } else {
            echo '<script>alert("Fält kan ej lämnas tomma!")</script>';
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

<h1>Guld och silver AB - Ändra konto</h1>

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
    
	echo "<p>" . "Inloggad: " . $_SESSION['username'] . "." . "<br>" . "Kontobalans: " . $_SESSION['balance'] . " kr." . "</p>";
?>
    </fieldset>
<?php
}

?>
<br>
<br>

<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a>

<?php
$username = $_SESSION['username'];
$sql1 = "SELECT * FROM Customers WHERE Username='$username'";
$res1 = mysqli_query($conn, $sql1);
$data1 = mysqli_fetch_assoc($res1);
$pw = $data1['Password'];

?>

<fieldset>
<form name="form" method="POST">
<center>
<div style="width:800px;">
  <div style="width:300px; float:left;">
    <p style="text-align:center;"><label for="firstname">Förnamn:</label></p>
    <p style="text-align:center;"><input type="text" id="firstname" value="<?php echo $data1['Firstname']?>" name="firstname"></p>
    <p style="text-align:center;"><label for="lastname">Efternamn:</label></p>
    <p style="text-align:center;"><input type="text" id="lastname" value="<?php echo $data1['Lastname']?>" name="lastname"></p>

    <p style="text-align:center;"><label for="username">Adress:</label></p>
    <p style="text-align:center;"><input type="text" id="address" value="<?php echo $data1['Address']?>" name="address"></p>
    <p style="text-align:center;"><label for="username">Postnummer:</label></p>
    <p style="text-align:center;"><input type="text" id="zip" value="<?php echo $data1['ZIP']?>" name="zip"></p>

</div>
  <div style="width:400px; float:right;">

    <p style="text-align:center;"><label for="username">Ort:</label></p>
    <p style="text-align:center;"><input type="text" id="city" value="<?php echo $data1['City']?>" name="city"></p>
    <p style="text-align:center;"><label for="username">Land:</label></p>
    <p style="text-align:center;"><input type="text" id="country" value="<?php echo $data1['Country']?>" name="country"></p>
    <p style="text-align:center;"><label for="username">E-post:</label></p>
    <p style="text-align:center;"><input type="text" id="email" value="<?php echo $data1['Email']?>" name="email"></p>
    <p style="text-align:center;"><label for="username">Telefonnummer:</label></p>
    <p style="text-align:center;"><input type="text" id="phone" value="<?php echo $data1['Phone']?>" name="phone"></p>

    <p style="text-align:center;"><label for="password">Lösenord:</label></p>
    <p style="text-align:center;"><input type="password" id="password" name="password"></p>
</div>
</div>
<div style="clear: both;"></div></center>
    <p style="text-align:center;"><button type="submit" value="Submit" name="change">Ändra</button></p>
</form>
</fieldset>
<center>
<p>
&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>