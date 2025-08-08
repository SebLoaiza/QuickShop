# QuickShop

QuickShop is a PHP-based product search application that aggregates listings from major retailers such as Amazon, Walmart, Target, and Costco. Users can search for products, filter results, and save items to a personal shopping list.

## Features
- Product search with aggregated results from multiple retailers
- Category filters and sorting options
- Shopping list management
- Integration with the Unwrangle API

## Requirements
- PHP 8.4+  
- MySQL 8.4+ or MariaDB 12.1+  
- Web server (Apache/Nginx)  
- API key for Unwrangle (configured in `resources/php/api.php`)

## Local Setup
1. Install [XAMPP](https://www.apachefriends.org/)
2. Place the project in `htdocs` (e.g., `C:\xampp\htdocs\QuickShop`)
3. Start Apache and MySQL from the XAMPP Control Panel
5. Visit [http://localhost/QuickShop](http://localhost/QuickShop)

## Deployment
For deployment to a live server, see the full [Deployment Guide](Deployment.md).

## License
This project was created as part of a school project and is for demonstration purposes only.
