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
	


        
	if(strlen($username) > 0 && strlen($firstname) > 0 && strlen($lastname) > 0 &&
	strlen($password) > 0 && strlen($password2) > 0 && strlen($_SESSION['address']) > 0 && strlen($_SESSION['zip']) > 0 && strlen($_SESSION['city']) > 0 &&
	strlen($_SESSION['country']) > 0 && strlen($_SESSION['email']) > 0 && strlen($_SESSION['phone']) > 0) {
		if($password === $password2) {
                    if(is_numeric($ssn) && strlen($ssn) == 10) {
                        if(is_numeric($_SESSION['zip'])) {
                            if(is_numeric($_SESSION['phone'])) {
                                if(isset($_POST['checkbox_name'])) {
				$hash_pwd = sha1($password);

                            $sql = "INSERT INTO db19880310.Customers (Username, Firstname, Lastname, Password, SSN, Address, ZIP, City, Country, Email, Phone)
                VALUES ('$username', '$firstname', '$lastname', '$hash_pwd', '$ssn', '".$_SESSION['address']."', '".$_SESSION['zip']."', '".$_SESSION['city']."', '".$_SESSION['country']."', '".$_SESSION['email']."', '".$_SESSION['phone']."')";

                            	if ($conn->query($sql) === TRUE) {
					header("Location: store.php");
					die;
                            	} else {
					echo "Användarnamnet är redan upptaget. Välj ett annat.";
                            	}
                            } else {
                                echo "Felaktigt angivet telefonnummer.";
                            }
			
			} else {
				echo "Du måste godkänna villkoren.";
			}
                            
                        } else {
                            echo "Felaktigt angivet postnummer.";
                        }
                        
                    } else {
                        echo "Felaktigt angivet personnummer.";
                    }

            		
		} else {
			echo "Lösenord överenstämmer inte.";
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
<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="store.php"><button type="submit" value="Submit">Produkter</button></a>

<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a><br>
<fieldset>
<form name="form" method="POST">
<center>
<p style="text-align:center;"><label for="username">Nuvarande lösenord:</label></p>
<p style="text-align:center;"><input type="text" id="username" value="<?php echo $_SESSION['username']?>" name="username"></p>
<p style="text-align:center;"><label for="password">Nytt lösenord:</label></p>
<p style="text-align:center;"><input type="text" id="persnr" name="persnr"></p>
<p style="text-align:center;"><label for="firstname">Nytt lösenord:</label></p>
<p style="text-align:center;"><input type="text" id="firstname" value="<?php echo $_SESSION['firstname']?>" name="firstname"></p>
</center>


       <p style="text-align:center;"><button type="submit" value="Submit">Byt lösenord</button></p>
     </form>
</fieldset>


</body>
</html>