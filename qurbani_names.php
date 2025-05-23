<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entry_id = $_POST['entry_id'];
    $names = $_POST['qurbani_names'];
    
    // Convert names array to comma-separated string
    $names_str = implode(', ', array_filter($names));
    
    // Update the database
    $sql = "UPDATE qurbani_entries SET qurbani_names = '$names_str' WHERE id = $entry_id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('قربانی کے نام محفوظ ہو گئے');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>قربانی کے نام درج کریں</title>
    <style>
        body {
            font-family: 'Jameel Noori Nastaleeq', Arial, sans-serif;
            background-color: #0089c7;
            margin: 0;
            padding: 20px;
            direction: rtl;
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
            border-bottom: 3px solid #0089c7;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .header h2 {
            font-size: 28px;
            margin-top: 0;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #0089c7;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            background: #006da0;
            transform: translateY(-2px);
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #0089c7;
            font-size: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #0089c7;
            border-radius: 8px;
            font-size: 18px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #006da0;
            box-shadow: 0 0 10px rgba(0,137,199,0.2);
        }
        .search-result {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }
        .search-result h3 {
            color: #0089c7;
            margin-top: 0;
            font-size: 24px;
        }
        .search-result p {
            margin: 10px 0;
            font-size: 18px;
            color: #495057;
        }
        .search-result button {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        .search-result button:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .name-inputs {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-top: 30px;
        }
        .name-inputs h3 {
            color: #0089c7;
            text-align: center;
            margin-top: 0;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .name-input {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        .name-input label {
            color: #0089c7;
            font-size: 18px;
            margin-bottom: 10px;
            display: block;
        }
        .name-input input {
            width: 100%;
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .name-input input:focus {
            border-color: #0089c7;
            box-shadow: 0 0 8px rgba(0,137,199,0.2);
        }
        button[type="submit"] {
            background: #0089c7;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 20px;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        button[type="submit"]:hover {
            background: #006da0;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>الخدمت سہراب گوٹھ</h1>
            <h2>قربانی کے نام درج کریں</h2>
        </div>
        <a href="index.php" class="back-btn">واپس جائیں</a>
    
        <!-- Add print button -->
        <button 
    onclick="printQurbaniNames()" 
    onmouseover="this.style.backgroundColor='#218838'; this.style.transform='scale(1.02)'"
    onmouseout="this.style.backgroundColor='#28a745'; this.style.transform='scale(1)'"
    onmousedown="this.style.transform='scale(0.98)'"
    onmouseup="this.style.transform='scale(1.02)'"
    style="
        background-color: #28a745;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        margin-bottom: 20px;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    "
>
    قربانی کے نام پرنٹ کریں
</button>

    
        <!-- Add hidden print section -->
        <div id="printSection" style="display: none;">
            <div class="print-header">
                <h1>الخدمت سہراب گوٹھ</h1>
                <h2>اجتماعی قربانی 2025</h2>
                <h3>قربانی کے نام</h3>
                <p>تاریخ: <?php echo date('d-m-Y'); ?></p>
            </div>
            <div id="printContent"></div>
        </div>

        <!-- Existing code continues... -->
        <div class="all-cows-section">
            <h3 style="text-align: center; color: #0089c7; font-size: 24px; margin: 20px 0;">تمام گائیوں کی تفصیلات</h3>
            <div id="allCowsList"></div>
        </div>

        <!-- Existing search form -->
        <div class="form-group">
            <label>جانور کا نمبر یا فون نمبر درج کریں</label>
            <input type="text" id="searchInput" onkeyup="searchEntry()">
        </div>

        <div id="searchResults"></div>
        <div id="namesForm"></div>
    </div>

    <script>
    // Add this new function to load all cows on page load
    window.addEventListener('load', function() {
        fetch('get_all_cows.php')
            .then(response => response.json())
            .then(data => {
                const cowsListDiv = document.getElementById('allCowsList');
                let html = '';
                
                data.forEach(cow => {
                    html += `
                        <div class="search-result">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h3>گائے نمبر: ${cow.animal_number}</h3>
                                    <p>کل حصے: ${cow.total_hissa}/7</p>
                                    <p>باقی حصے: ${7 - cow.total_hissa}</p>
                                </div>
                                <button onclick="showCowDetails('${cow.animal_number}')" 
                                        style="background: #0089c7; margin-right: 10px;">
                                    تفصیلات دیکھیں
                                </button>
                            </div>
                        </div>`;
                });
                
                cowsListDiv.innerHTML = html;
            });
    });

    // Add this function to show cow details
    function showCowDetails(animalNumber) {
        fetch('get_cow_details.php?animal_number=' + animalNumber)
            .then(response => response.json())
            .then(entries => {
                const resultsDiv = document.getElementById('searchResults');
                let html = `<div class="search-result" style="background: #e8eaf6;">
                    <h3>گائے نمبر ${animalNumber} کی تفصیلات</h3>`;
                
                entries.forEach(entry => {
                    html += `
                        <div style="background: white; padding: 15px; margin: 10px 0; border-radius: 8px;">
                            <p>نام: ${entry.customer_name}</p>
                            <p>حصے: ${entry.hissa_count}</p>
                            <button onclick="showNameInputs(${entry.id}, ${entry.hissa_count}, '${entry.qurbani_names}')"
                                    style="background: #28a745;">
                                نام درج کریں
                            </button>
                        </div>`;
                });
                
                html += '</div>';
                resultsDiv.innerHTML = html;
            });
    }

    function searchEntry() {
        const searchValue = document.getElementById('searchInput').value;
        if (!searchValue) return;

        fetch('search.php?query=' + searchValue)
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('searchResults');
                if (data.length === 0) {
                    resultsDiv.innerHTML = '<div class="search-result">کوئی ریکارڈ نہیں ملا</div>';
                    return;
                }

                data.forEach(entry => {
                    let html = `
                        <div class="search-result">
                            <h3>${entry.customer_name}</h3>
                            <p>جانور نمبر: ${entry.animal_number}</p>
                            <p>حصے: ${entry.hissa_count}</p>
                            <button onclick="showNameInputs(${entry.id}, ${entry.hissa_count}, '${entry.qurbani_names}')">
                                نام درج کریں
                            </button>
                        </div>`;
                    resultsDiv.innerHTML = html;
                });
            });
    }

    function showNameInputs(entryId, hissaCount, existingNames = '') {
        const namesArray = existingNames ? existingNames.split(',').map(name => name.trim()) : [];
        let html = `
            <form method="POST" class="name-inputs">
                <input type="hidden" name="entry_id" value="${entryId}">
                <h3>قربانی کے نام درج کریں</h3>`;
        
        for (let i = 0; i < hissaCount; i++) {
            html += `
                <div class="name-input">
                    <label>حصہ ${i + 1} کے لیے نام</label>
                    <input type="text" name="qurbani_names[]" 
                           value="${namesArray[i] || ''}" 
                           placeholder="نام درج کریں" required>
                </div>`;
        }
        
        html += '<button type="submit">محفوظ کریں</button></form>';
        document.getElementById('namesForm').innerHTML = html;
    }
    </script>

    <!-- Add this new style section in your existing styles -->
    <style>
        @media print {
            .print-header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
            }
            .cow-names {
                page-break-after: always;
                margin-bottom: 30px;
                direction: rtl;
            }
            .print-btn, .back-btn, .form-group, #allCowsList, #searchResults, #namesForm {
                display: none;
            }
        }
    </style>

    <!-- Add this new function in your existing script section -->
    <script>
    function printQurbaniNames() {
        fetch('get_all_qurbani_names.php')
            .then(response => response.json())
            .then(data => {
                let printHtml = '';
                
                // Group by animal number
                const cowGroups = {};
                data.forEach(entry => {
                    if (!cowGroups[entry.animal_number]) {
                        cowGroups[entry.animal_number] = [];
                    }
                    if (entry.qurbani_names) {
                        cowGroups[entry.animal_number].push({
                            customer_name: entry.customer_name,
                            names: entry.qurbani_names.split(',').map(name => name.trim()),
                            hissa_count: entry.hissa_count
                        });
                    }
                });
                
                // Generate HTML for each cow
                for (const [cowNumber, entries] of Object.entries(cowGroups)) {
                    if (entries.length > 0) {
                        printHtml += `
                            <div class="cow-names">
                                <h2 style="text-align: center; color: #000; margin-bottom: 20px;">
                                    گائے نمبر: ${cowNumber}
                                </h2>`;
                        
                        entries.forEach(entry => {
                            printHtml += `
                                <div style="margin: 15px 0; padding: 10px; border-bottom: 1px solid #ccc;">
                                    <p style="font-size: 18px; margin: 5px 0;">نام: ${entry.customer_name}</p>
                                    <p style="font-size: 16px; margin: 5px 0;">حصے: ${entry.hissa_count}</p>
                                    <div style="margin-right: 20px;">
                                        ${entry.names.map((name, idx) => 
                                            `<p style="margin: 5px 0;">${idx + 1}. ${name}</p>`
                                        ).join('')}
                                    </div>
                                </div>`;
                        });
                        
                        printHtml += '</div>';
                    }
                }
                
                document.getElementById('printContent').innerHTML = printHtml;
                
                // Print the document
                const originalContents = document.body.innerHTML;
                const printContents = document.getElementById('printSection').innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
                
                // Reinitialize the page
                location.reload();
            });
    }
    </script>
