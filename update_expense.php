<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $approved_by = mysqli_real_escape_string($conn, $_POST['approved_by']);
    
    // Handle file upload
    $bill_image_sql = "";
    if(isset($_FILES['bill_image']) && $_FILES['bill_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['bill_image']['type'];
        
        if(in_array($file_type, $allowed_types)) {
            $target_dir = "uploads/";
            
            // Get file extension and create new filename
            $file_extension = strtolower(pathinfo($_FILES['bill_image']['name'], PATHINFO_EXTENSION));
            $bill_image = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $bill_image;
            
            if(move_uploaded_file($_FILES['bill_image']['tmp_name'], $target_file)) {
                $bill_image_sql = ", bill_image = '$bill_image'";
                
                // Delete old image if exists
                $sql_old = "SELECT bill_image FROM expenses WHERE id = $id";
                $result_old = mysqli_query($conn, $sql_old);
                $row_old = mysqli_fetch_assoc($result_old);
                if($row_old['bill_image']) {
                    @unlink($target_dir . $row_old['bill_image']);
                }
            }
        }
    }
    
    $sql = "UPDATE expenses SET 
            description = '$description',
            amount = $amount,
            date = '$date',
            approved_by = '$approved_by'
            $bill_image_sql
            WHERE id = $id";
    
    mysqli_query($conn, $sql);
    
    header('Location: hisaab.php');
}