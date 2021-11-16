<?php
session_start();

include("db_connection.php");

if(isset($_SESSION['signedin']) && $_SESSION['signedin'] == true) {	
	echo "Inloggad som " . $_SESSION['username'] . ".";
} else {
	echo "Du behöver logga in för åtkomst till affären.";
	header("Location: home.php");	
	die;
	
}



if($_SERVER['REQUEST_METHOD'] == "POST") {
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$password = $_POST['password'];

	$ssn = $_POST['persnr'];
	

	$_SESSION['username'] = $_POST['username'];

	
        $hash_pwd = sha1($password);
        
        
	if(strlen($firstname) > 0 && strlen($lastname) > 0 &&
	strlen($password) > 0 && strlen($_SESSION['address']) > 0 && strlen($_SESSION['zip']) > 0 && strlen($_SESSION['city']) > 0 &&
	strlen($_SESSION['country']) > 0 && strlen($_SESSION['email']) > 0 && strlen($_SESSION['phone']) > 0) {
		if($hash_pwd === $pw) {
                    if(is_numeric($ssn) && strlen($ssn) == 10) {
                        if(is_numeric($_SESSION['zip'])) {
                            if(is_numeric($_SESSION['phone'])) {
                                

                                $sql1 = "UPDATE db19880310.Customers SET Firstname='".$_POST['firstname']."' WHERE Username='".$_SESSION['username']."'";
                                $conn->query($sql1);
                                $sql2 = "UPDATE db19880310.Customers SET Firstname='".$_POST['lastname']."' WHERE Username='".$_SESSION['username']."'";
                                $conn->query($sql2);
                                $sql3 = "UPDATE db19880310.Customers SET Firstname='".$_POST['address']."' WHERE Username='".$_SESSION['username']."'";
                                $conn->query($sql3);
                                $sql4 = "UPDATE db19880310.Customers SET Firstname='".$_POST['zip']."' WHERE Username='".$_SESSION['username']."'";
                                $conn->query($sql4);
                                $sql5 = "UPDATE db19880310.Customers SET Firstname='".$_POST['city']."' WHERE Username='".$_SESSION['username']."'";
                                $conn->query($sql5);
                                $sql6 = "UPDATE db19880310.Customers SET Firstname='".$_POST['country']."' WHERE Username='".$_SESSION['username']."'";
                                $conn->query($sql6);
                                $sql7 = "UPDATE db19880310.Customers SET Firstname='".$_POST['email']."' WHERE Username='".$_SESSION['username']."'";
                                $conn->query($sql7);
                                $sql8 = "UPDATE db19880310.Customers SET Firstname='".$_POST['phone']."' WHERE Username='".$_SESSION['username']."'";
                                
                            	if ($conn->query($sql8) === TRUE) {
					header("Location: store.php");
					die;
                                }
                            	
                            } else {
                                echo "Felaktigt angivet telefonnummer.";
                            }
			
			
                            
                        } else {
                            echo "Felaktigt angivet postnummer.";
                        }
                        
                    } else {
                        echo "Felaktigt angivet personnummer.";
                    }

            		
		} else {
			echo "Felaktigt lösenord.";
		}
	} else {
		echo "Fält kan ej lämnas tomma!";
	}
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <style>
body {background-color: powderblue;}
h1   {color: blue;}
p    {color: blue;}
</style>
</head>
<body>

<h1>Guld och silver AB</h1>


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



       <p style="text-align:center;"><button type="submit" value="Submit">Ändra</button></p>
     </form>
</fieldset>


</body>
</html>