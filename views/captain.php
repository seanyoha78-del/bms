<?php include '../nav/header.php'; ?>
<?php
$role = $_SESSION['position'];
$subpage = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';

$conn = mysqli_connect("localhost", "root", "", "barangay_system");

if (!$conn) {
    die("Database connection failed" . mysqli_connect_error());
}

// ✅ FIXED: Get CURRENT term by status
$termQuery = mysqli_query($conn, "SELECT term_id FROM term WHERE status = 'Current' LIMIT 1");
$currentTerm = mysqli_fetch_assoc($termQuery);

// ✅ FIXED: Resident table name (was 'residents', now 'resident')
$residentsResult = mysqli_query($conn, "SELECT resident_id, first_name, last_name FROM resident ORDER BY last_name, first_name");
?>


<?php if ($subpage == "dashboard") { ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-blue">Brgy. Captain Overview</h2>
                <p class="text-muted">Welcome, <?php echo $_SESSION['position']; ?>. Monitoring all barangay activities.</p>
            </div>
            <button class="btn btn-primary-custom"><i class="fas fa-print me-2"></i> Generate Annual Report</button>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-stats shadow-sm p-3">
                    <h6 class="text-muted">Total Residents</h6>
                    <h3 class="text-blue">1,240</h3>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> 2% this month</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats shadow-sm p-3">
                    <h6 class="text-muted">Total Budget</h6>
                    <h3 class="text-blue">₱4,500,200</h3>
                    <small class="text-blue">Current Balance</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats shadow-sm p-3">
                    <h6 class="text-muted">Active Projects</h6>
                    <h3 class="text-blue">12</h3>
                    <small class="text-warning">4 pending approval</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats shadow-sm p-3">
                    <h6 class="text-muted">Unresolved Concerns</h6>
                    <h3 class="text-blue">8</h3>
                    <small class="text-danger">Action required</small>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 bg-white">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 text-blue">Recent Activity Logs</h5>
            </div>
            <div class="card-body">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Oct 24, 2023</td>
                            <td>Secretary Maria</td>
                            <td>Updated Resident Records</td>
                            <td><span class="badge bg-success">Success</span></td>
                        </tr>
                        <tr>
                            <td>Oct 23, 2023</td>
                            <td>Treasurer Jose</td>
                            <td>Submitted Financial Report</td>
                            <td><span class="badge bg-warning text-dark">Pending Approval</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php } elseif ($subpage == "approval") { ?>
    <div class="main-content">
        <!-- approval  -->
        <div class="card">
            <h1>Pending Approvals</h1>
            <p>Requests requiring your sign-off.</p>
            <table>
                <tr>
                    <th>Type</th>
                    <th>Requested By</th>
                    <th>Details</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td>Budget Request</td>
                    <td>SK Chairman</td>
                    <td>Basketball Tournament - ₱15,000</td>
                    <td><button class="btn btn-primary">Approve</button></td>
                </tr>
            </table>
        </div>
    </div>

<?php } elseif ($subpage == "monitor") { ?>
    <div class="containers mt-4">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Barangay Officials</h3>
        </div>
        <hr>

        <!-- Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'];
                unset($_SESSION['message'], $_SESSION['msg_type']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="container mt-4">
            <div class="card-box" style="width: 1500px;">
                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>List of Officials</h4>
                    <a href="../page/captain.php?subpage=register" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Official
                    </a>
                </div>
                <hr>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 100px;">No.</th>
                                <th>Name</th>
                                <th style="width: 300px;">Position</th>
                                <th style="width: 220px;">Email</th>
                                <th style="width: 320px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $officialsQuery = mysqli_query($conn, "
                                SELECT 
                                    o.official_id,
                                    o.position,
                                    o.email,
                                    o.term_id,
                                    r.first_name,
                                    r.middle_name,
                                    r.last_name,
                                    r.resident_id
                                FROM barangay_official o
                                INNER JOIN resident r ON o.resident_id = r.resident_id
                                INNER JOIN term t ON o.term_id = t.term_id
                                WHERE t.status = 'current'
                                ORDER BY o.official_id DESC
                            ");

                            if (mysqli_num_rows($officialsQuery) > 0) {
                                $counter = 1;
                                while ($row = mysqli_fetch_assoc($officialsQuery)) :
                            ?>
                                    <tr class="text-center align-middle">
                                        <td><strong><?= $counter++ ?></strong></td>
                                        <td>
                                            <strong><?= htmlspecialchars($row['first_name']); ?> <?= htmlspecialchars($row['middle_name']); ?> <?= htmlspecialchars($row['last_name']); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark fs-6 px-3 py-2">
                                                <?= htmlspecialchars($row['position']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($row['email']): ?>
                                                <i class="fas fa-envelope me-1"></i>
                                                <?= htmlspecialchars($row['email']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">No email</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <!-- VIEW -->
                                            <a href="../page/captain.php?subpage=profile&id=<?= $row['official_id'] ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <!-- UPDATE POSITION MODAL TRIGGER -->
                                            <button class="btn btn-sm btn-outline-info ms-1" data-bs-toggle="modal" data-bs-target="#updatePosition<?= $row['official_id'] ?>">
                                                <i class="fas fa-edit"></i> Update
                                            </button>
                                            <!-- DELETE -->
                                            <a href="../page/captain.php?function=deleteOfficial&id=<?= $row['official_id'] ?>"
                                                class="btn btn-sm btn-outline-danger ms-1"
                                                onclick="return confirm('Delete <?= $row['first_name'] . ' ' . $row['last_name'] ?>?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- UPDATE POSITION MODAL -->
                                    <div class="modal fade" id="updatePosition<?= $row['official_id'] ?>">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title">Update Position</h5>
                                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="../page/captain.php?function=updatePosition" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="official_id" value="<?= $row['official_id']; ?>">
                                                        <input type="hidden" name="resident_id" value="<?= $row['resident_id']; ?>">

                                                        <div class="mb-3">
                                                            <label class="form-label">Position</label>
                                                            <select name="position" class="form-control" required>
                                                                <option value="Captain" <?= $row['position'] == 'Captain' ? 'selected' : ''; ?>>Captain</option>
                                                                <option value="Secretary" <?= $row['position'] == 'Secretary' ? 'selected' : ''; ?>>Secretary</option>
                                                                <option value="Treasurer" <?= $row['position'] == 'Treasurer' ? 'selected' : ''; ?>>Treasurer</option>
                                                                <option value="SK" <?= $row['position'] == 'SK' ? 'selected' : ''; ?>>SK Kagawad</option>
                                                                <option value="Health" <?= $row['position'] == 'Health' ? 'selected' : ''; ?>>Health committee</option>
                                                                <option value="Peace" <?= $row['position'] == 'Peace' ? 'selected' : ''; ?>>Peace & Orded committee</option>
                                                                <option value="Education" <?= $row['position'] == 'Education' ? 'selected' : ''; ?>>Education committee</option>
                                                                <option value="Infrastructure" <?= $row['position'] == 'Infrastructure' ? 'selected' : ''; ?>>Infrastructure Committee</option>
                                                                <option value="Environment" <?= $row['position'] == 'Environment' ? 'selected' : ''; ?>>Environment Committee</option>
                                                                
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="email" name="email" class="form-control"
                                                                value="<?= htmlspecialchars($row['email']); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-info">Update Official</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                <?php
                                endwhile;
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No officials registered yet</h5>
                                        <a href="../page/captain.php?subpage=register" class="btn btn-primary">Register First Official</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php } elseif ($subpage == "term") { ?>
    <div class="containers mt-4">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>TERM</h3>
            <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResident">
                + Add Resident
            </button> -->
        </div>
        <hr>
        <div class="container mt-4">
            <div class="card-box" style="width: 1500px;">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM term ORDER BY term_id DESC");
                if (mysqli_num_rows($result) > 0);
                $counter = 1;
                ?>
                <!-- HEADER -->
                <div class="d-flex justify-content-between mb-3">
                    <h4></h4>
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addTerm">
                        + Add Term
                    </button>
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
                        <thead class="table-light" style="text-align: center;">
                            <tr>
                                <th>No.</th>
                                <th>START_TERM</th>
                                <th>END_TERM</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr style="text-align: center;">
                                    <td><strong><?= $counter++ ?></strong></td>

                                    <td>
                                        <strong><?= date("F d, Y", strtotime($row['start_year'])) ?></strong>
                                    </td>

                                    <td>
                                        <strong><?= date("F d, Y", strtotime($row['end_year'])) ?></strong>
                                    </td>

                                    <td>
                                        <span class="badge 
                                                <?= $row['status'] == 'Current' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#updateTerm<?= $row['term_id'] ?>">
                                           <i class="fas fa-edit"></i> Update
                                        </button>
                                        <a href="../page/captain.php?function=deleteTerm&id=<?= $row['term_id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this Term?')">
                                            <i class="fas fa-trash"></i>Delete
                                        </a>
                                    </td>
                                </tr>
                                <!-- UPDATE TERM -->
                                <div class="modal fade" id="updateTerm<?= $row['term_id'] ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5>Update Status</h5>
                                                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="../page/captain.php?function=updateTermStatus" method="POST">
                                                <div class="modal-body">
                                                    <!-- PASS ID -->
                                                    <input type="hidden" name="id" value="<?= $row['term_id'] ?>">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="Current">Current</option>
                                                        <option value="Inactive">Inactive</option>
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

    <?php } elseif ($subpage == "register") { ?>
        <div class="containers mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Register Barangay Official</h3>
                <a href="../page/captain.php?subpage=monitor" class="btn btn-secondary">← Back to Officials</a>
            </div>
            <hr>

            <!-- Messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['message'];
                    unset($_SESSION['message'], $_SESSION['msg_type']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="container mt-5">
                <div class="card shadow p-4">
                    <form action="../page/captain.php?function=register" method="POST" id="officialForm">
                        <!-- CURRENT TERM -->
                        <?php if ($currentTerm && $currentTerm['term_id']): ?>
                            <div class="mb-3">
                                <label class="form-label">Current Term</label>
                                <input type="text" class="form-control" value="<?= $currentTerm['term_id']; ?>" readonly>
                                <input type="hidden" name="term_id" value="<?= $currentTerm['term_id']; ?>">
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning mb-3">
                                ⚠️ No active term found. Please set a term as 'Current' in the Term Management section.
                            </div>
                        <?php endif; ?>

                        <!-- RESIDENT -->
                        <div class="mb-3">
                            <label class="form-label">Select Resident <span class="text-danger">*</span></label>
                            <select name="resident_id" class="form-control" id="resident_id" required>
                                <option value="">Choose Resident</option>
                                <?php
                                mysqli_data_seek($residentsResult, 0); // Reset pointer
                                while ($row = mysqli_fetch_assoc($residentsResult)): ?>
                                    <option value="<?= htmlspecialchars($row['resident_id']); ?>">
                                        <?= htmlspecialchars($row['resident_id']); ?> -
                                        <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- POSITION -->
                        <div class="mb-3">
                            <label class="form-label">Position <span class="text-danger">*</span></label>
                            <select name="position" class="form-control" id="position" required>
                                <option value="">Select Position</option>
                                <option>Captain</option>
                                <option>Secretary</option>
                                <option>Treasurer</option>
                                <option>SK</option>
                                <option>Heatlh</option>
                                <option>Peace</option>
                                <option>Education</option>
                                <option>Infrastructure</option>
                                <option>Invironment</option>
                            </select>
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email">
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" id="password" required minlength="6">
                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password" required minlength="6">
                            <div class="form-text" id="passwordMatch" style="font-size: 0.875em;"></div>
                        </div>

                        <button type="submit" name="submit_official" class="btn btn-primary w-100" id="submitBtn">
                            <i class="fas fa-user-plus me-2"></i>Register Official
                        </button>
                    </form>
                </div>
            </div>

        <?php } elseif ($subpage == "profile") { ?>
            <?php
            $official_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            if ($official_id == 0) {
                echo "<div class='alert alert-danger'>No official selected.</div>";
                exit;
            }
            ?>
            <div class="containers mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Profile</h3>
                </div>
                <hr>
                <div class="container mt-5">
                    <div class="card-box" style="width: 1500px;">
                        <!-- HEADER -->
                        <div style="text-align: center;">
                            <h4><i class="fas fa-user-edit text-primary me-2"></i> Personal Information</h4>
                        </div>
                        <hr>
                        <?php
                        $officialsQuery = mysqli_query($conn, "
                            SELECT 
                                o.official_id,
                                o.position,
                                o.email,
                                r.first_name,
                                r.middle_name,
                                r.last_name,
                                r.birthdate,
                                r.gender,
                                r.civil_status,
                                r.address,
                                r.contact_number,
                                r.voter_status,
                                r.occupation
                            FROM barangay_official o
                            INNER JOIN resident r ON o.resident_id = r.resident_id
                            WHERE o.official_id = $official_id
                            LIMIT 1
                        ");
                        if (mysqli_num_rows($officialsQuery) > 0) {
                            $counter = 1;
                            if ($row = mysqli_fetch_assoc($officialsQuery)) :
                        ?>
                                <form>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>First Name:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['first_name']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>Middle Name:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['middle_name']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>Last Name:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['last_name']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>Birthdate:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" value="<?= htmlspecialchars($row['birthdate']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>Gender:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['gender']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>Civil Status:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['civil_status']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>Address:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['address']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>Contact No.:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['contact_number']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>Voter Status:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['voter_status']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>Occupation:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['occupation']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"><strong>Position:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['position']); ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-2 col-form-label"><strong>Email:</strong></label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" value="<?= htmlspecialchars($row['email']); ?>">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-save"></i> Save Changes
                                    </button>
                                </form>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Official not found.</div>
            <?php endif;
                        } ?>

        <?php } elseif ($subpage == "header") { ?>
            <div class="containers mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Edit Budget Information</h3>
                </div>
                <hr>
                <div class="container mt-5" style="margin-left: 30%;">
                    <div class="card-box" style="width: 800px;">
                        <?php
                        // FETCH DATA
                        $result = mysqli_query($conn, "SELECT * FROM voucher WHERE id = 1");

                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                        }

                        // UPDATE DATA
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                            $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
                            $municipality = mysqli_real_escape_string($conn, $_POST['municipality']);
                            $province = mysqli_real_escape_string($conn, $_POST['province']);
                            $address = mysqli_real_escape_string($conn, $_POST['address']);

                            $update = "UPDATE voucher SET 
                                barangay='$barangay',
                                municipality='$municipality',
                                province='$province',
                                address='$address'
                                WHERE id = 1";

                            if (mysqli_query($conn, $update)) {
                                echo "<script>alert('Updated successfully!'); window.location='';</script>";
                            } else {
                                echo "<script>alert('Update failed!');</script>";
                            }
                        }
                        ?>
                        <!-- HEADER -->
                        <div style="text-align: center;">
                            <h4><i class="fas  text-primary me-2"></i> Budget Information</h4>
                        </div>
                        <hr>
                        <form action="" method="POST">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Barangay:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" name="barangay" class="form-control" value="<?= htmlspecialchars($row['barangay'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Municipality:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" name="municipality" class="form-control" value="<?= htmlspecialchars($row['municipality']); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>Province:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" name="province" class="form-control" value="<?= htmlspecialchars($row['province'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label"><strong>address:</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($row['address'] ?? ''); ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Save Changes
                            </button>
                        </form>
                    </div>
                <?php } ?>
                <?php include '../nav/footer.php'; ?>