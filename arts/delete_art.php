<?php
require "../config/config.php";
session_start();

    $id = $_SESSION['id'];


if(isset($_GET['art_id']) && is_numeric($_GET['art_id'])) {
    $art_id = $_GET['art_id'];

    // Use a prepared statement to securely delete the category
    $sql = "DELETE FROM arts WHERE art_id = :art_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':art_id', $art_id, PDO::PARAM_INT);
    
    try {
        $stmt->execute();
        echo "<script>alert('Art deleted successfully.'); window.location.href = '../users/public-profile.php?id=$id';</script>";
       exit();
    } catch (Exception $e) {
        echo "<script>alert('Failed to delete artwork.');</script>";
        header("location: ../index.php");
    exit();
    }

    // Redirect back to the categories view with a message
    header("location: ../index.php");
    exit();
} else {
    // Redirect if the category_id is not set or not valid
    echo "<script>alert('Invalid category ID.');</script>";
    header("location: ../index.php");
    exit();
}
?>
