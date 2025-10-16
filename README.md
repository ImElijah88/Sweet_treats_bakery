# Sweet Treats Bakery Website

A full-featured bakery management system built with PHP, MySQL, HTML, CSS, and JavaScript.

## Features

### User Features
- 🏠 Homepage with cyberpunk-themed design
- 🍰 Browse daily menu with search functionality
- ⭐ Submit product reviews with star ratings
- 💬 Reply to other customer reviews
- 👤 User profile management with dietary preferences
- 📱 Fully responsive mobile design

### Admin Features
- 📊 Admin dashboard
- ➕ Add/Edit/Delete menu items
- 📦 Stock management with automatic status updates
- 💬 Reply to customer feedback
- 🖼️ Image upload for products

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Design**: Responsive, Mobile-First
- **Theme**: Dark/Light mode toggle

## Installation

### Prerequisites
- XAMPP or WAMP server
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Setup Steps

1. Clone the repository:
```bash
git clone https://github.com/ImElijah88/Sweet_treats_bakery.git
```

2. Copy to your web server directory:
```bash
# For XAMPP
cp -r Sweet_treats_bakery /xampp/htdocs/
```

3. Create database configuration:
```bash
cp config.example.php config.php
```

4. Edit `config.php` with your database credentials:
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sweet_treats_db";
```

5. Import database:
- Open phpMyAdmin (http://localhost/phpmyadmin)
- Create database: `sweet_treats_db`
- Import `sweet_treats_db.sql`

6. Access the website:
```
http://localhost/Sweet_treats_bakery/
```

## Default Login Credentials

### Admin Account
- Username: `admin`
- Password: `admin123`

### Test User Accounts
- Username: `james_wilson` | Password: `password123`
- Username: `emma_thompson` | Password: `mypassword`

## Project Structure

```
sweet_treats_website/
├── css/
│   └── style.css          # Main stylesheet
├── js/
│   └── script.js          # JavaScript functionality
├── images/                # Product and background images
├── uploads/               # User uploaded files
├── index.php              # Login page
├── home.php               # Homepage
├── menu.php               # Menu display
├── feedback.php           # Customer reviews
├── admin_dashboard.php    # Admin panel
├── manage_menu.php        # Menu management
├── view_feedback.php      # Admin feedback view
└── sweet_treats_db.sql    # Database structure
```

## Key Features Explained

### Stock Management
- Automatic status updates based on quantity
- **In Stock**: Quantity > 5
- **Low Stock**: Quantity 1-5
- **Out of Stock**: Quantity = 0

### User System
- Registration with email validation
- Profile management
- Dietary preferences tracking
- Delivery address management

### Feedback System
- Star ratings (1-5)
- Product-specific reviews
- Admin replies
- User-to-user replies with @mentions
- Emoji support

### Responsive Design
- Mobile-first approach
- Hamburger menu navigation
- Touch-friendly interface
- Optimized for all screen sizes

## Security Notes

⚠️ **Important**: This is an educational project. For production use:
- Implement password hashing (bcrypt)
- Use prepared statements for SQL queries
- Add CSRF protection
- Implement session timeout
- Sanitize all user inputs
- Use HTTPS

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## License

This project is created for educational purposes.

## Author

Created as a school project demonstrating full-stack web development skills.

## Acknowledgments

- Images sourced from various free stock photo websites
- Cyberpunk theme inspired by modern design trends
- Built with guidance from web development best practices
