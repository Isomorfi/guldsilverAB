<?php

$dbservername = "utbweb.its.ltu.se";
$dbusername = "19880310";
$dbpassword = "kallekongo";
$db = "db19880310";

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $db);
// Check connection
if ($conn->connect_error) {
  	die("Connection failed: " . $conn->connect_error);
}