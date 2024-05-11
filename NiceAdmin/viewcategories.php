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

?>

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Categories</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Categories</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Categories</h5>
              <!-- Display Success/Error Messages -->
              <?php 
              if(isset($_GET['msg'])){
                echo '<div class="alert alert-success" role="alert">'. htmlspecialchars($_GET['msg']) .'</div>';
              }
              ?>
              <a href="Categories.php" class="btn btn-primary mb-3">Add New Category</a>

              <!-- Table with stripped rows -->
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Category Name</th>
                    <th scope="col">Description</th> <!-- Added Description Column -->
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $sql = "SELECT * FROM `categories`";
                  foreach ($conn->query($sql) as $row) {
                      echo '<tr>';
                      echo '<th scope="row">'. htmlspecialchars($row['category_id']) .'</th>';
                      echo '<td>'. htmlspecialchars($row['name']) .'</td>';
                      echo '<td>'. htmlspecialchars($row['description']) .'</td>'; // Display the category description
                      echo '<td>
                              <a href="editcategories.php?category_id='. htmlspecialchars($row['category_id']) .'" class="btn btn-success"><i class="bi bi-pencil-square"></i></a>
                              <a href="deletecategories.php?category_id='. htmlspecialchars($row['category_id']) .'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this category?\')"><i class="bi bi-trash-fill"></i></a>
                            </td>';
                      echo '</tr>';
                  }
                  ?>
                </tbody>
              </table>
              <!-- End Table -->

            </div>
          </div>
        </div>
      </div>
    </section>
</main><!-- End #main -->

<?php require "includes/footer.php"; ?>
