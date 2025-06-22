
# Project Title

A brief description of what this project does and who it's for

# Anomaly Detection in User Behavior (PHP + MySQL + Python)

This project is a secure login and registration system developed using PHP and MySQL with the ability to track user session behavior (login/logout, IP address, session duration, etc.) and detect anomalies using a Python-based machine learning script.

---

## Features

* User registration and login system
* Role-based access (User / Admin)
* Session tracking:

  * Login time
  * Logout time
  * Session duration (in seconds)
  * IP address
  * User Agent (browser/device info)
* Admin dashboard to view all session activity
* Export session logs to CSV
* Python ML script (`anomaly_detection.py`) to detect abnormal user behavior

---

## Project Structure

```
anomaly_detection_user_behavior/
├── README.md                  # Project documentation
├── LICENSE                    # License file (e.g., MIT)
├── index.php                  # Entry point with login/registration forms
├── config.php                 # MySQL DB connection
├── login_register.php         # Handles login and registration
├── logout.php                 # Logs user out and updates session info
├── admin_page.php             # Admin dashboard for viewing sessions
├── export_to_csv.php          # Exports session logs to CSV
├── script.js                  # JS for toggling forms
├── style.css                  # Styling for the entire project
├── session_logs.txt           # Raw text-based logout session logs
├── anomaly_detection.py       # Python script for anomaly detection
├── /sql/
│   └── users_db.sql           # MySQL DB structure for setup
├── /outputs/
│   └── anomalies.csv          # Output from the Python script (if used)
└── /assets/                   # Screenshots or visuals (optional)
```

---

## Technologies Used

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL (via XAMPP)
* **Machine Learning:** Python, Pandas, Scikit-learn

---

## Database Schema

### Database: `users_db`

#### Table: `users`

| Field        | Type         | Description                 |
| ------------ | ------------ | --------------------------- |
| id           | INT (PK)     | Auto-incremented user ID    |
| name         | VARCHAR(100) | Full name of the user       |
| email        | VARCHAR(100) | Unique email address        |
| password     | VARCHAR(255) | Hashed password             |
| role         | ENUM         | 'user' or 'admin'           |
| login\_count | INT          | Number of successful logins |

#### Table: `user_sessions`

| Field             | Type        | Description                 |
| ----------------- | ----------- | --------------------------- |
| id                | INT (PK)    | Unique session record ID    |
| user\_id          | INT (FK)    | References `users.id`       |
| login\_time       | DATETIME    | Timestamp of login          |
| logout\_time      | DATETIME    | Timestamp of logout         |
| session\_duration | INT         | Session duration in seconds |
| ip\_address       | VARCHAR(45) | User's IP address           |
| user\_agent       | TEXT        | Browser/device information  |

---

## How to Set Up the Project

### Requirements

* XAMPP (Apache + MySQL)
* PHP
* Python 3.x (with pandas, scikit-learn)

### Installation Steps

1. **Clone the repository:**

```bash
git clone https://github.com/yourusername/anomaly_detection_user_behavior.git
```

2. **Move project folder to XAMPP's htdocs:**

```bash
mv anomaly_detection_user_behavior /xampp/htdocs/
```

3. **Start XAMPP** and ensure both Apache and MySQL are running.

4. **Import the database:**

   * Open phpMyAdmin
   * Create a new database `users_db`
   * Import `sql/users_db.sql`

5. **Open the project in browser:**

```
http://localhost/anomaly_detection_user_behavior/index.php
```

6. **Use the Admin panel** to view sessions and export to CSV

---

## Python Anomaly Detection Script

### File: `anomaly_detection.py`

This script loads session data (from MySQL or a CSV export), and uses machine learning techniques to identify anomalous user behavior (e.g., unusually long or short sessions).

### How to Run

```bash
python anomaly_detection.py
```

* Reads from exported CSV (e.g., `session_data.csv`)
* Uses methods like Z-score or Isolation Forest
* Outputs: `outputs/anomalies.csv`

---

## Potential Anomaly Indicators

* Sessions shorter than 3 seconds
* Sessions longer than 4 hours
* High frequency of logins in short time frame
* Unusual IP addresses
* Unknown browsers or devices

---

## Future Enhancements

* Integrate anomaly results into `admin_page.php`
* Add graphical display using Chart.js or Plotly
* Schedule Python script execution (via cron job)
* Trigger email alerts to admin on detection

---
## Screenshots
### Session Duration Over Time
![session duration over time](https://github.com/user-attachments/assets/96e693f5-7a6c-471b-bace-f24bdd912975)

### login count per day 
![login count per day](https://github.com/user-attachments/assets/9394ed80-4470-483f-b8d3-b185b22f34db)

### Correlation Heatmap
![correlation heatmap](https://github.com/user-attachments/assets/108b3ac1-6c7f-42de-ba2e-25ea6d45176e)

### Anomalies In Session Duration Over Time
![anomalies in session duration over time](https://github.com/user-attachments/assets/4e82ad5a-b7fb-4287-a6b6-3110e21b802a)

---
## bash run cmd

![Screenshot 2025-06-22 200610](https://github.com/user-attachments/assets/80e01d50-9f0a-4c71-b84a-4a28a342c74d)

---
## Output
![Screenshot 2025-06-22 200753](https://github.com/user-attachments/assets/762bdf62-b42b-4531-bfd2-ba884890c360)
![Screenshot 2025-06-22 200809](https://github.com/user-attachments/assets/51fa24d8-da89-446d-9039-8f19cc090de2)
![Screenshot 2025-06-22 200823](https://github.com/user-attachments/assets/e33a747f-f4d7-4fa6-8785-c32fa2e7f1dd)

---
## Author

Developed by **ANKIT ANNA**
