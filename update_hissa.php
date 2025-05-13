<?php
include 'db.php';

header('Content-Type: application/json');

if(isset($_POST['entry_id']) && isset($_POST['status'])) {
    $entry_id = mysqli_real_escape_string($conn, $_POST['entry_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $sql = "UPDATE qurbani_entries SET hissa_taken = '$status' WHERE id = '$entry_id'";
    
    if(mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
}
?>