<?php
ob_start();
require "../includes/header.php"; 
require "../config/config.php"; 

$message = '';
if (isset($_GET['key']) && isset($_GET['token'])) {
    $email = $_GET['key'];
    $token = $_GET['token'];
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = :token AND email = :email");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($reset) {
        if (isset($_POST['submit'])) {
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET mypassword = :new_password WHERE email = :email");
            $stmt->bindParam(':new_password', $new_password);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Delete the token
            $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $message = 'Your password has been successfully reset.';
        }
    } else {
        $message = 'This link is invalid or has expired.';
    }
} else {
    $message = 'Invalid request.';
}

echo $message;
?>
<section class="section-hero overlay inner-page bg-image" style="background-image: url('<?php echo APPURL; ?>/images/hero_1.jpg');" id="home-section">
  <div class="container">
    <div class="row">
      <div class="col-md-7">
        <h1 class="text-white font-weight-bold">Forget Password</h1>
        <div class="custom-breadcrumbs">
          <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
          <span class="text-white"><strong>Forget Password</strong></span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="site-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <form action="reset_password.php" class="p-4 border rounded" method="POST">
            <?php if (isset($reset)): ?>
          <div class="row form-group">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="text-black" for="email">New Password</label>
              <input type="password" name="new_password" required>
          </div>
      </div>
      <div class="row form-group">
            <div class="col-md-12">
              <input type="submit" name="submit" value="Send Password Reset Link" class="btn px-4 btn-primary text-white">
            </div>
          </div>
        </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>



<?php require "../includes/footer.php"; ?>
