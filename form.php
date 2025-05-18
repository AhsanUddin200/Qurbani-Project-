<?php
include 'db.php';

// Add tracking for available hissa
function getAvailableHissa($animal_number) {
    global $conn;
    $sql = "SELECT SUM(hissa_count) as total FROM qurbani_entries WHERE animal_number = '$animal_number'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $used_hissa = $row['total'] ?: 0;
    return 7 - $used_hissa; // Each cow has 7 total hissa
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $animal_type = $_POST['animal_type'];
    $hissa_count = $_POST['hissa_count'];
    $animal_number = $_POST['animal_number'];
    
    // Check available hissa before saving
    $available_hissa = getAvailableHissa($animal_number);
    
    if ($hissa_count <= $available_hissa) {
        $hissa_number = $_POST['hissa_number'];
        $address = $_POST['address'];
        $phone_number = $_POST['phone_number'];
        $amount = $_POST['amount'];
        
        $sql = "INSERT INTO qurbani_entries (customer_name, animal_type, hissa_count, 
                hissa_number, animal_number, address, phone_number, amount, entry_date) 
                VALUES ('$customer_name', '$animal_type', $hissa_count, '$hissa_number', 
                '$animal_number', '$address', '$phone_number', $amount, CURDATE())";

        if (mysqli_query($conn, $sql)) {
            // Get the last inserted ID
            $last_id = mysqli_insert_id($conn);
            
            // Store the receipt data in session for printing
            session_start();
            $_SESSION['print_receipt'] = [
                'id' => $last_id,
                'customer_name' => $customer_name,
                'animal_type' => $animal_type,
                'hissa_count' => $hissa_count,
                'hissa_number' => $hissa_number,
                'animal_number' => $animal_number,
                'address' => $address,
                'phone_number' => $phone_number,
                'amount' => $amount,
                'date' => date('d-m-Y'),
                'time' => date('h:i A'),
                'receipt_number' => 'QUR-'.date('Ymd').'-'.$last_id
            ];
            
            echo "<script>
                alert('Entry saved successfully!');
                window.open('print_receipt.php', '_blank');
            </script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Sorry! Only " . $available_hissa . " hissa available for this cow.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Al-Khidmat Ijtimai Qurbani 2025</title>
    <style>
        body {
            font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
            background-color: #0089c7;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            color: #0089c7;
        }
        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .header h2 {
            font-size: 28px;
            color: #0089c7;
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 25px;
            direction: rtl;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #0089c7;
            font-size: 20px;
        }
        input, select, textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #0089c7;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 18px;
            direction: rtl;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #0089c7;
            box-shadow: 0 0 8px rgba(0,137,199,0.3);
        }
        button {
            background-color: #0089c7;
            color: white;
            padding: 18px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 22px;
            width: 100%;
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: #006da0;
            transform: translateY(-2px);
        }
        #hissaInfo {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f0f9ff;
            border-radius: 8px;
            color: #0089c7;
            direction: rtl;
            border: 2px solid #0089c7;
        }
        #hissaDetails {
            font-size: 22px;
            margin-top: 15px;
            line-height: 1.6;
        }
        .print-btn {
            background-color: #28a745;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 20px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .print-btn:hover {
            background-color: #218838;
        }
        
        /* Add these print-specific styles */
        @media print {
            body {
                background-color: white;
                padding: 0;
                font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
            }
            .container {
                box-shadow: none;
                padding: 20px;
                max-width: none;
            }
            .print-btn, button[type="submit"], #hissaInfo {
                display: none;
            }
            .form-group {
                margin-bottom: 15px;
            }
            .form-group label {
                display: inline-block;
                width: 150px;
                color: #000;
            }
            input, select, textarea {
                border: none;
                padding: 5px;
                font-size: 16px;
            }
            .receipt-header {
                text-align: center;
                margin-bottom: 30px;
            }
            .receipt-footer {
                display: block !important;
                margin-top: 50px;
                text-align: right;
                font-size: 16px;
            }
            .receipt-title {
                font-size: 24px;
                text-align: center;
                margin: 20px 0;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>الخدمت سہراب گوٹھ</h1>
            <h2>اجتماعی قربانی 2025</h2>
            <!-- Add this link -->
            <a href="old_receipts.php" style="color: #0089c7; font-size: 18px;">پرانی رسیدیں دیکھیں</a>
        </div>
        
        <!-- Single hissaInfo div -->
        <div id="hissaInfo" style="text-align: center; margin-bottom: 20px; padding: 15px; background: #e8eaf6; border-radius: 6px; color: #1a237e; direction: rtl;">
            <div style="font-size: 24px; font-weight: bold;">معلومات حصص</div>
            <div id="hissaDetails" style="font-size: 20px; margin-top: 10px;">
                گائے کا نمبر درج کریں
            </div>
        </div>

        <form method="POST" id="qurbaniForm">
            <div class="form-group">
                <label>نام</label>
                <input type="text" name="customer_name" required>
            </div>

            <div class="form-group">
                <label>جانور کی قسم</label>
                <select name="animal_type" required>
                    <option value="گائے">گائے</option>
                    <option value="بکرا">بکرا</option>
                </select>
            </div>

            <div class="form-group">
                <label>حصے کی تعداد</label>
                <input type="number" name="hissa_count" id="hissa_count" min="1" max="7" required>
            </div>

            <div class="form-group">
                <label>حصہ نمبر</label>
                <input type="text" name="hissa_number" required>
            </div>

            <div class="form-group">
                <label>جانور کا نمبر</label>
                <input type="text" name="animal_number" id="animal_number" required>
            </div>

            <div class="form-group">
                <label>پتہ</label>
                <textarea name="address" required></textarea>
            </div>

            <div class="form-group">
                <label>فون نمبر</label>
                <input type="text" name="phone_number" required>
            </div>

            <div class="form-group">
                <label>رقم</label>
                <input type="number" name="amount" value="26000" required>
            </div>

            <button type="submit">محفوظ کریں</button>
        </form>
        
        <!-- Add print button after form -->
        <button onclick="printForm()" class="print-btn">پرنٹ کریں</button>
        
        <!-- Update receipt footer -->
        <div class="receipt-footer" style="display: none;">
            <div class="receipt-title">رسید اجتماعی قربانی</div>
            <div style="margin: 20px 0;">
                <p>تاریخ: <?php echo date('d-m-Y'); ?></p>
                <p>وقت: <?php echo date('h:i A'); ?></p>
                <p>سیریل نمبر: QUR-<?php echo date('Ymd').'-'.rand(1000,9999); ?></p>
            </div>
            <div style="margin-top: 40px;">
                <div style="float: left;">
                    <p>دستخط (صارف): ________________</p>
                </div>
                <div style="float: right;">
                    <p>دستخط (الخدمت): ________________</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load all cows data on page load
        window.addEventListener('load', function() {
            const hissaDetails = document.getElementById('hissaDetails');
            
            fetch('check_hissa.php')
                .then(response => response.json())
                .then(data => {
                    if (Object.keys(data.all_cows).length > 0) {
                        let html = '<div style="line-height: 2; margin-top: 10px;">';
                        html += '<div style="font-size: 24px; margin-bottom: 15px;">تمام گائیوں کی معلومات</div>';
                        
                        for (const [cowNum, available] of Object.entries(data.all_cows)) {
                            html += `
                                <div style="background: #fff; padding: 10px; margin: 10px 0; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <div style="font-size: 20px;">گائے نمبر ${cowNum}</div>
                                    <div style="color: #1a237e; font-size: 22px;">
                                        باقی حصے: ${available} / 7
                                    </div>
                                </div>`;
                        }
                        html += '</div>';
                        hissaDetails.innerHTML = html;
                    } else {
                        hissaDetails.innerHTML = '<div style="font-size: 20px;">کوئی گائے درج نہیں کی گئی</div>';
                    }
                });
        });

        // Existing animal number input handler
        document.getElementById('animal_number').addEventListener('input', function() {
            const animalNumber = this.value;
            const hissaDetails = document.getElementById('hissaDetails');
            
            if(animalNumber) {
                fetch('check_hissa.php?animal_number=' + animalNumber)
                    .then(response => response.text())
                    .then(data => {
                        try {
                            const result = JSON.parse(data);
                            const usedHissa = 7 - result.available;
                            hissaDetails.innerHTML = `
                                <div style="line-height: 2; margin-top: 10px;">
                                    <div style="font-size: 22px;">گائے نمبر ${result.animal_number}</div>
                                    <div style="color: #1a237e; font-size: 26px; margin: 15px 0;">
                                        باقی حصے: ${result.available} / 7
                                    </div>
                                    <div style="color: #666;">استعمال شدہ حصے: ${usedHissa}</div>
                                </div>`;
                            document.getElementById('hissa_count').max = result.available;
                        } catch(e) {
                            hissaDetails.innerHTML = 'گائے کا نمبر درج کریں';
                        }
                    });
            } else {
                hissaDetails.innerHTML = 'گائے کا نمبر درج کریں';
            }
        });

        // Form submission handler
        document.getElementById('qurbaniForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const animalNumber = document.getElementById('animal_number').value;
            const hissaCount = parseInt(document.getElementById('hissa_count').value);
            
            if(!animalNumber) {
                alert('براہ کرم گائے کا نمبر درج کریں');
                return;
            }

            try {
                const response = await fetch('check_hissa.php?animal_number=' + animalNumber);
                const data = await response.json();
                
                if(hissaCount > data.available) {
                    alert('معذرت! صرف ' + data.available + ' حصے دستیاب ہیں');
                } else {
                    document.getElementById('qurbaniForm').submit();
                }
            } catch(error) {
                alert('کچھ غلط ہو گیا۔ دوبارہ کوشش کریں');
            }
        });

        // Add print form function
        function printForm() {
            const formData = {
                name: document.querySelector('[name="customer_name"]').value,
                animalType: document.querySelector('[name="animal_type"]').value,
                hissaCount: document.querySelector('[name="hissa_count"]').value,
                hissaNumber: document.querySelector('[name="hissa_number"]').value,
                animalNumber: document.querySelector('[name="animal_number"]').value,
                address: document.querySelector('[name="address"]').value,
                phone: document.querySelector('[name="phone_number"]').value,
                amount: document.querySelector('[name="amount"]').value
            };

            if (formData.name && formData.animalNumber) {
                document.querySelector('.receipt-footer').style.display = 'block';
                
                // Hide form elements that shouldn't be printed
                const form = document.getElementById('qurbaniForm');
                const originalDisplay = form.style.display;
                form.style.display = 'none';
                
                window.print();
                
                // Restore form display
                setTimeout(() => {
                    form.style.display = originalDisplay;
                    document.querySelector('.receipt-footer').style.display = 'none';
                }, 1000);
            } else {
                alert('براہ کرم پہلے فارم مکمل کریں');
            }
        }
    </script>
</body>
</html>