<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Al-Khidmat Qurbani Project</title>
    <style>
        body {
            font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .navbar {
            background-color: #0089c7;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        .logo {
            color: white;
            font-size: 32px;
            text-decoration: none;
            font-weight: bold;
        }
        .nav-links {
            display: flex;
            gap: 30px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 22px;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .nav-links a:hover {
            background-color: white;
            color: #0089c7;
        }
        .main-content {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }
        .section-title {
            text-align: center;
            color: #0089c7;
            font-size: 42px;
            margin-bottom: 40px;
            direction: rtl;
        }
        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            direction: rtl;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h2 {
            color: #0089c7;
            font-size: 28px;
            margin-bottom: 15px;
        }
        .card p {
            color: #666;
            font-size: 18px;
            line-height: 1.6;
        }
        .welcome-text {
            text-align: center;
            direction: rtl;
            font-size: 24px;
            color: #444;
            margin: 30px 0;
            line-height: 1.8;
        }
        
        .search-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 20px auto 40px;
            max-width: 800px;
            direction: rtl;
        }
        .search-box {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .search-input {
            flex: 1;
            padding: 15px;
            font-size: 20px;
            border: 2px solid #0089c7;
            border-radius: 8px;
            direction: rtl;
        }
        .search-button {
            background: #0089c7;
            color: white;
            border: none;
            padding: 0 30px;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-button:hover {
            background: #006da0;
        }
        .search-results {
            margin-top: 20px;
            display: none;
        }
        .result-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }
        .result-card h3 {
            color: #0089c7;
            font-size: 24px;
            margin: 0 0 10px;
        }
        .result-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            font-size: 18px;
        }
        .detail-item {
            margin-bottom: 8px;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            background: #0089c7;
            color: white;
        }
        .hissa-taken {
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .hissa-pending {
            background: #ffc107;
            color: #000;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .take-hissa-btn {
            background: #0089c7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
            width: 100%;
        }
        .take-hissa-btn:hover {
            background: #006da0;
        }
        .result-card {
            position: relative;
        }
        .result-card.pending .status-badge {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .result-card.completed .status-badge {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .result-card.completed {
            background: #f8fff9;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">الخدمت قربانی پروجیکٹ</a>
            <div class="nav-links">
                <a href="form.php">قربانی فارم</a>
                <a href="zabiha.php">ذبیحہ فارم</a>
                <a href="maloomat.php">معلومات</a>
                <a href="hisaab.php">حساب کتاب</a>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <h1 class="section-title">الخدمت اجتماعی قربانی پروجیکٹ 2025</h1>
        
        <div class="search-container">
            <div class="search-box">
                <input type="text" class="search-input" id="searchInput" placeholder="نام، فون نمبر یا جانور کا نمبر درج کریں">
                <button class="search-button" onclick="searchRecords()">تلاش کریں</button>
            </div>
            <div class="search-results" id="searchResults"></div>
        </div>

        <div class="welcome-text">
            <p>اجتماعی قربانی میں خوش آمدید</p>
            <p>آپ کی قربانی کی سہولت کے لیے ہماری خدمات</p>
        </div>

        <div class="info-cards">
            <div class="card">
                <h2>قربانی کی تفصیلات</h2>
                <p>اپنی قربانی کی تفصیلات درج کریں اور حصہ حاصل کریں</p>
            </div>
            <div class="card">
                <h2>ذبیحہ کی معلومات</h2>
                <p>ذبیحہ کی تمام معلومات اور ترتیب دیکھیں</p>
            </div>
            <div class="card">
                <h2>حساب کتاب</h2>
                <p>مکمل حساب کتاب اور رقم کی تفصیلات</p>
            </div>
        </div>
    </div>

    <script>
        function updateHissaStatus(entryId, currentStatus) {
            fetch('update_hissa.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `entry_id=${entryId}&status=${currentStatus ? 0 : 1}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    searchRecords();
                }
            });
        }

        function searchRecords() {
            const searchValue = document.getElementById('searchInput').value;
            if (!searchValue) return;

            fetch('search.php?query=' + encodeURIComponent(searchValue))
                .then(response => response.json())
                .then(data => {
                    const resultsDiv = document.getElementById('searchResults');
                    resultsDiv.style.display = 'block';
                    
                    if (data.length === 0) {
                        resultsDiv.innerHTML = '<div class="result-card">کوئی ریکارڈ نہیں ملا</div>';
                        return;
                    }

                    let html = '';
                    data.forEach(record => {
                        const hissaStatus = record.hissa_taken == 1;
                        html += `
                            <div class="result-card">
                                ${hissaStatus ? 
                                    '<div class="hissa-taken">حصہ لے گیا</div>' : 
                                    '<div class="hissa-pending">حصہ باقی ہے</div>'
                                }
                                <h3>${record.customer_name}</h3>
                                <div class="result-details">
                                    <div class="detail-item">جانور کا نمبر: ${record.animal_number}</div>
                                    <div class="detail-item">جانور کی قسم: ${record.animal_type}</div>
                                    <div class="detail-item">حصے: ${record.hissa_count}</div>
                                    <div class="detail-item">حصہ نمبر: ${record.hissa_number}</div>
                                    <div class="detail-item">فون: ${record.phone_number}</div>
                                    <div class="detail-item">رقم: ${record.amount}</div>
                                    <div class="detail-item" style="grid-column: 1 / -1;">پتہ: ${record.address}</div>
                                </div>
                                <button onclick="updateHissaStatus(${record.id}, ${hissaStatus})" class="take-hissa-btn">
                                    ${hissaStatus ? 'حصہ واپس کریں' : 'حصہ لے لیں'}
                                </button>
                            </div>`;
                    });
                    resultsDiv.innerHTML = html;
                });
        }
    </script>
</body>
</html>