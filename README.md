# Product Management System

A simple web app built with PHP, MySQL, HTML, and CSS. It has two parts:

1. **Public catalog page** (`index.php`) — displays all products in a clean, responsive grid, each showing its image, name, price, and description.
2. **Admin dashboard** (`admin/`) — lets an administrator add, view, update, and delete products, including uploading a product image.

## Database Design

- `products` table — stores name, price, description.
- `product_images` table — stores the image file path for a product, linked to `products` via a `product_id` foreign key (one-to-many relationship: a product can have images stored in its own table, kept separate from the main products data for cleaner structure).

## How to Run Locally

1. Install [XAMPP](https://www.apachefriends.org) (includes Apache, MySQL, PHP).
2. Place this project folder inside your XAMPP `htdocs` directory.
3. Start **Apache** and **MySQL** from the XAMPP Control Panel.
4. Open `http://localhost/phpmyadmin`, go to the **SQL** tab, paste in the contents of `schema.sql`, and click **Go**. This creates the `product_system` database and its two tables.
5. Visit `http://localhost/product-system/index.php` to view the public catalog.
6. Visit `http://localhost/product-system/admin/index.php` to manage products (add/edit/delete).

## File Structure

```
product-system/
├── db_connect.php        # Shared database connection
├── index.php             # Public product catalog page
├── style.css             # Styling for all pages
├── schema.sql             # Database schema (run once in phpMyAdmin)
├── uploads/               # Uploaded product images are saved here
└── admin/
    ├── index.php          # Admin dashboard (list all products)
    ├── add.php            # Add a new product (with image upload)
    ├── edit.php           # Edit an existing product
    └── delete.php         # Delete a product
```

## Features

- Product catalog with responsive card layout
- Admin CRUD (Create, Read, Update, Delete) operations
- Image upload with file type validation
- Server-side validation (required fields, numeric price)
- Prices displayed in NPR (Rs.)
