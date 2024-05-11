<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>
<?php

// Check if user is logged in and is an Artist
if(!isset($_SESSION['type']) || $_SESSION['type'] !== "Artist") {                
    header("location: ".APPURL."");
    exit;
}

// Fetch categories for the dropdown
$get_categories = $conn->query("SELECT * FROM categories");
$categories = $get_categories->fetchAll(PDO::FETCH_OBJ);

// Handling form submission
if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $id = $_SESSION['id']; // Assuming the artist's ID is stored in session
    
    // Image upload handling
    $image = $_FILES['image']['name'];
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    
    // Validate and upload image
    if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Prepare SQL statement to insert art data
        $sql = "INSERT INTO arts (title, description, artist_id, category_id, image) VALUES (:title, :description, :artist_id, :category_id, :image)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':title' => $title, ':description' => $description, ':artist_id' => $artist_id, ':category_id' => $category_id, ':image' => $image]);
        
        // Redirect to index.php
         echo "<script>alert('Art Posted Successfully..'); window.location.href = '../arts/post-art.php?id=$id';</script>";
        exit;
    } else {
        echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
    }
} 
?>
<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Post Art</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Post Art</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Art Post Form -->
<section class="site-section">
    <div class="container">
        <h2>Post Art</h2>
        <form action="post-art.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category->category_id; ?>"><?php echo htmlspecialchars($category->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Art Image:</label>
                <input type="file" name="image" id="image" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Post Art</button>
        </form>
    </div>
</section>

<?php require "../includes/footer.php"; ?>
