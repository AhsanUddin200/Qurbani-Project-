<?php
include 'db.php';

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM expenses WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $expense = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>رسید پرنٹ - Al-Khidmat</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }
        
        body {
            font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
            direction: rtl;
            padding: 20px;
        }
        
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #000;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .details {
            margin: 20px 0;
        }
        
        .row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            border-bottom: 1px dashed #ccc;
            padding: 5px 0;
        }
        
        .label {
            font-weight: bold;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
        }
        
        .print-btn {
            background: #0089c7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>Al-Khidmat</h1>
            <h2>رسید اخراجات</h2>
        </div>
        
        <div class="details">
            <div class="row">
                <span class="label">تاریخ:</span>
                <span><?php echo date('d-m-Y', strtotime($expense['date'])); ?></span>
            </div>
            <div class="row">
                <span class="label">تفصیل:</span>
                <span><?php echo $expense['description']; ?></span>
            </div>
            <div class="row">
                <span class="label">رقم:</span>
                <span><?php echo number_format($expense['amount']); ?> روپے</span>
            </div>
            <div class="row">
                <span class="label">منظور کنندہ:</span>
                <span><?php echo $expense['approved_by']; ?></span>
            </div>
        </div>
        
        <?php if($expense['bill_image']): ?>
        <div class="row">
            <span class="label">بل کی تصویر:</span>
            <img src="uploads/<?php echo $expense['bill_image']; ?>" style="max-width: 100%; margin-top: 20px;">
        </div>
        <?php endif; ?>
        
        <div class="footer">
            <p>--- Al-Khidmat Qurbani Management System ---</p>
        </div>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="print-btn">پرنٹ کریں</button>
    </div>
</body>
</html>