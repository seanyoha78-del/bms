<?php
include '../nav/header.php';

$role = $_SESSION['position'];
$subpage = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';


$name = $_POST['name'] ?? '';
$purpose = $_POST['purpose'] ?? '';
$type = $_POST['type'] ?? '';
$date = date("F d, Y");
$bdy = $_POST['birthday'] ?? '';
$nameB = $_POST['business'] ?? '';

$conn = mysqli_connect("localhost", "root", "", "barangay_system");

if (!$conn) {
    die("Database connection failed" . mysqli_connect_error());
}
?>
<?php if ($subpage == "dashboard") { ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-blue">Secretary Center</h2>
                <p class="text-muted">Records management and document issuance.</p>
            </div>
            <div class="btn-group">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addModel"><i class="fas fa-user-plus me-2"></i> New Resident</button>
                <button class="btn btn-primary-custom"><i class="fas fa-file-export me-2"></i> Issue Certificate</button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card p-3 shadow-sm text-center">
                    <i class="fas fa-id-card fa-2x text-blue mb-2"></i>
                    <h4 class="mb-0">125</h4>
                    <small class="text-muted">Pending Clearances</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow-sm text-center">
                    <i class="fas fa-file-contract fa-2x text-blue mb-2"></i>
                    <h4 class="mb-0">42</h4>
                    <small class="text-muted">Business Permits</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow-sm text-center">
                    <i class="fas fa-users fa-2x text-blue mb-2"></i>
                    <h4 class="mb-0">892</h4>
                    <small class="text-muted">Registered Voters</small>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between">
                    <h5 class="text-blue">Resident Directory</h5>
                    <input type="text" id="search" class="form-control form-control-sm w-25" placeholder="Search resident...">
                </div>
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dela Cruz, Juan</td>
                            <td>45 Blue St. Zone 2</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td><button class="btn btn-sm btn-light border"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        <tr>
                            <td>Santos, Ana</td>
                            <td>12 Green Rd. Zone 1</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td><button class="btn btn-sm btn-light border"><i class="fas fa-edit"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php } elseif ($subpage == "resident") { ?>
    <div class="containers mt-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Resident</h3>
            <?php if ($_SESSION['position'] == 'Secretary'): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModel">
                + Add Resident
            </button>
            <?php endif; ?>
        </div>
        <hr>
        <!-- TABLE -->
        <div class="container mt-4">
            <div class="card-box" style="width: 1500px;">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM resident ORDER BY resident_id DESC");
                if (mysqli_num_rows($result) > 0);
                $counter = 1;
                ?>
                <!-- HEADER -->
                <h5 class="mb-3">🏠 Resident records</h5>
                <hr>
                <!-- TOP CONTROLS -->
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <select class="form-select d-inline-block w-auto">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        <span class="ms-2">entries per page</span>
                    </div>
                    <div>
                        <input type="text" id="search" class="form-control" placeholder="Search...">
                    </div>
                </div>
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
                    <table class="table table-bordered table-hover" style="text-align: center;">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Birthdate</th>
                                <th>Gender</th>
                                <th>Civil_Status</th>
                                <th>Address</th>
                                <th>Contact No.</th>
                                <th>Voter_Status</th>
                                <th>Occupation</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><?= $counter++ ?></td>
                                    <td><?= $row['first_name'] ?> <?= $row['middle_name'] ?> <?= $row['last_name'] ?></td>
                                    <td><?= date("F d, Y", strtotime($row['birthdate'])) ?></td>
                                    <td><?= $row['gender'] ?></td>
                                    <td><?= $row['civil_status'] ?></td>
                                    <td><?= $row['address'] ?></td>
                                    <td><?= $row['contact_number'] ?></td>
                                    <td><?= $row['voter_status'] ?></td>
                                    <td><?= $row['occupation'] ?></td>
                                    <!-- <td>
                                        <button class="btn btn-success action-btn">⟳</button>
                                        <button class="btn btn-warning action-btn">⚠</button>
                                    </td> -->
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <!-- FOOTER -->
                <div class="d-flex justify-content-between mt-3">
                    <small>Showing 1 to 3 of 3 entries</small>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled"><a class="page-link">«</a></li>
                            <li class="page-item active"><a class="page-link">1</a></li>
                            <li class="page-item"><a class="page-link">»</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

    <?php } elseif ($subpage == "blotter") { ?>
        <div class="containers mt-4">
            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Blotter</h3>
                <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResident">
                + Add Resident
            </button> -->
            </div>
            <hr>
            <div class="container mt-4">
                <div class="card-box" style="width: 1500px;">
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM blotter ORDER BY blotter_id DESC");
                    if (mysqli_num_rows($result) > 0);
                    $counter = 1;
                    ?>
                    <!-- HEADER -->
                    <div class="d-flex justify-content-between mb-3">
                        <h4>🚨 Blotter Records</h4>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addModal">
                            + Add Blotter
                        </button>
                    </div>
                    <hr>
                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light" style="text-align: center;">
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Incident type</th>
                                    <th>Description</th>
                                    <th>Action Taken</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $row['bltr_incident_date'] ?></td>
                                        <td><?= $row['bltr_incident_time'] ?></td>
                                        <td><?= $row['bltr_incident_location'] ?></td>
                                        <td><?= $row['incident_type'] ?></td>
                                        <td><?= $row['description'] ?></td>
                                        <td><?= $row['action_taken'] ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#views">View</button>
                                            <a href="?delete=<?= $counter++ ?>" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <!-- VIEW MODAL -->
                                    <div class="modal fade" id="views">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5>Blotter Details</h5>
                                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Date:</strong> <?= $row['bltr_incident_date'] ?></p>
                                                    <p><strong>Time:</strong> <?= $row['bltr_incident_time'] ?></p>
                                                    <p><strong>Location:</strong> <?= $row['bltr_incident_location'] ?></p>
                                                    <p><strong>Complainant Name:</strong> <?= $row['bltr_compl_name'] ?></p>
                                                    <p><strong>Complainant Age:</strong> <?= $row['bltr_compl_age'] ?></p>
                                                    <p><strong>Complainant Address:</strong> <?= $row['bltr_compl_address'] ?></p>
                                                    <p><strong>Respondent Name:</strong> <?= $row['bltr_resp_name'] ?></p>
                                                    <p><strong>Respondent Age:</strong> <?= $row['bltr_resp_age'] ?></p>
                                                    <p><strong>Respondent Address:</strong> <?= $row['bltr_resp_address'] ?></p>
                                                    <p><strong>Incident Type:</strong> <?= $row['incident_type'] ?></p>
                                                    <p><strong>Description:</strong> <?= $row['description'] ?></p>
                                                    <p><strong>Action Taken:</strong> <?= $row['action_taken'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } elseif ($subpage == "certificates") { ?>
            <div class="containers mt-4">
                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Documents</h3>
                    <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResident">
                + Add Resident
            </button> -->
                </div>
                <hr>
                <div class="container mt-4">
                    <h3 class="mb-4">Barangay Document Generator</h3>
                    <!-- TABS -->
                    <ul class="nav nav-tabs no-print">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#residency">Residency</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#clearance">Clearance</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#indigency">Indigency</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#goodmoral">Business Permit</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-4">
                        <!-- RESIDENCY -->
                        <div class="tab-pane fade show active" id="residency">

                            <div class="card p-3 mb-3 no-print">
                                <form method="POST">
                                    <input type="hidden" name="type" value="residency">
                                    <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
                                    <input type="text" name="purpose" class="form-control mb-2" placeholder="Purpose" required>
                                    <button class="btn btn-primary">Generate Residency</button>
                                </form>
                            </div>
                        </div>
                        <!-- CLEARANCE -->
                        <div class="tab-pane fade" id="clearance">
                            <div class="card p-3 mb-3 no-print">
                                <form method="POST">
                                    <input type="hidden" name="type" value="clearance">
                                    <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
                                    <input type="text" name="purpose" class="form-control mb-2" placeholder="Purpose" required>
                                    <button class="btn btn-primary">Generate Clearance</button>
                                </form>
                            </div>
                        </div>
                        <!-- INDIGENCY -->
                        <div class="tab-pane fade" id="indigency">
                            <div class="card p-3 mb-3 no-print">
                                <form method="POST">
                                    <input type="hidden" name="type" value="indigency">
                                    <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
                                    <input type="text" name="purpose" class="form-control mb-2" placeholder="Purpose" required>
                                    <button class="btn btn-primary">Generate Indigency</button>
                                </form>
                            </div>
                        </div>
                        <!-- BUSINESS PERMIT -->
                        <div class="tab-pane fade" id="goodmoral">
                            <div class="card p-3 mb-3 no-print">
                                <form method="POST">
                                    <input type="hidden" name="type" value="goodmoral">
                                    <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
                                    <input type="text" name="birthday" class="form-control mb-2" placeholder="Birthday" required>
                                    <input type="text" name="business" class="form-control mb-2" placeholder="Name Of Business" required>
                                    <input type="text" name="purpose" class="form-control mb-2" placeholder="Purpose" required>
                                    <button class="btn btn-primary">Generate Permit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- GENERATED DOCUMENT -->
                    <?php if ($type): ?>
                        <div class="text-end mb-3 no-print">
                            <button onclick="printDiv('printableArea')" class="btn btn-success">Print</button>
                        </div>
                        <div class="box">
                            <h5 class="text-center">
                                <?php
                                switch ($type) {
                                    case "residency":
                                        echo "CERTIFICATE OF RESIDENCY";
                                        break;
                                    case "clearance":
                                        echo "BARANGAY CLEARANCE";
                                        break;
                                    case "indigency":
                                        // echo "CERTIFICATE OF INDIGENCY";
                                        break;
                                    case "goodmoral":
                                        // echo "BUSINESS PERMIT";
                                        break;
                                }
                                ?>
                            </h5>
                            <br>
                            <!-- <p>This is to certify that <strong><?= $name ?></strong>.</p> -->
                            <?php if ($type == "residency"): ?>
                                <p>He/She is a resident of this barangay.</p>
                            <?php endif; ?>
                            <?php if ($type == "clearance"): ?>
                                <p>He/She has no derogatory record filed in this barangay.</p>
                            <?php endif; ?>
                            <?php if ($type == "indigency"): ?>

                                <div id="printableArea">
                                    <div style="text-align: center;">
                                        <strong>
                                            <p style="margin-bottom: 3px;">REPUBLIC OF THE PHILIPPINES</p>
                                            <p style="margin-bottom: 3px;">PROVINCE OF LEYTE</p>
                                            <p style="margin-bottom: 3px;">MUNICIPALITY OF JULITA</p>
                                            <p style="margin-bottom: 3px;">BARANGAY HINDANG</p> <br>
                                            <P>CERTIFICATE OF INDIGENCY</P>
                                        </strong>
                                    </div>
                                    <p><b>TO WHOME IT MAY CONCERN:</b></p>
                                    <p>&emsp;&emsp;THIS IS TO CERTIFY that <b><?= $name ?></b>, Filipino, of legal age, single and was born at Barangay Hindang Julita,
                                        Letye is a bonafide resident of Brgy.Hindang Julita, Leyte.</p>
                                    <p>&emsp;&emsp;THIS FURTHER CERTIFIES that according to available records filed in the office
                                        she belongs or appeared in the list of INDIGENT FAMILY with low income.</p>
                                    <p>&emsp;&emsp;THIS CERTIFICATION is issued this <?= $date ?> at Barangay Hindang Julita,
                                        Leyte upon the request of <b><?= $name ?></b>. to be used for <strong><?= $purpose ?></strong>.</p><br><br>
                                    <table style="width:100%">
                                        <tr style="text-align: center;">
                                            <td><strong>Prepared by:<br><br>
                                                    VICKY A. MARTEJA <br>
                                                    Brgy.Secretary
                                                </strong></td>
                                            <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</td>
                                            <td><strong>Approved by: <br><br>
                                                    GASPAR C. RIVAS <br>
                                                    Punong Barangay
                                                </strong></td>
                                        </tr>
                                    </table>
                                </div>
                            <?php endif; ?>
                            <?php if ($type == "goodmoral"): ?>
                                <div id="printableArea">
                                    <table class="space">
                                        <tr style="text-align: center;">
                                            <td><img src="../img/Logo.png" alt="logo" width="100px" height="70px"></td>
                                            <td>Republic of the Philippines <br>
                                                Province of Leyte <br>
                                                Municipality of Julita <br>
                                                Barangay Hindang
                                            </td>
                                        </tr>
                                    </table><br><br>
                                    <div style="text-align: center;">
                                        <strong>
                                            <p style="margin-bottom: 3px;">OFFICE OF THE PUNONG BARANGAY</p>
                                            <p style="margin-bottom: 3px;">BARANGAY BUSINESS PERMIT</p>
                                            <p style="margin-bottom: 3px;">CLEARANCE</p> <br>
                                        </strong>
                                    </div>
                                    <p>TO WHOM IT MAY CONCERN:</p>
                                    <p>&emsp;&emsp;&emsp;THIS IS TO CERTIFY that. <b><?= $name ?></b> single, Filipino. born on <?= $bdy ?> a resident of Brgy.
                                        Hindang. Julita, Leyte, and is known to me personally for being honest, hard working, having good moral and spiritual character.</p>
                                    <p>&emsp;&emsp;&emsp;This certify that the aforementioned name have opeating <?= $nameB ?> located in the barangay.</p>
                                    <p>&emsp;&emsp;&emsp;This certifies further that the aforementioned has not been charge of any criminal or administrative case
                                        which may affect his reputation and good standing in the community.</p>
                                    <p>&emsp;&emsp;&emsp;This clearance is issued upon request for requirements for <b><?= $purpose ?></b> purposes only.</p>
                                    <p>&emsp;&emsp;&emsp;Given this <b><?= $date ?></b> at Brgy. Hindang Julita, Leyte.</p>
                                </div>
                            <?php endif; ?>
                            <br><br>
                        </div>
                    <?php endif; ?>
                </div>
            <?php } elseif ($subpage == "register") { ?>
                <div class="containers mt-4">
                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Register</h3>
                        <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResident">
                + Add Resident
            </button> -->
                    </div>
                    <hr>
                    <div class="container mt-4">
                        <div class="card-box" style="width: 1500px;">
                            <!-- HEADER -->
                            <div class="d-flex justify-content-between mb-3">
                                <h4>Register a Official</h4>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addModal">
                                    + Add Blotter
                                </button>
                            </div>
                            <hr>

                        <?php } ?>
                        <?php include '../nav/footer.php'; ?>