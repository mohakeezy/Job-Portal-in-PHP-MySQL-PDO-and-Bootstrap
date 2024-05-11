<?php
require "../includes/header.php";  // Assuming session_start() is called within this file
require "../config/config.php";

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'];

try {
    // Prepare a SQL statement to fetch the favorited arts
    $sql = "SELECT arts.art_id, arts.title, arts.description, arts.image, users.fullname as artist_name
            FROM favorites
            JOIN arts ON favorites.art_id = arts.art_id
            JOIN users ON arts.artist_id = users.id
            WHERE favorites.user_id = :user_id AND arts.status = 1";  // Active arts only

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $favorite_arts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit;
}
?>

<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Your Favorites</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Favorites</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="site-section">
    <div class="container">
        <h2 class="section-title text-center">Your Favorite Artworks</h2>
        <div class="row">
            <?php if (!empty($favorite_arts)): ?>
                <?php foreach ($favorite_arts as $art): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="../images/<?php echo htmlspecialchars($art['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($art['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($art['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($art['description']); ?></p>
                                <small class="text-muted">Artist: <?php echo htmlspecialchars($art['artist_name']); ?></small>
                                <div class="mt-2">
                                    <a href="art_details.php?art_id=<?php echo $art['art_id']; ?>" class="btn btn-primary">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">You have no favorite arts yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require "../includes/footer.php"; ?>
