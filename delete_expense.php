<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $sql = "DELETE FROM expenses WHERE id = $id";
    mysqli_query($conn, $sql);
}

header('Location: hisaab.php');