<?php
include '../nav/header.php';

$role = $_SESSION['position'];
$subpage = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';
$conn = mysqli_connect("localhost", "root", "", "barangay_system");

if (!$conn) {
    die("Database connection failed" . mysqli_connect_error());
}

$termQuery = mysqli_query($conn, "SELECT * FROM term WHERE status='Current' LIMIT 1");
$currentTerm = mysqli_fetch_assoc($termQuery);

if (!$currentTerm) {
    die("No active term found.");
}

$start = $currentTerm['start_year'];
$end = $currentTerm['end_year'];

// 🔥 FUNCTION: GET USED PER FUND
function getFundUsed($conn, $fund_id, $start, $end)
{
    $sql = "
        SELECT SUM(program_budget) AS total_used
        FROM program
        WHERE program_status = 'Approve'
        AND fund_id = '$fund_id'
        AND program_date_created BETWEEN '$start' AND '$end'
    ";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total_used'] ?? 0;
}

// 🔥 GET ALL FUNDS
$result = mysqli_query($conn, "
    SELECT * 
    FROM sk_funds 
    WHERE date_released BETWEEN '$start' AND '$end'
");

$totalFunds = 0;
$totalUsed = 0;
$data = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {

        $used = getFundUsed($conn, $row['fund_id'], $start, $end);

        $totalFunds += $row['allocated_amount'];
        $totalUsed += $used;

        $row['used'] = $used;
        $data[] = $row;
    }
}

$remainingTotal = $totalFunds - $totalUsed;

$labels = [];
$data = [];

