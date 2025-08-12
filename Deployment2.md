# QuickShop Deployment Guide (Self-Hosted Server — Apache)

This document outlines how to deploy the QuickShop website on your own Linux server using **Apache**. These instructions assume you have SSH access with sudo privileges.

---

## Overview

QuickShop is a product search website that aggregates listings from major retailers such as Amazon, Walmart, Costco, and Target. Users can search for products, view aggregated results, and save items to a personal shopping list. The application uses:

- HTML/CSS/JavaScript for the frontend
- PHP for backend logic
- MySQL for storing user data and saved items
- Unwrangle API to retrieve product listings

---

## Server Requirements

| Requirement         | Description                                                                                |
|---------------------|--------------------------------------------------------------------------------------------|
| Operating System    | Linux server (e.g., Ubuntu 22.04/24.04 LTS, Debian 12, Rocky/Alma 9)                      |
| Web Server          | Apache 2.4+                                                                                |
| PHP Support         | PHP 8.2 with extensions: mysqli, curl, mbstring, xml, zip, openssl                       |
| Database            | MySQL 8.4+ or MariaDB 10.6+                                                                |
| Shell Access        | SSH with sudo privileges                                                                   |
| HTTPS (SSL)         | Recommended for secure login sessions (Let’s Encrypt via Certbot)                          |

Downloads / Documentation (if not using your OS package manager):
- Apache HTTP Server: https://httpd.apache.org/
- PHP: https://www.php.net/
- MySQL Community Server: https://dev.mysql.com/downloads/mysql/
- MariaDB Server: https://mariadb.org/download/
- Certbot (Let’s Encrypt): https://certbot.eff.org/
- Git (optional): https://git-scm.com/downloads

---

## Deployment Steps

### 0) Prepare the Server

1. SSH into the server:

        ssh youruser@yourserver

2. Update system packages:

        sudo apt update && sudo apt -y upgrade

3. Install Apache:

        sudo apt -y install apache2

4. Install PHP and required extensions (Apache + mod_php):

        sudo apt -y install libapache2-mod-php php-mysql php-curl php-mbstring php-xml php-zip

   Verify PHP version:

        php -v

   Note: Ensure PHP reports 8.2+; if not, install a newer PHP per your distro’s documentation or vendor packages.

5. Install a database server (choose one):

   MySQL:

        sudo apt -y install mysql-server
        sudo mysql_secure_installation

   MariaDB:

        sudo apt -y install mariadb-server
        sudo mysql_secure_installation

6. (Optional) Tools for file handling:

        sudo apt -y install git unzip

7. Open the firewall for Apache (if UFW is used):

        sudo ufw allow OpenSSH
        sudo ufw allow 'Apache Full'
        sudo ufw enable

---

### 1) Upload Website Files

1. Create the web root and set ownership:

        sudo mkdir -p /var/www/quickshop
        sudo chown -R $USER:$USER /var/www/quickshop

2. Copy the **contents** of the QuickShop project to the server (do **not** copy the parent folder itself). From your local machine:

        scp -r QuickShop/* youruser@yourserver:/var/www/quickshop/

   Expected structure inside `/var/www/quickshop/`:

        index.html
        html/
        resources/

3. Set permissions for Apache:

        sudo chown -R www-data:www-data /var/www/quickshop
        sudo find /var/www/quickshop -type d -exec chmod 755 {} \;
        sudo find /var/www/quickshop -type f -exec chmod 644 {} \;

---

### 2) Configure Apache Virtual Host

1. Create a vhost file:

        sudo nano /etc/apache2/sites-available/quickshop.conf

2. Paste the configuration (replace `yourdomain.com`):

        <VirtualHost *:80>
            ServerName yourdomain.com
            ServerAlias www.yourdomain.com

            DocumentRoot /var/www/quickshop
            DirectoryIndex index.php index.html

            <Directory /var/www/quickshop>
                Options FollowSymLinks
                AllowOverride All
                Require all granted
            </Directory>

            ErrorLog ${APACHE_LOG_DIR}/quickshop_error.log
            CustomLog ${APACHE_LOG_DIR}/quickshop_access.log combined
        </VirtualHost>

3. Enable the site and required module(s), then reload Apache:

        sudo a2ensite quickshop
        sudo a2enmod rewrite
        # (Optional) disable the default site to avoid conflicts:
        sudo a2dissite 000-default.conf
        sudo systemctl reload apache2

---

### 3) Create the Database

1. Log in to the database shell:

        sudo mysql

2. Create the database and a dedicated user (replace the password):

        CREATE DATABASE quickshop_data CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        CREATE USER 'quickshop_user'@'localhost' IDENTIFIED BY 'REPLACE_WITH_STRONG_PASSWORD';
        GRANT ALL PRIVILEGES ON quickshop_data.* TO 'quickshop_user'@'localhost';
        FLUSH PRIVILEGES;
        EXIT;

Keep a note of:
- Database name: quickshop_data
- Username: quickshop_user
- Password: (the one you set)

---

### 4) Import the Database Tables

Import the schema file directly:

        mysql -u quickshop_user -p quickshop_data < /var/www/quickshop/resources/sql/schema.sql

This creates the required tables for storing user logins and shopping list items.

---

### 5) Enter Your Database Credentials

1. Edit the connection file:

        sudo nano /var/www/quickshop/resources/php/connection.php

2. You will see the following lines:

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "QuickShop_data";

3. Replace them with your actual database credentials:

        $servername = "localhost";
        $username = "quickshop_user";
        $password = "REPLACE_WITH_STRONG_PASSWORD";
        $dbname = "quickshop_data";

4. Save and exit.

---

### Enable HTTPS 

1. Install Certbot for Apache:

        sudo apt -y install certbot python3-certbot-apache

2. Obtain and install certificates (replace domain names):

        sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

Your site will load securely at `https://yourdomain.com` after certificates are installed.

---

## Unwrangle API Key Setup

QuickShop uses the Unwrangle API to retrieve product listings. This is handled by a PHP file located at:

        /var/www/quickshop/resources/php/api.php

An API key is already included in the project for demonstration purposes. If deploying for public use, replace it with your own:

1. Sign up at https://unwrangle.com
2. Generate your API key
3. Open the file and update the key:

        sudo nano /var/www/quickshop/resources/php/api.php

   Find the line:

        &api_key=b04ebc4496cb02fe0d586570b793ad9dae75a9b4

   Replace the value after `api_key=` with your new key, then save.

---

## Verifying the Site

After completing the steps:

1. Visit your domain (`https://yourdomain.com`)
2. Confirm the homepage loads
3. Search for a product — listings from multiple retailers should appear
4. Navigate to the cart (shopping list) and test saving items
5. Test the login/logout functionality
6. Review logs if needed:

        sudo tail -f /var/log/apache2/error.log
        sudo tail -f /var/log/apache2/access.log

---

## Support

For technical questions or assistance with setup, please contact the development team:

- johnsk26@rpi.edu
- dalyo@rpi.edu
- zhenga6@rpi.edu
- loaizs@rpi.edu
