<?php
include '../nav/header.php';

$role = $_SESSION['position'];
$subpage = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';

$conn = mysqli_connect("localhost", "root", "", "barangay_system");

if (!$conn) {
    die("Database connection failed" . mysqli_connect_error());
}
?>

<?php if ($subpage == "dashboard") { ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-blue">Financial Administration</h2>
                <p class="text-muted">Treasury monitoring and budget allocation.</p>
            </div>
            <button class="btn btn-primary-custom"><i class="fas fa-plus-circle me-2"></i> Record Transaction</button>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-4 mb-4" style="background-color: #003366; color: white;">
                    <h6>Remaining Barangay Budget</h6>
                    <h2 class="display-5 fw-bold">₱2,840,150.00</h2>
                    <div class="d-flex justify-content-between mt-3">
                        <small>FY 2023 - 2024</small>
                        <small>75% Remaining</small>
                    </div>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: 75%"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm border-0 p-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Expenses (MTD)</span>
                                <span class="text-danger fw-bold">₱42,000</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card shadow-sm border-0 p-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Collections (MTD)</span>
                                <span class="text-success fw-bold">₱85,300</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="text-blue">Recent Financial Ledgers</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ref ID</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#TRX-9921</td>
                            <td>Office Supplies</td>
                            <td>Paper, Toner for Office</td>
                            <td>₱4,500.00</td>
                            <td><span class="text-danger">Expense</span></td>
                        </tr>
                        <tr>
                            <td>#TRX-9922</td>
                            <td>Clearance Fees</td>
                            <td>Daily Collection</td>
                            <td>₱1,200.00</td>
                            <td><span class="text-success">Income</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php } elseif ($subpage == "finance") { ?>
    <div class="containers mt-4">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Financial</h3>
            <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResident">
                + Add Resident
            </button> -->
        </div>
        <hr>
        <div class="container mt-4">
            <div class="card-box" style="width: 1500px;">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM budget ORDER BY budget_id DESC");
                if (mysqli_num_rows($result) > 0)
                    $counter = 1;
                ?>
                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>💰 Financial Reports</h4>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBudget">
                        + Add Budget
                    </button>
                </div>
                <hr>
                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No.</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Amount (₱)</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr class="text-center">
                                    <td><?= $counter++ ?></td>
                                    <td><?= $row['budget_date'] ?></td>
                                    <td><?= $row['budget_payee'] ?></td>
                                    <td><?= $row['budget_particulars'] ?></td>
                                    <td>₱<?= $row['budget_amount'] ?></td>
                                    <td><span class="badge bg-warning"><?= $row['budget_status'] ?></span></td>
                                    <td>
                                        <a href="../page/treasurer.php?function=viewBudget&id=<?= $row['budget_id'] ?>" class="btn btn-sm btn-primary">View</a>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#updateStatus<?= $row['budget_id'] ?>">
                                            Update
                                        </button>
                                        <a href="?delete=<?= $i ?>" class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                                <!-- UPDATE BUDGET -->
                                <div class="modal fade" id="updateStatus<?= $row['budget_id'] ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5>Update Status</h5>
                                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="../page/treasurer.php?function=updateStatus" method="POST">
                                                <div class="modal-body">
                                                    <!-- PASS ID -->
                                                    <input type="hidden" name="id" value="<?= $row['budget_id'] ?>">
                                                    <label>Status</label>
                                                    <select name="budget_status" class="form-control" required>
                                                        <option value="Pending">Pending</option>
                                                        <option value="Approve">Approve</option>
                                                        <option value="Decline">Decline</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-info">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
// ✅ Fetch all projects for the dropdown
$conn = new PDO("mysql:host=localhost;dbname=barangay_system", "root", "");
$projects = $conn->query("SELECT project_id, project_name FROM project")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ADD BUDGET -->
<div class="modal fade" id="addBudget" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5>Add Budget</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../page/treasurer.php?function=addBudget" method="POST">
                <div class="modal-body">
                    <!-- ✅ Project Dropdown -->
                    <label>Project</label>
                    <select name="project_id" class="form-control" required>
                        <option value="">Select Project</option>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= $project['project_id']; ?>">
                                <?= $project['project_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Budget Date</label>
                    <input type="date" name="budget_date" class="form-control">

                    <label>Payee</label>
                    <input type="text" name="budget_payee" class="form-control">

                    <label>Amount</label>
                    <input type="number" name="budget_amount" class="form-control">

                    <label>Employee No</label>
                    <input type="text" name="budget_employee_no" class="form-control">

                    <label>Fund</label>
                    <input type="text" name="budget_fund" class="form-control">

                    <label>TIN</label>
                    <input type="text" name="budget_tin" class="form-control">

                    <label>Particulars</label>
                    <input type="text" name="budget_particulars" class="form-control">

                    <label>Certifier A Name</label>
                    <input type="text" name="budget_cert_a_name" class="form-control">
                    <label>Certifier A Date</label>
                    <input type="date" name="budget_cert_a_date" class="form-control">

                    <label>Certifier B Name</label>
                    <input type="text" name="budget_cert_b_name" class="form-control">
                    <label>Certifier B Date</label>
                    <input type="date" name="budget_cert_date" class="form-control">

                    <label>Certifier C Name</label>
                    <input type="text" name="budget_cert_c_name" class="form-control">
                    <label>Certifier C Date</label>
                    <input type="date" name="budget_cert_cdate" class="form-control">

                    <label>Account</label>
                    <input type="text" name="budget_account" class="form-control">
                    <label>Account Code</label>
                    <input type="text" name="budget_account_code" class="form-control">

                    <label>Debit</label>
                    <input type="number" name="budget_debit" class="form-control">
                    <label>Credit</label>
                    <input type="number" name="budget_credit" class="form-control">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <?php } ?>
    <?php include '../nav/footer.php'; ?>