$result = mysqli_query($conn, "
    SELECT * 
    FROM sk_funds
    WHERE date_released BETWEEN '$start' AND '$end'
");

while ($row = mysqli_fetch_assoc($result)) {

    $fundId = $row['fund_id'];
    $fundName = $row['fund_name']; // adjust if needed

    // GET USED AMOUNT
    $used = getFundUsed($conn, $fundId, $start, $end);

    $labels[] = $fundName;
    $data[] = (float)$used;
}
?>

<?php if ($subpage == "dashboard") { ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-blue">SK Council Portal</h2>
                <p class="text-muted">Empowering youth programs and monitoring SK funds.</p>
            </div>
            <button class="btn btn-primary-custom"><i class="fas fa-calendar-plus me-2"></i> New Youth Event</button>
        </div>
        <!-- SUMMARY CARDS -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card card-stats shadow-sm p-3">
                    <div class="card-body">
                        <h6>Total Funds</h6>
                        <h4>₱ <?= number_format($totalFunds, 2) ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-stats shadow-sm p-3">
                    <div class="card-body">
                        <h6>Total Used</h6>
                        <h4>₱ <?= number_format($totalUsed, 2) ?></h4>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-stats shadow-sm p-3">
                    <div class="card-body">
                        <h6>Remaining Balance</h6>
                        <h4>₱ <?= number_format($remainingTotal, 2) ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Fund Usage (Per Fund)</h5>
            </div>

            <div class="card-body d-flex justify-content-center">
                <div style="width: 1000px; height: 600px;">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const labels = <?= json_encode($labels) ?>;
                const data = <?= json_encode($data) ?>;

                const canvas = document.getElementById('pieChart');

                if (!canvas) return;

                new Chart(canvas.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // 🔥 allows custom height/width
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

            });
        </script>
    <?php } elseif ($subpage == "program") { ?>
        <?php
        $termQuery = mysqli_query($conn, "SELECT * FROM term WHERE status='Current' LIMIT 1");
        $currentTerm = mysqli_fetch_assoc($termQuery);

        // ✅ SAFETY CHECK
        if (!$currentTerm) {
            die("No active term found.");
        }

        $start = $currentTerm['start_year'];
        $end   = $currentTerm['end_year'];

        // ✅ FORMAT DISPLAY DATES
        $startDate = !empty($start)
            ? date('F d, Y', strtotime($start))
            : 'N/A';

        $endDate = !empty($end)
            ? date('F d, Y', strtotime($end))
            : 'N/A';

        $termRange = $startDate . " - " . $endDate;

        // Get concerns created in the same year as current term
        $result = mysqli_query($conn, "
            SELECT * 
            FROM program 
            WHERE program_date_created BETWEEN '{$currentTerm['start_year']}' 
            AND '{$currentTerm['end_year']}'
            ORDER BY program_id DESC
        ");
        ?>
        <div class="containers mt-4">
            <!-- PAGE HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="fw-bold">Youth Programs</h3>
                    <p class="text-muted mb-0">Manage youth activities, events, and projects</p>
                </div>
            </div>
            <hr>
            <div class="container mt-4">
                <div class="card-box" style="width: 1500px;">
                    <!-- HEADER -->
                    <div class="d-flex justify-content-between mb-3">
                        <h4>Youth Program</h4>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProgram">
                            <i class="bi bi-plus-circle"></i> Add Program
                        </button>
                    </div>
                    <hr>
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
                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light" style="text-align: center;">
                                <tr>
                                    <th>#</th>
                                    <th>Program Name</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Budget</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): $counter = 1; ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr style="text-align: center;">
                                            <td><?= $counter++ ?></td>
                                            <td><b><?= $row['program_name'] ?></b></td>
                                            <td><?= $row['program_description'] ?></td>
                                            <td><?= date('F d, Y', strtotime($row['program_date'])) ?></td>
                                            <td>₱<?= number_format($row['program_budget'], 2) ?></td>
                                            <td style="width: 100px;">
                                                <span class="badge 
                                        <?= $row['program_status'] == 'Approve' ? 'bg-success' : ($row['program_status'] == 'Pending' ? 'bg-warning' : 'bg-danger') ?>">
                                                    <?= $row['program_status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <!-- <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#views"><i class="fas fa-eye"></i> View</button> -->
                                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#updatePrograms<?= $row['program_id'] ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </button>
                                                <a href="../page/sk_kagawad.php?function=deleteProgram&id=<?= $row['program_id'] ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this concern?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <!-- UPDATE PROGRAM -->
                                        <div class="modal fade" id="updatePrograms<?= $row['program_id'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5>Update Status</h5>
                                                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="../page/sk_kagawad.php?function=updatePrograms" method="POST">
                                                        <div class="modal-body">
                                                            <!-- PASS ID -->
                                                            <input type="hidden" name="id" value="<?= $row['program_id'] ?>">
                                                            <label>Status</label>
                                                            <select name="program_status" class="form-control" required>
                                                                <option value="Pending">Pending</option>
                                                                <option value="Approve">Approve</option>
                                                                <option value="Decline">Decline</option>
                                                            </select>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-success">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center;">
                                            No programs found for the current term (<?= $termRange ?>).
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } elseif ($subpage == "funds") { ?>
            <?php
            $termQuery = mysqli_query($conn, "SELECT * FROM term WHERE status='Current' LIMIT 1");
            $currentTerm = mysqli_fetch_assoc($termQuery);

            if (!$currentTerm) {
                die("No active term found.");
            }

            $start = $currentTerm['start_year'];
            $end = $currentTerm['end_year'];

            $termRange = $start . " - " . $end;

            $result = mysqli_query($conn, "
            SELECT * 
            FROM sk_funds 
            WHERE date_released BETWEEN '{$currentTerm['start_year']}' 
            AND '{$currentTerm['end_year']}'
            ORDER BY fund_id DESC
        ");

            $data = [];

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
            ?>
            <div class="containers mt-4">
                <!-- PAGE HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="fw-bold">SK Funds Tracking</h3>
                        <p class="text-muted mb-0">Monitor youth funds, expenses, and remaining balance</p>
                    </div>
                </div>
                <hr>
                <div class="container mt-4">
                    <div class="card-box" style="width: 1500px;">
                        <!-- HEADER -->
                        <div class="d-flex justify-content-between mb-3">
                            <h4>Funds Tracking</h4>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFund">
                                <i class="bi bi-plus-circle"></i> Add Funds
                            </button>
                        </div>
                        <hr>
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
                        <!-- TABLE -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light" style="text-align: center;">
                                    <tr>
                                        <th>#</th>
                                        <th>Fund Name</th>
                                        <th>Allocated</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data)): ?>
                                        <?php $i = 1;
                                        foreach ($data as $row): ?>
                                            <tr style="text-align: center;">
                                                <td><?= $i++ ?></td>
                                                <td><b><?= $row['fund_name'] ?></b></td>
                                                <td>₱<?= number_format($row['allocated_amount'], 2) ?></td>
                                                <td><?= date('F d, Y', strtotime($row['date_released'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4">
                                                No fund records found for the current term (<?= $termRange ?>).
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            <?php } elseif ($subpage == "report") { ?>
                <?php
                $termQuery = mysqli_query($conn, "SELECT * FROM term WHERE status='Current' LIMIT 1");
                $currentTerm = mysqli_fetch_assoc($termQuery);

                // ✅ SAFETY CHECK
                if (!$currentTerm) {
                    die("No active term found.");
                }

                $start = $currentTerm['start_year'];
                $end   = $currentTerm['end_year'];

                // ✅ FORMAT DISPLAY DATES
                $startDate = !empty($start)
                    ? date('F d, Y', strtotime($start))
                    : 'N/A';

                $endDate = !empty($end)
                    ? date('F d, Y', strtotime($end))
                    : 'N/A';

                $termRange = $startDate . " - " . $endDate;

                // ✅ FIXED QUERY (TERM FILTER ADDED)
                $result = mysqli_query($conn, "
                SELECT * 
                    FROM program 
                    WHERE program_status = 'Approve'
                    AND program_date_created BETWEEN '{$currentTerm['start_year']}' 
                    AND '{$currentTerm['end_year']}'
                    ORDER BY program_id DESC
                ");
                ?>
                <!-- report -->
                <div class="containers mt-4">
                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Reports</h3>
                    </div>
                    <hr>
                    <!-- PROGRAM TABLE -->
                    <div class="container mt-4">
                        <div class="card-box mb-4" style="width: 1500px;">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>📅Youth Program Reports</h5>
                            </div>
                            <hr>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Project Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($result) > 0): $counter = 1; ?>
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?= $counter++ ?></td>
                                                <td><strong><?= htmlspecialchars($row['program_name']) ?></strong></td>
                                                <td><span class="badge bg-success"><?= htmlspecialchars($row['program_status']) ?></span></td>
                                            </tr>
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

            <?php } ?>
            <?php include '../nav/footer.php'; ?>