<?php
require "../config/config.php";
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: loginadmin.php");
    exit;
}
$id = $_SESSION['id']; // Retrieve admin ID from session

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Use a prepared statement to securely delete the category
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    try {
        $stmt->execute();
        echo "<script>alert('User deleted successfully.');window.location.href = 'viewusers.php';</script>";
        exit();
    } catch (Exception $e) {
         echo "<script>alert('Failed to delete User.');window.location.href = 'viewusers.php';</script>";
        exit();
    }

    // Redirect back to the categories view with a message
    header("location: ../index.php");
    exit();
} else {
    // Redirect if the category_id is not set or not valid
    echo "<script>alert('Invalid User ID.');window.location.href = 'viewusers.php';</script>";
    exit();
}
?>
