<h1>PHP Blog Website</h1>

A simple and lightweight blog platform built with PHP and MySQL where users can create and share text posts categorized by genre.  
Now supports uploading images along with text posts!

---

<h1>Features</h1>

- Post Text Content with genre categorization.
- Upload Images along with posts.
- Browse Posts by genre.
- Search Posts functionality (if planned).
- Secure Uploads using file validation.

---

<h1>Built With</h1>

- Frontend: HTML5, CSS3
- Backend: PHP
- Database: MySQL

---

<h1>Project Structure</h1>

```
/blog
  ├── index.php           # Homepage displaying posts
  ├── upload_post.php     # Upload new post with image
  ├── view_post.php       # View single post details
  ├── genres.php          # Browse posts by genre
  ├── assets/             # Uploaded images, CSS, JS
  ├── database/           # SQL connection and queries
  └── README.md
```

---

<h1>How to Run</h1>

1. Clone the repository
   ```bash
   git clone https://github.com/yourusername/php-blog-website.git
   ```

2. Setup the database 
   - Import the provided `.sql` file into your MySQL server.
   - Update database connection details in `/database/connection.php`.

3. Start a local server (e.g., XAMPP, MAMP).

4. Access the project
   Open `localhost/blog` in your browser.

---

<h1>Database Overview</h1>

- Posts Table – stores post title, content, image path, and genre.
- Genres Table – stores different blog genres (like Technology, Lifestyle, etc.).

---

<h1>Future Enhancements</h1>

- User authentication (login/signup).
- Comments on posts.
- Tags and multiple genres per post.
- Post likes and sharing.

---

<h1>Contributing</h1>

Pull requests are welcome!  
For major changes, please open an issue first to discuss improvements.
