<?php include '../nav/header.php' ?>
<?php
// CONNECT DATABASE (if not yet included)
$conn = mysqli_connect("localhost", "root", "", "barangay_system");
?>

<div class="containers mt-4">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Budget</h3>
            <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResident">
                + Add Resident
            </button> -->
        </div>
        <hr>
        <div class="container mt-4">
            <div class="card-box" style="width: 1500px;">
                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>📊 Budget Management</h4>
                    <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBudget">
                        + Add Budget
                    </button> -->
                </div>
                <hr>
                <?php if (!empty($budget)): ?>
                    <div class="voucher">
                        <h4>DISBURSEMENT VOUCHER</h4>
                        <table class="table table-bordereds">
                            <tr>
                                <td>Barangay: <b>HINDANG</b></td>
                                <td>Municipality: <b>JULITA</b></td>
                                <td>Province: <b>LEYTE</b></td>
                                <td>DV No:</td>
                            </tr>
                            <tr>
                                <td>Payee/Office: <b><?= htmlspecialchars($budget['budget_payee']) ?></b></td>
                                <td>Employee No:<?= htmlspecialchars($budget['budget_employee_no']) ?></td>
                                <td>Date: <b><?= htmlspecialchars($budget['budget_date']) ?></b></td>
                                <td>Fund: <b><?= htmlspecialchars($budget['budget_fund']) ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="2">Address: <b>BRGY. HINDANG</b></td>
                                <td>TIN: <b><?= htmlspecialchars($budget['budget_tin']) ?></b></td>
                                <td>Amount: <b>₱ <?= number_format($budget['budget_amount'], 2) ?></b></td>
                            </tr>
                        </table>
                        <table class="table table-bordereds">
                            <tr>
                                <th>PARTICULARS</th>
                                <th width="200">Amount</th>
                            </tr>
                            <tr>
                                <td><?= htmlspecialchars($budget['budget_particulars']) ?></td>
                                <td>₱ <?= number_format($budget['budget_amount'], 2) ?></td>
                            </tr>
                            <tr>
                                <td>Amount in words: <b></b></td>
                                <td></td>
                            </tr>
                        </table>
                        <table class="table table-bordereds text-center small-text">
                            <tr>
                                <td>
                                    Certified:<br>
                                    Existence of Available appropriations<br><br>
                                    <div class="signature"></div>
                                    <b><?= htmlspecialchars($budget['budget_cert_a_name']) ?></b><br>
                                    Chair, Committee on Appropriations<br>
                                    Date: <?= htmlspecialchars($budget['budget_cert_a_date']) ?>
                                </td>
                                <td>
                                    Certified:<br>
                                    Funds (Cash) available<br><br>
                                    <div class="signature"></div>
                                    <b><?= htmlspecialchars($budget['budget_cert_b_name']) ?></b><br>
                                    Barangay Treasurer<br>
                                    Date: <?= htmlspecialchars($budget['budget_cert_date']) ?>
                                </td>
                                <td>
                                    Certified:<br>
                                    Approved for Payment<br><br>
                                    <div class="signature"></div>
                                    <b><?= htmlspecialchars($budget['budget_cert_c_name']) ?></b><br>
                                    Punong Barangay<br>
                                    Date: <?= htmlspecialchars($budget['budget_cert_cdate']) ?>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordereds small-text">
                            <tr>
                                <th colspan="4">Accounting Entries</th>
                            </tr>
                            <tr>
                                <th>Account</th>
                                <th>Code</th>
                                <th>Debit</th>
                                <th>Credit</th>
                            </tr>
                            <tr>
                                <td>ADVANCES</td>
                                <td><?= htmlspecialchars($budget['budget_account_code']) ?></td>
                                <td><?= number_format($budget['budget_debit'], 2) ?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Cash in Bank - LCCA</td>
                                <td>1-01-02-010</td>
                                <td></td>
                                <td><?= number_format($budget['budget_credit'], 2) ?></td>
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

<?php include '../nav/footer.php' ?>