<h1>Medlink</h1>

"Medlink" is a medical appointment and laboratory test management system designed to simplify healthcare access for patients and clinics.

---

<h1>Features</h1>

- Doctor Appointment Booking by department, date, and available time slots.
- Doctor Weekday Availability view and management.
- Laboratory Test Results viewing by patients.
- Appointment Ending feature with dynamic updates.
- Secure Data Handling using PHP & MySQL with prepared statements.

---

<h1>Built With</h1>

- Frontend: HTML5, CSS3, JavaScript
- Backend: PHP
- Database: MySQL

---

<h1>Project Structure</h1>

```
/medlink
  ├── index.php            # Home page
  ├── book_appointment.php  # Appointment booking page
  ├── end_appointment.php   # Appointment ending feature
  ├── test_results.php      # Laboratory test results page
  ├── assets/               # CSS, JS, images
  ├── database/             # SQL connection and setup scripts
  └── README.md
```

---

<h1>How to Run</h1>

1. Clone the repository  
   ```bash
   git clone https://github.com/yourusername/medlink.git
   ```

2. Setup the database  
   - Import the provided `.sql` file into your MySQL server.
   - Update database credentials in `/database/connection.php`.

3. Start a local server (e.g., XAMPP, MAMP).

4. Access the project
   Open `localhost/medlink` in your browser.

---

<h1>Database Overview</h1>

- Departments Table – stores departments like physicians, surgeons, etc.
- Doctors Table – stores doctors' details and their weekday availability.
- Appointments Table – manages patient bookings and time slots.
- Laboratory Tests Table – stores and links patient test results.

---

<h1>Future Enhancements</h1>

- Email/SMS notification system for appointment reminders.
- Admin panel for better management.
- Mobile responsive design.
- Doctor profile pages.

---

<h1>Contributing</h1>

Pull requests are welcome!  
For major changes, please open an issue first to discuss what you would like to change.

