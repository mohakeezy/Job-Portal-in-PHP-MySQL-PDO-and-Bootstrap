<?php 
require "includes/header.php";
require "includes/sidebar.php";
require "../config/config.php";

// Fetch user data for the form and handle the form submission
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $id = $_GET['id'];
} 
    //else {
//     echo "<script>alert('Invalid user ID.'); window.location.href = 'viewusers.php';</script>";
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $type = $_POST['type'];
        $id = $_POST['id'];

        if (empty($fullname) || empty($email)) {
            echo "<script>alert('Full name and email cannot be empty.');</script>";
        } else {
            // Check if the username is already in use by another user
            $checkSql = "SELECT id FROM `users` WHERE username = :username AND id != :id";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':username', $username);
            $checkStmt->bindParam(':id', $id);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                echo "<script>alert('Username already in use by another user. Please choose another username.');</script>";
            } else {
                try {
                    $sql = "UPDATE `users` SET `fullname` = :fullname, `email` = :email, `username` = :username, `type` = :type WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(['fullname' => $fullname, 'email' => $email, 'username' => $username, 'type' => $type, 'id' => $id]);

                    if ($stmt->rowCount()) {
                        echo "<script>alert('User updated successfully.'); window.location.href = 'viewusers.php';</script>";
                    } else {
                        echo "<script>alert('No changes made to the user.');</script>";
                    }
                } catch (PDOException $e) {
                    echo "<script>alert('An error occurred while updating the user: " . htmlspecialchars($e->getMessage()) . "');</script>";
                }
            }
        }
    } elseif (isset($_POST['cancel'])) {
         echo "<script>window.location.href = 'viewusers.php';</script>";
    }
}

// Prepare and execute the statement to fetch user data
$sql = "SELECT * FROM `users` WHERE id = :id LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "<script>alert('User not found.'); window.location.href = 'viewusers.php';</script>";
    exit;
}
?>

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Edit User</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Edit User</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit User Form</h5>
                    <form action="edit_users.php" method="post">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                        <div class="row mb-3">
                            <label for="fullname" class="col-sm-2 col-form-label">Full Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="fullname" value="<?php echo htmlspecialchars($row['fullname']); ?>" class="form-control" id="fullname">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" class="form-control" id="username">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="form-control" id="email">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="type" class="col-sm-2 col-form-label">User Type</label>
                            <div class="col-sm-10">
                                <select name="type" class="form-control" id="type">
                                    <option value="Artist" <?php echo ($row['type'] == 'Artist' ? 'selected' : ''); ?>>Artist</option>
                                    <option value="User" <?php echo ($row['type'] == 'User' ? 'selected' : ''); ?>>User</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </section>
</main><!-- End #main -->

<?php require "includes/footer.php"; ?>
