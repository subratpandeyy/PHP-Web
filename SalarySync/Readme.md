<h1>SalarySync</h1>

SalarySync is a comprehensive web-based salary management system designed to handle employee salaries efficiently. It helps organizations streamline employee records, salary details, and automate calculations related to payments.

---

<h1>Features</h1>

- Manage employee records (Add, Edit, Delete employees)
- Assign and calculate salaries
- Generate payment records
- Automatically update employee IDs on deletion to maintain order
- Responsive and user-friendly interface
- Secure data handling

---

<h1>Built With</h1>

- Frontend: HTML5, CSS3, JavaScript
- Backend: PHP
- Database: MySQL

---

<h1>Project Structure</h1>

```
/salarysync
  ├── index.php            # Dashboard/Homepage
  ├── add_employee.php     # Add a new employee
  ├── edit_employee.php    # Edit employee details
  ├── delete_employee.php  # Delete employee and update IDs
  ├── salary_management.php # Handle salary-related functions
  ├── database/            # Database connection and operations
  └── assets/              # Stylesheets, scripts, images
```

---

<h1>How to Run</h1>

1. Clone the repository

   ```bash
   git clone https://github.com/subratpandeyy/salarysync.git
   ```

2. Set up the database

   - Import the provided SQL file into your MySQL server.
   - Update the database connection settings in `/database/connection.php`.

3. Start your local server (for example, using XAMPP, MAMP).

4. Access the project by navigating to `localhost/salarysync` in your web browser.

---

<h1>Database Overview</h1>

- Employees Table: stores employee information including name, ID, position, and salary.
- Salary Table: manages salary transactions and payment records.

---

<h1>Future Enhancements</h1>

- Add role-based login (Admin, HR, Manager)
- Detailed salary slip generation
- Salary history tracking
- Email notifications for payments

---

<h1>Contributing</h1>

Contributions are welcome. For major changes, please open an issue first to discuss what you would like to change.

