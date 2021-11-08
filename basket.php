<?php

include("db_connection.php");

$sql = "SELECT  FROM db19880310.Kunder WHERE användarnamn='$username'";
	$res = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($res);
	
	
	if($password === $data['losenord']) {
		header("Location: store.php");
		die;
	} else {
		echo "Wrong password or username";
	}







?>


<!DOCTYPE html>
<html>
<head>
<style>
body {background-color: powderblue;}
h1   {color: blue;}
p    {color: blue;}
</style>
</head>
<body>

<h1>Guld och silver AB - Min Kundvagn</h1>

<a href="home.php"><button type="submit" value="Submit">Logga ut</button></a>

<a href="mypages.php"><button type="submit" value="Submit">Mina sidor</button></a>

<a href="store.php"><button type="submit" value="Submit">Produkter</button></a>



<p style="text-align:center;"><img src="https://cdn-3d.niceshops.com/upload/image/product/large/default/fiberlogy-fibersilk-metallic-gold-326274-sv.jpg" alt="Logo" width="150" height="100"></p>
<p style="text-align:center;"><label for="fname">99,9% rent guld? Pris 488kr/g.</label></p>

<p style="text-align:center;"><form action="/action_page.php"></p>
<p style="text-align:center;"><label for="fname">Antal gram:</label></p>
<p style="text-align:center;"><input type="text" id="fname" name="fname">

    <script>
        function getvalue() {
            return document.getElementById('fname').value + " gram har lagts till i varukorgen!";
        }
    </script>

    <button type="button" onclick="alert(getvalue())">Köp</button><br><br>
</form></p>


<p style="text-align:center;"><img src="https://th.bing.com/th/id/R.4647e7752887fe3122b9e7036a0e68ce?rik=nDnCb7zPvrhJXw&pid=ImgRaw&r=0" alt="Logo" width="150" height="100"></p>
<p style="text-align:center;"><form action="/action_page.php"></p>
<p style="text-align:center;"><label for="fname">99,9% rent silver. Pris 6,40kr/g.</label></p>
<p style="text-align:center;"><label for="fname">Antal gram:</label></p>
<p style="text-align:center;"><input type="text" id="fname" name="fname"><button type="button" onclick="alert('Vara tillagd i varukorg!')">Köp</button><br><br>
</form></p>




</body>
</html>