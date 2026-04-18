LevelUpGames: Beginner transactional website project.

This is a proof of concept website, developed to gain experience in web development, docker and LAMP
no licences are used in the development of this project.
AI has been used to an extent in this project but not to create the full project or files.

Codebase/Resources used:
-LAMP (Linux (WSL) Apache MySql/MariaDB PhpMyAdmin), and SQL
-Web Suite + PHP
-Docker

This project is a functional website that contains the following:

- User registration and login  
- Secure password handling  
- Session‑based authentication  
- Account management (update details + change password)  
- View products and add to cart

This repo is my first fully developed project, subject to change

Features:
Authentication
-register with name, email, and password  
-log in using email + password (no actual email validation only %@% and %.com)
-password hashing using `password_hash()`  
-secure session handling  
-logout ending sessions
Account Management
-changing of account email, name and password
Product browsing
-pulling products from SQL database and displaying them
-details page that shows more information
Personalised basket
-unique to each user via relational database
-adding/removing of items with multiple items to be added
Customer support features
-message uploads to database with id's

Setup Instructions:
Clone the repo
git clone https://github.com/yourusername/yourrepo.git
cd yourrepo

Copy the example files:
cp src/Backend/dbexample.php src/Backend/db.php

Then edit `db.php` with your own settings. DB import in repo

Import the database schema
inside your MySQL or MariaDB client: SOURCE levelupgames_db.sql;

Run the project locally through localhost (ports 8080 and 8081) or Using PHP’s built‑in server: php -S localhost:8080 -t .

Notes:
This project is a proof of concept, not production code.
(This additionally functions as an experiment with Github)
My aims are to show my skills in Website Dev as well as user security using the following
-animated CSS and structured HTML
-PHP code that is scalable and readable
-limited access without login
-passwords are hashed  
-SQL queries use prepared statements  
-sessions are validated before accessing protected pages  