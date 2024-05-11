<?php
require "../config/config.php";
session_start();

// Check if the user is logged in by checking if 'user_id' is set in $_SESSION
if (!isset($_SESSION['id'])) {
    // If not logged in, redirect to the login page
    echo "<script>alert('You must be logged in to add favorites.'); window.location.href = '../index.php';</script>";
    exit;
}

$userId = $_SESSION['id'];  // Retrieve the user ID from the session

if (isset($_GET['art_id']) && is_numeric($_GET['art_id'])) {
    $artId = $_GET['art_id'];

    try {
        $conn->beginTransaction();

        // Check if the art is already in the user's favorites
        $check = $conn->prepare("SELECT * FROM favorites WHERE user_id = :user_id AND art_id = :art_id");
        $check->bindParam(':user_id', $userId);
        $check->bindParam(':art_id', $artId);
        $check->execute();

        if ($check->rowCount() > 0) {
            // If the favorite already exists, no need to insert
            echo "<script>alert('This artwork is already in your favorites.'); window.location.href = '../index.php';</script>";
            $conn->rollBack();
            exit;
        }

        // Insert the favorite as it does not exist
        $insert = $conn->prepare("INSERT INTO favorites (user_id, art_id) VALUES (:user_id, :art_id)");
        $insert->bindParam(':user_id', $userId);
        $insert->bindParam(':art_id', $artId);
        $insert->execute();

        if ($insert->rowCount() > 0) {
            $conn->commit();  // Commit the transaction
            echo "<script>alert('Artwork added to favorites.'); window.location.href = '../index.php';</script>";
        } else {
            $conn->rollBack();  // Roll back the transaction if insert fails
            echo "<script>alert('Failed to add to favorites.'); window.location.href = '../index.php';</script>";
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "<script>alert('Database error: " . $e->getMessage() . "'); window.location.href = '../index.php';</script>";
    }
} else {
    echo "<script>alert('Invalid art ID.'); window.location.href = '../index.php';</script>";
}
?>
