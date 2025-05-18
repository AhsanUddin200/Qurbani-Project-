<?php
include 'db.php';

// Get all entries or search by phone/name
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM qurbani_entries WHERE 
        customer_name LIKE '%$search%' OR 
        phone_number LIKE '%$search%' OR
        animal_number LIKE '%$search%'
        ORDER BY entry_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>پرانی رسیدیں - Al-Khidmat</title>
    <style>
        body {
            font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
            background-color: #f0f7ff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .search-box {
            margin: 20px 0;
            text-align: center;
        }
        .search-input {
            padding: 10px 20px;
            width: 300px;
            font-size: 18px;
            border: 2px solid #0089c7;
            border-radius: 8px;
            direction: rtl;
        }
        .entries-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            direction: rtl;
        }
        .entries-table th, .entries-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: right;
        }
        .entries-table th {
            background: #0089c7;
            color: white;
        }
        .print-btn {
            background: #28a745;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
        @media print {
            .no-print { display: none; }
            .receipt-content { display: block !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-btn no-print">واپس جائیں</a>
        <h1 style="text-align: center; color: #0089c7;">پرانی رسیدیں</h1>
        
        <div class="search-box no-print">
            <input type="text" class="search-input" placeholder="نام یا فون نمبر سے تلاش کریں" 
                   onkeyup="searchEntries(this.value)">
        </div>
        
        <table class="entries-table">
            <thead>
                <tr>
                    <th>تاریخ</th>
                    <th>نام</th>
                    <th>گائے نمبر</th>
                    <th>حصے</th>
                    <th>رقم</th>
                    <th class="no-print">رسید</th>
                </tr>
            </thead>
            <tbody id="entriesBody">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($row['entry_date'])); ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['animal_number']; ?></td>
                    <td><?php echo $row['hissa_count']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td class="no-print">
                        <button class="print-btn" onclick="printReceipt(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                            پرنٹ کریں
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <!-- Hidden receipt template -->
        <div id="receiptTemplate" style="display: none;">
            <div class="receipt-content" style="padding: 20px;">
                <h2 style="text-align: center;">الخدمت سہراب گوٹھ</h2>
                <h3 style="text-align: center;">اجتماعی قربانی رسید</h3>
                <div style="margin: 20px 0; direction: rtl;">
                    <p>نام: <span id="receipt-name"></span></p>
                    <p>گائے نمبر: <span id="receipt-animal"></span></p>
                    <p>حصے: <span id="receipt-hissa"></span></p>
                    <p>رقم: <span id="receipt-amount"></span></p>
                    <p>فون: <span id="receipt-phone"></span></p>
                    <p>تاریخ: <span id="receipt-date"></span></p>
                </div>
                <div style="margin-top: 50px; display: flex; justify-content: space-between;">
                    <div>دستخط (صارف): ________________</div>
                    <div>دستخط (الخدمت): ________________</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function searchEntries(query) {
            const rows = document.querySelectorAll('#entriesBody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query.toLowerCase()) ? '' : 'none';
            });
        }

        function printReceipt(data) {
            document.getElementById('receipt-name').textContent = data.customer_name;
            document.getElementById('receipt-animal').textContent = data.animal_number;
            document.getElementById('receipt-hissa').textContent = data.hissa_count;
            document.getElementById('receipt-amount').textContent = data.amount;
            document.getElementById('receipt-phone').textContent = data.phone_number;
            document.getElementById('receipt-date').textContent = new Date(data.entry_date).toLocaleDateString();
            
            const receiptTemplate = document.getElementById('receiptTemplate');
            receiptTemplate.style.display = 'block';
            window.print();
            receiptTemplate.style.display = 'none';
        }
    </script>
</body>
</html>