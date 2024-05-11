<?php
require 'config/config.php'; // Database connection
require "includes/header.php";
  try {
    // Fetch all active arts in random order
    $sql = "SELECT arts.art_id, arts.title, arts.description, arts.image, arts.created_at, arts.updated_at, arts.status, users.fullname as artist_name FROM arts JOIN users ON arts.artist_id = users.id WHERE arts.status = 1 ORDER BY RAND()";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $arts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the latest 4 posts ordered by creation date descending
    $sql_latest = "SELECT arts.art_id, arts.title, arts.description, arts.image, arts.created_at, users.fullname as artist_name FROM arts JOIN users ON arts.artist_id = users.id WHERE arts.status = 1 ORDER BY arts.created_at DESC LIMIT 3";
    $stmt_latest = $conn->prepare($sql_latest);
    $stmt_latest->execute();
    $latest_arts = $stmt_latest->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
  }

  try {
    $sql_count = "SELECT COUNT(*) as total_arts FROM arts WHERE status = 1";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->execute();
    $result = $stmt_count->fetch(PDO::FETCH_ASSOC);
    $total_arts = $result['total_arts'];
    $usersCount = $conn->query("SELECT COUNT(*) FROM users WHERE type = 'User'")->fetchColumn();

// Tirooyinka farshaxanka (arts)
$artsCount = $conn->query("SELECT COUNT(*) FROM arts")->fetchColumn();

// Tirooyinka fanaaniinta (artists)
$artistsCount = $conn->query("SELECT COUNT(*) FROM users WHERE type = 'artist'")->fetchColumn();

  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $total_arts = 0; // default to 0 in case of an error
  }
  try {

    $sql = "SELECT * FROM categories"; // Adjust if your table or columns differ
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Set the resulting array to associative
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    $categories = []; // Define as an empty array in case of database connection failure
}


