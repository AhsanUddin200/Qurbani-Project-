<?php
include 'db.php';

// Get total collections
$sql_total = "SELECT SUM(amount) as total_amount, COUNT(*) as total_entries FROM qurbani_entries";
$result_total = mysqli_query($conn, $sql_total);
$total_data = mysqli_fetch_assoc($result_total);

// Get animal-wise summary with correct hissa counting
$sql_animals = "SELECT 
                animal_number,
                SUM(hissa_count) as total_hissa,
                SUM(amount) as total_amount,
                GROUP_CONCAT(DISTINCT hissa_number) as hissa_numbers
                FROM qurbani_entries 
                GROUP BY animal_number 
                ORDER BY animal_number";
$result_animals = mysqli_query($conn, $sql_animals);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>حساب کتاب - Al-Khidmat</title>
    <style>
        body {
            font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
            background-color: #f0f7ff;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .summary-card {
            background: #0089c7;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .summary-card h2 {
            margin: 0;
            font-size: 24px;
        }
        .summary-card .number {
            font-size: 36px;
            margin: 15px 0;
        }
        .animals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .animals-table th, .animals-table td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: right;
        }
        .animals-table th {
            background: #0089c7;
            color: white;
        }
        .animals-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .back-btn {
            background: #0089c7;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .section-title {
            color: #0089c7;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            margin-top: 10px;
        }
        .progress {
            height: 100%;
            background: #28a745;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-btn">واپس جائیں</a>
        <h1 class="section-title">حساب کتاب</h1>

        <div class="summary-cards">
            <div class="summary-card">
                <h2>کل رقم</h2>
                <div class="number"><?php echo number_format($total_data['total_amount']); ?> روپے</div>
            </div>
            <div class="summary-card">
                <h2>کل حصے</h2>
                <div class="number"><?php echo $total_data['total_entries']; ?></div>
            </div>
            <div class="summary-card">
                <h2>فی حصہ اوسط</h2>
                <div class="number">
                    <?php 
                    $average = $total_data['total_entries'] > 0 ? 
                        round($total_data['total_amount'] / $total_data['total_entries']) : 0;
                    echo number_format($average); ?> روپے
                </div>
            </div>
        </div>

        <h2 style="color: #0089c7;">جانور کے حساب سے تفصیل</h2>
        <table class="animals-table">
            <thead>
                <tr>
                    <th>جانور نمبر</th>
                    <th>کل حصے</th>
                    <th>باقی حصے</th>
                    <th>کل رقم</th>
                    <th>مکمل</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result_animals)): 
                    $remaining = 7 - $row['total_hissa']; // Assuming 7 hissa per animal
                    $completion = ($row['total_hissa'] / 7) * 100;
                ?>
                <tr>
                    <td><?php echo $row['animal_number']; ?></td>
                    <td><?php echo $row['total_hissa']; ?></td>
                    <td><?php echo max(0, $remaining); ?></td>
                    <td><?php echo number_format($row['total_amount']); ?> روپے</td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress" style="width: <?php echo $completion; ?>%"></div>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
