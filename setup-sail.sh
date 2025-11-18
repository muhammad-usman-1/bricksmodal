#!/bin/bash

# Laravel Sail Setup Script
# This script helps set up Laravel Sail when dependencies aren't installed yet

echo "🚀 Setting up Laravel Sail..."

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies using Docker..."
    
    # Use a temporary PHP container to install dependencies
    docker run --rm \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php81-composer:latest \
        composer install --ignore-platform-reqs
    
    echo "✅ Dependencies installed!"
else
    echo "✅ Dependencies already installed"
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64" .env 2>/dev/null; then
    echo "🔑 Generating application key..."
    if [ -f "vendor/autoload.php" ]; then
        php artisan key:generate
    else
        echo "⚠️  Please run 'php artisan key:generate' after starting Sail"
    fi
fi

echo ""
echo "✅ Setup complete! You can now start Sail with:"
echo "   ./vendor/bin/sail up"
echo ""
echo "Or if you prefer using docker-compose directly:"
echo "   docker-compose up -d"




