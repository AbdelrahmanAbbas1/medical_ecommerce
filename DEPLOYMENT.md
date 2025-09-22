# Deployment Guide

This guide will help you deploy the Medical E-commerce System to a free hosting platform.

## 🚀 Recommended Free Hosting Platforms

### 1. **Render** (Recommended)
- **Free Tier**: 750 hours/month
- **Features**: Automatic deployments, custom domains, SSL
- **Database**: PostgreSQL (free tier)
- **URL**: https://render.com

### 2. **Railway**
- **Free Tier**: $5 credit/month
- **Features**: Easy deployment, automatic HTTPS
- **Database**: MySQL/PostgreSQL
- **URL**: https://railway.app

### 3. **Heroku** (Limited Free)
- **Free Tier**: Limited (consider paid plans)
- **Features**: Easy deployment, add-ons
- **URL**: https://heroku.com

### 4. **Vercel** (Frontend only)
- **Free Tier**: Unlimited static sites
- **Note**: Not suitable for full Laravel apps

## 📋 Pre-Deployment Checklist

### ✅ Required Files
- [x] `.env` file configured
- [x] Database migrations ready
- [x] Seeders created
- [x] Storage link created
- [x] Assets compiled (`npm run build`)
- [x] Configuration cached

### ✅ Environment Variables
Ensure these are set in your hosting platform:
```env
APP_NAME="Medical E-commerce"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-app-url.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=your-db-username
DB_PASSWORD=your-db-password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

## 🎯 Deployment Steps (Render)

### 1. Prepare Repository
```bash
# Ensure all files are committed
git add .
git commit -m "Ready for deployment"
git push origin main
```

### 2. Create Render Account
1. Go to https://render.com
2. Sign up with GitHub
3. Connect your repository

### 3. Create Web Service
1. Click "New +" → "Web Service"
2. Connect your repository
3. Configure settings:
   - **Name**: medical-ecommerce
   - **Runtime**: PHP
   - **Build Command**: `composer install --optimize-autoloader --no-dev && npm install && npm run build`
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

### 4. Create Database
1. Click "New +" → "PostgreSQL"
2. Configure:
   - **Name**: medical-ecommerce-db
   - **Database**: medical_ecommerce
   - **User**: medical_user

### 5. Configure Environment Variables
Add these to your web service:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=medical_ecommerce
DB_USERNAME=medical_user
DB_PASSWORD=your-db-password
```

### 6. Deploy
1. Click "Create Web Service"
2. Wait for deployment to complete
3. Run migrations and seeders via Render shell:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   ```

## 🎯 Deployment Steps (Railway)

### 1. Install Railway CLI
```bash
npm install -g @railway/cli
railway login
```

### 2. Initialize Railway
```bash
railway init
railway add mysql
```

### 3. Deploy
```bash
railway up
```

### 4. Run Migrations
```bash
railway run php artisan migrate --force
railway run php artisan db:seed --force
railway run php artisan storage:link
```

## 🔧 Post-Deployment Steps

### 1. Verify Deployment
- [ ] Home page loads
- [ ] Products display correctly
- [ ] Cart functionality works
- [ ] Checkout process works
- [ ] Admin login works
- [ ] Admin panel accessible

### 2. Test Admin Features
- [ ] Login with admin credentials
- [ ] Create/edit/delete products
- [ ] View orders
- [ ] Check product logging

### 3. Performance Optimization
```bash
# On your hosting platform
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 🚨 Common Issues & Solutions

### Issue: 500 Error
**Solution**: Check environment variables and logs
```bash
php artisan config:clear
php artisan cache:clear
```

### Issue: Database Connection Failed
**Solution**: Verify database credentials and connection string

### Issue: Assets Not Loading
**Solution**: Ensure `npm run build` was executed and files exist in `public/build/`

### Issue: Storage Link Missing
**Solution**: Run `php artisan storage:link` on the server

### Issue: Permission Errors
**Solution**: Ensure proper file permissions
```bash
chmod -R 755 storage bootstrap/cache
```

## 📊 Monitoring & Maintenance

### 1. Log Monitoring
- Check application logs regularly
- Monitor error rates
- Set up alerts for critical issues

### 2. Database Maintenance
- Regular backups
- Monitor database size
- Optimize queries if needed

### 3. Performance Monitoring
- Monitor response times
- Check memory usage
- Optimize assets if needed

## 🔐 Security Considerations

### 1. Environment Security
- Never commit `.env` files
- Use strong database passwords
- Enable HTTPS/SSL

### 2. Application Security
- Keep Laravel updated
- Use strong admin passwords
- Regular security audits

## 📞 Support

If you encounter issues during deployment:
1. Check the hosting platform's documentation
2. Review Laravel deployment guides
3. Contact the hosting platform's support
4. Check application logs for specific errors

## 🎉 Success!

Once deployed successfully, your medical e-commerce system will be accessible to users worldwide with:
- ✅ Responsive design
- ✅ Secure authentication
- ✅ Product management
- ✅ Order processing
- ✅ Admin panel
- ✅ Change logging
- ✅ Test data included

**Live URL**: https://your-app-name.onrender.com
**Admin Login**: admin@example.com / admin
