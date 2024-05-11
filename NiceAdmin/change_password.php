<?php
require '../config/config.php'; // Database connection
require "includes/header.php";
require "includes/sidebar.php";

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: loginadmin.php");
    exit;
}

$id = $_SESSION['id']; // Retrieve admin ID from session

// Fetch admin data from the database
$stmt = $conn->prepare("SELECT adminname FROM admins WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    echo "Admin data not found!";
    exit;
}

// Update password if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Fetch current password from the database
    $passwordStmt = $conn->prepare("SELECT mypassword FROM admins WHERE id = :id");
    $passwordStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $passwordStmt->execute();
    $passwordData = $passwordStmt->fetch(PDO::FETCH_ASSOC);

    // Verify current password
    if (!password_verify($currentPassword, $passwordData['mypassword'])) {
        // $error = "Current password is incorrect.";
         echo "<script>alert('Current password is incorrect.'); window.location.href = 'change_password.php';</script>";
    } elseif ($newPassword !== $confirmPassword) {
        // $error = "New password and confirm password do not match.";
         echo "<script>alert('New password and confirm password do not match.'); window.location.href = 'change_password.php';</script>";
    } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password in the database
        $updateStmt = $conn->prepare("UPDATE admins SET mypassword = :password WHERE id = :id");
        $updateStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $updateStmt->execute();

        // $success = "Password updated successfully.";
        echo "<script>alert('Password updated successfully.'); window.location.href = 'change_password.php';</script>";
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Change Password</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item">Admins</li>
                <li class="breadcrumb-item active">Change Password</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if (isset($success)) : ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<?php require "includes/footer.php"; ?>
