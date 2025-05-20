<?php
require 'db.php';

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="qurbani_report.xls"');

// Get dates
$start_date = $_GET['start_date'] ?? date('Y-m-d');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Get data
$sql_qurbani = "SELECT 
    animal_number,
    SUM(hissa_count) as total_hissa,
    SUM(amount) as total_amount
    FROM qurbani_entries 
    GROUP BY animal_number 
    ORDER BY animal_number";
$result_qurbani = mysqli_query($conn, $sql_qurbani);

$sql_expenses = "SELECT * FROM expenses 
                WHERE date BETWEEN '$start_date' AND '$end_date' 
                ORDER BY date";
$result_expenses = mysqli_query($conn, $sql_expenses);

// Start Excel content
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>
    <table border="1">
        <tr>
            <th colspan="4">Al-Khidmat Qurbani Report (<?php echo date('d-m-Y', strtotime($start_date)) . ' to ' . date('d-m-Y', strtotime($end_date)); ?>)</th>
        </tr>
        
        <!-- Qurbani Details -->
        <tr>
            <th colspan="4">قربانی کی تفصیل</th>
        </tr>
        <tr>
            <th>جانور نمبر</th>
            <th>کل حصے</th>
            <th>کل رقم</th>
            <th>باقی حصے</th>
        </tr>
        <?php 
        $total_amount = 0;
        while($row = mysqli_fetch_assoc($result_qurbani)): 
            $total_amount += $row['total_amount'];
            $remaining = 7 - $row['total_hissa'];
        ?>
        <tr>
            <td><?php echo $row['animal_number']; ?></td>
            <td><?php echo $row['total_hissa']; ?></td>
            <td><?php echo number_format($row['total_amount']); ?></td>
            <td><?php echo max(0, $remaining); ?></td>
        </tr>
        <?php endwhile; ?>

        <!-- Expenses -->
        <tr>
            <th colspan="4">اخراجات کی تفصیل</th>
        </tr>
        <tr>
            <th>تاریخ</th>
            <th>تفصیل</th>
            <th>رقم</th>
            <th>منظور کنندہ</th>
        </tr>
        <?php 
        $total_expenses = 0;
        while($expense = mysqli_fetch_assoc($result_expenses)): 
            $total_expenses += $expense['amount'];
        ?>
        <tr>
            <td><?php echo date('d-m-Y', strtotime($expense['date'])); ?></td>
            <td><?php echo $expense['description']; ?></td>
            <td><?php echo number_format($expense['amount']); ?></td>
            <td><?php echo $expense['approved_by']; ?></td>
        </tr>
        <?php endwhile; ?>

        <!-- Summary -->
        <tr>
            <th colspan="4">مجموعی خلاصہ</th>
        </tr>
        <tr>
            <td colspan="2">کل آمدنی:</td>
            <td colspan="2"><?php echo number_format($total_amount); ?></td>
        </tr>
        <tr>
            <td colspan="2">کل اخراجات:</td>
            <td colspan="2"><?php echo number_format($total_expenses); ?></td>
        </tr>
        <tr>
            <td colspan="2">خالص رقم:</td>
            <td colspan="2"><?php echo number_format($total_amount - $total_expenses); ?></td>
        </tr>
    </table>
</body>
</html>