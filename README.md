# Medical E-commerce System

A comprehensive medical e-commerce platform built with Laravel, featuring customer ordering, admin management, and product tracking capabilities.

## 🏥 Project Overview

This is a full-stack medical e-commerce system that allows customers to browse and purchase medical products while providing administrators with comprehensive management tools. The system includes product management, order processing, inventory tracking, and audit logging.

## ✨ Features

### Customer Side (Public Area)
- **Product Catalog**: Browse medical products with search, filter, and sort functionality
- **Shopping Cart**: Add, update, and remove items from cart
- **Checkout Process**: Complete orders without registration (guest checkout)
- **Order Confirmation**: View order details and confirmation
- **Responsive Design**: Mobile and desktop optimized

### Admin Panel (Requires Login)
- **Authentication**: Secure admin login with Laravel Breeze
- **Product Management**: Full CRUD operations for products
  - Create, read, update, delete products
  - Soft delete functionality (no restore)
  - Image upload support
  - Category management
- **Order Management**: View and manage customer orders
  - Order listing with search and filters
  - Detailed order information
  - Customer contact details
- **Product Change Logging**: Automatic tracking of all product modifications
  - Created, updated, deleted, force_deleted actions
  - User attribution
  - Before/after value tracking

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates with Bootstrap 5
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Styling**: Bootstrap 5 with Bootstrap Icons
- **JavaScript**: Alpine.js for interactivity

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- MySQL 5.7 or higher
- Node.js and NPM (for asset compilation)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd medical_ecommerce
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medical_ecommerce
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Database Setup
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 6. Asset Compilation
```bash
npm run build
# or for development
npm run dev
```

### 7. Start the Application
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## 👤 Test Credentials

### Admin User
- **Email**: `admin@example.com`
- **Password**: `admin`

### Regular User
- **Email**: `abdelrahman@example.com`
- **Password**: `password`