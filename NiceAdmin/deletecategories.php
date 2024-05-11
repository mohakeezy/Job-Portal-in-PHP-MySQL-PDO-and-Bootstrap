

<?php
require "../config/config.php";
session_start();
// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: loginadmin.php");
    exit;
}
$id = $_SESSION['id']; // Retrieve admin ID from session

if(isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Use a prepared statement to securely delete the category
    $sql = "DELETE FROM categories WHERE category_id = :category_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    
    try {
        $stmt->execute();
        echo "<script>alert('category deleted successfully.');window.location.href = 'viewcategories.php';</script>";
        exit();
    } catch (Exception $e) {
         echo "<script>alert('Failed to delete category.');window.location.href = 'viewcategories.php';</script>";
        exit();
    }

    // Redirect back to the categories view with a message
    header("location: ../index.php");
    exit();
} else {
    // Redirect if the category_id is not set or not valid
    echo "<script>alert('Invalid category ID.');window.location.href = 'viewcategories.php';</script>";
    exit();
}
?>

