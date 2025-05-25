<?php
include 'db.php';

// Get total collections
$sql_total = "SELECT SUM(amount) as total_amount, COUNT(*) as total_entries FROM qurbani_entries";
$result_total = mysqli_query($conn, $sql_total);
$total_data = mysqli_fetch_assoc($result_total);

// Get animal-wise summary with correct hissa counting and amount calculation
$sql_animals = "SELECT 
                animal_number,
                SUM(hissa_count) as total_hissa,
                SUM(hissa_count * amount) as total_amount,
                (7 - SUM(hissa_count)) as free_hissa
                FROM qurbani_entries 
                GROUP BY animal_number 
                HAVING free_hissa > 0
                ORDER BY animal_number";
$result_animals = mysqli_query($conn, $sql_animals);

// Add after existing SQL queries
$sql_expenses = "SELECT SUM(amount) as total_expenses FROM expenses";
$result_expenses = mysqli_query($conn, $sql_expenses);
$expense_data = mysqli_fetch_assoc($result_expenses);

// Get expense details
$sql_expense_details = "SELECT * FROM expenses ORDER BY date DESC";
$result_expense_details = mysqli_query($conn, $sql_expense_details);

// Calculate net amount
$total_income = $total_data['total_amount'] ?? 0;
$total_expenses = $expense_data['total_expenses'] ?? 0;
$net_amount = $total_income - $total_expenses;

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
        <div class="summary-cards">
            <div class="summary-card">
                <h2>کل آمدنی</h2>
                <div class="number"><?php echo number_format($total_income); ?> روپے</div>
            </div>
            <div class="summary-card" style="background: #dc3545;">
                <h2>کل اخراجات</h2>
                <div class="number"><?php echo number_format($total_expenses); ?> روپے</div>
            </div>
            <div class="summary-card" style="background: #28a745;">
                <h2>خالص رقم</h2>
                <div class="number"><?php echo number_format($net_amount); ?> روپے</div>
            </div>
        </div>

        <!-- Add expense form -->
       
        // Add this before the expense table
        <div class="expense-section">
            <h2 style="color: #0089c7;">رپورٹ</h2>
            <form action="print_report.php" method="GET" class="expense-form" target="_blank">
                <div class="form-group" style="display: flex; gap: 20px;">
                    <div style="flex: 1;">
                        <label>شروع تاریخ</label>
                        <input type="date" name="start_date" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div style="flex: 1;">
                        <label>آخری تاریخ</label>
                        <input type="date" name="end_date" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <button type="submit" class="submit-btn">رپورٹ دیکھیں</button>
            </form>
        </div>
        <div class="expense-section">
            <h2 style="color: #0089c7;">اخراجات کا اندراج</h2>
            <form action="add_expense.php" method="POST" class="expense-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label>تفصیل</label>
                    <input type="text" name="description" required>
                </div>
                <div class="form-group">
                    <label>رقم</label>
                    <input type="number" name="amount" required>
                </div>
                <div class="form-group">
                    <label>تاریخ</label>
                    <input type="date" name="date" required>
                </div>
                
                <div class="form-group">
                    <label>بل کی تصویر (اختیاری)</label>
                    <input type="file" name="bill_image" accept="image/jpeg,image/png,image/jpg" class="file-input">
                    <small style="color: #666; display: block; margin-top: 5px;">* صرف JPG, JPEG یا PNG فارمیٹ</small>
                </div>
                <button type="submit" class="submit-btn">اخراجات شامل کریں</button>
            </form>
        </div>

        <!-- Update expense table headers -->
        <table class="animals-table">
            <thead>
                <tr>
                    <th>تاریخ</th>
                    <th>تفصیل</th>
                    <th>رقم</th>
                    <th>منظور کنندہ</th>
                    <th>بل</th>
                    <th>عمل</th>
                </tr>
            </thead>
            <tbody>
                <?php while($expense = mysqli_fetch_assoc($result_expense_details)): ?>
                <tr>
                    <td><?php echo date('d-m-Y', strtotime($expense['date'])); ?></td>
                    <td><?php echo $expense['description']; ?></td>
                    <td><?php echo number_format($expense['amount']); ?> روپے</td>
                    <td><?php echo $expense['approved_by']; ?></td>
                    <td>
                        <?php if($expense['bill_image']): ?>
                            <a href="uploads/<?php echo $expense['bill_image']; ?>" target="_blank" class="view-bill">
                                بل دیکھیں
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    // Add this in the table where expenses are listed, in the action column
                    <td>
                        <button onclick="editExpense(<?php echo htmlspecialchars(json_encode($expense)); ?>)" class="edit-btn">ترمیم</button>
                        <a href="print_receipt.php?id=<?php echo $expense['id']; ?>" target="_blank" class="print-btn">رسید پرنٹ</a>
                        <form action="delete_expense.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $expense['id']; ?>">
                            <button type="submit" class="delete-btn">حذف کریں</button>
                        </form>
                    </td>
                    
                    // Add this CSS
                    <style>
                        .print-btn {
                            background: linear-gradient(135deg, #17a2b8, #138496);
                            color: white;
                            border: none;
                            padding: 8px 15px;
                            border-radius: 5px;
                            cursor: pointer;
                            margin-right: 5px;
                            text-decoration: none;
                            display: inline-block;
                        }
                    
                        .print-btn:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 4px 10px rgba(23,162,184,0.2);
                        }
                    </style>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Add Edit Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>ترمیم کریں</h2>
                <form action="update_expense.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>تفصیل</label>
                        <input type="text" name="description" id="edit_description" required>
                    </div>
                    <div class="form-group">
                        <label>رقم</label>
                        <input type="number" name="amount" id="edit_amount" required>
                    </div>
                    <div class="form-group">
                        <label>تاریخ</label>
                        <input type="date" name="date" id="edit_date" required>
                    </div>
                    <div class="form-group">
                        <label>منظور کنندہ کا نام</label>
                        <input type="text" name="approved_by" id="edit_approved_by" required>
                    </div>
                    <div class="form-group">
                        <label>بل کی تصویر (اختیاری)</label>
                        <input type="file" name="bill_image" accept="image/*" class="file-input">
                    </div>
                    <button type="submit" class="submit-btn">تبدیلیاں محفوظ کریں</button>
                </form>
            </div>
        </div>

        <!-- Add these styles -->
        <style>
            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
            }

            .modal-content {
                background-color: #fefefe;
                margin: 5% auto;
                padding: 20px;
                border-radius: 15px;
                width: 80%;
                max-width: 600px;
                direction: rtl;
            }

            .close {
                color: #aaa;
                float: left;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }

            .edit-btn {
                background: linear-gradient(135deg, #ffc107, #ff9800);
                color: white;
                border: none;
                padding: 8px 15px;
                border-radius: 5px;
                cursor: pointer;
                margin-right: 5px;
            }

            .edit-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 10px rgba(255,193,7,0.2);
            }
        </style>

        <!-- Add this JavaScript -->
        <script>
            const modal = document.getElementById('editModal');
            const span = document.getElementsByClassName('close')[0];

            function editExpense(expense) {
                document.getElementById('edit_id').value = expense.id;
                document.getElementById('edit_description').value = expense.description;
                document.getElementById('edit_amount').value = expense.amount;
                document.getElementById('edit_date').value = expense.date;
                document.getElementById('edit_approved_by').value = expense.approved_by;
                modal.style.display = 'block';
            }

            span.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        </script>
        <style>
            .file-input {
                border: 2px dashed #ddd;
                padding: 20px;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .file-input:hover {
                border-color: #0089c7;
            }
            
            .view-bill {
                color: #0089c7;
                text-decoration: none;
                padding: 5px 10px;
                border: 1px solid #0089c7;
                border-radius: 5px;
                transition: all 0.3s ease;
            }
            
            .view-bill:hover {
                background: #0089c7;
                color: white;
            }
        </style>
        
<style>
    /* Update existing styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin: 30px 0;
    }

    .summary-card {
        background: linear-gradient(135deg, #0089c7, #006da0);
        color: white;
        padding: 25px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .summary-card:hover {
        transform: translateY(-5px);
    }

    .summary-card h2 {
        margin: 0;
        font-size: 26px;
        font-weight: bold;
    }

    .summary-card .number {
        font-size: 42px;
        margin: 20px 0;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    .section-title {
        color: #0089c7;
        text-align: center;
        margin: 40px 0;
        font-size: 36px;
        position: relative;
        padding-bottom: 15px;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: #0089c7;
        border-radius: 2px;
    }

    /* Update tables */
    .animals-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 30px 0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .animals-table th {
        background: linear-gradient(135deg, #0089c7, #006da0);
        color: white;
        padding: 20px;
        font-size: 18px;
    }

    .animals-table td {
        padding: 18px;
        border-bottom: 1px solid #eee;
        font-size: 16px;
    }

    .animals-table tr:last-child td {
        border-bottom: none;
    }

    .animals-table tr:hover {
        background-color: #f8f9fa;
    }

    /* Update expense form */
    .expense-form {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 15px;
        margin: 30px 0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        color: #0089c7;
        font-size: 18px;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus {
        border-color: #0089c7;
        outline: none;
    }

    .submit-btn, .delete-btn {
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .submit-btn {
        background: linear-gradient(135deg, #0089c7, #006da0);
        color: white;
        border: none;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,137,199,0.2);
    }

    .delete-btn {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
    }

    .delete-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220,53,69,0.2);
    }

    .progress-bar {
        height: 25px;
        background: #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
    }

    .progress {
        height: 100%;
        background: linear-gradient(135deg, #28a745, #218838);
        border-radius: 12px;
        transition: width 0.5s ease;
    }

    /* Add responsive design */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
        
        .summary-cards {
            grid-template-columns: 1fr;
        }
        
        .animals-table {
            font-size: 14px;
        }
        
        .animals-table th, .animals-table td {
            padding: 12px;
        }
    }
</style>

// Add this after the existing report form
<div class="expense-section">
    <h2 style="color: #0089c7;">مکمل مالی رپورٹ</h2>
    <form action="export_financial_report.php" method="GET" class="expense-form" target="_blank">
        <div class="form-group" style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label>شروع تاریخ</label>
                <input type="date" name="start_date" required value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div style="flex: 1;">
                <label>آخری تاریخ</label>
                <input type="date" name="end_date" required value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
        <button type="submit" class="submit-btn">ایکسل رپورٹ ڈاؤنلوڈ کریں</button>
    </form>
</div>

// Add this in the <head> section after other styles
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Add graphs section -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 40px;">
    <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h2 style="color: #0089c7; text-align: center;">مالی رپورٹ</h2>
        <canvas id="financialChart"></canvas>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h2 style="color: #0089c7; text-align: center;">جانوروں کی تفصیلات</h2>
        <canvas id="animalsChart"></canvas>
    </div>
</div>

<script>
// Get financial data
<?php
$sql_financial = "SELECT 
    'آمدنی' as type, SUM(hissa_count * amount) as total
    FROM qurbani_entries
UNION ALL
    SELECT 'اخراجات' as type, SUM(amount) as total
    FROM expenses";

$result_financial = mysqli_query($conn, $sql_financial);
$financial_data = [];
$financial_labels = [];

while($row = mysqli_fetch_assoc($result_financial)) {
    $financial_labels[] = $row['type'];
    $financial_data[] = intval($row['total']);
}

// Get animals data
$sql_animals = "SELECT animal_number, SUM(hissa_count) as total_hissa 
                FROM qurbani_entries 
                GROUP BY animal_number 
                ORDER BY animal_number";
$result_animals = mysqli_query($conn, $sql_animals);

$animal_labels = [];
$animal_data = [];

while($row = mysqli_fetch_assoc($result_animals)) {
    $animal_labels[] = 'جانور نمبر ' . $row['animal_number'];
    $animal_data[] = intval($row['total_hissa']);
}
?>

// Financial Chart
new Chart(document.getElementById('financialChart'), {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($financial_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($financial_data); ?>,
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderColor: [
                'rgba(40, 167, 69, 1)',
                'rgba(220, 53, 69, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        family: 'Jameel Noori Nastaleeq',
                        size: 16
                    },
                    padding: 20
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += new Intl.NumberFormat('ur-PK').format(context.raw) + ' روپے';
                        return label;
                    }
                }
            }
        }
    }
});

// Animals Chart
new Chart(document.getElementById('animalsChart'), {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($animal_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($animal_data); ?>,
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(0, 123, 255, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(111, 66, 193, 0.8)',
                'rgba(23, 162, 184, 0.8)',
                'rgba(102, 16, 242, 0.8)',
                'rgba(0, 182, 122, 0.8)',
                'rgba(108, 117, 125, 0.8)',
                'rgba(253, 126, 20, 0.8)',
                'rgba(32, 201, 151, 0.8)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        family: 'Jameel Noori Nastaleeq',
                        size: 16
                    },
                    padding: 20
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += context.raw + ' حصے';
                        return label;
                    }
                }
            }
        }
    }
});
</script>
