#!/bin/bash

# Laravel Forge Deployment Script for Moodle

# Exit on any error
set -e

echo "Starting Moodle deployment..."

# Navigate to the site directory
cd $FORGE_SITE_PATH

# Pull the latest changes from the repository
echo "Pulling latest changes from $FORGE_SITE_BRANCH branch..."
git pull origin $FORGE_SITE_BRANCH

# Install/Update Composer dependencies (if any)
if [ -f "composer.json" ]; then
    echo "Installing Composer dependencies..."
    $FORGE_COMPOSER install --no-dev --optimize-autoloader --no-interaction
fi

# Copy production config file
echo "Setting up production configuration..."
if [ -f "config-production.php" ]; then
    cp config-production.php config.php
    echo "Production config applied."
else
    echo "WARNING: config-production.php not found. Manual config setup required."
fi

# Create moodledata directory if it doesn't exist
echo "Setting up moodledata directory..."
MOODLEDATA_PATH=${DATAROOT:-"$FORGE_SITE_PATH/storage/moodledata"}
if [ ! -d "$MOODLEDATA_PATH" ]; then
    mkdir -p "$MOODLEDATA_PATH"
    echo "Created moodledata directory at $MOODLEDATA_PATH"
else
    echo "Moodledata directory already exists at $MOODLEDATA_PATH"
fi

# Set proper permissions
echo "Setting proper permissions..."
chmod -R 755 .
if [ -d "$MOODLEDATA_PATH" ]; then
    chmod -R 777 "$MOODLEDATA_PATH"
    echo "Set permissions for moodledata directory: $MOODLEDATA_PATH"
else
    echo "ERROR: Moodledata directory not found after creation attempt: $MOODLEDATA_PATH"
fi

# Run Moodle CLI upgrade (if Moodle is already installed)
echo "Checking for Moodle upgrades..."
if [ -f "config.php" ]; then
    $FORGE_PHP admin/cli/upgrade.php --non-interactive || echo "Upgrade check completed or not needed"
fi

# Clear Moodle caches
echo "Clearing Moodle caches..."
if [ -f "config.php" ]; then
    $FORGE_PHP admin/cli/purge_caches.php || echo "Cache purge completed"
fi

# Set final permissions
chmod 644 config.php

echo "Moodle deployment completed successfully!"

# Send notification to Slack
# curl -X POST -H 'Content-type: application/json' \
#   --data '{"text":"Moodle deployment completed on community.theviifoundation.org"}' \
#   YOUR_WEBHOOK_URL
