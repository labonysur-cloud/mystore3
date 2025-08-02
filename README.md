My Store – An Online Clothing Store 🛍️
My Store is an open-source online clothing store web application built with PHP (server-side) and MySQL (database) 🐘🐬. It lets customers browse products by categories and subcategories, register and log in to their own accounts, manage a shopping cart, and place orders. An admin panel allows the store owner to manage all aspects of the site – creating/editing categories (with a parent-child hierarchy), adding products (each with multiple images), and processing orders. The repository includes a mystore.sql file that defines the database schema (tables for users, categories, products, cart items, orders, and product images). To run it locally, install a PHP/MySQL stack (for example, XAMPP on Windows) and clone the project into the htdocs folder
github.com
.
✨ Key Features
🔐 User Accounts (Registration & Login): Customers can create an account and securely log in. Each user has a profile page and can view their order history
seclgroup.com
.
🏷️ Category Hierarchy: Products are organized into categories and subcategories. Admins can add or edit parent and child categories in the backend
seclgroup.com
.
🛠️ Admin Panel: A separate admin interface lets administrators manage users, categories, products, and orders. The admin can activate/deactivate items and update inventory.
🖼️ Product Listings: Each product has a title, description, price, and supports multiple images (e.g. different views of the item)
seclgroup.com
. On the storefront, products are displayed with their images and details.
🛒 Shopping Cart: Customers can add products to a cart, adjust quantities, and remove items. The cart updates totals automatically
seclgroup.com
.
📦 Order Processing: After checkout, orders are recorded and can be tracked. The system handles order placement, status updates, and order history
seclgroup.com
.
🛠️ Technologies Used
🐘 PHP: Server-side scripting language for dynamic page rendering.
🐬 MySQL: Relational database for storing users, products, orders, etc.
⚙️ XAMPP: Local development stack (Apache + PHP + MySQL). The XAMPP Control Panel starts Apache and MySQL servers for local testing
docs.ushahidi.com
.
⚙️ Setup / Installation
Install XAMPP: Download and install XAMPP (Apache + PHP + MySQL) on your computer. Launch the XAMPP Control Panel and start the Apache and MySQL services
docs.ushahidi.com
.
Copy Project Files: Clone this repository or download the ZIP and extract it into XAMPP’s htdocs folder (e.g. C:\xampp\htdocs\mystore). This makes the project accessible at http://localhost/mystore.
Start Servers: Ensure the Apache and MySQL modules are running in XAMPP
docs.ushahidi.com
. You can verify by visiting http://localhost and seeing the XAMPP welcome page.
Verify PHP: (Optional) Open a terminal and run php -v to check your PHP version matches XAMPP’s version.
💾 Database Setup
Open phpMyAdmin by visiting http://localhost/phpmyadmin in your browser.
Create a new database for the store (e.g. name it mystore_db).
Select the new database, click the Import tab, and choose the provided mystore.sql file. Then click Go to import the schema
simplebackups.com
. This will create all the necessary tables (users, categories, products, cart_items, orders, product_images, etc.).
After import, confirm that tables like users, categories, products, orders, etc. exist.
Update Config: If your MySQL username/password are not the defaults (root/empty for XAMPP), edit config.php (or the DB config file) to set the correct database name, username, and password.
📁 Folder Structure
The project has a simple structure. For example:
📁 Root Directory: Core PHP files (e.g. index.php, login.php, register.php, config.php, etc.) and the SQL schema file (mystore.sql). These handle frontend pages and configuration.
📁 admin/: Admin panel scripts (e.g. admin/index.php, admin/categories.php, admin/products.php, admin/orders.php). These pages allow administrators to manage categories, products, orders, and users.
📁 images/: Contains uploaded product image files. When adding/editing a product, multiple images can be uploaded into this folder.
📁 css/ and js/ (if present): Static assets like stylesheets and JavaScript files for the site’s appearance and interactivity.
