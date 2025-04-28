<?php
include 'db_config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Post ID");
}

$post_id = $_GET['id'];

$stmt = $conn->prepare("SELECT posts.*, users.username, categories.name AS category_name FROM posts 
                        JOIN users ON posts.author = users.username 
                        LEFT JOIN categories ON posts.category_id = categories.id 
                        WHERE posts.id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$post) {
    die("Post not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo-no-background.png" type="image">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="welcome_style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baskervville+SC&family=League+Spartan:wght@100..900&family=Space+Grotesk:wght@300..700&display=swap"
 rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<header>
            <div class="logo">
                <h1><a href="index.html">PHP Blog</a></h1>
            </div>
            <nav>
              <input type="checkbox" id="check">
              <label for="check" class="checkbtn">
                <i class="fa-solid fa-bars"></i>
              </label>
                <ul>
                    <li><a href="index.php">Home</a></li>
                </ul>
            </nav>
        </header>

    
    <div class="post-container">
    <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>

    <?php if (!empty($post['image_path'])): ?>
        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Blog Image" class="full-post-image">
    <?php endif; ?>

    <div class="post-content">
        <p><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>
    </div>

    <div class="post-meta">
        <span><b>Category:</b> <?php echo htmlspecialchars($post['category_name'] ?? 'Uncategorized'); ?></span>
        <span><b>Posted by:</b> <i><?php echo htmlspecialchars($post['username']); ?></i></span>
        <span><b>Posted on:</b> <?php echo htmlspecialchars($post['created_at']); ?></span>
    </div>
</div>
</body>
</html>
