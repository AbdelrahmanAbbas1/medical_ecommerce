# 🚀 Render Free Deployment Guide (Backup Option)

## 🎯 Why Render?
- ✅ **Free tier** - 750 hours/month
- ✅ **Easy setup** - GitHub integration
- ✅ **Built-in database** - PostgreSQL included
- ✅ **Automatic HTTPS** - Professional URLs

## 📋 Prerequisites
- GitHub account
- Render account
- Your Laravel project

## 🚀 Step-by-Step Deployment

### Step 1: Push to GitHub
Same as Railway - push your code to GitHub

### Step 2: Create Render Account
1. Go to https://render.com
2. Sign up with GitHub
3. Authorize Render

### Step 3: Create Database
1. Click "New" → "PostgreSQL"
2. Name: `medical-store-db`
3. Database: `medical_store`
4. User: `medical_user`
5. Click "Create Database"

### Step 4: Deploy Web Service
1. Click "New" → "Web Service"
2. Connect your GitHub repository
3. Configure:
   - **Name:** medical-store
   - **Environment:** PHP
   - **Build Command:** `composer install --no-dev --optimize-autoloader && npm ci && npm run build`
   - **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`

### Step 5: Environment Variables
Add these in Render dashboard:
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your_generated_key
APP_URL=https://your-app-name.onrender.com
DB_CONNECTION=pgsql
DB_HOST=your_db_host
DB_PORT=5432
DB_DATABASE=medical_store
DB_USERNAME=medical_user
DB_PASSWORD=your_db_password
```

## 🧪 Test Deployment
Same testing checklist as Railway.

## 🔧 Troubleshooting
- Check logs in Render dashboard
- Ensure all environment variables are set
- Run migrations in Render console

Perfect backup option! 🎯
