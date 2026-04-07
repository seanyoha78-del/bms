<?php
include '../nav/header.php';

$role = $_SESSION['position'];
$subpage = isset($_GET['subpage']) ? $_GET['subpage'] : 'dashboard';
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

        <div class="row mb-4">
            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="text-blue mb-4">Upcoming Youth Programs</h5>
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-light text-blue text-center p-2 rounded me-3" style="width: 60px;">
                                <h4 class="mb-0">28</h4>
                                <small>OCT</small>
                            </div>
                            <div>
                                <h6 class="mb-0">Inter-Barangay Basketball League</h6>
                                <p class="small text-muted mb-0">Barangay Plaza | 8:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-light text-blue text-center p-2 rounded me-3" style="width: 60px;">
                                <h4 class="mb-0">15</h4>
                                <small>NOV</small>
                            </div>
                            <div>
                                <h6 class="mb-0">Youth Empowerment Workshop</h6>
                                <p class="small text-muted mb-0">Session Hall | 1:00 PM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card shadow-sm border-0 bg-white p-4">
                    <h5 class="text-blue">SK Fund Snapshot</h5>
                    <div class="text-center my-4">
                        <h2 class="text-blue">₱124,500.00</h2>
                        <p class="text-muted">Available Disbursement</p>
                    </div>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Sports Equipment</span>
                            <span class="fw-bold">₱15,000</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Scholarship Aid</span>
                            <span class="fw-bold">₱40,000</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Livelihood Training</span>
                            <span class="fw-bold">₱25,000</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php } elseif ($subpage == "program") { ?>
    <!-- SK fund  -->
    <div class="card">
        <h1>SK Dedicated Ledger</h1>
        <p>Track the 10% SK fund allocation and spending.</p>
    </div>
<?php } elseif ($subpage == "funds") { ?>
    <!-- Youth Program  -->
    <div class="card">
        <h1>Youth Programs</h1>
        <p>Manage sports fests, scholarships, and youth assemblies.</p>
    </div>
<?php } elseif ($subpage == "blotter") { ?>
    <!-- reports  -->
    <div class="card">
        <h1>SK Reports</h1>
        <p>Council meeting minutes and project liquidation.</p>
    </div>

<?php } ?>
<?php include '../nav/footer.php'; ?>