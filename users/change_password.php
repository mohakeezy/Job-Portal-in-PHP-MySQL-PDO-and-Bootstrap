<?php
require "../includes/header.php";
require "../config/config.php";

// Redirect if not logged in or if the user is not an artist (or other intended role)
if (!isset($_SESSION['id'])) {
    header("location: " . APPURL);
    exit;
}

$id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            echo "<script>alert('Some inputs are empty.');</script>";
        } else {
            try {
                // First, verify the current password
                $stmt = $conn->prepare("SELECT mypassword FROM users WHERE id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($current_password, $user['mypassword'])) {
                    if ($new_password === $confirm_password) {
                        // Hash the new password before saving
                        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

                        // Update query with hashed password
                        $sql = "UPDATE users SET mypassword = :new_password WHERE id = :id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':new_password', $new_password_hashed);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $result = $stmt->execute();

                        if ($result) {
                            echo "<script>alert('User password updated successfully.'); window.location.href = '../users/change_password.php?id=$id';</script>";
                            exit;
                        } else {
                            echo "<script>alert('No changes were made to the user password.'); window.location.href = '../users/change_password.php?id=$id';</script>";
                        }
                    } else {
                        echo "<script>alert('New passwords do not match.'); window.location.href = '../users/change_password.php?id=$id';</script>";
                    }
                } else {
                    echo "<script>alert('Current password is incorrect.'); window.location.href = '../users/change_password.php?id=$id';</script>";
                }
            } catch (PDOException $e) {
                echo "<script>alert('An error occurred: " . htmlspecialchars($e->getMessage()) . "'); window.location.href = '../users/change_password.php?id=$id';</script>";
            }
        }
    }
}
?>

<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Change Password</h1>
                <div class="custom-breadcrumbs">
                    <a href="index.php">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Change Password</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="site-section">
    <div class="container">
        <h2>Change Your Password</h2>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="change_password.php" method="post">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</section>

<?php require "../includes/footer.php"; ?>