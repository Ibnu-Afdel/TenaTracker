#!/bin/bash
set -e  # Stop script if any command fails

# Install dependencies
npm install

# Build assets
npm run build

# Clear and cache Laravel configs
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache

# Run database migrations (without confirmation)
php artisan migrate --force
