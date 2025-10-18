Sweet Treats Bakery Website

This is my bakery website project for school. It's a website where customers can browse the menu and leave reviews, and admins can manage everything from the backend.

What it does:
- Users can register and login
- Browse the menu and search for items
- Leave reviews and ratings
- Admin can add/edit/delete menu items
- Stock tracking (shows if items are in stock or low stock)
- Works on mobile and desktop

What I used:
- PHP for backend
- MySQL database
- HTML/CSS for the pages
- JavaScript for interactive stuff

How to run it:
1. Download or clone this project
2. Put it in your htdocs folder (if using XAMPP)
3. Open phpMyAdmin and import the sweet_treats_db.sql file
4. Make sure the database name in config.php matches (should be sweet_treats_db)
5. Go to http://localhost/sweet_treats_website/ in your browser

How to use:

There's two types of users:

Customers can browse the menu, leave reviews, and manage their profile.

Admin can manage the menu items, update stock, and reply to customer feedback.

To login as admin use:
- Username: admin
- Password: admin123

Files:

Main files in the project:
- index.php - landing page
- home.php - main home page after login
- menu.php - shows all the bakery items
- feedback.php - customer reviews page
- admin_dashboard.php - admin control panel
- manage_menu.php - where admin edits menu items
- config.php - database connection
- sweet_treats_db.sql - database file to import
