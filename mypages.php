<?php
session_start();

include("db_connection.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = $_POST['username'];
	$password = $_POST['password'];


	$sql = "SELECT losenord FROM db19880310.Kunder WHERE användarnamn='$username'";
	$res = mysqli_query($conn, $sql);
	$data = mysqli_fetch_assoc($res);
	
	
	if($password === $data['losenord']) {
		header("Location: store.php");
		die;
	} else {
		echo "Wrong password or username";
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
$sql = "SELECT * FROM db19880310.Comments WHERE ProductID='1'";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
//echo "Kundrecensioner: ";
echo "<br>";
//echo "<table border='1'>";
while ($row = mysqli_fetch_assoc($result)) {
?>
<center><style type="text/css">
    .fieldset-auto-width {
         display: inline-block;
	text-align:left;
    }
</style>

    <fieldset class="fieldset-auto-width">
    <p>
<?php
    echo "<h4>" . $row['Username'] . "&nbsp;" . $row['CommentDate'] . "</h4>";
    echo "<p>" . $row['Comment'] . "</p>";
    
?>
   </p>
   </fieldset></center>
<br>
<?php

}

?>

</fieldset>


</body>
</html>