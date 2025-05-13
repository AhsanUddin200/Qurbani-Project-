<?php
include 'db.php';

if(isset($_GET['animal_number'])) {
    $animal_number = mysqli_real_escape_string($conn, $_GET['animal_number']);
    
    // Get cow details
    $sql = "SELECT 
            animal_number,
            COUNT(*) as total_entries,
            SUM(hissa_count) as total_hissa
            FROM qurbani_entries 
            WHERE animal_number = '$animal_number'";
    
    $result = mysqli_query($conn, $sql);
    $cow_info = mysqli_fetch_assoc($result);
    
    // Get all entries for this cow
    $sql = "SELECT * FROM qurbani_entries 
            WHERE animal_number = '$animal_number' 
            ORDER BY entry_date DESC";
    $entries = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>حصص کی تفصیلات - Al-Khidmat</title>
    <style>
        body {
            font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #0089c7;
            padding: 15px;
            color: white;
            text-align: right;
        }
        .back-btn {
            background: white;
            color: #0089c7;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            margin-right: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .cow-summary {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            direction: rtl;
        }
        .cow-title {
            font-size: 32px;
            color: #0089c7;
            margin-bottom: 20px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .summary-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            font-size: 20px;
        }
        .progress-bar {
            height: 25px;
            background: #e9ecef;
            border-radius: 12px;
            margin: 20px 0;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #0089c7, #00a8f3);
            transition: width 0.3s;
            position: relative;
        }
        .progress-text {
            position: absolute;
            width: 100%;
            text-align: center;
            color: white;
            line-height: 25px;
            font-size: 16px;
        }
        .entries-list {
            display: grid;
            gap: 20px;
            direction: rtl;
        }
        .entry-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .entry-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .entry-item {
            padding: 10px;
            background: #f0f9ff;
            border-radius: 6px;
            font-size: 18px;
        }
        .entry-date {
            text-align: left;
            color: #666;
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="maloomat.php" class="back-btn">واپس جائیں</a>
    </div>

    <div class="container">
        <?php if(isset($cow_info)): 
            $used_hissa = $cow_info['total_hissa'] ?: 0;
            $progress = ($used_hissa / 7) * 100;
        ?>
            <div class="cow-summary">
                <div class="cow-title">گائے نمبر <?php echo $animal_number; ?></div>
                
                <div class="summary-grid">
                    <div class="summary-item">
                        کل حصے دار: <?php echo $cow_info['total_entries']; ?>
                    </div>
                    <div class="summary-item">
                        لیے گئے حصے: <?php echo $used_hissa; ?>/7
                    </div>
                    <div class="summary-item">
                        باقی حصے: <?php echo 7 - $used_hissa; ?>
                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $progress; ?>%">
                        <div class="progress-text"><?php echo round($progress); ?>%</div>
                    </div>
                </div>
            </div>

            <div class="entries-list">
                <?php while($entry = mysqli_fetch_assoc($entries)): ?>
                    <div class="entry-card">
                        <div class="entry-grid">
                            <div class="entry-item">نام: <?php echo $entry['customer_name']; ?></div>
                            <div class="entry-item">فون: <?php echo $entry['phone_number']; ?></div>
                            <div class="entry-item">حصے: <?php echo $entry['hissa_count']; ?></div>
                            <div class="entry-item">حصہ نمبر: <?php echo $entry['hissa_number']; ?></div>
                            <div class="entry-item" style="grid-column: 1/-1">
                                پتہ: <?php echo $entry['address']; ?>
                            </div>
                        </div>
                        <div class="entry-date">
                            تاریخ: <?php echo date('d-m-Y', strtotime($entry['entry_date'])); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; font-size: 24px; color: #666;">
                گائے کا نمبر درست نہیں ہے
            </div>
        <?php endif; ?>
    </div>
</body>
</html>