<?php
$conn = mysqli_connect("localhost", "root", "", "qurbani_system");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>