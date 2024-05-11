<?php require "includes/header.php"; ?>
<?php require "config/config.php"; ?>

<?php
$arts = [];
$searchTerm = ""; // Initialize search term variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['art-title'])) {
    $searchTerm = htmlspecialchars($_POST['art-title']); // Sanitize the input

    if($searchTerm==""){
        echo "<script>alert('rt');</script>";
    }

    try {
        $sql = "SELECT arts.art_id, arts.title, arts.description, arts.image, arts.created_at, users.fullname as artist_name FROM arts JOIN users ON arts.artist_id = users.id WHERE arts.title LIKE ? AND arts.status = 1 ORDER BY arts.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, "%" . $searchTerm . "%");
        $stmt->execute();
        $arts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<section class="section-hero overlay inner-page bg-image" style="background-image: url('images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Search</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <a href="#">Search</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong><?php echo $searchTerm; ?></strong></span>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container">
    <h2 class="section-title text-center">Search Results for '<?php echo $searchTerm; ?>' (found)</h2>
    <div class="row">
        <?php if (count($arts) > 0): ?>
            <?php foreach ($arts as $art): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="images/<?php echo htmlspecialchars($art['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($art['title']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($art['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($art['description'], 0, 100)) . '...'; ?></p>
                        <small class="text-muted">Posted by <?php echo htmlspecialchars($art['artist_name']); ?> on <?php echo date('F j, Y', strtotime($art['created_at'])); ?></small>
                        <div><a href="art_details.php?art_id=<?php echo $art['art_id']; ?>" class="btn btn-primary">View More</a></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No results found for '<?php echo $searchTerm; ?>'. Please try a different search term.</p>
        <?php endif; ?>
    </div>
</div>
<section class="site-section">
  <div class="container">
    <h2 class="section-title text-center">All Arts</h2>
    <div class="row">
        <?php foreach ($arts as $art): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="./images/<?php echo htmlspecialchars($art['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($art['title']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($art['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars(substr($art['description'], 0, 100)) . '...'; ?></p>
                    <small class="text-muted">Posted by <?php echo htmlspecialchars($art['artist_name']); ?> on <?php echo date('F j, Y', strtotime($art['created_at'])); ?></small>
                    <div class="mt-2">
                        <a href="art_details.php?art_id=<?php echo $art['art_id']; ?>" class="btn btn-primary">View More</a>

                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</section>

<?php require "includes/footer.php"; ?>
