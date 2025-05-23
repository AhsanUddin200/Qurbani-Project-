<?php
include 'db.php';

$animal_number = $_GET['animal_number'];
$sql = "SELECT id, customer_name, hissa_count, qurbani_names 
        FROM qurbani_entries 
        WHERE animal_number = '$animal_number'";

$result = mysqli_query($conn, $sql);
$entries = array();

while($row = mysqli_fetch_assoc($result)) {
    $entries[] = $row;
}

echo json_encode($entries);
?>