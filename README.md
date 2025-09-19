# FoodMart

FoodMart is a Laravel-based ecommerce web application built as a learning project. It demonstrates core Laravel concepts like authentication, routing, controllers, migrations, and CRUD operations while simulating an online store. The project includes product management, category organization, shopping cart functionality, and an admin dashboard.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Getting Started](#getting-started)
- [User Roles](#user-roles)
- [Screenshots](#screenshots)
- [Project Structure](#project-structure)
- [Development Notes](#development-notes)
- [Future Enhancements](#future-enhancements)
- [Contributing](#contributing)
- [License](#license)

---

## Features

- User Authentication (login & signup)
- Product Management (CRUD)
- Category Management
- Shopping Cart with add/update/remove items
- Checkout simulation
- Admin Dashboard for management
- Responsive UI

---

## Tech Stack

- **Framework:** Laravel 10 (PHP 8+)
- **Frontend:** Blade, HTML5, CSS3, JavaScript
- **Database:** MySQL
- **Authentication:** Laravel Breeze / Auth scaffolding
- **Version Control:** Git & GitHub

---

## Getting Started

```bash
# 1. Clone the Repository
git clone https://github.com/yourusername/FoodMart.git
cd FoodMart

# 2. Install Dependencies
composer install
npm install && npm run dev

# 3. Configure Environment
cp .env.example .env   # update DB credentials

# 4. Generate App Key
php artisan key:generate

# 5. Run Migrations & Seeders
php artisan migrate --seed

# 6. Start the Development Server
php artisan serve
```

Visit the app at: `http://127.0.0.1:8000`

---

## User Roles

- **Guest:** Browse products & categories
- **User:** Register, login, manage cart, checkout
- **Admin:** Manage categories, products, and view dashboard

---

## Screenshots

> Place screenshots in `docs/screenshots/` folder and update the paths below.

**Home Page**  
![Home Page](docs/screenshots/home.png)

**Product Listing**  
![Products](docs/screenshots/products.png)

**Shopping Cart**  
![Cart](docs/screenshots/cart.png)

**Admin Dashboard**  
![Admin Dashboard](docs/screenshots/admin.png)

---

## Project Structure

```
FoodMart/
│── app/                # Application files
│── bootstrap/          # Bootstrap files
│── config/             # Configuration files
│── database/           # Migrations & seeders
│── public/             # Public assets
│── resources/          # Blade templates, CSS, JS
│── routes/             # Web & API routes
│── storage/            # Logs, cache, etc.
│── tests/              # Unit & feature tests
│── .env.example        # Environment variables template
│── composer.json       # Composer dependencies
│── package.json        # NPM dependencies
```

---

## Development Notes

- Created as a practical project to learn Laravel.
- Covers authentication, CRUD, routing, MVC pattern.
- Serves as a foundation for building scalable ecommerce apps.

---

## Future Enhancements

- Payment Gateway Integration (Stripe, PayPal)
- Product Search with filters
- Wishlist functionality
- Order History tracking
- Product Reviews & Ratings
- Inventory Management
- Email Notifications
- REST API for mobile apps or third-party use

---

## Contributing

1. Fork the repository
2. Create a new branch (`feature/your-feature`)
3. Commit changes
4. Open a Pull Request

---

## License

This project is licensed under the **MIT License**.
