<?php
include 'db_config.php';
include 'db_config.php';
$search_username = '';
$search_category = '';
$posts = [];
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['search_username'])) {
        $search_username = trim($_GET['search_username']);
    }
    if (isset($_GET['search_category'])) {
        $search_category = trim($_GET['search_category']);
    }
}

try {
            $sql = "SELECT posts.id, posts.title, posts.body, posts.created_at, users.username, categories.name AS category_name 
            FROM posts 
            JOIN users ON posts.author = users.username 
            LEFT JOIN categories ON posts.category_id = categories.id 
            WHERE 1=1";

    $params = [];
    $types = "";
    if (!empty($search_username)) {
        $sql .= " AND users.username LIKE CONCAT('%', ?, '%')";
        $params[] = $search_username;
        $types .= "s";
    }
    if (!empty($search_category)) {
        $sql .= " AND categories.id = ?";
        $params[] = $search_category;
        $types .= "i";
    }

    $sql .= " ORDER BY posts.created_at DESC";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }

    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching posts: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred while fetching posts.";
}
$categories = [];
try {
    $stmt = $conn->prepare("SELECT id, name FROM categories ORDER BY name ASC");
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        $stmt->close();
    } else {
        throw new Exception("Prepare failed: " . $conn->error);
    }
} catch (Exception $e) {
    error_log("Error fetching categories: " . $e->getMessage());

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo-no-background.png" type="image">
    <title>PHP Blog</title>
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
                    <li><a href="index.html">Home</a></li>
                    <li><a href="login.html" class="btn-1">Get Started</a></li>
                </ul>
            </nav>
        </header>
    <div class="container">

        <main> 
        <?php
            if (isset($_SESSION['message'])) {
                echo "<p class='success-message'>" . htmlspecialchars($_SESSION['message']) . "</p>";
                unset($_SESSION['message']);
            }
            if (isset($_SESSION['error'])) {
                echo "<p class='error-message'>" . htmlspecialchars($_SESSION['error']) . "</p>";
                unset($_SESSION['error']);
            }
            ?>
            <?php if (count($posts) > 0): ?>
                <?php foreach ($posts as $post): ?>

                    <div class="post">
    <h3>
    <a href="full-scrn.php?id=<?php echo $post['id']; ?>" class="post-link"><?php echo htmlspecialchars($post['title']); ?></h3>

    <!-- Display Image if Available -->
    <?php if (!empty($post['image_path'])): ?>
        <a href="full-scrn.php?id=<?php echo $post['id']; ?>">
            <img src="<?php echo htmlspecialchars($post['image_path']); ?>" 
                 alt="Blog Image" class="post-image">
        </a>
    <?php endif; ?>

    </a>
        <p><?php echo nl2br(htmlspecialchars(substr($post['body'], 0, 150))); ?>...</p>
    

    <p class="meta">
        Category: <?php echo htmlspecialchars($post['category_name'] ?? 'Uncategorized'); ?><br>
        <b>Posted by <i><?php echo htmlspecialchars($post['username']); ?></i></b> <br>
        <time>Posted on: <?php echo htmlspecialchars($post['created_at']); ?></time>
    </p>
</div>


                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts found<?php echo (!empty($search_username) || !empty($search_category)) ? " matching your criteria." : "."; ?></p>            <?php endif; ?>
            </main>
    </div>
    <script src="script.js"></script>

</body>
</html>