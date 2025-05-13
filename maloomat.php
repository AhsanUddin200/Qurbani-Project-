<?php
include 'db.php';

// Get unique cows and their details
$sql = "SELECT DISTINCT animal_number, 
        (SELECT COUNT(*) FROM qurbani_entries q2 WHERE q2.animal_number = q1.animal_number) as total_entries,
        (SELECT SUM(hissa_count) FROM qurbani_entries q3 WHERE q3.animal_number = q1.animal_number) as total_hissa
        FROM qurbani_entries q1 ORDER BY animal_number";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>معلومات قربانی - Al-Khidmat</title>
    <style>
        body {
            font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
            background-color: #f0f7ff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .section-title {
            text-align: center;
            color: #0089c7;
            font-size: 42px;
            margin: 30px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .cow-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            padding: 20px;
        }
        .cow-card {
            background: white;
            border-radius: 15px;
            padding: 0;
            box-shadow: 0 4px 15px rgba(0,137,199,0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            direction: rtl;
            overflow: hidden;
            border: 2px solid #0089c7;
            min-width: 380px;
        }
        .cow-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,137,199,0.25);
        }
        .cow-header {
            background: #0089c7;
            color: white;
            font-size: 28px;
            padding: 20px;
            text-align: center;
            margin: 0;
        }
        .cow-info {
            padding: 20px;
            font-size: 20px;
            line-height: 1.8;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .hissa-progress {
            margin: 0 20px 20px;
        }
        .progress-container {
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            height: 25px;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #0089c7, #00a8f3);
            transition: width 0.5s ease;
            position: relative;
        }
        .progress-text {
            position: absolute;
            width: 100%;
            text-align: center;
            color: white;
            font-size: 16px;
            line-height: 25px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
            z-index: 1;
        }
        /* Add these navbar styles */
                .navbar {
                    background: #0089c7;
                    padding: 15px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    text-align: right;
                }
                .back-btn {
                    background: white;
                    color: #0089c7;
                    padding: 10px 25px;
                    border-radius: 8px;
                    text-decoration: none;
                    font-size: 20px;
                    transition: all 0.3s ease;
                    display: inline-block;
                }
                .back-btn:hover {
                    background: #f0f0f0;
                    transform: translateX(-5px);
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="navbar">
                    <a href="index.php" class="back-btn">واپس جائیں</a>
                </div>
                <h1 class="section-title">الخدمت اجتماعی قربانی 2025</h1>
                
                <div class="cow-cards">
            <?php 
            $cards = array();
            while($row = mysqli_fetch_assoc($result)) {
                $cards[] = $row;
            }
            
            // Group cards in rows of 3
            $grouped_cards = array_chunk($cards, 3);
            
            foreach($grouped_cards as $row_cards):
                foreach($row_cards as $row): 
                    $used_hissa = $row['total_hissa'] ?: 0;
                    $progress = ($used_hissa / 7) * 100;
                    $remaining = 7 - $used_hissa;
            ?>
                <div class="cow-card" onclick="window.location.href='hissa_details.php?animal_number=<?php echo $row['animal_number']; ?>'">
                    <div class="cow-header">گائے نمبر <?php echo $row['animal_number']; ?></div>
                    <div class="cow-info">
                        <div class="info-item">
                            <span>کل حصے دار:</span>
                            <span><?php echo $row['total_entries']; ?></span>
                        </div>
                        <div class="info-item">
                            <span>لیے گئے حصے:</span>
                            <span><?php echo $used_hissa; ?>/7</span>
                        </div>
                        <div class="info-item">
                            <span>باقی حصے:</span>
                            <span><?php echo $remaining; ?></span>
                        </div>
                    </div>
                    <div class="hissa-progress">
                        <div class="progress-container">
                            <div class="progress-text"><?php echo round($progress); ?>%</div>
                            <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                        </div>
                    </div>
                </div>
            <?php 
                endforeach;
            endforeach; 
            ?>
        </div>
    </div>
</body>
</html>