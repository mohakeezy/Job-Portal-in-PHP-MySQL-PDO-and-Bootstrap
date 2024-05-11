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
$stmt = $conn->prepare("SELECT adminname, email FROM admins WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    echo "Admin data not found!";
    exit;
}

// Update profile if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve new profile information from the form
    $newAdminName = $_POST['adminname'];
    $newEmail = $_POST['email'];

    // Update admin's profile information in the database
    $updateStmt = $conn->prepare("UPDATE admins SET adminname = :adminname, email = :email WHERE id = :id");
    $updateStmt->bindParam(':adminname', $newAdminName, PDO::PARAM_STR);
    $updateStmt->bindParam(':email', $newEmail, PDO::PARAM_STR);
    $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $updateStmt->execute();

   echo "<script>alert('Profile updated.'); window.location.href = 'edit_profile.php';</script>";
}
?>

<main id="main" class="main">
   <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Admins</li>
          <li class="breadcrumb-item active">Edit Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body pt-3">
              <form method="POST">
                <div class="mb-3">
                  <label for="adminname" class="form-label">Admin Name</label>
                  <input type="text" class="form-control" id="adminname" name="adminname" value="<?php echo htmlspecialchars($admin['adminname']); ?>">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
</main><!-- End #main -->

<?php require "includes/footer.php"; ?>
