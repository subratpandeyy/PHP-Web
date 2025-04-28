
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
include 'db_config.php';
$post_id = '';
$title = '';
$body = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $post_id = $_POST['id'];
    $title = trim($_POST['title']);
    $body = trim($_POST['body']);
    $category_id = $_POST['category'];

    if (empty($title) || empty($body) || empty($category_id)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: update_post.php?id=" . urlencode($post_id));
        exit();
    }

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Invalid CSRF token!";
        header("Location: update_post.php?id=" . urlencode($post_id));
        exit();
    }

    $stmt = $conn->prepare("UPDATE posts SET title = ?, body = ?, category_id = ? WHERE id = ? AND author = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        $_SESSION['error'] = "An error occurred. Please try again later.";
        header("Location: update_post.php?id=" . urlencode($post_id));
        exit();
    }

    $stmt->bind_param("ssisi", $title, $body, $category_id, $post_id, $_SESSION['username']);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Post updated successfully!";
        header("Location: view_post.php");
        exit();
    } else {
        error_log("Error updating post: " . $stmt->error);
        $_SESSION['error'] = "An error occurred while updating the post. Please try again later.";
        header("Location: update_post.php?id=" . urlencode($post_id));
        exit();
    }

    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $post_id = $_GET['id'];

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $stmt = $conn->prepare("SELECT title, body, category_id FROM posts WHERE id = ? AND author = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        $_SESSION['error'] = "An error occurred. Please try again later.";
        header("Location: dashboard.php");
        exit();
    }

    $stmt->bind_param("is", $post_id, $_SESSION['username']);
    $stmt->execute();
    $stmt->bind_result($title, $body, $category_id);
    $stmt->fetch();
    $stmt->close();


    if (empty($title) && empty($body)) {
        $_SESSION['error'] = "Post not found or you do not have permission to edit this post.";
        header("Location: dashboard.php");
        exit();
    }
}
    else {
    $_SESSION['error'] = "Post ID is required!";
    header("Location: dashboard.php");
    exit();

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Post</title>
    <link rel="stylesheet" href="welcome_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Baskervville+SC&family=League+Spartan:wght@100..900&family=Space+Grotesk:wght@300..700&display=swap"
 rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
    <div class="logo"><a href="index.html">PHP Blog</a></div>
        <nav>
            <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="blog-temp.php">Blog</a></li>
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
        </aside>
        <div class="input">
            <h2>Update Post</h2>
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
<form action="update_post.php" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($post_id); ?>">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
    </div>
    <div class="form-group">
        <label for="category">Category</label>
        <select id="category" name="category" class="form-control" required>
            <option value="" class="dropdown-content">-- Select Category --</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php echo ($category_id == $category['id']) ? 'selected' : ''; ?> class="dropdown-content">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="body">Body</label>
        <textarea id="body" name="body" class="form-control" required><?php echo htmlspecialchars($body); ?></textarea>
    </div>
    <div class="button-container">
        <button type="submit" class="btn-primary">Update Post</button>
    </div>
</form>
</div>
</div>
</body>
</html>
