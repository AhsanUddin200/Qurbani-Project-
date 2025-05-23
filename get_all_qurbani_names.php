<?php
include 'db.php';

$sql = "SELECT animal_number, customer_name, hissa_count, qurbani_names 
        FROM qurbani_entries 
        WHERE qurbani_names IS NOT NULL AND qurbani_names != ''
        ORDER BY animal_number, id";

$result = mysqli_query($conn, $sql);
$entries = array();

while($row = mysqli_fetch_assoc($result)) {
    $entries[] = $row;
}

echo json_encode($entries);
?>