#!/bin/bash

# Laravel Medical E-Commerce Deployment Script
# Run this script on your production server

echo "🚀 Starting Laravel Medical E-Commerce Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "Please run this script from your Laravel project root directory"
    exit 1
fi

print_status "Found Laravel project"

# 1. Install/Update Composer Dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
if [ $? -eq 0 ]; then
    print_status "Composer dependencies installed"
else
    print_error "Failed to install Composer dependencies"
    exit 1
fi

# 2. Install NPM Dependencies and Build Assets
echo "🎨 Installing NPM dependencies and building assets..."
npm ci --production
if [ $? -eq 0 ]; then
    npm run build
    if [ $? -eq 0 ]; then
        print_status "Assets built successfully"
    else
        print_error "Failed to build assets"
        exit 1
    fi
else
    print_error "Failed to install NPM dependencies"
    exit 1
fi

# 3. Generate Application Key (if not set)
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate
    print_status "Application key generated"
fi

# 4. Run Database Migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force
if [ $? -eq 0 ]; then
    print_status "Database migrations completed"
else
    print_error "Database migrations failed"
    exit 1
fi

# 5. Create Storage Link
echo "🔗 Creating storage link..."
php artisan storage:link
print_status "Storage link created"

# 6. Clear and Cache Configuration
echo "🧹 Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_status "Configuration cached"

# 7. Set Proper Permissions
echo "🔐 Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
print_status "Permissions set"

# 8. Clear Application Cache
echo "🧹 Clearing application cache..."
php artisan cache:clear
print_status "Application cache cleared"

print_status "🎉 Deployment completed successfully!"
print_warning "Don't forget to:"
echo "  1. Update your .env file with production settings"
echo "  2. Configure your web server (Apache/Nginx)"
echo "  3. Set up SSL certificate"
echo "  4. Test your application"
