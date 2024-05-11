<?php
require "../includes/header.php";
require "../config/config.php";

// Redirect if not logged in or not an artist
if (!isset($_SESSION['id'])) {
    header("location: " . APPURL);
    exit;
}

// Fetch categories for the dropdown
$get_categories = $conn->query("SELECT * FROM categories");
$categories = $get_categories->fetchAll(PDO::FETCH_OBJ);

// Check if an artwork ID is passed and fetch artwork details
if (isset($_GET['art_id']) && is_numeric($_GET['art_id'])) {
    $art_id = $_GET['art_id'];
    $stmt = $conn->prepare("SELECT * FROM arts WHERE art_id = :art_id AND artist_id = :artist_id");
    $stmt->bindParam(':art_id', $art_id);
    $stmt->bindParam(':artist_id', $_SESSION['id']);
    $stmt->execute();
    $art = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$art) {
        echo "<script>alert('Artwork not found.'); window.location.href='" . APPURL . "';</script>";
        exit;
    }
} else {
    echo "<script>alert('No artwork specified.'); window.location.href='" . APPURL . "';</script>";
    exit;
}

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $artist_id = $_SESSION['id']; // Assuming the artist's ID is stored in session

    // Image upload handling
    $image = $_FILES['image']['name'];
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    // Check if image is being updated
    if (!empty($image)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    } else {
        // If no new image is uploaded, keep the old one
        $image = $art->image;
    }

    // Prepare SQL statement to update art data
    $sql = "UPDATE arts SET title = :title, description = :description, artist_id = :artist_id, category_id = :category_id, image = :image WHERE art_id = :art_id";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([':title' => $title, ':description' => $description, ':artist_id' => $artist_id, ':category_id' => $category_id, ':image' => $image, ':art_id' => $art_id])) {
        // echo "<script>alert('DONE.');</script>";
         header("location: " . APPURL);
            exit();
    } else {
        echo "<script>alert('Failed to update artwork.');</script>";
    }
}

// Flush the buffer and send its contents to the browser
ob_end_flush();
?>

<!-- Art Edit Form -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Edit Art</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Edit Art</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="site-section">
    <div class="container">
        <h2>Edit Art</h2>
        <form action="edit_art.php?art_id=<?php echo htmlspecialchars($art_id); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($art->title); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" rows="4" required><?php echo htmlspecialchars($art->description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->category_id; ?>" <?php echo ($category->category_id == $art->category_id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Art Image (Current Image: <?php echo htmlspecialchars($art->image); ?>):</label>
                <input type="file" name="image" id="image" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update Art</button>
        </form>
    </div>
</section>

<?php require "../includes/footer.php"; ?>
