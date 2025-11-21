# Laravel Forge Nginx Setup for Moodle

## Critical Steps to Fix Styles and Images

### 1. Update Site Configuration in Laravel Forge

1. **Set Document Root**:
   - Go to your site in Forge
   - Navigate to **Meta** tab
   - Set **Web Directory** to: `/public`
   - This is CRITICAL for Moodle 5.1+

### 2. Update Nginx Configuration

1. In Laravel Forge, go to your site
2. Click on **Nginx Configuration**
3. **Replace the entire configuration** with the content from `nginx-forge-moodle.conf`
4. **Important**: Update these lines in the config:
   - Change `unix:/var/run/php/php8.4-fpm.sock` to match your PHP version
   - Change `/var/log/nginx/*-error.log` to `/var/log/nginx/YOUR_SITE_ID-error.log`

### 3. Key Configuration Features

This nginx config includes:

✅ **Proper FastCGI with PATH_INFO handling** - Critical for Moodle 4.5+  
✅ **Moodle Routing Engine** - Handles URL rewriting correctly  
✅ **Static file serving** - CSS, JS, images with proper caching  
✅ **Security headers** - Blocks access to sensitive files  
✅ **Large file uploads** - 100M limit for Moodle content

### 4. After Applying Configuration

1. **Restart Nginx**: Forge will do this automatically
2. **Deploy your site** to ensure all changes are applied
3. **Clear Moodle caches**:
   ```bash
   php admin/cli/purge_caches.php
   ```
4. **Test the site** - styles and images should now load correctly

### 5. Troubleshooting

If styles still don't load:

1. Check nginx error logs in Forge
2. Verify document root is set to `/public`
3. Ensure PHP-FPM socket path is correct
4. Check file permissions on moodledata directory (should be 777)

## Environment Variables Required

Make sure your `.env` file includes:

```
WWWROOT=https://community.theviifoundation.org
DATAROOT=/home/forge/community.theviifoundation.org/storage/moodledata
```
