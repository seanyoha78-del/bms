<?php
include '../nav/header.php';

$role = $_SESSION['position'];
$subpage = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';

$conn = mysqli_connect("localhost", "root", "", "barangay_system");

if (!$conn) {
    die("Database connection failed" . mysqli_connect_error());
}

// TERM
$termQuery = mysqli_query($conn, "SELECT * FROM term WHERE status='Current' LIMIT 1");
$currentTerm = mysqli_fetch_assoc($termQuery);

if (!$currentTerm) {
    die("No active term found.");
}

$start = $currentTerm['start_year'];
$end = $currentTerm['end_year'];
// TOTAL BUDGET (ALL APPROVED PROJECTS)
$totalBudgetQuery = mysqli_query($conn, "
    SELECT SUM(budget) as total 
    FROM project
    WHERE status = 'Approve'
    AND created_at BETWEEN '$start' AND '$end'
");
$totalBudget = mysqli_fetch_assoc($totalBudgetQuery)['total'] ?? 0;


// TOTAL RELEASED (FROM BUDGET TABLE)
$totalReleasedQuery = mysqli_query($conn, "
    SELECT SUM(budget_amount) as total 
    FROM budget
    WHERE budget_status = 'Approve'
    AND budget_created_date BETWEEN '$start' AND '$end'
");
$totalReleased = mysqli_fetch_assoc($totalReleasedQuery)['total'] ?? 0;

// REMAINING BUDGET
$remainingBudget = $totalBudget - $totalReleased;

// CHART: Budget Released per Month (FILTERED BY CURRENT TERM)
// $chartData = [];
// DEFAULT: ALL MONTHS (0 VALUES)
$months = [
    'Jan' => 0,
    'Feb' => 0,
    'Mar' => 0,
    'Apr' => 0,
    'May' => 0,
    'Jun' => 0,
    'Jul' => 0,
    'Aug' => 0,
    'Sep' => 0,
    'Oct' => 0,
    'Nov' => 0,
    'Dec' => 0
];

// FETCH DATA (FILTERED BY TERM)
$query = mysqli_query($conn, "
    SELECT 
        DATE_FORMAT(budget_created_date, '%b') AS month,
        SUM(budget_amount) AS total
    FROM budget
    WHERE budget_status = 'Approve'
    AND budget_created_date BETWEEN '$start' AND '$end'
    GROUP BY MONTH(budget_created_date)
");

while ($row = mysqli_fetch_assoc($query)) {
    $months[$row['month']] = (float)$row['total'];
}

// FINAL DATA FOR CHART
$labels = array_keys($months);
$data = array_values($months);
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

            <!-- TOTAL BUDGET -->
            <div class="col-md-4">
                <div class="card shadow-sm p-4 bg-primary text-white">
                    <h6>Overall Budget</h6>
                    <h3>₱<?= number_format($totalBudget, 2) ?></h3>
                </div>
            </div>

            <!-- RELEASED -->
            <div class="col-md-4">
                <div class="card shadow-sm p-4 bg-danger text-white">
                    <h6>Released Budget</h6>
                    <h3>₱<?= number_format($totalReleased, 2) ?></h3>
                </div>
            </div>

            <!-- REMAINING -->
            <div class="col-md-4">
                <div class="card shadow-sm p-4 bg-success text-white">
                    <h6>Remaining Budget</h6>
                    <h3>₱<?= number_format($remainingBudget, 2) ?></h3>
                </div>
            </div>

        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Budget Release Overview</h5>
                <small class="text-muted">
                    <?= date('F d, Y', strtotime($start)) ?> -
                    <?= date('F d, Y', strtotime($end)) ?>
                </small>
            </div>

            <div class="card-body">
                <canvas id="budgetChart" height="100"></canvas>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const labels = <?= json_encode($labels) ?>;
                const data = <?= json_encode($data) ?>;

                console.log(labels, data); // 🔥 DEBUG

                const canvas = document.getElementById('budgetChart');

                if (!canvas) {
                    console.log("Chart canvas not found");
                    return;
                }

                new Chart(canvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Budget Released',
                            data: data,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

            });
        </script>

    <?php } elseif ($subpage == "finance") { ?>
        <?php
        $termQuery = mysqli_query($conn, "SELECT * FROM term WHERE status='Current' LIMIT 1");
        $currentTerm = mysqli_fetch_assoc($termQuery);

        // ✅ FORMAT START DATE
        $startDate = !empty($currentTerm['start_year'])
            ? date('F d, Y', strtotime($currentTerm['start_year']))
            : 'N/A';

        // ✅ FORMAT END DATE
        $endDate = !empty($currentTerm['end_year'])
            ? date('F d, Y', strtotime($currentTerm['end_year']))
            : 'N/A';

        // ✅ FINAL DISPLAY
        $termRange = $startDate . " - " . $endDate;

        // Get concerns created in the same year as current term
        $result = mysqli_query($conn, "
        SELECT * 
        FROM budget 
        WHERE budget_created_date BETWEEN '{$currentTerm['start_year']}' 
        AND '{$currentTerm['end_year']}'
        ORDER BY budget_id DESC
    ");
        ?>
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
                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>💰 Financial Reports</h4>
                        <?php if ($_SESSION['position'] == 'Treasurer'): ?>

                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBudget">
                                + Add Budget
                            </button>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <!-- TABLE -->
                    <div class="table-responsive">
                        <?php if (!empty($_SESSION['message'])): ?>
                            <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
                                <?= $_SESSION['message'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>

                            <?php
                            unset($_SESSION['message']);
                            unset($_SESSION['msg_type']);
                            ?>
                        <?php endif; ?>
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
                                <?php if (mysqli_num_rows($result) > 0): $counter = 1; ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="text-center">
                                            <td><?= $counter++ ?></td>
                                            <td><?= $row['budget_date'] ?></td>
                                            <td><?= $row['budget_payee'] ?></td>
                                            <td><?= $row['budget_particulars'] ?></td>
                                            <td>₱<?= number_format($row['budget_amount'], 2) ?></td>
                                            <td><span class="badge 
                                            <?= $row['budget_status'] == 'Approve' ? 'bg-success' : ($row['budget_status'] == 'Pending' ? 'bg-warning' : 'bg-danger') ?>">
                                                    <?= $row['budget_status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="../page/treasurer.php?function=viewBudget&id=<?= $row['budget_id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> View</a>
                                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#updateStatus<?= $row['budget_id'] ?>">
                                                    <i class="fas fa-edit"></i>Update
                                                </button>
                                                <a href="../page/kagawad.php?function=deleteProgram&id=<?= $row['project_id'] ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this program?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
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
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7">
                                            No programs found for the current term (<?= $termRange ?>).
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
            // ✅ FETCH PROJECTS (ONLY PENDING)
            $project = mysqli_query($conn, "
                SELECT project_id, project_name 
                FROM project 
                WHERE status = 'Pending'
            ");
            ?>

            <!-- ADD BUDGET MODAL -->
            <div class="modal fade" id="addBudget" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- HEADER -->
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Add Budget</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- FORM -->
                        <form action="../page/treasurer.php?function=addBudget" method="POST">

                            <div class="modal-body">

                                <!-- PROJECT DROPDOWN -->
                                <label class="form-label">Project</label>
                                <select name="project_id" class="form-control mb-2" required>
                                    <option value="">Select Project</option>

                                    <?php
                                    if ($project && mysqli_num_rows($project) > 0) {
                                        while ($p = mysqli_fetch_assoc($project)) {
                                    ?>
                                            <option value="<?= $p['project_id']; ?>">
                                                <?= $p['project_name']; ?>
                                            </option>
                                    <?php
                                        }
                                    } else {
                                        echo '<option disabled>No pending projects</option>';
                                    }
                                    ?>
                                </select>

                                <!-- BUDGET FIELDS -->
                                <label>Budget Date</label>
                                <input type="date" name="budget_date" class="form-control mb-2">

                                <label>Payee</label>
                                <input type="text" name="budget_payee" class="form-control mb-2">

                                <label>Amount</label>
                                <input type="number" name="budget_amount" class="form-control mb-2">

                                <label>Employee No</label>
                                <input type="text" name="budget_employee_no" class="form-control mb-2">

                                <label>Fund</label>
                                <input type="text" name="budget_fund" class="form-control mb-2">

                                <label>TIN</label>
                                <input type="text" name="budget_tin" class="form-control mb-2">

                                <label>Particulars</label>
                                <input type="text" name="budget_particulars" class="form-control mb-2">

                                <!-- CERTIFICATION -->
                                <label>Certifier A Name</label>
                                <input type="text" name="budget_cert_a_name" class="form-control mb-2">
                                <label>Certifier A Date</label>
                                <input type="date" name="budget_cert_a_date" class="form-control mb-2">

                                <label>Certifier B Name</label>
                                <input type="text" name="budget_cert_b_name" class="form-control mb-2">
                                <label>Certifier B Date</label>
                                <input type="date" name="budget_cert_date" class="form-control mb-2">

                                <label>Certifier C Name</label>
                                <input type="text" name="budget_cert_c_name" class="form-control mb-2">
                                <label>Certifier C Date</label>
                                <input type="date" name="budget_cert_cdate" class="form-control mb-2">

                                <!-- ACCOUNT -->
                                <label>Account</label>
                                <input type="text" name="budget_account" class="form-control mb-2">

                                <label>Account Code</label>
                                <input type="text" name="budget_account_code" class="form-control mb-2">

                                <!-- DEBIT / CREDIT -->
                                <label>Debit</label>
                                <input type="number" name="budget_debit" class="form-control mb-2">

                                <label>Credit</label>
                                <input type="number" name="budget_credit" class="form-control mb-2">

                            </div>

                            <!-- FOOTER -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php include '../nav/footer.php'; ?>