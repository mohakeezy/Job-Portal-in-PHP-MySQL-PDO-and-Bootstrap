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

// Prepare and execute the query
$stmt = $conn->prepare("SELECT adminname, email, created_at FROM admins WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Admins</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>
              </ul>

              <div class="tab-content pt-2">
                <?php if ($admin): ?>
                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">Profile Details</h5>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Admin Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($admin['adminname']); ?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($admin['email']); ?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Joined</div>
                    <div class="col-lg-9 col-md-8"><?php echo date('F j, Y', strtotime($admin['created_at'])); ?></div>
                  </div>
                </div>
                <?php else: ?>
                <div class="tab-pane fade show active" id="profile-overview">
                  <h5 class="card-title">Admin data not found!</h5>
                  <p>Please contact system administrator.</p>
                </div>
                <?php endif; ?>
                
                  <a class="btn btn-primary" href="edit_profile.php?id=<?php echo $_SESSION['id']; ?>">Edit Profile</a></li>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</main><!-- End #main -->

<?php require "includes/footer.php"; ?>
