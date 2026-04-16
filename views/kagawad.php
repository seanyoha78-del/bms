<?php
include '../nav/header.php';
?>
<?php
$role = $_SESSION['position'];
$subpage = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';

$conn = mysqli_connect("localhost", "root", "", "barangay_system");

if (!$conn) {
    die("Database connection failed" . mysqli_connect_error());
}

$termQuery = mysqli_query($conn, "SELECT * FROM term WHERE status='Current' LIMIT 1");
$currentTerm = mysqli_fetch_assoc($termQuery);

$start = $currentTerm['start_year'];
$end   = $currentTerm['end_year'];
// Blotter count
$concernQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM concern 
    WHERE date_created BETWEEN '$start' AND '$end'
");
$concernCount = mysqli_fetch_assoc($concernQuery)['total'];

$reportQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM project 
    WHERE status = 'Approve'
    AND created_at BETWEEN '$start' AND '$end'
");
$reportCount = mysqli_fetch_assoc($reportQuery)['total'];

$pendingQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM project 
    WHERE status = 'Pending'
    AND committees = '$role'
    AND created_at BETWEEN '$start' AND '$end'
");
$pendingCount = mysqli_fetch_assoc($pendingQuery)['total'];

$query = mysqli_query($conn, "
    SELECT 
        DAYNAME(date_created) as day,
        COUNT(*) as total
    FROM concern
    WHERE date_created BETWEEN '$start' AND '$end'
    GROUP BY DAYOFWEEK(date_created)
");

// Ensure all days exist
$days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
$dataMap = array_fill_keys($days, 0);

while ($row = mysqli_fetch_assoc($query)) {
    $dataMap[$row['day']] = $row['total'];
}

?>

<?php if ($subpage == "dashboard") { ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-blue">Kagawad Dashboard</h2>
                <p class="text-muted">Community concerns and committee management.</p>
            </div>
            <!-- <div class="btn-group">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addProgram"><i class="fas fa-plus me-2"></i> Program</button>
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#dsa"><i class="fas fa-plus me-2"></i> Reports</button>
            </div> -->
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card card-stats shadow-sm p-3 text-center">
                    <i class="bi bi-chat-dots-fill fa-2x text-blue mb-2"></i>
                    <h4 class="mb-0"><?= $concernCount ?></h4>
                    <small class="text-muted">Resident Concern</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stats shadow-sm p-3 text-center">
                    <i class="bi bi-flag-fill fa-2x text-blue mb-2"></i>
                    <h4 class="mb-0"><?= $reportCount ?></h4>
                    <small class="text-muted">Reports</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stats shadow-sm p-3 text-center">
                    <i class="bi bi-cast fa-2x text-blue mb-2"></i>
                    <h4 class="mb-0"><?= $pendingCount ?></h4>
                    <small class="text-muted">Pending Projects</small>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Concerns Overview</h5>
                <small class="text-muted">
                    <?= date('F d, Y', strtotime($start)) ?> -
                    <?= date('F d, Y', strtotime($end)) ?>
                </small>
            </div>

            <div class="card-body">
                <canvas id="concernChart" height="100"></canvas>
            </div>
        </div>
        

    <?php } elseif ($subpage == "concern") { ?>
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
            FROM concern 
            WHERE concern_status = 'Pending'
            AND date_created BETWEEN '{$currentTerm['start_year']}' 
            AND '{$currentTerm['end_year']}'
            ORDER BY concern_id DESC
        ");
        ?>
        <div class="containers mt-4">
            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Concern</h3>
                <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResident">
                + Add Resident
            </button> -->
            </div>
            <hr>
            <!-- COMMUNITY CONCERNS -->
            <div class="container mt-4">
                <div class="card-box mb-4" style="width: 1500px;">
                    <div class="d-flex justify-content-between mb-3">
                        <h5> 🗣️ Community Concerns</h5>
                        <?php if ($_SESSION['position'] == 'Secretary'): ?>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addConcern">
                                + Add Concern
                            </button>
                        <?php endif; ?>
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
                    <table class="table table-bordered text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Concern</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): $counter = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td style="width: 200px;"><strong><?= $row['concern_name'] ?></strong></td>
                                        <td><?= $row['concern_description'] ?></td>
                                        <td style="width: 200px;">
                                            <span class="badge 
                                        <?= $row['concern_status'] == 'Approve' ? 'bg-success' : ($row['concern_status'] == 'Pending' ? 'bg-warning' : 'bg-danger') ?>">
                                                <?= $row['concern_status'] ?>
                                            </span>
                                        </td>
                                        <td style="width: 300px;">
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#updateStatuses<?= $row['concern_id'] ?>">
                                                <i class="fas fa-edit"></i> Update
                                            </button>
                                            <a href="../page/kagawad.php?function=deleteConcern&id=<?= $row['concern_id'] ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this concern?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- UPDATE CONCERN -->
                                    <div class="modal fade" id="updateStatuses<?= $row['concern_id'] ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5>Update Status</h5>
                                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="../page/kagawad.php?function=updateStatuses" method="POST">
                                                    <div class="modal-body">
                                                        <!-- PASS ID -->
                                                        <input type="hidden" name="id" value="<?= $row['concern_id'] ?>">
                                                        <label>Status</label>
                                                        <select name="concern_status" class="form-control" required>
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
    <?php } elseif ($subpage == "reports") { ?>
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
        SELECT 
            p.project_name,
            c.concern_name,
            p.status AS project_status
        FROM project p
        LEFT JOIN concern c 
            ON p.concern_id = c.concern_id
        WHERE p.status = 'Approve'
        AND p.created_at BETWEEN '$start' AND '$end'
        ORDER BY p.project_id ASC
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
                        <h5>📅 Reports</h5>
                        <input type="text" class="form-control w-25" placeholder="Search...">
                    </div>
                    <hr>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Concern</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): $counter = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><strong><?= htmlspecialchars($row['project_name']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['concern_name'] ?? 'N/A') ?></td>
                                        <td><span class="badge bg-success"><?= htmlspecialchars($row['project_status']) ?></span></td>
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

    <?php } elseif ($subpage == "project") { ?>
        <?php
        $termQuery = mysqli_query($conn, "SELECT * FROM term WHERE status='Current' LIMIT 1");
        $currentTerm = mysqli_fetch_assoc($termQuery);

        // format dates
        $startDate = !empty($currentTerm['start_year'])
            ? date('F d, Y', strtotime($currentTerm['start_year']))
            : 'N/A';

        $endDate = !empty($currentTerm['end_year'])
            ? date('F d, Y', strtotime($currentTerm['end_year']))
            : 'N/A';

        $termRange = $startDate . " - " . $endDate;
        ?>

        <div class="containers mt-4">
            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Project</h3>
                <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResident">
                + Add Resident
            </button> -->
            </div>
            <hr>
            <!-- Project TABLE -->
            <div class="container mt-4">
                <div class="card-box mb-4" style="width: 1500px;">
                    <?php
                    $role = $_SESSION['position'];

                    if ($role == "Captain") {

                        // Captain sees ALL projects
                        $result = mysqli_query($conn, "
                        SELECT *
                        FROM project
                        WHERE committees = '$role'
                        AND created_at BETWEEN '{$currentTerm['start_year']}'
                        AND '{$currentTerm['end_year']}'
                        ORDER BY project_id DESC
                    ");
                    } else {

                        // Others see ONLY THEIR committee
                        $result = mysqli_query($conn, "
                        SELECT *
                        FROM project
                        WHERE committees = '$role'
                        AND created_at BETWEEN '{$currentTerm['start_year']}'
                        AND '{$currentTerm['end_year']}'
                        ORDER BY project_id DESC
                    ");
                    }
                    ?>
                    <div class="d-flex justify-content-between mb-3">
                        <h5>📅 Projects</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProject">
                            + Add Project
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
                    <table class="table table-bordered table-hover text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Project</th>
                                <th>Description</th>
                                <th>Material</th>
                                <th>Budget</th>
                                <th>Labor</th>
                                <th>Start Date</th>
                                <th>Status</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): $counter = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td style="width: 50px;"><?= $counter++ ?></td>
                                        <td style="width: 200px;"><strong><?= $row['project_name'] ?></strong></td>
                                        <td style="width: 500px;"><?= $row['description'] ?></td>
                                        <td style="width: 90px;">₱ <?= number_format($row['budget'], 2) ?></td>
                                        <td style="width: 90px;">₱ <?= number_format($row['labor_cost'], 2) ?></td>
                                        <td style="width: 90px;">₱ <?= number_format($row['material_cost'], 2) ?></td>
                                        <td><?= date("F d, Y", strtotime($row['start_date'])) ?></td>
                                        <td><span class="badge 
                                            <?= $row['status'] == 'Approve' ? 'bg-success' : ($row['status'] == 'Pending' ? 'bg-warning' : 'bg-danger') ?>">
                                                <?= $row['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#updateProgram<?= $row['project_id'] ?>">
                                                <i class="fas fa-edit"></i> Update
                                            </button>
                                            <a href="../page/kagawad.php?function=deleteProgram&id=<?= $row['project_id'] ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this program?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- UPDATE PROGRAM -->
                                    <div class="modal fade" id="updateProgram<?= $row['project_id'] ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5>Update Status</h5>
                                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="../page/kagawad.php?function=updateProgram" method="POST">
                                                    <div class="modal-body">
                                                        <!-- PASS ID -->
                                                        <input type="hidden" name="id" value="<?= $row['project_id'] ?>">
                                                        <label>Status</label>
                                                        <select name="status" class="form-control" required>
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
        <?php } ?>
        <?php include '../nav/footer.php'; ?>