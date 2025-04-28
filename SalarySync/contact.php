<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact page</title>
    <link rel="stylesheet" href="homestyle.css">
    <link rel="shortcut icon" href="shrt.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container" id="container">
            
        <nav>
            <div class="logo">
                <img src="logo-no-background.png">
            </div>
            <ul>

                <li><a href="home.php">Home</a></li>

                <li><a href="service.php">Services</a></li>

                <li><a href="about.php">About</a></li>

                <li><a href="contact.php">Contact Us</a></li>

                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        </div>
        
        <br>
    <div class="cont-body">
    <div class="contact-form">
        <h1>Contact Us</h1>
        <form>
            <div class="input-group">
                <label for="Name">Name</label>
                <input type="text" id="Name" name="Name" required placeholder="Enter your name">
            </div>
            
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required placeholder="Enter your email">
            </div>
            
            <div class="input-group">
                <label for="message">Message</label>
                <input type="text" name="message" id="message" required placeholder="Enter your message">
            </div>
            <div class="submit">
                <button type="submit">Send</button>
            </div>
        </div>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
    </footer>
</body>
</html>