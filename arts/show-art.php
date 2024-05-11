<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>

<?php 
    // Ensure the user is logged in and is an artist
    if(!isset($_SESSION['type']) || $_SESSION['type'] !== "Artist") {                
        header("location: ".APPURL."");
        exit;
    }

    // Check for a valid artist ID in the URL
    if(isset($_GET['artist_id']) && is_numeric($_GET['artist_id'])) {
        $artist_id = $_GET['artist_id'];

        // Ensure the logged-in user matches the artist ID
        if($_SESSION['id'] !== $artist_id) {              
            header("location: ".APPURL."");
            exit;
        }

        // Fetch the artist's profile
        $select = $conn->prepare("SELECT username FROM users WHERE id = :artist_id");
        $select->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
        $select->execute();
        $profile = $select->fetch(PDO::FETCH_OBJ);

        // Fetch the artist's arts
        $getArts = $conn->prepare("SELECT art_id, title, description, image, created_at, updated_at FROM arts WHERE artist_id = :artist_id");
        $getArts->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
        $getArts->execute();
        $arts = $getArts->fetchAll(PDO::FETCH_OBJ);

    } else {
        echo "404";
        exit;
    }
?>

<!-- Page Content -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('<?php echo APPURL; ?>/images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold"><?php echo htmlspecialchars($profile->username); ?>'s Art Gallery</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Art Gallery</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="site-section">
    <div class="container">
        <?php if(empty($arts)): ?>
            <p>No art pieces found for this artist.</p>
        <?php else: ?>
            <div class="row">
              <?php foreach($arts as $art): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="art-images/<?php echo htmlspecialchars($art->image); ?>" alt="<?php echo htmlspecialchars($art->title); ?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($art->title); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($art->description); ?></p>
                            <small>Posted on <?php echo date("F d, Y", strtotime($art->created_at)); ?></small>
                        </div>
                    </div>
                </div>
              <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require "../includes/footer.php"; ?>
