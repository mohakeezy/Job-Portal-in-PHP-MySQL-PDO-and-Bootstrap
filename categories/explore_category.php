<?php 
require "../includes/header.php"; 
require "../config/config.php"; 

// Start output buffering
ob_start(); 

// Initialize variables and default values
$arts = [];
$error = '';

// Fetch category_id from GET request, validate and sanitize
$category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);

if ($category_id === null || $category_id === false) {
    $error = 'Invalid category ID.';
} else {
    try {
        // Prepare and execute the SQL statement
        $sql = "SELECT arts.*, users.fullname as artist_name FROM arts 
                JOIN users ON arts.artist_id = users.id 
                WHERE arts.category_id = :category_id AND arts.status = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $arts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if any art was found
        if (empty($arts)) {
            $error = 'No art found for this category.';
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }

    try {
    // Fetch category name
    $categorySql = "SELECT name FROM categories WHERE category_id = :category_id";
    $categoryStmt = $conn->prepare($categorySql);
    $categoryStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $categoryStmt->execute();
    $categoryResult = $categoryStmt->fetch(PDO::FETCH_ASSOC);
    if ($categoryResult) {
        $categoryName = $categoryResult['name'];
    } else {
        $error = 'Category not found.';
    }

    // More code...
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
}
?>

<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold"><?php echo htmlspecialchars($categoryName); ?></h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Category View</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>


    <div class="container">
        <h2 class="section-title text-center">Arts In This Category: (<?php echo htmlspecialchars($categoryName); ?>)</h2>
        <?php if ($error): ?>
            <p class="error alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($arts as $art): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="../images/<?php echo htmlspecialchars($art['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($art['title']); ?>" style="height: 400px;width: 100%; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($art['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($art['description']); ?></p>
                                <small class="text-muted">Posted by <?php echo !empty($art['artist_name']) ? htmlspecialchars($art['artist_name']) : 'Unknown Artist'; ?> on <?php echo date('F j, Y', strtotime($art['created_at'])); ?></small>
                                <a href="art_details.php?art_id=<?php echo $art['art_id']; ?>" class="btn btn-primary mt-auto">View More</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php require "../includes/footer.php"; ?>
