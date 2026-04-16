<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barangay Management System</title>
    <link href="../img/Logo.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/dist/css/style.css">
</head>

<body class="login-body">
    <div class="card login-card p-4 shadow">
        <div class="text-center mb-4">
            <h3 class="text-blue fw-bold">BMS LOGIN</h3>
            <p class="text-muted">Enter credentials to access portal</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <form action="../page/index.php?function=login" method="post">
            <div class="mb-3">
                <label class="form-label text-blue">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-blue">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary-custom w-100 py-2">Sign In</button>
        </form>

        <div class="mt-4 text-center">
            <small class="text-muted">Protected System Access Only</small>
        </div>
    </div>
</body>

</html>