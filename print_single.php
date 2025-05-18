<?php
include 'db.php';

if(isset($_POST['receipt_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['receipt_id']);
    $sql = "SELECT * FROM qurbani_entries WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if(!$row) {
        die('Receipt not found');
    }
} else {
    die('No receipt ID provided');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Receipt</title>
    <style>
        body {
            font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
            padding: 20px;
            direction: rtl;
        }
        .receipt {
            border: 2px solid #000;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .details {
            font-size: 18px;
            line-height: 2;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <div class="header">
            <h1>الخدمت سہراب گوٹھ</h1>
            <h2>اجتماعی قربانی 2025</h2>
            <div>رسید نمبر: QUR-<?php echo date('Ymd', strtotime($row['entry_date'])) . '-' . $row['id']; ?></div>
            <div style="font-size: 14px; margin-top: 10px; color: #666;">
                حصے کی معلومات کے لیے رابطہ کریں
            </div>
            <div style="font-size: 16px; margin-top: 5px;">
                خالد موسیٰ: 03332339098<br>
                عمر دراز: 03323087851
            </div>
        </div>
        
        <div class="details">
            <p>نام: <?php echo $row['customer_name']; ?></p>
            <p>جانور کی قسم: <?php echo $row['animal_type']; ?></p>
            <p>جانور کا نمبر: <?php echo $row['animal_number']; ?></p>
            <p>حصے کی تعداد: <?php echo $row['hissa_count']; ?></p>
            <p>حصہ نمبر: <?php echo $row['hissa_number']; ?></p>
            <p>پتہ: <?php echo $row['address']; ?></p>
            <p>فون نمبر: <?php echo $row['phone_number']; ?></p>
            <p>رقم: <?php echo number_format($row['hissa_count'] * 26000); ?> روپے</p>
            <p>تاریخ: <?php echo date('d-m-Y', strtotime($row['entry_date'])); ?></p>
        </div>
        
        <div class="footer">
            <div>دستخط (صارف): ________________</div>
            <div>دستخط (الخدمت): ________________</div>
        </div>
    </div>
</body>
</html>