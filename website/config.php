<?php
$conn = mysqli_connect("localhost", "root", "", "fruitwala");

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
