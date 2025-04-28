<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    $username = $_SESSION['username'];

    // Delete the post if it belongs to the logged-in user
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND author = ?");
    if ($stmt) {
        $stmt->bind_param("is", $post_id, $username);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Post deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete the post.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing the delete query.";
    }
    $conn->close();
}

header("Location: view_post.php");
exit();
?>
