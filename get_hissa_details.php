<?php
include 'db.php';

header('Content-Type: application/json');

if(isset($_GET['animal_number'])) {
    $animal_number = mysqli_real_escape_string($conn, $_GET['animal_number']);
    
    $sql = "SELECT customer_name, hissa_count, hissa_number, phone_number, entry_date 
            FROM qurbani_entries 
            WHERE animal_number = '$animal_number' 
            ORDER BY entry_date DESC";
            
    $result = mysqli_query($conn, $sql);
    
    $entries = array();
    while($row = mysqli_fetch_assoc($result)) {
        $entries[] = $row;
    }
    
    echo json_encode($entries);
} else {
    echo json_encode([]);
}
?>