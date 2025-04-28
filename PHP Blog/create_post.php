<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db_config.php';
$title = '';
$body = '';
$category_id = '';
$imagePath = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $body = trim($_POST['body']);
    $category_id = $_POST['category'];

    // Check if all required fields are filled
    if (empty($title) || empty($body) || empty($category_id)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: create_post.php");
        exit();
    }

    // Handle Image Upload
    if (!empty($_FILES['post_image']['name'])) {
        $uploadDir = "uploads/";
        $imageName = basename($_FILES["post_image"]["name"]);
        $imagePath = $uploadDir . time() . "_" . $imageName;
        $imageFileType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $allowedTypes)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG & GIF files are allowed.";
            header("Location: create_post.php");
            exit();
        }

        if (!move_uploaded_file($_FILES["post_image"]["tmp_name"], $imagePath)) {
            $_SESSION['error'] = "Failed to upload image.";
            header("Location: create_post.php");
            exit();
        }
    }

    // Insert Post with Image Path
    $stmt = $conn->prepare("INSERT INTO posts (title, body, author, category_id, image_path) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        $_SESSION['error'] = "An error occurred. Please try again later.";
        header("Location: create_post.php");
        exit();
    }

    $stmt->bind_param("sssis", $title, $body, $_SESSION['username'], $category_id, $imagePath);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Post created successfully! Please refresh...";
        header("Location: dashboard.php");
        exit();
    } else {
        error_log("Error creating post: " . $stmt->error);
        $_SESSION['error'] = "An error occurred while creating the post. Please try again.";
        header("Location: create_post.php");
        exit();
    }

    $stmt->close();
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
    <title>Create New Post</title>
    <link rel="icon" href="logo-no-background.png" type="image">
    <link rel="stylesheet" href="welcome_style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baskervville+SC&family=League+Spartan:wght@100..900&family=Space+Grotesk:wght@300..700&display=swap"
 rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<header>
<div class="logo"><a href="index.html">HackForce Academy</a></div>
        <nav>
            <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="#">Offerings</a></li>
                    <li><a href="blog-temp.php">Blog</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
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
        <h2>Create New Post</h2>
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='message success-message'>" . htmlspecialchars($_SESSION['message']) . "</div>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='message error-message'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']);
        }
        ?>
        
        <form method="POST" action="create_post.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required class="dropdown-btn">
                    <option value="" class="dropdown-content">-- Select Category --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php echo ($category_id == $category['id']) ? 'selected' : ''; ?> class="dropdown-content">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="body">Body:</label>
                <textarea id="body" name="body" required><?php echo htmlspecialchars($body); ?></textarea>
            </div>

            <div class="form-group">
                <label for="post_image">Upload Image:</label>
                <input type="file" id="post_image" name="post_image" accept="image/*">
            </div>

            <div class="button-container">
                <button type="submit" class="btn-primary">Create Post</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
