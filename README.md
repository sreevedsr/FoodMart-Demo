FoodMart

A modern ecommerce web application built with Laravel. FoodMart provides a platform for users to browse products, manage their cart, and complete purchases, while offering admins tools to manage categories, products, and users.

Features

User Authentication – Secure login & signup system.

Product Management – Create, update, delete, and list products.

Category Management – Organize products into categories.

Shopping Cart – Add, update, and remove products from the cart.

Checkout Flow – Basic checkout process for order simulation.

Admin Dashboard – Manage categories, products, and users.

Responsive UI – Clean and simple layout for desktop and mobile.

Tech Stack

Backend: Laravel 10 (PHP 8+)

Frontend: Blade templates, HTML5, CSS3, JavaScript

Database: MySQL

Authentication: Laravel Breeze / Auth scaffolding

Version Control: Git & GitHub

Getting Started
1. Clone the Repository
git clone https://github.com/yourusername/FoodMart.git
cd FoodMart

2. Install Dependencies
composer install
npm install && npm run dev

3. Configure Environment

Copy .env.example to .env and update database credentials:

cp .env.example .env

4. Generate App Key
php artisan key:generate

5. Run Migrations & Seeders
php artisan migrate --seed

6. Start Development Server
php artisan serve


The application will be available at:
http://127.0.0.1:8000

User Roles

Guest – Can browse products & categories.

User – Can register, login, manage cart, and checkout.

Admin – Can manage categories, products, and view dashboard.

Screenshots
Home Page

Product Listing

Shopping Cart

Admin Dashboard

(Add actual screenshots inside docs/screenshots/ folder in the project.)

Project Structure
FoodMart/
│── app/                # Laravel application files
│── bootstrap/          # Bootstrap files
│── config/             # Configuration files
│── database/           # Migrations and seeders
│── public/             # Public assets
│── resources/          # Blade templates, CSS, JS
│── routes/             # Web & API routes
│── storage/            # Storage (logs, cache, etc.)
│── tests/              # PHPUnit tests
│── .env.example        # Environment variables template
│── composer.json       # Composer dependencies
│── package.json        # NPM dependencies

Development Notes

Built as part of learning Laravel fundamentals.

Implements authentication, CRUD operations, and MVC design pattern.

Can be extended with additional ecommerce features.

Future Enhancements

Planned improvements and features to extend the project:

Payment Integration – Add Stripe/PayPal for real transactions.

Search & Filters – Product search with category and price filters.

Wishlist – Allow users to save products for later.

Order History – Track past purchases and invoices.

Product Reviews & Ratings – Enable users to leave feedback.

Inventory Management – Track product stock levels.

Email Notifications – Send order confirmation and updates.

REST API – Expose APIs for mobile or third-party integration.

Contributing

Contributions are welcome.

Fork the repository

Create a new branch (feature/your-feature)

Commit changes

Open a Pull Request

License

This project is licensed under the MIT License.