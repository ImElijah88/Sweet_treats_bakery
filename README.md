# Sweet Treats Bakery Website

Welcome to the Sweet Treats Bakery website! This is a full-featured bakery management system built with PHP and MySQL. It's designed to be a one-stop shop for both customers and administrators, with a user-friendly interface and a robust set of features.

## Features

This project is packed with features to help you manage your bakery and interact with your customers:

*   **User-friendly interface:** A clean and intuitive design that's easy to navigate.
*   **User registration and login:** Customers can create an account to save their preferences and track their orders.
*   **Browse the menu:** A beautiful and responsive menu with a search function to help customers find their favorite treats.
*   **Submit reviews and ratings:** Customers can leave feedback and rate your products to help you improve your offerings.
*   **Admin dashboard:** A powerful admin dashboard to manage your menu, track your stock, and respond to customer feedback.
*   **Stock tracking:** Keep track of your inventory with automatic stock status updates (in stock, low stock, and out of stock).
*   **Responsive design:** The website is fully responsive and looks great on all devices, from desktops to mobile phones.

## Technologies

This project is built with a combination of modern and classic web technologies:

*   **PHP:** The backbone of the application, handling all the server-side logic.
*   **MySQL:** The database of choice, storing all the data for the application.
*   **HTML/CSS:** The building blocks of the user interface, with a clean and modern design.
*   **JavaScript:** Used to add interactivity and a better user experience.

## Installation

To get started with the Sweet Treats Bakery website, you'll need to have a web server with PHP and MySQL installed (like XAMPP or WAMP).

1.  **Clone the repository:**
    ```
    git clone https://github.com/your-username/sweet-treats-bakery.git
    ```
2.  **Copy the files:** Copy the files to your web server's root directory (e.g., `htdocs` in XAMPP).
3.  **Create the config file:** Create a `config.php` file from the `config.example.php` template and update it with your database credentials.
4.  **Import the database:** Import the `sweet_treats_db.sql` file into your MySQL database.
5.  **Access the website:** You can now access the website at `http://localhost/sweet-treats-bakery/`.

## Usage

The Sweet Treats Bakery website has two user roles:

*   **Customer:** Customers can browse the menu, leave reviews, and manage their profile.
*   **Admin:** The admin can manage the menu, track stock, and respond to customer feedback.

The default admin login is:

*   **Username:** `admin`
*   **Password:** `admin123`

## Project Structure

The project is organized into the following directories and files:

```
├── css/style.css           # The main stylesheet for the website
├── js/script.js            # The main JavaScript file for the website
├── images/                 # All the images for the website
├── index.php               # The login page
├── home.php                # The home page
├── menu.php                # The menu page
├── feedback.php            # The customer feedback page
├── admin_dashboard.php     # The admin dashboard
├── manage_menu.php         # The menu management page
└── sweet_treats_db.sql     # The database schema
```

## Notes

This project was originally created as a school project to demonstrate web development concepts like database operations, session management, and responsive design. It has since been improved and expanded to be a more complete and robust application.