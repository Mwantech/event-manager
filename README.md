Event Manager Web App
A user-friendly web application for creating, sharing, and managing events. This app allows event organizers to set up events, generate unique shareable links, and enables attendees to upload photos and videos. Built with PHP and JavaScript, it provides seamless event management and media upload features with user authentication and performance optimizations.

Features
Event Creation: Organizers can create events by adding details such as event name, date, time, location, and description.
Unique Shareable Links: Each event generates a unique URL, making it easy to share with attendees.
Media Upload: Attendees can upload photos and videos to the event page for the organizer to view.
Event Gallery: A gallery view for uploaded photos and videos, with options for organizers to download media.
User Authentication: Allows organizers to access a secure dashboard for managing their events.
Responsive Design: Optimized for desktop and mobile, making it accessible from any device.
Moderation Options: Photo moderation tools for organizers to manage uploads.
Database Storage: Stores event and media information securely in a MySQL database.
Tech Stack
Frontend: JavaScript, HTML, CSS
Backend: PHP
Database: MySQL
Setup and Installation
Clone the repository:

bash
Copy code
git clone https://github.com/your-username/event-manager.git
cd event-manager
Database Configuration:

Create a MySQL database and import the provided database.sql file (located in the db folder) to set up tables for events and media uploads.
Configure your database connection in config.php:
php
Copy code
<?php
$host = 'localhost';
$db = 'your_database_name';
$user = 'your_username';
$password = 'your_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
Server Requirements:

This app requires a server with PHP and MySQL support (e.g., Apache with XAMPP or LAMP stack).
Run the app on localhost by placing the files in the server's root directory (e.g., htdocs for XAMPP).
Configuration:

Update configurations for your server and any required paths in the config.php file.
Run the Application:

Access the app by navigating to http://localhost/event-manager-web-app in your browser.
Usage
Event Creation: Access the event creation page from the dashboard. Fill out the event details and click "Create Event" to generate a unique link for sharing.
Share Link: Share the unique event link with attendees.
Media Upload: Attendees can upload photos and videos through the shared event link.
Gallery View: Organizers can view and download uploaded media from the event gallery.
Folder Structure
bash
Copy code
event-manager-web-app/
├── assets/           # CSS, JavaScript, and image assets
├── db/               # Database setup files
├── includes/         # PHP scripts for handling backend logic
├── templates/        # HTML templates for various pages
├── config.php        # Database and app configuration file
└── index.php         # Entry point of the application
Contributing
Fork the repository.
Create a new branch (git checkout -b feature/your-feature-name).
Commit your changes (git commit -am 'Add new feature').
Push to the branch (git push origin feature/your-feature-name).
Create a Pull Request.
License
This project is licensed under the MIT License - see the LICENSE file for details.

Contact
For any questions or issues, please reach out via GitHub Issues or email.


