<?php
require '../config/config.php'; // Database connection
require "includes/header.php";
require "includes/sidebar.php";

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: loginadmin.php");
    exit;
}
$id = $_SESSION['id']; // Retrieve admin ID from session

  if(isset($_POST['submit'])) {

    if(empty($_POST['fullname']) OR empty($_POST['username']) OR empty($_POST['email']) OR empty($_POST['password']) OR empty($_POST['re-password'])) {
      echo "<script>alert('some inputs are empty')</script>";
    } else {

      $fullname = $_POST['fullname'];
      $username = $_POST['username'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $repassword = $_POST['re-password'];
      $img = 'm.png';
      $type = $_POST['type'];

      //checking for password match
      if($password == $repassword) {

        
        //checking for username 
        if(strlen($email) > 22 OR strlen($username) > 15 ) {
          echo "<script>alert('email or username is too big')</script>";

        } else {


          //checking for username or pass avaialibilty
          $validate = $conn->query("SELECT * FROM users WHERE email = '$email' OR username = '$username'");
          $validate->execute();

          if($validate->rowCount() > 0) {
            echo "<script>alert('email or username is aleardy taken')</script>";

          } else {

            $insert = $conn->prepare("INSERT INTO users (fullname,username, email, mypassword, img, type) 
            VALUES (:fullname, :username, :email, :mypassword, :img, :type)");
  
            $insert->execute([
              ':fullname' =>  $fullname,
              ':username' =>  $username,
              ':email' =>  $email,
              ':mypassword' =>  password_hash($password, PASSWORD_DEFAULT),
              ':img' =>  $img,
              ':type' =>  $type,
            ]);  
  
            echo "<script>alert('Data Saved')</script>";
          }
          
         

        }

       

      } else {
        echo "<script>alert('passwords are not matching')</script>";

      }


    }
  }

  if(isset($_POST['cancel'])) {
       echo "<script>window.location.href = 'viewusers.php';</script>";
    }


?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Users </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
          <li class="breadcrumb-item active">Registeration Form</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Form</h5>

              <form action="register.php" method="post">
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">FullName</label>
                  <div class="col-sm-10">
                    <input type="text" name="fullname" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail" class="col-sm-2 col-form-label">Username</label>
                  <div class="col-sm-10">
                    <input type="text" name="username" class="form-control">
                  </div>
                </div>
                 <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">User Type</label>
                  <div class="col-sm-10">
                    <select class="form-select" name="type" aria-label="Default select example">
                      <option selected>select User Type</option>
                      <option value="Artist">Artist</option>
                      <option value="User">User</option>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" name="email" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" name="password" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Re-Password</label>
                  <div class="col-sm-10">
                    <input type="password" name="re-password" class="form-control">
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Save</label>
                  <div class="col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">Save Form</button>
                    <button type="submit" name="cancel" class="btn btn-danger">Cancel</button>
                  </div>
                </div>

              </form><!-- End Form -->

            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->

  <?php require "includes/footer.php"; ?>