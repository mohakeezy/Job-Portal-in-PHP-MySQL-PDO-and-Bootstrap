<?php 
require "../includes/header.php"; 
require "../config/config.php"; 

if(isset($_POST['cancel'])) {
    echo "<script>alert('Redirecting...'); window.location.href = '../index.php';</script>";
}

// Fetch the user's profile details
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $profile = $stmt->fetch(PDO::FETCH_OBJ);

    if(!$profile) {
        echo "User not found.";
        exit;
    }

    // Initialize artworks array
    $artworks = null;

    // If the user is an artist, fetch their artworks
    if ($profile->type == 'Artist') {
        $artworksStmt = $conn->prepare("SELECT * FROM arts WHERE artist_id = :artist_id");
        $artworksStmt->bindParam(':artist_id', $id, PDO::PARAM_INT);
        $artworksStmt->execute();
        $artworks = $artworksStmt->fetchAll(PDO::FETCH_OBJ);
    }
} else {
    echo "404";
    exit;
}
?>

<section class="section-hero overlay inner-page bg-image" style="background-image: url('<?php echo APPURL; ?>/images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold"><?php echo htmlspecialchars($profile->username); ?>'s Profile</h1>
            </div>
        </div>
    </div>
</section>

<section class="site-section" id="profile-section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-7">
                <div class="card p-3 py-4">
                    <div class="text-center">
                        <img src="../images/<?php echo htmlspecialchars($profile->img); ?>" width="100" class="rounded-circle">
                    </div>
                    <div class="text-center mt-3">
                        <h5 class="mt-2 mb-0"><?php echo htmlspecialchars($profile->username); ?></h5>
                        <p><?php echo htmlspecialchars($profile->bio); ?></p>
                    </div>
                     <div class="px-3 text-center">
                            <a href="<?php echo $profile->facebook; ?>" class="pt-3 pb-3 pr-3 pl-0 underline-none"><span class="icon-facebook"></span></a>
                            <a href="<?php echo $profile->twitter; ?>" class="pt-3 pb-3 pr-3 pl-0"><span class="icon-twitter"></span></a>
                            <a href="<?php echo $profile->linkedin; ?>" class="pt-3 pb-3 pr-3 pl-0"><span class="icon-linkedin"></span></a>
                        </div>
                </div>
            </div>
        </div>

        <?php if ($profile->type == 'Artist' && $artworks): ?>
        <div class="container mt-4">
            <div class="row">
                <?php foreach($artworks as $artwork): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="../images/<?php echo htmlspecialchars($artwork->image); ?>" alt="<?php echo htmlspecialchars($artwork->title); ?>" class="card-img-top artwork-img">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($artwork->title); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($artwork->description); ?></p>
                                <a href="../arts/edit_art.php?art_id=<?php echo $artwork->art_id; ?>" class="btn btn-primary">Edit</a>
                                <a href="../arts/delete_art.php?art_id=<?php echo $artwork->art_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this artwork?');">Delete</a>


                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center"></p>
        <?php endif; ?>
        </div>
    </div>
</div>

</section>

<?php require "../includes/footer.php"; ?>
