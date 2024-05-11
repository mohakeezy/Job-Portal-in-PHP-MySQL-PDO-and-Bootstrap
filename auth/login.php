<?php 
ob_start(); // Start output buffering at the very top
require "../includes/header.php"; 
require "../config/config.php"; 

if (isset($_SESSION['username'])) {
    header("location: " . APPURL);
    exit(); // Stop script execution after a redirect
}

$message = '';

if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $message = 'Some inputs are empty.';
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mypassword'])) {
            // Set session variables after successful login
            $_SESSION['username'] = $user['username'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['image'] = $user['img'];

            header("location: " . APPURL);
            exit();
        } else {
             echo "<script>alert('Invalid username or password.')</script>";
        }
    }
}
?>

<section class="section-hero overlay inner-page bg-image" style="background-image: url('<?php echo APPURL; ?>/images/hero_1.jpg');" id="home-section">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h1 class="text-white font-weight-bold">Log In</h1>
        <div class="custom-breadcrumbs">
          <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
          <span class="text-white"><strong>Log In</strong></span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="site-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <form action="login.php" class="p-4 border rounded" method="POST">
          <div class="row form-group">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="text-black" for="email">Email</label>
              <input type="email" id="email" class="form-control" placeholder="Email address" name="email">
            </div>
          </div>
          <div class="row form-group mb-4">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="text-black" for="password">Password</label>
              <input type="password" id="password" class="form-control" placeholder="Password" name="password">
            </div>
          </div>
          <div class="row form-group">
            <div class="col-md-12">
              <input type="submit" name="submit" value="Log In" class="btn px-4 btn-primary text-white">
            </div>
          </div>
          <div class="row form-group">
            <div class="col-md-12">
              <a href="forgot_password.php">Forgot Password?</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require "../includes/footer.php"; ?>
