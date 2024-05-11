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

if(isset($_POST['submit'])){
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $image = $_FILES['image']['name'];
    $target_dir = "../UploadCategories/";
    $target_file = $target_dir . basename($image);

    if(empty($name) || empty($description) || empty($image)){
        echo "<script>alert('One or more inputs are empty. Please fill out all fields.');</script>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE name = :name");
        $stmt->execute([':name' => $name]);
        if($stmt->rowCount() > 0) {
            echo "<script>alert('This category already exists.');</script>";
        } else {
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if($check !== false) {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $insert = $conn->prepare("INSERT INTO categories (name, description, image) VALUES (:name, :description, :image)");
                        $insert->execute([':name' => $name, ':description' => $description, ':image' => $target_file]);
                        echo "<script>alert('Category added successfully.'); window.location.href='viewcategories.php?msg=Added New Category';</script>";
                    } else {
                        echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                    }
                } else {
                    echo "<script>alert('File is not an image.');</script>";
                }
            }
        }
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Categories</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Category</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Add New Category</h5>

              <form action="Categories.php" method="post" enctype="multipart/form-data">
  <div class="row mb-3">
    <label for="inputName" class="col-sm-2 col-form-label">Category Name</label>
    <div class="col-sm-10">
      <input type="text" name="name" placeholder="Enter Category Name" class="form-control" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="inputDescription" class="col-sm-2 col-form-label">Description</label>
    <div class="col-sm-10">
      <textarea name="description" placeholder="Enter Category Description" class="form-control" required></textarea>
    </div>
  </div>
  <div class="row mb-3">
    <label for="inputImage" class="col-sm-2 col-form-label">Category Image</label>
    <div class="col-sm-10">
      <input type="file" name="image" class="form-control">
    </div>
  </div>
  <div class="row mb-3">
    <div class="col-sm-10 offset-sm-2">
      <button type="submit" name="submit" class="btn btn-primary">Save Category</button>
    </div>
  </div>
</form>
<!-- End Form -->
            </div>
          </div>
        </div>
      </div>
    </section>
</main><!-- End #main -->

<?php require "includes/footer.php"; ?>
