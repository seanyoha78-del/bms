<?php
include '../nav/header.php';
?>
<?php
$role = $_SESSION['position'];
$subpage = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';

$conn = mysqli_connect("localhost", "root", "", "barangay_system");

if (!$conn) {
    die("Database connection failed" . mysqli_connect_error());
    $concerns = mysqli_query($conn, "SELECT concern_id, concern_name FROM concern");
}

?>

<?php if ($subpage == "dashboard") { ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-blue">Kagawad Dashboard</h2>
                <p class="text-muted">Community concerns and committee management.</p>
            </div>
            <div class="btn-group">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addProgram"><i class="fas fa-plus me-2"></i> Program</button>
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#dsa"><i class="fas fa-plus me-2"></i> Reports</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="text-blue mb-4">Tracked Community Concerns</h5>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <h6 class="mb-1">Clogged Drainage - Zone 4</h6>
                                    <small>Reported by: Juan Tamad | Oct 20, 2023</small>
                                </div>
                                <span class="badge bg-info">In Progress</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <h6 class="mb-1">Broken Street Lights - Zone 1</h6>
                                    <small>Reported by: Maria Leonora | Oct 18, 2023</small>
                                </div>
                                <span class="badge bg-danger">Critical</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 bg-blue text-white" style="background-color: #003366;">
                    <div class="card-body">
                        <h5>Committee Meetings</h5>
                        <p class="small">Next session: Tomorrow, 2:00 PM</p>
                        <hr>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle me-2"></i> Health & Wellness</li>
                            <li><i class="fas fa-check-circle me-2"></i> Peace & Order</li>
                            <li><i class="fas fa-circle me-2 opacity-50"></i> Infrastructure</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD PROGRAM -->
    <div class="modal fade" id="addProgram">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5>Add Program</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="../page/kagawad.php?function=addProgram" method="POST">
                    <div class="modal-body">
                        <label>Program Name</label>
                        <input type="text" name="project_name" class="form-control">

                        <label>Description</label>
                        <input type="text" name="description" class="form-control">

                        <!-- ✅ NEW: Concern Dropdown -->
                        <label>Concern</label>
                        <select name="concern_id" class="form-control">
                            <option value="">Select Concern</option>
                            <?php while ($row = mysqli_fetch_assoc($concerns)) { ?>
                                <option value="<?= $row['concern_id']; ?>">
                                    <?= $row['concern_name']; ?>
                                </option>
                            <?php } ?>
                        </select>

                        <label>Date</label>
                        <input type="date" name="start_date" class="form-control">

                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info">Save</button>
                    </div>
                </form>
            </div>
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
    WHERE date_created BETWEEN '{$currentTerm['start_year']}' 
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
                            <th>Status</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): $counter = 1; ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $counter++ ?></td>
                                    <td><?= $row['concern_name'] ?></td>
                                    <td>
                                        <span class="badge 
                                        <?= $row['concern_status'] == 'Approve' ? 'bg-success' : ($row['concern_status'] == 'Pending' ? 'bg-warning' : 'bg-danger') ?>">
                                            <?= $row['concern_status'] ?>
                                        </span>
                                    </td>
                                    <td style="width: 400px;">
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#updateStatuses<?= $row['concern_id'] ?>">
                                            <i class="fas fa-edit"></i> Update
                                        </button>
                                        <a href="../page/kagawad.php?function=deleteConcern&id=<?= $row['concern_id'] ?>"
                                            class="btn btn-sm btn-danger"
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
                <?php
                $result = mysqli_query($conn, "
                    SELECT 
                        p.project_name,
                        c.concern_name,
                        p.status AS project_status
                    FROM project p
                    LEFT JOIN concern c 
                        ON p.concern_id = c.concern_id  -- link via concern_id
                    WHERE p.status = 'Approve'
                    ORDER BY p.project_id ASC
                ");
                $counter = 1;
                ?>
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
                        <?php $counter = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><strong><?= htmlspecialchars($row['project_name']) ?></strong></td>
                                <td><?= htmlspecialchars($row['concern_name'] ?? 'N/A') ?></td>
                                <td><span class="badge bg-success"><?= htmlspecialchars($row['project_status']) ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php } elseif ($subpage == "program") { ?>
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
    FROM project 
    WHERE created_at BETWEEN '{$currentTerm['start_year']}' 
    AND '{$currentTerm['end_year']}'
    ORDER BY project_id DESC
");
    ?>
    <div class="containers mt-4">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Program</h3>
            <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResident">
                + Add Resident
            </button> -->
        </div>
        <hr>
        <!-- PROGRAM TABLE -->
        <div class="container mt-4">
            <div class="card-box mb-4" style="width: 1500px;">
                <div class="d-flex justify-content-between mb-3">
                    <h5>📅 Programs</h5>
                    <input type="text" class="form-control w-25" placeholder="Search...">
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
                            <th>Program</th>
                            <th style="width: 700px;">Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): $counter = 1; ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $counter++ ?></td>
                                    <td><strong><?= $row['project_name'] ?></strong></td>
                                    <td><?= $row['description'] ?></td>
                                    <td><?= date("F d, Y", strtotime($row['start_date'])) ?></td>
                                    <td><?= date("F d, Y", strtotime($row['end_date'])) ?></td>
                                    <td><span class="badge 
                                            <?= $row['status'] == 'Approve' ? 'bg-success' : ($row['status'] == 'Pending' ? 'bg-warning' : 'bg-danger') ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#updateProgram<?= $row['project_id'] ?>">
                                            <i class="fas fa-edit"></i> Update
                                        </button>
                                        <a href="../page/kagawad.php?function=deleteProgram&id=<?= $row['project_id'] ?>"
                                            class="btn btn-sm btn-danger"
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