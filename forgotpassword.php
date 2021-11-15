<?php
session_start();

if ( function_exists( 'mail' ) )
{
    echo 'mail() is available';
}
else
{
    echo 'mail() has been disabled';
}

include("db_connection.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = $_POST['username'];
	$email = $_POST['email'];

	$sql = "SELECT Password, Email, Username FROM db19880310.Customers WHERE Username='$username' AND Email='$email'";
	$res = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($res);
        
        if(isset($data['Email'])) {
            if(isset($data['Username'])) {
                $password = $data['Password'];
                $to = $email;
                $subject = "Glömt lösenord";
		$headers = "From: noreply@example.com\r\n";
		$headers.= "MIME-Version: 1.0\r\n";
		$headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$headers.= "X-Priority: 1\r\n";
		
                $txt = "Här kommer ditt bortglömda lösenord!" . "\r\n" . "$password" . "\r\n" . "När du har lyckats kryptera det så kan du logga in igen." . "\r\n" . "Hälsningar vi på Guld och silver AB"";
              

                $sendMail = mail($to,$subject,$txt,$headers);
                
                if($sendMail)
{
echo "Email Sent Successfully";
}
else

{
echo "Mail Failed";
}
            } else {
                echo "Användarnamnet existerar inte.";
            }
        } else {
            echo "E-posten existerar inte.";
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

<fieldset>
     
<form name="form" method="POST">
<p style="text-align:center;"><label for="username">Användarnamn:</label></p>
<p style="text-align:center;"><input type="text" id="username" name="username"></p><br>
<p style="text-align:center;"><label for="text">E-post:</label></p>
<p style="text-align:center;"><input type="text" id="email" name="email"></p><br>


       <p style="text-align:center;"><button type="submit" value="Submit">Skicka</button></p>
     </form>
</fieldset>


</body>
</html>