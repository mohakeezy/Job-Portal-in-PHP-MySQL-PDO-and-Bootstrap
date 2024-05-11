<?php require "includes/header.php"; ?>
<?php require "includes/sidebar.php"; ?>
<?php require "../config/config.php"; ?>

<?php 
if(isset($_POST['submit'])) {

    if(empty($_POST['adminname']) OR empty($_POST['email']) OR empty($_POST['password']) OR empty($_POST['re-password'])) {
      echo "<script>alert('some inputs are empty')</script>";
    } else {

      $adminname = $_POST['adminname'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $repassword = $_POST['re-password'];

      //checking for password match
      if($password != $repassword) {
        echo "<script>alert('password are not same')</script>";
      } 

        
        $insert = $conn->prepare("INSERT INTO admins (adminname, email, mypassword) 
            VALUES (:adminname, :email, :mypassword)");
  
            $insert->execute([
              ':adminname' =>  $adminname,
              ':email' =>  $email,
              ':mypassword' =>  password_hash($password, PASSWORD_DEFAULT),
            ]);  
  
            echo "<script>alert('Data Saved')</script>";
          }
        }
          
         

       
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Admins </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
          <li class="breadcrumb-item active">Admins</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">

      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Form</h5>

              <form action="admin.php" method="post">
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Admin Name</label>
                  <div class="col-sm-10">
                    <input type="text" name="adminname" placeholder="Enter Admin Name" class="form-control">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                  <input type="email" id="fname" class="form-control" placeholder="Email address" name="email">
                </div>
              </div>
              <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                  <input type="password" id="fname" class="form-control" placeholder="Password" name="password">
                </div>
              </div>
              <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Re-type Password</label>
                  <div class="col-sm-10">
                  <input type="password" id="fname" class="form-control" placeholder="Re-type Password" name="re-password">
                </div>
              </div>
               
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Save</label>
                  <div class="col-sm-10">
                    <button type="submit" name="submit" class="btn btn-primary">Save Form</button>
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