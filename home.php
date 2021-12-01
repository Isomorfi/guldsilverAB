<?php
include("db_connection.php");




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
unset($_SESSION['balance']);


$sql = "SELECT Orders.OrderID, OrderItems.ProductID, OrderItems.Quantity, Orders.OrderDate, Orders.Status
FROM Orders
INNER JOIN OrderItems ON Orders.OrderID=OrderItems.OrderID WHERE OrderDate < NOW() - INTERVAL 1 day AND Status='Basket' ORDER BY ProductID ASC";
$res = mysqli_query($conn, $sql);
$gold = 0;


$current = 0;
$previous = 0;
$counter = 0;

// Radera alla kundvagnar som är äldre än ett dygn och har status basket. Återställ lagersaldo.

if (mysqli_num_rows($res) > 0) {
while($data = mysqli_fetch_assoc($res)) {
	$ord = $data['OrderID'];
	$current = $data['ProductID'];
	if($counter > 0) {
		if($data['ProductID'] == $previous) {
			$previous = $current;
			$gold = $gold + (int)$data['Quantity'];
		
		}
		else {
			$sql2 = "SELECT Stock FROM Products WHERE ProductID='$previous'";
			$res2 = mysqli_query($conn, $sql2);
			$data2 = mysqli_fetch_assoc($res2);
			$stock = (int)$data2['Stock'] + $gold;
			$sql1 = "UPDATE Products SET Stock='$stock' WHERE ProductID='$previous'";
			$res1 = mysqli_query($conn, $sql1);
			$gold = (int)$data['Quantity'];
			$counter = 0;
			$previous = $current;
		}


	}
	else {
		$previous = $current;
		$gold = $gold + (int)$data['Quantity'];

	}
	$counter = $counter + 1;
	

}

$sql2 = "SELECT Stock FROM Products WHERE ProductID='$previous'";
$res2 = mysqli_query($conn, $sql2);
$data2 = mysqli_fetch_assoc($res2);
$stock = (int)$data2['Stock'] + $gold;
$sql1 = "UPDATE Products SET Stock='$stock' WHERE ProductID='$previous'";

$conn->query($sql1);



$sql4 = "DELETE OrderItems FROM OrderItems INNER JOIN Orders ON Orders.OrderID=OrderItems.OrderID WHERE OrderDate < NOW() - INTERVAL 1 day AND Status='Basket'";
$conn->query($sql4);

$sql3 = "DELETE Orders FROM Orders WHERE OrderDate < NOW() - INTERVAL 1 day AND Status='Basket'";
$conn->query($sql3);

}
?>

<Head>      
    <link rel="stylesheet" href="style.css">
    <Title>     
    Sverige-mineral AB  
    </Title>  
   
    </Head>  
    
    <BODY>
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
        <header>

                <center><label>&#10004; Snabb leverans  &#10004; Låga priser  &#10004; Miljöcertifierade produkter</label></center>
                <div class="topnav">

                   <a href="#">
                       <h1>Sverige-mineralen AB</h1>
                   </a>

                   <div id="topnav-right">
                      <a href="login.php">
                         <h2>Logga in</h2>
                      </a>
                      <a href="signup.php">
                         <h2>Skapa konto</h2>
                      </a>
                       
                   </div>
                </div>

      </header>
        


    <center><h1>Välkommen till Sveriges minsta e-handelsbutik för mineraler.</h1></center>
        <br>
        <br>
        



<div style="width:650px; margin:0 auto;">

    <div style="width:300px; float:left;">
        <p><label for="fname">I två orter i Sverige har det visat sig finnas perfekta förhållanden för mineralbildning. Dessa orter är Överkalix i norrbottens län, samt Kolsva i västmanlands län.
                Mineralerna i fråga är guld respektive silver. Men även i våra grannländer Norge och Finland finns det gott om mineraler. Detta är något som vi på Sverige-mineralen har uppmärksammat och dragit nytta av genom gedigna kunskaper och erfarenheter inom geologi, mineralteknik och gruvdrift.
                Kärnan i vår verksamhet är just brytning och försäljning av våra svenska mineraler, men tack vare våran unika energisparande förädlingsprocess så importerar vi även mineraler från våra nordiska grannländer för att kunna erbjuda fler produkter, som dessutom, 
                med nordiska mått, är närproducerade och miljöcertifierade. Dessutom till ett pris som ligger minst 50% under marknadsvärde. Denna kombination gör våra produkter helt unika.
                
                
                
                
               
    </div>

    <div style="width:300px; float:right;">
        <br><br>
        <p><img src="https://packbud.com/sites/packbud/files/field/gmapimagei154428739gmapimage106949.png" alt="Logo" width="350" height="300"></p>

    </div>


</div>
        <center>
<div style="clear: both;"> <br><br> För att ta del av våra erbjudanden krävs det att du registrerar ett konto hos oss.</label></p></div>
        </center>






<br>
<br>
<p style="text-align:center;"><label for="fname"><b>Några av våra produkter:</b></label></p>
        





    <center>
        <div class="a"><img src="http://www.thejewellerytube.com/wp-content/uploads/2020/08/fine-gold-bricks-the-jewellery-tube.jpg" alt="Logo" width="200" height="200"><br><br>
        <label>Guld</label></div>
<div class="a"><img src="https://www.valutahandel.se/wp-content/uploads/silver-tackor.jpg" alt="Logo" width="200" height="200"><br><br>
        <label>Silver</label></div>
<div class="a"><img src="https://agmetalminer.com/mmwp/wp-content/uploads/2021/01/ShawnHempel_AdobeStock_copperbars_012621.jpg" alt="Logo" width="200" height="200"><br><br>
        <label>Koppar</label></div>
<div class="a"><img src="https://media.istockphoto.com/photos/diamond-texture-closeup-and-kaleidoscope-top-view-of-round-gemstone-picture-id990183542?k=20&m=990183542&s=612x612&w=0&h=Um2NiUZOuj6UyABQ-vshECNoshXYqQZs_y9GWZs6dQc=" alt="Logo" width="200" height="200"><br><br>
    <label>Diamant</label></div><br>
        
    </center>
<center>
    <br><br><br>
&copy; <?php echo date ('Y') . " Sverige-mineralen AB. All rights reserved."; ?></center>
    </BODY>