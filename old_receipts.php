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
            .container { display: none; }
            #singleReceipt { display: block !important; }
            body { padding: 0; margin: 0; }
        }
        #singleReceipt {
            display: none;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            direction: rtl;
            font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
        }
        .receipt-box {
            border: 2px solid #000;
            padding: 20px;
            margin: 20px;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .receipt-details {
            font-size: 18px;
            line-height: 2;
        }
        .receipt-footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid #000;
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
                    <td><?php echo number_format($row['hissa_count'] * 26000); ?> روپے</td>
                    <td class="no-print">
                        <form action="print_single.php" method="POST" target="_blank">
                            <input type="hidden" name="receipt_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="print-btn">پرنٹ کریں</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div id="printArea" style="display: none;">
            <div style="padding: 20px; font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif; direction: rtl;">
                <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 20px;">
                    <h1 style="margin: 0;">الخدمت سہراب گوٹھ</h1>
                    <h2 style="margin: 10px 0;">اجتماعی قربانی 2025</h2>
                    <div id="printReceiptNumber"></div>
                </div>
                
                <div style="font-size: 18px; line-height: 2;">
                    <p>نام: <span id="printName"></span></p>
                    <p>جانور کی قسم: <span id="printAnimalType"></span></p>
                    <p>جانور کا نمبر: <span id="printAnimalNumber"></span></p>
                    <p>حصے کی تعداد: <span id="printHissaCount"></span></p>
                    <p>حصہ نمبر: <span id="printHissaNumber"></span></p>
                    <p>پتہ: <span id="printAddress"></span></p>
                    <p>فون نمبر: <span id="printPhone"></span></p>
                    <p>رقم: <span id="printAmount"></span></p>
                    <p>تاریخ: <span id="printDate"></span></p>
                </div>
                
                <div style="margin-top: 50px; padding-top: 20px; border-top: 1px solid #000; display: flex; justify-content: space-between;">
                    <div>دستخط (صارف): ________________</div>
                    <div>دستخط (الخدمت): ________________</div>
                </div>
            </div>
        </div>

        <style>
            @media print {
                body * { visibility: hidden; }
                #printArea, #printArea * { visibility: visible; }
                #printArea {
                    display: block !important;
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }
            }
        </style>

        <script>
            function printSingleReceipt(data) {
                document.getElementById('printName').textContent = data.customer_name;
                document.getElementById('printAnimalType').textContent = data.animal_type;
                document.getElementById('printAnimalNumber').textContent = data.animal_number;
                document.getElementById('printHissaCount').textContent = data.hissa_count;
                document.getElementById('printHissaNumber').textContent = data.hissa_number;
                document.getElementById('printAddress').textContent = data.address;
                document.getElementById('printPhone').textContent = data.phone_number;
                document.getElementById('printAmount').textContent = data.amount;
                document.getElementById('printDate').textContent = new Date(data.entry_date).toLocaleDateString();
                document.getElementById('printReceiptNumber').textContent = 'رسید نمبر: QUR-' + data.entry_date.replace(/-/g, '') + '-' + data.id;
                
                window.print();
            }
        </script>

        <script>
            function searchEntries(query) {
                const rows = document.querySelectorAll('#entriesBody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query.toLowerCase()) ? '' : 'none';
                });
            }
        </script>
    </div>
</body>
</html>