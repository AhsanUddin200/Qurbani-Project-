<?php
include 'db.php';

$sql = "SELECT animal_number, SUM(hissa_count) as total_hissa 
        FROM qurbani_entries 
        GROUP BY animal_number 
        ORDER BY animal_number";

$result = mysqli_query($conn, $sql);
$cows = array();

while($row = mysqli_fetch_assoc($result)) {
    $cows[] = $row;
}

echo json_encode($cows);
?>