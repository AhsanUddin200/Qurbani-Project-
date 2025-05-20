<?php
include 'db.php';

if(isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
    
    // Fix the date range query to include both start and end dates
    $sql = "SELECT * FROM expenses 
            WHERE DATE(date) >= DATE('$start_date') 
            AND DATE(date) <= DATE('$end_date') 
            ORDER BY date DESC";
    $result = mysqli_query($conn, $sql);
    
    // Fix total calculation query
    $sql_total = "SELECT SUM(amount) as total FROM expenses 
                  WHERE DATE(date) >= DATE('$start_date') 
                  AND DATE(date) <= DATE('$end_date')";
    $result_total = mysqli_query($conn, $sql_total);
    $total = mysqli_fetch_assoc($result_total);
}

// Add error reporting for debugging
if (!$result) {
    echo "Error: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>اخراجات کی رپورٹ - Al-Khidmat</title>
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
        
        .report {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .date-range {
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: right;
        }
        
        th {
            background: #f5f5f5;
        }
        
        .total-row {
            font-weight: bold;
            background: #f5f5f5;
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
    <div class="report">
        <div class="header">
            <h1>Al-Khidmat</h1>
            <h2>اخراجات کی رپورٹ</h2>
        </div>
        
        <div class="date-range">
            <strong>تاریخ:</strong> 
            <?php echo date('d-m-Y', strtotime($start_date)); ?> 
            تا 
            <?php echo date('d-m-Y', strtotime($end_date)); ?>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>تاریخ</th>
                    <th>تفصیل</th>
                    <th>رقم</th>
                    <th>منظور کنندہ</th>
                </tr>
            </thead>
            <tbody>
                <?php while($expense = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($expense['date'])); ?></td>
                    <td><?php echo $expense['description']; ?></td>
                    <td><?php echo number_format($expense['amount']); ?> روپے</td>
                    <td><?php echo $expense['approved_by']; ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="2">کل رقم</td>
                    <td colspan="2"><?php echo number_format($total['total']); ?> روپے</td>
                </tr>
            </tbody>
        </table>
        
        <div class="footer" style="text-align: center; margin-top: 30px;">
            <p>--- Al-Khidmat Qurbani Management System ---</p>
        </div>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="print-btn">پرنٹ کریں</button>
    </div>
</body>
</html>