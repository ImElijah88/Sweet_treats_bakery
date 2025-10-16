# Sweet Treats Bakery Website

A bakery management system built with PHP and MySQL.

## Features

- User registration and login
- Browse menu with search
- Submit reviews with ratings
- Admin dashboard for menu management
- Stock tracking
- Responsive design

## Technologies

- PHP
- MySQL
- HTML/CSS
- JavaScript

## Installation

1. Clone the repository
2. Copy to your web server (XAMPP/WAMP)
3. Create `config.php` from `config.example.php`
4. Import `sweet_treats_db.sql` into MySQL
5. Access at `http://localhost/Sweet_treats_bakery/`

## Login

Admin: `admin` / `admin123`

## Project Structure

```
├── css/style.css
├── js/script.js
├── images/
├── index.php (login)
├── home.php
├── menu.php
├── feedback.php
├── admin_dashboard.php
├── manage_menu.php
└── sweet_treats_db.sql
```

## Features

**Stock Management**
- In Stock: Quantity > 5
- Low Stock: Quantity 1-5
- Out of Stock: Quantity = 0

**User Features**
- Profile management
- Dietary preferences
- Product reviews
- Reply to reviews

**Admin Features**
- Add/edit/delete menu items
- Update stock
- Reply to feedback
- Image uploads

## Notes

This is a school project demonstrating web development concepts including database operations, session management, and responsive design.
