<?php 
require "../includes/header.php"; 
require "../config/config.php"; 

// Start output buffering
ob_start(); 

// Validate the 'art_id' GET parameter
if (isset($_GET['art_id']) && ctype_digit($_GET['art_id'])) {
    $art_id = $_GET['art_id'];

    try {
        // Prepare SQL to fetch artwork details along with artist and category information
        $sql = "SELECT a.art_id, a.title, a.description, a.image, a.created_at, 
                       u.fullname as artist_name, u.img as artist_image, 
                       c.name as category_name
                FROM arts a
                JOIN users u ON a.artist_id = u.id
                JOIN categories c ON a.category_id = c.category_id
                WHERE a.art_id = :art_id AND a.status = 1";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':art_id', $art_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            // Redirect if no artwork is found
            header("Location: 404.php");
            exit;
        }

        $art = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // Log error message and redirect in case of an exception
        error_log('PDOException - ' . $e->getMessage(), 0);
        header("Location: error.php");
        exit;
    }
} else {
    // Redirect if 'art_id' parameter is missing or invalid
    header("Location: 404.php");
    exit;
}

?>

<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Art Details</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Art Details</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="site-section">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="d-flex align-items-center">
                    <div class="border  p-2 d-inline-block mr-3 circular-image">
                        <!-- Display artist image or a default image if not available -->
                        <?php if (!empty($art['artist_image'])): ?>
                            <img src="../images/<?php echo htmlspecialchars($art['artist_image']); ?>" style="height:80px; width:100%; object-fit: cover;border-radius: 15px;" alt="<?php echo htmlspecialchars($art['artist_name']); ?> Image" class="img-fluid rounded circular-image">
                        <?php else: ?>
                            <img src="../images/default_user.png" alt="Default Artist Image" class="img-fluid rounded">
                        <?php endif; ?>
                    </div>
                    <div>
                        <h2><?php echo htmlspecialchars($art['artist_name']); ?></h2>
                        <div>
                            <span class="ml-0 mr-2 mb-2"><span class="icon-briefcase mr-2"></span>Category: <?php echo htmlspecialchars($art['category_name']); ?></span>
                            <span class="m-2"><span class="icon-room mr-2"></span>Location: Somalia/Mogadishu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="mb-5">
                    <figure class="mb-5">
                        <!-- Display artwork image -->
                        <img src="../images/<?php echo htmlspecialchars($art['image']); ?>" style="height:600px; width:100%; object-fit: cover;" alt="<?php echo htmlspecialchars($art['title']); ?>" class="img-fluid rounded">
                    </figure>
                    <h3 class="h5 d-flex align-items-center mb-4 text-primary"><span class="icon-align-left mr-3"></span><?php echo htmlspecialchars($art['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($art['description'])); ?></p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="bg-light p-3 border rounded mb-4">
                    <h3 class="text-primary mt-3 h5 pl-3 mb-3">Art Details</h3>
                    <ul class="list-unstyled pl-3 mb-0">
                        <li class="mb-2"><strong class="text-black">Published on:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($art['created_at']))); ?></li>
                        <li class="mb-2"><strong class="text-black">Category:</strong> <?php echo htmlspecialchars($art['category_name']); ?></li>
                    </ul>
                </div>

                <div class="bg-light p-3 border rounded">
                    <h3 class="text-primary mt-3 h5 pl-3 mb-3">Share</h3>
                    <div class="px-3">
                        <a href="#" class="pt-3 pb-3 pr-3 pl-0"><span class="icon-facebook"></span></a>
                        <a href="#" class="pt-3 pb-3 pr-3 pl-0"><span class="icon-twitter"></span></a>
                        <a href="#" class="pt-3 pb-3 pr-3 pl-0"><span class="icon-linkedin"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require "../includes/footer.php"; ?>
