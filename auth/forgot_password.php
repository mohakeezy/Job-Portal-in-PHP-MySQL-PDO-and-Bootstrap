<?php
ob_start();
require "../includes/header.php";
require "../config/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Ensure PHPMailer is loaded via Composer's autoload

if (isset($_POST['submit']) && !empty($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        echo "<script>alert('Invalid email format');</script>";
        return;
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = new DateTime('now +1 hour');
        $expiresFormatted = $expires->format('Y-m-d H:i:s');

        // Store the token and expiration date in the database
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expDate) VALUES (:email, :token, :expDate)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expDate', $expiresFormatted);
        $stmt->execute();

        // Prepare and send the reset email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mohamedabdifitahali60@gmail.com'; // Use your Gmail
            $mail->Password = 'Biin@1100'; // Use your generated App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('mohamedabdifitahali60@gmail.com', 'Som Artisans');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Reset your password';
            $url = "https://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=$token";
            $mail->Body    = "Please click on the following link to reset your password: <a href='$url'>$url</a>";

            $mail->send();
            echo "<script>alert('A password reset link has been sent to your email.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error sending email: " . $mail->ErrorInfo . "');</script>";
        }

    } else {
        echo "<script>alert('No account found with that email address.');</script>";
    }
}
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
        <form action="forgot_password.php" method="POST" class="p-4 border rounded">
          <div class="row form-group">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="text-black" for="email">Email</label>
              <input type="email" id="email" class="form-control" placeholder="Email address" name="email" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-md-12">
              <input type="submit" name="submit" value="Send Password Reset Link" class="btn px-4 btn-primary text-white">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require "../includes/footer.php"; ?>
