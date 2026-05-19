<?php
$servername = "localhost";
$username = "root"; // default for XAMPP
$password = "";     // leave empty unless you set one
$dbname = "food_ordering"; // your database name

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
