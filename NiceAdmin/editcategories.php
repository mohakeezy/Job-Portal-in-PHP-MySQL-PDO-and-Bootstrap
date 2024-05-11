<?php 
require '../config/config.php'; // Database connection
require "includes/header.php";
require "includes/sidebar.php";

session_start(); // Start session at the very beginning

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: loginadmin.php");
    exit;
}

// Validate category_id
$category_id = isset($_GET['category_id']) && ctype_digit($_GET['category_id']) ? $_GET['category_id'] : null;
if (!$category_id) {
    echo "<script>alert('Invalid category ID.'); window.location.href = 'viewcategories.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);

        // Handle the image upload
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "../images/";
            $fileName = basename($_FILES['image']['name']);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            if (in_array(strtolower($fileType), $allowTypes)) {
                // Upload file to the server
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    $imagePath = $targetFilePath;
                }
            }
        }

        if (empty($name)) {
            echo "<script>alert('Category name is empty.');</script>";
        } else {
            try {
                // Update query with conditional image update
                $sql = "UPDATE `categories` SET `name` = :name, `description` = :description" . ($imagePath ? ", `image` = :image" : "") . " WHERE category_id = :category_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                if ($imagePath) {
                    $stmt->bindParam(':image', $imagePath);
                }
                $stmt->bindParam(':category_id', $category_id);
                $result = $stmt->execute();

                if ($result) {
                    echo "<script>alert('Category updated successfully.'); window.location.href = 'viewcategories.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('No changes were made to the category.');</script>";
                }
            } catch (PDOException $e) {
                echo "<script>alert('An error occurred: " . htmlspecialchars($e->getMessage()) . "');</script>";
            }
        }
    } elseif (isset($_POST['cancel'])) {
        echo "<script>window.location.href = 'viewcategories.php';</script>";
    }
}

// Fetch category data
$sql = "SELECT * FROM `categories` WHERE category_id = :category_id LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "<script>alert('Category not found.'); window.location.href = 'viewcategories.php';</script>";
    exit;
}
?>

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Edit Categories</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Edit Category</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form</h5>
                    <form action="editcategories.php?category_id=<?php echo htmlspecialchars($category_id); ?>" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Category Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="description" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea name="description" class="form-control" id="description"><?php echo htmlspecialchars($row['description']); ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="image" class="col-sm-2 col-form-label">Category Image</label>
                            <div class="col-sm-10">
                                <input type="file" name="image" class="form-control" id="image">
                            </div>
                        </div>
                        <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category_id); ?>">
                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                <button type="submit" name="cancel" the "btn btn-danger">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </section>
</main>

<?php require "includes/footer.php"; ?>
