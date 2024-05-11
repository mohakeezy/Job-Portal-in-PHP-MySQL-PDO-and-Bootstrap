<?php
session_start();
require "../config/config.php"; // Database connection



$message = ''; // To hold error messages

if (isset($_POST['submit'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $message = 'Some inputs are empty';
    } else {
        // Prepared statement to prevent SQL Injection
        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if password is correct
        if ($admin && password_verify($password, $admin['mypassword'])) {
            // Setting session variables
            $_SESSION['id'] = $admin['id'];
            $_SESSION['adminname'] = $admin['adminname'];
            $_SESSION['email'] = $admin['email'];

            header("location: index.php"); // Redirect to the dashboard
            exit();
        } else {
            $message = 'Invalid email or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>LAM | Login</title>
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<main>
  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <div class="d-flex justify-content-center py-4">
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">LAM</span>
              </a>
            </div>
            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                  <p class="text-center small">Enter your email & password to login</p>
                </div>
                <form action="loginadmin.php" method="post">
                  <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="form-control" name="email" required>
                  </div>
                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" name="password" required>
                  </div>
                  <div class="col-12">
                    <button type="submit" name="submit" class="btn btn-primary w-100">Log In</button>
                  </div>
                </form>
                <?php if ($message) echo "<p class='text-danger mt-2'>$message</p>"; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>
<?php include "../includes/footer.php"; ?>
</body>
</html>
