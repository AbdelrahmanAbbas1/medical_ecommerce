# 🚀 Complete Deployment Guide for Laravel Medical E-Commerce

## 📋 Pre-Deployment Checklist

### ✅ Development Backup (COMPLETED)
- [x] Git repository initialized
- [x] All files committed with hash: `6406b89`
- [x] Development version safely backed up

---

## 🎯 Deployment Options

### Option 1: Free Hosting (Recommended for Testing)
- **Railway**: Easy Laravel deployment
- **Render**: Free tier available
- **Heroku**: Free tier discontinued, but paid plans available
- **PlanetScale + Vercel**: Database + Frontend hosting

### Option 2: VPS/Cloud Hosting (Recommended for Production)
- **DigitalOcean**: $5/month droplet
- **Linode**: $5/month nanode
- **AWS EC2**: Pay-as-you-go
- **Google Cloud**: Free tier available

### Option 3: Shared Hosting (Budget Option)
- **cPanel hosting** with PHP 8.1+ support
- **Laravel Forge** compatible hosts

---

## 🛠️ Step-by-Step Deployment Process

### Step 1: Prepare Your Application

#### Files to Modify for Production:

1. **`.env` Configuration** (Use `deployment-config.env` as template):
```bash
# Copy deployment config
cp deployment-config.env .env.production
```

2. **Update these values in your `.env`:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_DATABASE=your_production_db_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_secure_password
```

### Step 2: Choose Your Deployment Method

#### Method A: Railway (Easiest)

1. **Sign up at [Railway.app](https://railway.app)**
2. **Connect your GitHub repository**
3. **Add MySQL database**
4. **Set environment variables:**
   - Copy from `deployment-config.env`
   - Set `APP_KEY` (will be auto-generated)
   - Set database credentials from Railway

#### Method B: VPS Deployment

1. **Create VPS** (DigitalOcean/Linode)
2. **Install LAMP/LEMP stack**
3. **Upload your files**
4. **Run deployment script**

#### Method C: Shared Hosting

1. **Upload files via FTP/cPanel**
2. **Create database**
3. **Configure environment**

---

## 🔧 Detailed VPS Deployment Instructions

### Prerequisites
- Ubuntu 20.04+ server
- Root access or sudo privileges
- Domain name (optional)

### 1. Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath php8.1-cli php8.1-common php8.1-json php8.1-opcache php8.1-readline

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Database Setup

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE medical_store_production;
CREATE USER 'medical_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON medical_store_production.* TO 'medical_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Application Deployment

```bash
# Create application directory
sudo mkdir -p /var/www/medical-store
cd /var/www/medical-store

# Clone your repository (if using Git)
sudo git clone https://github.com/yourusername/medical-ecommerce.git .

# Or upload files via SCP/SFTP
# scp -r /path/to/local/project/* user@server:/var/www/medical-store/

# Set ownership
sudo chown -R www-data:www-data /var/www/medical-store
sudo chmod -R 755 /var/www/medical-store

# Run deployment script
sudo -u www-data ./deploy.sh
```

### 4. Web Server Configuration

#### Nginx Configuration (`/etc/nginx/sites-available/medical-store`):

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/medical-store/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/medical-store /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 5. SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

---

## 📁 Files You Need to Modify for Deployment

### 1. Environment Configuration
- **File**: `.env` (copy from `deployment-config.env`)
- **Changes**: Database credentials, APP_URL, APP_ENV=production

### 2. Web Server Configuration
- **File**: `/etc/nginx/sites-available/medical-store` (create new)
- **Changes**: Document root, server name, PHP-FPM configuration

### 3. Database Configuration
- **File**: Database server setup
- **Changes**: Create database, user, and grant permissions

### 4. Storage Permissions
- **Files**: `storage/` and `bootstrap/cache/` directories
- **Changes**: Set proper ownership and permissions

---

## 🔐 Security Considerations

### 1. Environment Security
```bash
# Secure .env file
chmod 600 .env
chown www-data:www-data .env
```

### 2. Database Security
- Use strong passwords
- Limit database user privileges
- Enable MySQL SSL if possible

### 3. File Permissions
```bash
# Set proper permissions
find /var/www/medical-store -type f -exec chmod 644 {} \;
find /var/www/medical-store -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
```

### 4. Firewall Configuration
```bash
# Enable UFW firewall
sudo ufw enable
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw allow mysql
```

---

## 🧪 Testing Your Deployment

### 1. Basic Functionality Test
- [ ] Homepage loads
- [ ] User registration works
- [ ] User login works
- [ ] Admin login works
- [ ] Product listing displays
- [ ] Cart functionality works
- [ ] Checkout process works

### 2. Admin Panel Test
- [ ] Admin can access product management
- [ ] Admin can create/edit/delete products
- [ ] Admin can view orders
- [ ] Image uploads work
- [ ] Soft delete functionality works

### 3. Performance Test
- [ ] Page load times are acceptable
- [ ] Database queries are optimized
- [ ] Static assets load quickly

---

## 🚨 Troubleshooting Common Issues

### 1. "Application key not set"
```bash
php artisan key:generate
```

### 2. "Storage link not found"
```bash
php artisan storage:link
```

### 3. "Permission denied" errors
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 4. "Database connection failed"
- Check database credentials in `.env`
- Ensure MySQL service is running
- Verify database exists and user has permissions

### 5. "Assets not loading"
```bash
npm run build
php artisan view:clear
```

---

## 📊 Monitoring and Maintenance

### 1. Log Monitoring
```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# View Nginx logs
tail -f /var/log/nginx/error.log
```

### 2. Database Backups
```bash
# Create backup script
mysqldump -u medical_user -p medical_store_production > backup_$(date +%Y%m%d_%H%M%S).sql
```

### 3. Regular Updates
- Keep Laravel and dependencies updated
- Monitor security advisories
- Update server packages regularly

---

## 🎉 Deployment Checklist

- [ ] Development version backed up in Git
- [ ] Production environment configured
- [ ] Database created and configured
- [ ] Application deployed to server
- [ ] Web server configured
- [ ] SSL certificate installed
- [ ] Environment variables set
- [ ] Permissions configured
- [ ] Application tested
- [ ] Domain configured (if applicable)
- [ ] Monitoring set up

---

## 📞 Support

If you encounter any issues during deployment:

1. Check the Laravel logs: `storage/logs/laravel.log`
2. Check web server logs
3. Verify environment configuration
4. Test database connectivity
5. Check file permissions

**Your Laravel Medical E-Commerce application is now ready for production!** 🚀
