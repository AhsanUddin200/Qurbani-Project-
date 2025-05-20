<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $approved_by = mysqli_real_escape_string($conn, $_POST['approved_by']);
    
    // Handle file upload
    $bill_image = null;
    // Handle file upload
    if(isset($_FILES['bill_image']) && $_FILES['bill_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['bill_image']['type'];
        
        if(in_array($file_type, $allowed_types)) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Get file extension and create new filename
            $file_extension = strtolower(pathinfo($_FILES['bill_image']['name'], PATHINFO_EXTENSION));
            $bill_image = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $bill_image;
            
            if(move_uploaded_file($_FILES['bill_image']['tmp_name'], $target_file)) {
                // File uploaded successfully
            } else {
                $bill_image = null;
            }
        }
    }
    
    $sql = "INSERT INTO expenses (description, amount, date, approved_by, bill_image) 
            VALUES ('$description', $amount, '$date', '$approved_by', " 
            ($bill_image ? "'$bill_image'" : "NULL") . ")";
    
    mysqli_query($conn, $sql);
    
    header('Location: hisaab.php');
}