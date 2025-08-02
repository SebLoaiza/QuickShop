# QuickShop Deployment Guide

This document outlines how to deploy the QuickShop website to a live web server using a standard hosting provider. These instructions are designed for someone managing a website through a hosting control panel such as cPanel. Only minimal setup and coding knowledge is required.

---

## Overview

QuickShop is a product search website that aggregates listings from major retailers such as Amazon, Walmart, Costco, and Target. Users can search for products, view aggregated results, and save items to a personal shopping list. The application uses:

- HTML/CSS/JavaScript for the frontend
- PHP for backend logic 
- MySQL for storing user data and saved items
- Unwrangle API to retrieve product listings

---

## Hosting Requirements

To deploy QuickShop, you need a hosting environment that provides the following:

| Requirement         | Description                           |
|---------------------|---------------------------------------|
| Web Server          | Apache or Nginx                       |
| PHP Support         | PHP X.x+                              |
| MySQL Database      | MySQL X.x+ or MariaDB 1X.x+           |
| File Management     | cPanel File Manager or equivanlent    |
| Database Access     | phpMyAdmin or equivalent              |
| HTTPS (SSL)         | Recommended for secure login sessions |

---

## Deployment Steps

### 1. Upload Website Files

1. Log into your hosting control panel (such as cPanel).
2. Open **File Manager**.
3. Navigate to the folder named `/public_html/` (this is the root of your website).
4. Upload the **contents** of the QuickShop project into `/public_html/`.

> Important: Upload all individual files and folders inside the `QuickShop` folder — not the folder itself. This includes:
> - `index.html`
> - `html/` folder
> - `resources/` folder

### 2. Create the Database

1. In your control panel, go to **MySQL Databases**.
2. Create a new database (`quickshop_data`).
3. Create a new MySQL user and set a secure password.
4. Assign this user to the database and select **All Privileges**.

Keep a note of:
- Database name
- Username
- Password


### 3. Import the Database Tables

1. Go to **phpMyAdmin** from your control panel.
2. Select the database you created on the left panel.
3. Click on the **Import** tab.
4. Click **Choose File** and select the following file from the project:
   ```
   /resources/sql/schema.sql
   ```
5. Click **Go** to run the import.

This will create the required tables for storing user logins and shopping list items.

### 4. Enter Your Database Credentials

1. In **File Manager**, navigate to:
   ```
   /public_html/resources/php/
   ```
2. Open the file named `connection.php`.
3. You will see the following lines:

   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "QuickShop_data";
   ```

4. Replace them with your actual database credentials. For example:

   ```php
   $servername = "localhost";
   $username = "your_hosting_db_user";
   $password = "your_hosting_db_password";
   $dbname = "your_hosting_db_name";
   ```

5. Click **Save**.

---

### Enable HTTPS

1. Go to your hosting control panel’s **SSL** section.
2. Enable **Let's Encrypt SSL** for your domain (free on most hosts).
3. Once active, your site will load securely at `https://yourdomain.com`.

---

## Unwrangle API Key Setup

QuickShop uses the Unwrangle API to retrieve product listings. This is handled by a PHP file located at:

```
/resources/php/api.php
```

An API key is already included in the project for demonstration purposes. If deploying for public use, replace it with your own:

1. Sign up at [https://unwrangle.com](https://unwrangle.com)
2. Generate your API key
3. Open `api.php` and locate this line:
   ```php
   &api_key=b04ebc4496cb02fe0d586570b793ad9dae75a9b4
   ```
4. Replace the value after `api_key=` with your new key

---

## Verifying the Site

After completing the steps:

1. Visit your domain (`https://yourdomain.com`)
2. Confirm the homepage loads
3. Try searching for a product - listings from multiple retailers should appear
4. Navigate to the cart (shopping list) and test saving items
5. Test the login/logout functionality

---

## Support

For technical questions or assistance with setup, please contact the development team:

- email@email.com
- email@email.com
- email@email.com
