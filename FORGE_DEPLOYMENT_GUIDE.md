# Moodle Deployment Guide for Laravel Forge

This guide will walk you through deploying your Moodle 5.0.1 instance from development (`moodle.test`) to production using Laravel Forge.

## Prerequisites ✅

- [x] Laravel Forge server provisioned and ready
- [x] your.tld configured
- [x] Local Moodle development environment working at `moodle.test`
- [x] Fork Moodle repository from `https://github.com/moodle/moodle/tree/MOODLE_501_STABLE`

## Step 1: Laravel Forge Site Setup

### 1.1 Create Site in Forge

1. Go to your Laravel Forge server dashboard
2. Click "New Site"
3. Configure:
   - **Domain**: your.tld
   - **Project Type**: Select "Static HTML" or "General PHP"
   - **Web Directory**: `/public` (since Moodle's public files are in the public directory)

### 1.2 Configure Git Repository

1. In Forge site settings, go to "Git Repository"
2. Set up repository:
   - **Provider**: GitHub
   - **Repository**: YOUR MOODLE FORK
   - **Branch**: `MOODLE_501_STABLE`
   - **Deploy automatically**: Enable this for automatic deployments

## Step 2: Database Configuration

### 2.1 Create Database

1. In Forge server dashboard, go to "Database"
2. Create a new database:
   - **Database Name**: your preferred DB name
   - **Database User**: Create a dedicated user with a secure password

### 2.2 Configure Environment Variables

Since this is a public repository, database credentials are handled via environment variables in Laravel Forge:

1. In Forge site settings, go to "Environment"
2. Add the following environment variables:
   ```
   WWWROOT='https://your.tld'
   DATAROOT='/home/forge/your.tld/storage/moodledata'
   ADMIN=admin
   DB_HOST=localhost
   DB_DATABASE=community_moodle
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_secure_password
   DB_PORT=3306
   ```

The `config-production.php` file uses `getenv()` to read these values securely without exposing them in the repository.

## Step 3: PHP Configuration

### 3.1 Required PHP Extensions

Ensure these PHP extensions are enabled on your Forge server:

- mysqli (for MySQL database)
- gd (for image processing)
- intl (for internationalization)
- zip (for file compression)
- curl (for external requests)
- openssl (for security)
- mbstring (for multibyte strings)
- xml (for XML processing)
- json (for JSON handling)

### 3.2 PHP Settings

Recommended PHP settings for Moodle (configure in Forge server settings):

```ini
memory_limit = 512M
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
max_input_vars = 5000
```

## Step 4: Deployment Script Setup

### 4.1 Configure Deployment Script

1. In Forge site settings, go to "Deployment Script"
2. Replace the default script with the contents of `deploy-forge.sh`
3. The script will:
   - Pull latest changes from MOODLE_501_STABLE branch
   - Install Composer dependencies
   - Apply production configuration
   - Set up moodledata directory
   - Run Moodle upgrades
   - Clear caches
   - Set proper permissions

### 4.2 Environment Variables (if needed)

If you need environment-specific settings, you can set these in Forge:

1. Go to "Environment"
2. Add any required environment variables

## Step 5: SSL Certificate

### 5.1 Enable SSL

1. In Forge site settings, go to "SSL"
2. Choose your preferred option:
   - **LetsEncrypt** (recommended for free SSL)
   - **Cloudflare** (if using Cloudflare)
   - **Custom Certificate** (if you have your own)

### 5.2 Force HTTPS

After SSL is configured:

1. Enable "Force HTTPS" in Forge
2. This ensures all traffic is redirected to HTTPS

## Step 6: Initial Deployment

### 6.1 First Deployment

1. Ensure `config-production.php` is committed to your repository
2. Push your local changes to the MOODLE_501_STABLE branch
3. Trigger deployment in Forge (or wait for auto-deployment)

---

### 6.2 Initial Moodle Installation

After first deployment, you need to run the Moodle installer:

1. SSH into your server:

   ```bash
   forge ssh your-server-name
   ```

2. Navigate to your site directory:

3. Run Moodle CLI installer:
   ```bash
   php admin/cli/install_database.php --agree-license --fullname="Full Name" --shortname="Short Name" --adminuser=admin --adminpass="YourSecurePassword" --adminemail="admin@email.org"
   ```

## Step 7: Post-Installation Configuration

### 7.1 Directory Permissions

Ensure correct permissions are set:

```bash
# Set web files permissions
chmod -R 755 /home/forge/your.tld
# Set moodledata permissions
chmod -R 777 /home/forge/your.tld/storage/moodledata
```

### 7.2 Verify Installation

1. Visit `https://your.tld`
2. You should see the Moodle login page
3. Log in with the admin credentials you set during installation

## Step 8: Data Migration (Optional)

If you want to migrate data from your development site:

### 8.1 Export Development Data

1. Create a backup of your development site:
   ```bash
   # On your local machine
   php admin/cli/backup.php --courseid=1 --destination=/path/to/backup
   ```

### 8.2 Import to Production

1. Upload backup file to production server
2. Restore using Moodle CLI tools

## Step 9: Performance Optimization

### 9.1 Caching Configuration

Add these to your production config for better performance:

```php
// Enable caching
$CFG->cachejs = 1;
$CFG->cssoptimiser = 1;
$CFG->yuicomboloading = 1;

// Optional: Redis cache (if available)
// $CFG->session_handler_class = '\core\session\redis';
// $CFG->session_redis_host = '127.0.0.1';
// $CFG->session_redis_port = 6379;
```

### 9.2 Database Optimization

Consider setting up database query caching and optimization.

## Monitoring and Maintenance

### Daily Tasks

- Monitor server resources
- Check error logs
- Backup database

### Weekly Tasks

- Update Moodle if security patches are available
- Review user activity and performance

### Monthly Tasks

- Full system backup
- Security audit
- Performance optimization review

## Troubleshooting

### Common Issues

1. **Permission Errors**:

   ```bash
   chmod -R 755 /home/forge/community.theviifoundation.org
   chmod -R 777 /home/forge/community.theviifoundation.org/storage/moodledata
   ```

2. **Database Connection Issues**:

   - Verify database credentials in config.php
   - Check if database user has proper permissions
   - Ensure MySQL service is running

3. **SSL Issues**:

   - Regenerate SSL certificate in Forge
   - Check DNS settings for domain

4. **Performance Issues**:
   - Increase PHP memory limit
   - Enable Moodle caching
   - Consider CDN for static assets

## Support and Resources

- **Moodle Documentation**: https://docs.moodle.org/
- **Laravel Forge Documentation**: https://forge.laravel.com/docs
- **Moodle Developer Resources**: https://moodledev.io/

---

**Next Steps**: Follow this guide step by step, and your Moodle instance will be successfully deployed to production on Laravel Forge!
