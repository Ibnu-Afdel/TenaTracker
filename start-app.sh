#!/bin/bash
set -e

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8080