try {
    $sql = "SELECT arts.art_id, arts.title, arts.description, arts.image, arts.created_at, arts.status, users.fullname as artist_name,
            EXISTS (SELECT 1 FROM favorites WHERE favorites.art_id = arts.art_id AND favorites.user_id = :user_id) AS is_favorited
            FROM arts
            JOIN users ON arts.artist_id = users.id
            WHERE arts.status = 1
            ORDER BY RAND()";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $arts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

?>
    <!-- HOME -->
    <section class="home-section section-hero overlay bg-image" style="background-image: url('images/hero_1.jpg');" id="home-section">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-12">
            <div class="mb-5 text-center">
              <h1 class="text-white font-weight-bold">The Easiest Way To Get Your Art</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate est, consequuntur perferendis.</p>
            </div>
            <form method="post" action="search.php" class="search-jobs-form" onsubmit="performSearch(event)">
    <div class="row mb-5">
        <div class="col-12 col-sm-6 col-md-6 col-lg-8 mb-4 mb-lg-0">
            <input name="art-title" type="text" class="form-control form-control-lg" placeholder="Art title" id="searchInput">
        </div>

        <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0">
            <button type="submit" class="btn btn-primary btn-lg btn-block text-white btn-search"><span class="icon-search icon mr-2"></span>Search Art</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 popular-keywords">
            <h3>Trending Keywords:</h3>
            <ul class="keywords list-unstyled m-0 p-0">
                <li><a href="#" class="">1</a></li>
                <li><a href="#" class="">2</a></li>
                <li><a href="#" class="">3</a></li>
            </ul>
        </div>
    </div>
</form>

          </div>
        </div>
      </div>

      <a href="#next" class="scroll-button smoothscroll">
        <span class=" icon-keyboard_arrow_down"></span>
      </a>

    </section>

  
    
  <section class="py-5 bg-image overlay-primary fixed overlay" id="next" style="background-image: url('images/hero_1.jpg');">
  <div class="container">
    <div class="row mb-5 justify-content-center">
      <div class="col-md-7 text-center">
        <h2 class="section-title mb-2 text-white">Local artisan marketplace</h2>
        <p class="lead text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita unde officiis recusandae sequi excepturi corrupti.</p>
      </div>
    </div>
    <div class="row pb-0 block__19738 section-counter">

      <div class="col-6 col-md-6 col-lg-4 mb-5 mb-lg-0">
        <div class="d-flex align-items-center justify-content-center mb-2">
          <strong class="number" data-number="<?php echo $usersCount; ?>">0</strong>
        </div>
        <span class="caption">Users</span>
      </div>

      <div class="col-6 col-md-6 col-lg-4 mb-5 mb-lg-0">
        <div class="d-flex align-items-center justify-content-center mb-2">
          <strong class="number" data-number="<?php echo htmlspecialchars($total_arts); ?>"><?php echo htmlspecialchars($total_arts); ?></strong>
        </div>
        <span class="caption">Art Posted</span>
      </div>

      <div class="col-6 col-md-6 col-lg-4 mb-5 mb-lg-0">
        <div class="d-flex align-items-center justify-content-center mb-2">
          <strong class="number" data-number="<?php echo $artistsCount; ?>">0</strong>
        </div>
        <span class="caption">Artists</span>
      </div>

    </div>
  </div>
</section>

<section class="site-section">
  <div class="container">
  <h2 class="section-title text-center">Categories</h2>
  <div class="row">
   <!-- Dynamic category listing -->
<?php foreach ($categories as $category): ?>
  <div class="col-md-4">
    <div class="card">
      <?php if (!empty($category['image']) && file_exists("UploadCategories/" . $category['image'])): ?>
        <!-- Display image if it exists -->
        <a href="categories/explore_category.php?category_id=<?php echo $category['category_id']; ?>">
        <img src="UploadCategories/<?php echo htmlspecialchars($category['image']); ?>" class="card-img-top" alt="Image for <?php echo htmlspecialchars($category['name']); ?>">
      <?php else: ?>
        <!-- Default image placeholder if no image exists -->
        <img src="path/to/default/image.jpg" class="card-img-top" alt="Default Image">
      <?php endif; ?>
      <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
        <p class="card-text"><?php echo htmlspecialchars($category['description']); ?></p>
      </div>
    </a>
    </div>
  </div>
<?php endforeach; ?>
  </div>
</div>
</section>


    <!-- New Section for Latest Posts -->
<section class="latest-posts-section">
    <div class="container">
        <h2 class="section-title text-center">Latest Art Posts</h2>
        <div class="row">
            <?php foreach ($latest_arts as $art): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                  
                    <img src="images/<?php echo htmlspecialchars($art['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($art['title']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($art['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($art['description'], 0, 100)) . '...'; ?></p>
                        <small class="text-muted">Posted by <?php echo htmlspecialchars($art['artist_name']); ?> on <?php echo date('F j, Y', strtotime($art['created_at'])); ?></small>
                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <a href="arts/art_details.php?art_id=<?php echo $art['art_id']; ?>" class="btn btn-primary">View More</a>
                             <a href="Favourite/favourite_art.php?art_id=<?php echo $art['art_id']; ?>" class="btn btn-warning">Favorite</a>
                        </div>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


    
<section class="site-section">
  <div class="container">
    <h2 class="section-title text-center">All Arts</h2>
    <div class="row">
        <?php foreach ($arts as $art): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="images/<?php echo htmlspecialchars($art['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($art['title']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($art['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars(substr($art['description'], 0, 100)) . '...'; ?></p>
                    <small class="text-muted">Posted by <?php echo htmlspecialchars($art['artist_name']); ?> on <?php echo date('F j, Y', strtotime($art['created_at'])); ?></small>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <a href="arts/art_details.php?art_id=<?php echo $art['art_id']; ?>" class="btn btn-primary">View More</a>
                        <?php if (!$art['is_favorited']): ?>
                            <a href="Favourite/favourite_art.php?art_id=<?php echo $art['art_id']; ?>" class="btn btn-warning">Favorite</a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>Already Favorite</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</section>







   
    
<?php require "includes/footer.php"; ?>

