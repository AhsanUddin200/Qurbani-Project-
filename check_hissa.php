<?php
include 'db.php';

header('Content-Type: application/json');

if(isset($_GET['animal_number'])) {
    $animal_number = mysqli_real_escape_string($conn, $_GET['animal_number']);
    
    $sql = "SELECT animal_number, SUM(hissa_count) as total FROM qurbani_entries GROUP BY animal_number";
    $result = mysqli_query($conn, $sql);
    
    $all_cows = [];
    while($row = mysqli_fetch_assoc($result)) {
        $used_hissa = $row['total'] ?: 0;
        $all_cows[$row['animal_number']] = 7 - (int)$used_hissa;
    }
    
    echo json_encode([
        'animal_number' => $animal_number,
        'available' => isset($all_cows[$animal_number]) ? $all_cows[$animal_number] : 7,
        'all_cows' => $all_cows
    ]);
} else {
    // Return all cows data when no specific animal number is requested
    $sql = "SELECT animal_number, SUM(hissa_count) as total FROM qurbani_entries GROUP BY animal_number";
    $result = mysqli_query($conn, $sql);
    
    $all_cows = [];
    while($row = mysqli_fetch_assoc($result)) {
        $used_hissa = $row['total'] ?: 0;
        $all_cows[$row['animal_number']] = 7 - (int)$used_hissa;
    }
    
    echo json_encode([
        'all_cows' => $all_cows
    ]);
}
?>