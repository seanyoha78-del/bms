<?php
// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protect page: must be logged in
if (!isset($_SESSION['official_id'])) {

    header("Location: ../page/index.php?subpage=login");
    exit();
} else {

    // Check role
    if ($_SESSION['position'] === 'Captain') {

        // Captain allowed
        // page continues

    } else if ($_SESSION['position'] === 'Kagawad') {

        // Kagawad allowed
        // page continues

    } else if ($_SESSION['position'] === 'Secretary') {

        // Secretary allowed
        // page continues

    } else if ($_SESSION['position'] === 'Treasurer') {

        // Secretary allowed
        // page continues

    } else if ($_SESSION['position'] === 'Sk kagawad') {
    } else {

        // Other roles not allowed
        header("Location: ../page/index.php?subpage=login");
        exit();
    }
}

// Prevent browser caching (fixes back button after logout)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Management System</title>
    <link href="../img/logo.png" rel="icon">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/dist/css/style.css">
</head>

<body>
    <?php
    $current = $_GET['subpage'] ?? '';
    ?>
    <?php
    $role = $_SESSION['position'];
    ?>

    <div class="sidebar">
        <h4>BMS PORTAL</h4>
        <div class="text-center mb-4">
            <small class="badge bg-light text-dark"><?php echo $role; ?></small>
        </div>
        <ul class="nav flex-column">

            <?php if ($role == "Captain") { ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current == 'dashboard') ? 'active' : '' ?>" href="../page/captain.php?subpage=dashboard">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'project') ? 'active' : '' ?>" href="../page/captain.php?subpage=program"><i class="fas fa-project-diagram me-2"></i> Projects</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'monitor') ? 'active' : '' ?>" href="../page/captain.php?subpage=monitor"><i class="fas fa-users-cog me-2"></i> Staff Management</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'approval') ? 'active' : '' ?>" href="../page/captain.php?subpage=approval"><i class="fas fa-file-signature me-2"></i> Approvals</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'resident') ? 'active' : '' ?>" href="../page/captain.php?subpage=resident"><i class="fas fa-address-book me-2"></i> Resident Records</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'term') ? 'active' : '' ?>" href="../page/captain.php?subpage=term"><i class="fas fa-certificate me-2"></i>Term</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'permit') ? 'active' : '' ?>" href="#"><i class="fas fa-file-alt me-2"></i> Permits</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'finance') ? 'active' : '' ?>" href="../page/treasurer.php?subpage=finance"><i class="fas fa-file-invoice-dollar me-2"></i> Financial Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-youth-visiting me-2"></i> Youth Programs</a></li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-coins me-2"></i> SK Funds</a></li>
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-gear me-2"></i> Settings</a></li>

            <?php } elseif ($role == "Kagawad") { ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current == 'dashboard') ? 'active' : '' ?>" href="../page/kagawad.php?subpage=dashboard">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'concern') ? 'active' : '' ?>" href="../page/kagawad.php?subpage=concern"><i class="fas fa-bullhorn me-2"></i>Community Concerns</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'reports') ? 'active' : '' ?>" href="../page/kagawad.php?subpage=reports"><i class="fas fa-clipboard-list me-2"></i>Committee Reports</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'program') ? 'active' : '' ?>" href="../page/kagawad.php?subpage=program"><i class="fas fa-bullhorn me-2"></i>Programs</a></li>

            <?php } elseif ($role == "Secretary") { ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current == 'dashboard') ? 'active' : '' ?>" href="../page/secretary.php?subpage=dashboard">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'resident') ? 'active' : '' ?>" href="../page/secretary.php?subpage=resident"><i class="fas fa-address-book me-2"></i> Resident Records</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'certificates') ? 'active' : '' ?>" href="../page/secretary.php?subpage=certificates"><i class="fas fa-certificate me-2"></i> Certificates</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'blotter') ? 'active' : '' ?>" href="../page/secretary.php?subpage=blotter"><i class="fas fa-file-alt me-2"></i> Blotter</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'concern') ? 'active' : '' ?>" href="../page/secretary.php?subpage=concern"><i class="fas fa-bullhorn me-2"></i>Community Concerns</a></li>
            <?php } elseif ($role == "Treasurer") { ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current == 'dashboard') ? 'active' : '' ?>" href="../page/treasurer.php?subpage=dashboard">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'budget') ? 'active' : '' ?>" href="../page/treasurer.php?subpage=budget"><i class="fas fa-wallet me-2"></i> Budget Tracker</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'finance') ? 'active' : '' ?>" href="../page/treasurer.php?subpage=finance"><i class="fas fa-file-invoice-dollar me-2"></i> Financial Reports</a></li>

            <?php } elseif ($role == "SK kagawad") { ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($current == 'dashboard') ? 'active' : '' ?>" href="../page/sk_kagawad.php?subpage=dashboard">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'program') ? 'active' : '' ?>" href="../page/sk_kagawad.php?subpage=program"><i class="fas fa-youth-visiting me-2"></i> Youth Programs</a></li>
                <li class="nav-item"><a class="nav-link <?= ($current == 'funds') ? 'active' : '' ?>" href="../page/sk_kagawad.php?subpage=funds"><i class="fas fa-coins me-2"></i> SK Funds</a></li>
            <?php } ?>
            <li class="nav-item mt-5">
                <a class="nav-link text-warning" href="../login/logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>