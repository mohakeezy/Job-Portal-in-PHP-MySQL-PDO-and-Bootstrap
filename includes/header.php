<?php 

  session_start();



  define("APPURL", "http://localhost/LAM");

?>
<!doctype html>
<html lang="en">
  <head>
    <title>LOCAL ARTISAN MARKETPLACE</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Free-Template.co" />
    <link rel="shortcut icon" href="ftco-32x32.png">
    
    <link rel="stylesheet" href="<?php echo APPURL; ?>/css/custom-bs.css">
    <link rel="stylesheet" href="<?php echo APPURL; ?>/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="<?php echo APPURL; ?>/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="<?php echo APPURL; ?>/fonts/icomoon/style.css">
    <link rel="stylesheet" href="<?php echo APPURL; ?>/fonts/line-icons/style.css">
    <link rel="stylesheet" href="<?php echo APPURL; ?>/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo APPURL; ?>/css/animate.min.css">
    <link rel="stylesheet" href="<?php echo APPURL; ?>/css/quill.snow.css">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="<?php echo APPURL; ?>/css/style.css">   
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">



     <style>

      .fa-heart-o { /* Non-favorite state */
    color: grey; /* Example color */
}
.fa-heart { /* Favorite state */
    color: red; /* Example color */
}

    .circular-image {
        width: 100px; /* or any size */
        height: 100px; /* should be the same as width to maintain aspect ratio */
        border-radius: 50%; /* this creates the circle */
        object-fit: cover; /* keeps the image aspect ratio */
        border: 2px solid #fff; /* optional: adds a border around the image */
    }

        /* Ensure all card images have the same size */
        .card-img-top {
            height:400px; /* Fixed height for consistency */
            width: 100%;  /* Full width to cover the card */
            object-fit: cover; /* Covers the dimension of the block, may crop some parts of the image */
        }
        .card-body {
            min-height: 180px; /* Ensures all card bodies are of equal height for text alignment */
        }
        .artist-profile img {
    max-width: 150px; /* Adjust size as needed */
}
.artwork-img {
        width: 100%;  /* Makes image responsive */
        height: 400px; /* Fixed height */
        object-fit: cover; /* Ensures images cover the area without distorting the aspect ratio */
    }

    .card{
       height:100%; /* Fixed height for consistency */
            width: 100%;  /* Full width to cover the card */
            object-fit: cover;
    }


    </style> 
  </head>
  <body id="top">

   <div id="overlayer"></div>
  <div class="loader">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div> 
    

<div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div> <!-- .site-mobile-menu -->
    

    <!-- NAVBAR -->
    <header class="site-navbar mt-3">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div  class="site-logo col-6"><a href="<?php echo APPURL; ?>">
            <!-- logo -->
            <div class="site-logo">
              <a href="<?php echo APPURL; ?>">
                <!-- <img style="width: 40%;height: 20%;" src="images/logo.png" alt=""> -->
                LAM
              </a>
            </div>
            <!-- logo -->
            </div>

          <nav class="mx-auto site-navigation">
            <ul style="margin-right: -500px" class="site-menu js-clone-nav d-inline d-xl-block ml-0 pl-0">
              <li><a href="<?php echo APPURL; ?>"  class="nav-link active">Home</a></li>
              <li><a href="<?php echo APPURL; ?>/about.php">About</a></li>
              <li><a href="<?php echo APPURL; ?>/contact.php">Contact</a></li>
              <!-- <li><a href="<?php echo APPURL; ?>/gerneral/workers.php">Artists</a></li>
              <li><a href="<?php echo APPURL; ?>/gerneral/companies.php">Companies</a></li> -->
              <li><a href="<?php echo APPURL; ?>/gerneral/artist.php">Artist</a></li>
              <!-- <li><a href="<?php echo APPURL; ?>/gerneral/buyers.php">Buyers</a></li> -->



              <?php if(isset($_SESSION['username'])) : ?>
                  <?php if(isset($_SESSION['type']) AND $_SESSION['type'] == "Artist") : ?>
                    <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Sell Now
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="<?php echo APPURL; ?>/arts/post-art.php">Requirements</a>
                  <a class="dropdown-item" href="#">Painting</a>
                  <a class="dropdown-item" href="#">Sculpture</a>
                  <a class="dropdown-item" href="#">Photography</a>
                </div>
              </li>
                  <?php endif; ?>
                  <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $_SESSION['username']; ?>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo APPURL; ?>/users/public-profile.php?id=<?php echo $_SESSION['id']; ?>">Public profile</a>
                    <a class="dropdown-item" href="<?php echo APPURL; ?>/users/update-profile.php?upd_id=<?php echo $_SESSION['id']; ?>">Update profile</a>
                    <a class="dropdown-item" href="<?php echo APPURL; ?>/users/change_password.php?upd_id=<?php echo $_SESSION['id']; ?>">Change Password</a>
                    <?php if(isset($_SESSION['type']) AND $_SESSION['type'] == "User" OR $_SESSION['type'] == "Artist") : ?>
                      <a class="dropdown-item" href="<?php echo APPURL; ?>/Favourite/view_favorite_art.php?id=<?php echo $_SESSION['id']; ?>">Favourite Art</a>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['type']) AND $_SESSION['type'] == "Artist") : ?>
                      <a class="dropdown-item" href="<?php echo APPURL; ?>/arts/show-art.php?artist_id=<?php echo $_SESSION['id']; ?>">Show Art</a>

                    <?php endif; ?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo APPURL; ?>/auth/logout.php">Logout</a>
                  </div>
                </li>

              <?php else: ?>  

            
             
                <li class="d-lg-inline"><a href="<?php echo APPURL; ?>/auth/login.php">Log In</a></li>
                <li class="d-lg-inline"><a href="<?php echo APPURL; ?>/auth/register.php">Register</a></li>
              <?php endif; ?>
            </ul>
          </nav>
          
      
        </div>
      </div>
    </header>