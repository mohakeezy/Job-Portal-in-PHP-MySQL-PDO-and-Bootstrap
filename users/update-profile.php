<?php 
require "../includes/header.php"; 
require "../config/config.php"; 

// Redirect if not logged in
if(!isset($_SESSION['username'])) {
    header("location: ".APPURL."");
    exit;
}

// Redirect if no update ID provided or if it doesn't match the session ID
if(!isset($_GET['upd_id']) || $_SESSION['id'] != $_GET['upd_id']) {
    header("location: ".APPURL."");
    exit;
}

$id = $_GET['upd_id'];
$select = $conn->prepare("SELECT * FROM users WHERE id = :id");
$select->bindParam(':id', $id, PDO::PARAM_INT);
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

if(!$row) {
    echo "User not found.";
    exit;
}

if(isset($_POST['submit'])) {
    $imgName = $row->img; // Default to the existing image
    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['img']['type'];
        if(in_array($fileType, $allowedTypes)) {
            if($_FILES['img']['size'] <= 5000000) {
                $temp = explode(".", $_FILES["img"]["name"]);
                $newfilename = round(microtime(true)) . '.' . end($temp); // New file name
                $dir_img = 'user-images/' . $newfilename;
                if(move_uploaded_file($_FILES['img']['tmp_name'], $dir_img)) {
                    $imgName = $newfilename;
                } else {
                    echo "<script>alert('Failed to move uploaded file.');</script>";
                }
            } else {
                echo "<script>alert('File size is too large. Limit is 5MB.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only JPG, PNG, and GIF are allowed.');</script>";
        }
    }

    if(empty($_POST['username']) || empty($_POST['email'])) {
        echo "<script>alert('Username or email are empty');</script>";
    } else {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $bio = $_POST['bio'];
        $facebook = $_POST['facebook'];
        $twitter = $_POST['twitter'];
        $linkedin = $_POST['linkedin'];

        $update = $conn->prepare("UPDATE users SET username = :username, email = :email,
        bio = :bio, facebook = :facebook, twitter = :twitter, linkedin = :linkedin, img = :img WHERE id = :id");
        
        if($update->execute([
            ':username' => $username,
            ':email' => $email,
            ':bio' => $bio,
            ':facebook' => $facebook,
            ':twitter' => $twitter,
            ':linkedin' => $linkedin,
            ':img' => $imgName,
            ':id' => $id
        ])) {
            header("location: profile.php?id=".$id); // Redirect to the updated profile
            exit;
        } else {
            echo "<script>alert('Failed to update profile.');</script>";
        }
    }
}
?>


<!-- HTML form for updating profile goes here, similar to the provided form structure -->
<section class="section-hero overlay inner-page bg-image" style="background-image: url('../images/hero_1.jpg');" id="home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-white font-weight-bold">Update Profile</h1>
                <div class="custom-breadcrumbs">
                    <a href="<?php echo APPURL; ?>">Home</a> <span class="mx-2 slash">/</span>
                    <span class="text-white"><strong>Update Profile</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="site-section" id="next-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-5 mb-lg-0">
                <form action="update-profile.php?upd_id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="">
                    <div class="row form-group">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="text-black" for="fname">Username</label>
                            <input type="text" id="fname" value="<?php echo htmlspecialchars($row->username); ?>" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="text-black" for="lname">Email</label>
                            <input type="email" id="lname" value="<?php echo htmlspecialchars($row->email); ?>" name="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-12">
                            <label class="text-black" for="bio">Bio</label>
                            <textarea name="bio" id="bio" cols="30" rows="7" class="form-control"><?php echo htmlspecialchars($row->bio); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
        <label for="img">Profile Image:</label>
        <input type="file" name="img" id="img" class="form-control">
    </div>

                    <!-- Social links and image upload fields -->
                    <div class="row form-group">
        <div class="col-md-12">
            <label class="text-black" for="facebook">Facebook</label>
            <input type="text" name="facebook" id="facebook" value="<?php echo htmlspecialchars($row->facebook ?? ''); ?>" class="form-control">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-12">
            <label class="text-black" for="twitter">Twitter</label>
            <input type="text" name="twitter" id="twitter" value="<?php echo htmlspecialchars($row->twitter ?? ''); ?>" class="form-control">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-12">
            <label class="text-black" for="linkedin">LinkedIn</label>
            <input type="text" name="linkedin" id="linkedin" value="<?php echo htmlspecialchars($row->linkedin ?? ''); ?>" class="form-control">
        </div>
    </div>


                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="submit" name="submit" value="Update" class="btn btn-primary btn-md text-white">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require "../includes/footer.php"; ?>
