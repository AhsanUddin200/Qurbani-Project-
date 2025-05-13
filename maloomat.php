<?php
include 'db.php';

// Get unique cows and their details
$sql = "SELECT DISTINCT animal_number, 
        (SELECT COUNT(*) FROM qurbani_entries q2 WHERE q2.animal_number = q1.animal_number) as total_entries,
        (SELECT SUM(hissa_count) FROM qurbani_entries q3 WHERE q3.animal_number = q1.animal_number) as total_hissa
        FROM qurbani_entries q1 ORDER BY animal_number";
$result = mysqli_query($conn, $sql);

// Initialize cards array first
$cards = array();
while($row = mysqli_fetch_assoc($result)) {
    $cards[] = $row;
}

// Calculate statistics
$total_cows = count($cards);
$total_hissa = array_sum(array_column($cards, 'total_hissa'));
$total_entries = array_sum(array_column($cards, 'total_entries'));
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
            /* Add these new styles */
                    .stats-container {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 20px;
                        margin-bottom: 30px;
                    }
                    .stat-card {
                        background: white;
                        padding: 25px;
                        border-radius: 15px;
                        text-align: center;
                        box-shadow: 0 4px 15px rgba(0,137,199,0.15);
                        border: 2px solid #0089c7;
                        transition: all 0.3s ease;
                    }
                    .stat-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 8px 25px rgba(0,137,199,0.25);
                    }
                    .stat-title {
                        font-size: 24px;
                        color: #0089c7;
                        margin-bottom: 15px;
                    }
                    .stat-value {
                        font-size: 36px;
                        color: #333;
                        font-weight: bold;
                    }
                    .filters {
                        background: white;
                        padding: 20px;
                        border-radius: 15px;
                        margin-bottom: 30px;
                        box-shadow: 0 4px 15px rgba(0,137,199,0.15);
                        direction: rtl;
                    }
                    .search-input {
                        padding: 12px 20px;
                        font-size: 18px;
                        border: 2px solid #0089c7;
                        border-radius: 8px;
                        width: 300px;
                        margin-left: 20px;
                        direction: rtl;
                    }
                    .filter-btn {
                        padding: 12px 25px;
                        font-size: 18px;
                        background: #0089c7;
                        color: white;
                        border: none;
                        border-radius: 8px;
                        cursor: pointer;
                        margin-left: 10px;
                        transition: all 0.3s ease;
                    }
                    .filter-btn.active {
                        background: #006694;
                    }
                    .filter-btn:hover {
                        background: #006694;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="navbar">
                        <a href="index.php" class="back-btn">واپس جائیں</a>
                    </div>
                    <h1 class="section-title">الخدمت اجتماعی قربانی 2025</h1>
                    
                    <div class="stats-container">
                        <div class="stat-card">
                            <div class="stat-title">کل گائیں</div>
                            <div class="stat-value"><?php echo $total_cows; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-title">کل حصے</div>
                            <div class="stat-value"><?php echo $total_hissa; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-title">کل حصے دار</div>
                            <div class="stat-value"><?php echo $total_entries; ?></div>
                        </div>
                    </div>
                    
                    <div class="filters">
                        <input type="text" class="search-input" id="searchInput" placeholder="گائے نمبر تلاش کریں" onkeyup="filterCards()">
                        <button class="filter-btn active" onclick="filterByStatus('all')">تمام</button>
                        <button class="filter-btn" onclick="filterByStatus('available')">دستیاب</button>
                        <button class="filter-btn" onclick="filterByStatus('full')">مکمل</button>
                    </div>
                    
                    <div class="cow-cards">
                        <?php 
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
            
                <script>
                    function filterCards() {
                        const searchValue = document.getElementById('searchInput').value.toLowerCase();
                        const cards = document.getElementsByClassName('cow-card');
                        
                        Array.from(cards).forEach(card => {
                            const number = card.querySelector('.cow-header').textContent.toLowerCase();
                            if (number.includes(searchValue)) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    }
                
                    function filterByStatus(status) {
                        const cards = document.getElementsByClassName('cow-card');
                        const buttons = document.getElementsByClassName('filter-btn');
                    
                        // Update button states
                        Array.from(buttons).forEach(btn => btn.classList.remove('active'));
                        event.target.classList.add('active');
                    
                        Array.from(cards).forEach(card => {
                            const hissaText = card.querySelector('.info-item:nth-child(2) span:last-child').textContent;
                            const usedHissa = parseInt(hissaText);
                            
                            if (status === 'all') {
                                card.style.display = '';
                            } else if (status === 'available' && usedHissa < 7) {
                                card.style.display = '';
                            } else if (status === 'full' && usedHissa >= 7) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    }
                </script>
            </body>
</html>