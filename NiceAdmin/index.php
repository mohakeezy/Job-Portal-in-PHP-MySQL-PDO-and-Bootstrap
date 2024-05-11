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

// Tirooyinka isticmaalayaasha
$usersCount = $conn->query("SELECT COUNT(*) FROM users WHERE type = 'User'")->fetchColumn();

// Tirooyinka farshaxanka (arts)
$artsCount = $conn->query("SELECT COUNT(*) FROM arts")->fetchColumn();

// Tirooyinka fanaaniinta (artists)
$artistsCount = $conn->query("SELECT COUNT(*) FROM users WHERE type = 'artist'")->fetchColumn();



?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="col-lg-12">
      <div class="row">

        <!-- Users Card -->
        <div class="col-lg-4">
            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title">Users</h5>
                <div class="d-flex align-items-center">
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $usersCount; ?></h6>
                    </div>
                  </div>
                </div>
            </div>
        </div>
      </div>

       <!-- Users Arts -->
        <div class="col-lg-4">
            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title">Arts</h5>
                <div class="d-flex align-items-center">
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-image"></i>
                    </div>
                    <div class="ps-3">
                     <h6><?php echo $artsCount; ?></h6>
                    </div>
                  </div>
                </div>
            </div>
          </div>
      </div>

      <!-- Users Artists -->
        <div class="col-lg-4">
            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title">Artists</h5>
                <div class="d-flex align-items-center">
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div class="ps-3">
                     <h6><?php echo $artistsCount; ?></h6>
                    </div>
                  </div>
                </div>
            </div>
        </div>
      </div>
    </div><!-- End Left side columns -->
  </div>
</section>
</main><!-- End #main -->

  <?php require "includes/footer.php"; ?>