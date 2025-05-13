<?php
include 'db.php';

header('Content-Type: application/json');

if(isset($_GET['query'])) {
    $search = mysqli_real_escape_string($conn, $_GET['query']);
    
    $sql = "SELECT * FROM qurbani_entries 
            WHERE customer_name LIKE '%$search%' 
            OR phone_number LIKE '%$search%'
            OR animal_number LIKE '%$search%'
            ORDER BY entry_date DESC";
            
    $result = mysqli_query($conn, $sql);
    
    $records = array();
    while($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
    }
    
    echo json_encode($records);
} else {
    echo json_encode([]);
}
?>