#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x build-app.sh`

# Exit on error
set -e

# Install dependencies
npm install

# Build frontend assets
npm run build

# Clear caches
php artisan optimize:clear

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
