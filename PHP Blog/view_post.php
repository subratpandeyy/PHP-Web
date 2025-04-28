<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
include 'db_config.php';
$username = $_SESSION['username'];
$selected_category = '';
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $selected_category = $_GET['category'];
}
$categories = [];
$stmt = $conn->prepare("SELECT id, name FROM categories ORDER BY name ASC");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    $stmt->close();
} else {
    error_log("Prepare failed: " . $conn->error);
}
if (!empty($selected_category)) {
    $query = "SELECT posts.id, posts.title, posts.body, posts.created_at, categories.name AS category_name 
            FROM posts 
            JOIN categories ON posts.category_id = categories.id 
            WHERE posts.author = ? AND categories.id = ? 
            ORDER BY posts.created_at DESC";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("si", $username, $selected_category);
    }
} else {
    $query ="SELECT posts.id, posts.title, posts.body, posts.created_at, categories.name AS category_name 
            FROM posts 
            JOIN categories ON posts.category_id = categories.id 
            WHERE posts.author = ? 
            ORDER BY posts.created_at DESC";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $username);
    }
}

$posts = [];
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    $stmt->close();
} else {
    error_log("Prepare failed: " . $conn->error);
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Blog</title>
    <link rel="stylesheet" href="welcome_style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baskervville+SC&family=League+Spartan:wght@100..900&family=Space+Grotesk:wght@300..700&display=swap"
 rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<header>
        <div class="logo">PHP Blog</div>
        <nav>
            <ul>
                    <li><a href="index.html">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
    <aside>
            <ul>
            <h1 class="left-element">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1><br>
                <li><a href="dashboard.php" class="lnk">Dashboard</a></li> 
                <li><a href="create_post.php" class="lnk">Create New Post</a></li>
                <li><a href="view_post.php" class="lnk">View Posts</a></li>
                <li><a href="profile.php" class="lnk">Update Profile</a></li>
                </ul>
                <hr>
            
                <h2>Your Posts</h2>
                <div class="divide">
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
            <form class="search-form" method="GET" action="view_post.php">
                <!-- <label for="category">Filter by Category:</label> -->
                 <div class="form-group">
                <select name="category" id="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php echo ($selected_category == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                </div>
                <button type="submit">Filter</button>
            </form>
            </div>
        </aside>
        <main>
            
            <?php if (count($posts) > 0): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>
                        <p class="meta">Category: <?php echo htmlspecialchars($post['category_name']); ?> | Posted on <?php echo htmlspecialchars($post['created_at']); ?></p>
                        <a class="meta" href="update_post.php?id=<?php echo urlencode($post['id']); ?>" >Update Post</a>
                        <br><br>

                        <!-- read -->
                        <button class="read-more-btn">Read more...</button>

                        <!-- Delete -->
                        <form method="POST" action="delete_post.php">
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['id']); ?>">
                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this post?');">Delete Post</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-posts">You have no posts in this category.</p>
            <?php endif; ?>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>