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

?>

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Users</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Users</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Users</h5>
              <!-- Display Success/Error Messages -->
              <?php 
              if(isset($_GET['msg'])){
                echo '<div class="alert alert-success" role="alert">'. htmlspecialchars($_GET['msg']) .'</div>';
              }
              ?>
              <a href="register.php?id=<?php echo $_SESSION['id']; ?>" class="btn btn-primary mb-3">Add New User</a>

              <!-- Table with stripped rows -->
              <table class="table table-striped">
                <thead>
                  <tr>
                <th scope="col">ID</th>
                <th scope="col">Full Name</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Type</th>
                <th scope="col">Bio</th>
                <th scope="col">Created At</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $stmt = $conn->prepare("SELECT id, fullname, username, email, type, bio, created_at FROM users");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($stmt->rowCount() > 0) {
                    foreach ($result as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['bio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo '<td>
                              <a href="edit_users.php?id='. htmlspecialchars($row['id']) .'" class="btn btn-success"><i class="bi bi-pencil-square"></i></a>
                              <a href="delete_users.php?id='. htmlspecialchars($row['id']) .'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this User?\')"><i class="bi bi-trash-fill"></i></a>
                            </td>';
                      echo '</tr>';
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No users found</td></tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='7' class='text-center'>Error: " . $e->getMessage() . "</td></tr>";
            }
            ?>
       </tbody>
              </table>
              <!-- End Table -->

            </div>
          </div>
        </div>
      </div>
    </section>
</main><!-- End #main -->


<?php require "includes/footer.php"; // Include the footer file ?>
