<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>

<?php 
    // Check if the user is authorized to update the art
    if(isset($_SESSION['type']) && $_SESSION['type'] !== "Artist") {
        header("location: ".APPURL."");
    } 

    // Fetch categories for the art
    $get_categories = $conn->query("SELECT * FROM categories");
    $get_categories->execute();
    $categories = $get_categories->fetchAll(PDO::FETCH_OBJ);

    // Fetch the art details to be updated
    if(isset($_GET['art_id'])) {
        $art_id = $_GET['art_id'];
        $stmt = $conn->prepare("SELECT * FROM art WHERE art_id = :art_id");
        $stmt->bindParam(':art_id', $art_id, PDO::PARAM_INT);
        $stmt->execute();
        $art = $stmt->fetch(PDO::FETCH_OBJ);

        // Redirect if the art does not belong to the logged-in user
        if($art->artist_id !== $_SESSION['id']) {
            header("location: ".APPURL."");
        }
    } else {
        header("location: ".APPURL."/404.php");
    }

    // Handle the form submission to update the art
    if(isset($_POST['submit'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];

        // Perform validation checks...

        $update_stmt = $conn->prepare("UPDATE art SET title = :title, description = :description, category_id = :category_id WHERE art_id = :art_id");
        $update_result = $update_stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category_id' => $category_id,
            ':art_id' => $art_id
        ]);

        if($update_result) {
            header("Location: viewart.php?msg=Art updated successfully.");
        } else {
            echo "<script>alert('An error occurred while updating the art.');</script>";
        }
    }
?>

<!-- HTML Form for Updating Art -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Update Art</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Update Art</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="site-section">
    <div class="container">
        <form action="updateart.php?art_id=<?php echo $art_id; ?>" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($art->title); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($art->description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" class="form-control">
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category->category_id; ?>" <?php echo $category->category_id == $art->category_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($category->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update Art</button>
        </form>
    </div>
</section>

<?php require "../includes/footer.php"; ?>
