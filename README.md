# MyStore 3

MyStore 3 is a PHP + MySQL online store application with customer shopping flows and an admin panel for catalog and order management.

## Overview

The project provides:
- Customer authentication (register, login, logout)
- Product browsing by category/subcategory
- Product detail view
- Shopping cart management
- Checkout and order creation
- Customer dashboard and order history
- Admin dashboard for managing products, orders, and users

## Technology Stack

- **Backend:** PHP (PDO)
- **Database:** MySQL / MariaDB
- **Frontend:** Server-rendered PHP, HTML, CSS, JavaScript
- **Assets:** Static CSS/JS and product images under `mystore/assets/`

## Repository Structure

```text
.
├── mystore/
│   ├── admin/                 # Admin pages (dashboard, products, orders, users)
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/products/
│   ├── includes/              # Shared bootstrap/helpers (DB + business logic)
│   ├── pages/                 # Customer-facing routes
│   └── index.php              # Store landing entry point
├── online-storeSQL.sql        # Database schema + seed data script
└── README.md
```

## Application Modules

### 1) Shared Includes (`mystore/includes/`)
- `db.php`: PDO connection bootstrap.
- `functions.php`: Core domain operations, including:
  - Category retrieval
  - Product retrieval
  - Cart operations
  - Order creation and order item persistence
  - Authentication helpers and session utility functions

### 2) Customer Pages (`mystore/pages/`)
- `products.php`, `product_detail.php`
- `cart.php`, `checkout.php`, `process_payment.php`
- `register.php`, `login.php`, `logout.php`
- `dashboard.php`, `orders.php`, `settings.php`
- `about.php`

### 3) Admin Pages (`mystore/admin/`)
- `index.php`: admin dashboard
- `products.php`: product management
- `orders.php`: order management
- `users.php`: user management
- `login.php`: admin authentication page

## Data Model

Schema is defined in `online-storeSQL.sql`. Core tables include:
- `users`
- `categories`
- `products`
- `orders`
- `order_items`
- `cart_items`
- `product_images`
- `tags`
- `product_tags`

> Note: the SQL file currently includes repeated schema/seed sections; import once into a clean database.

## Local Setup

### Prerequisites
- PHP 8.x+
- MySQL 8.x+ (or MariaDB equivalent)
- Web server (Apache/Nginx) or local stack (e.g., XAMPP)

### 1. Clone repository
```bash
git clone https://github.com/labonysur-cloud/mystore3.git
cd mystore3
```

### 2. Create database and import schema
```bash
mysql -u root -p -e "CREATE DATABASE mystore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p mystore < online-storeSQL.sql
```

### 3. Configure database connection
File: `mystore/includes/db.php`

Current configuration uses:
- host: `localhost`
- db: `mystore`
- user: `root`
- password: read from `DB_PASSWORD` environment variable (falls back to empty string)

Set environment variable before running:

```bash
export DB_PASSWORD='your_local_db_password'
```

### 4. Run application
Serve from repository root so `mystore/` is accessible by URL.

Example using PHP built-in server:
```bash
php -S 127.0.0.1:8000
```
Then open:
- `http://127.0.0.1:8000/mystore/`

## Authentication and Authorization

- Sessions are used for login state.
- User password storage uses `password_hash(...)` and verification via `password_verify(...)`.
- Admin pages enforce an admin session check before access.

## Security Notes

- Do not commit real database credentials.
- Use `DB_PASSWORD` environment variable for local/CI secrets.
- Keep production credentials in secure environment configuration.

## Validation

There is no formal test framework configured in this repository yet.
For quick validation after changes, run syntax checks:

```bash
find mystore -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Roadmap Suggestions

- Add automated tests (PHPUnit) for core business logic in `includes/functions.php`
- Add migrations/versioned database management
- Introduce CSRF protection on form submissions
- Add centralized input validation and stronger error handling
- Add CI pipeline for linting, syntax validation, and security checks

## License

No license file is currently defined in this repository.
Add a `LICENSE` file to clarify usage and contribution terms.
