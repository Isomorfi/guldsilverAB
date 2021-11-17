<?php
session_start();
include("db_connection.php");
unset($_SESSION['signedin']);
unset($_SESSION['username']);
unset($_SESSION['firstname']);
unset($_SESSION['lastname']);
unset($_SESSION['address']);
unset($_SESSION['zip']);
unset($_SESSION['city']);
unset($_SESSION['country']);
unset($_SESSION['email']);
unset($_SESSION['phone']);

?>

<!DOCTYPE html>
<html>
<head>
<style>
body {background-color: powderblue;}
h1   {color: blue;}
p    {color: blue;}
h4   {color: blue;}
</style>
</head>
<body>

<?php



$sql = "SELECT COUNT(Username) as users FROM Customers";
$res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($res);
$num = $data['users'];

$sql = "SELECT Quantity FROM OrderItems";
$res = mysqli_query($conn, $sql);
$Quan = 0;
while($data = mysqli_fetch_assoc($res)) {


$Quan = $Quan + $data['Quantity'];

}
?>

<h1>Guld och silver AB</h1>





<a href="signup.php"><button type="submit" value="Submit">Skapa konto</button></a>

<a href="login.php"><button type="submit" value="Submit">Logga in</button></a>

<p style="text-align:center;"><label for="fname">Välkommen till Sveriges minsta e-handelsbutik för guld och silver.<br> Våra produkter består av 99,9% rent guld och silver och utvinns i våra <br> egna gruvor på Tallvik i Överkalix i norr respektive Kolsva i söder.<br> Att produkterna utvinns i Sverige och har en så pass hög halt av guld och silver<br> i kombination med vårat låga pris, som är 50% under marknadsvärde,<br> gör våra produkter helt unika. <br><br> För att ta del av våra erbjudanden krävs det att du registrerar ett konto hos oss.</label></p>



<p style="text-align:center;"><img src="https://packbud.com/sites/packbud/files/field/gmapimagei154428739gmapimage106949.png" alt="Logo" width="350" height="300"></p>


<p style="text-align:center;"><label for="fname"><b>Våra produkter:</b></label></p>


<p style="text-align:center;">
<img <?php
    $sql = "SELECT Price, PicSrc FROM db19880310.Products WHERE ProductID='1'";
    $res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($res);
    ?>
src="<?php echo $data['PicSrc'] ?>" 
alt="Logo" width="250" height="200"></p>

<p style="text-align:center;"><label for="fname">99,9% rent guld. Utvunnet och producerat i Överkalix.
    <br> Pris: <?php echo $data['Price'] ?>kr/g.</label></p>
<br>


<p style="text-align:center;">
<img <?php
    $sql = "SELECT Price, PicSrc FROM db19880310.Products WHERE ProductID='2'";
    $res = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($res); 
    ?>
src="<?php echo $data['PicSrc'] ?>"
alt="Logo" width="250" height="200"></p>

<p style="text-align:center;"><form action="/action_page.php"></p>
<p style="text-align:center;"><label for="fname">99,9% rent silver. Utvunnet och producerat i Kolsva.
    <br> Pris: <?php echo $data['Price'] ?>kr/g.</label></p>
</form></p><br><br>
<br><br>
<p style="text-align:center;"><?php echo "Sedan 2021-11-09 : " . $num . " nya användare - " . $Quan . " sålda produkter."?></p>
<br><br><br>
<center>
<p>

&copy; <?php echo date ('Y') . " Guld och silver AB. All rights reserved."; ?></p></center>
</body>
</html>