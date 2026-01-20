#!/bin/bash

# Production Docker Setup Script for Modular Laravel
# This script sets up the production environment with Docker, SSL, and multi-tenant support

set -e

echo "🚀 Setting up Modular Laravel Production Environment..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

# Check required environment variables
if [ -z "$DOMAIN_NAME" ]; then
    echo "❌ DOMAIN_NAME environment variable is not set."
    echo "   Please set it: export DOMAIN_NAME=yourdomain.com"
    exit 1
fi

if [ -z "$DB_PASSWORD" ]; then
    echo "❌ DB_PASSWORD environment variable is not set."
    echo "   Please set it: export DB_PASSWORD=your_secure_password"
    exit 1
fi

if [ -z "$REDIS_PASSWORD" ]; then
    echo "❌ REDIS_PASSWORD environment variable is not set."
    echo "   Please set it: export REDIS_PASSWORD=your_redis_password"
    exit 1
fi

# Create .env.production file
echo "📝 Creating .env.production file..."
cat > .env.production << EOF
APP_NAME=Laravel
APP_ENV=production
APP_DEBUG=false
APP_URL=https://${DOMAIN_NAME}

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=${DB_DATABASE:-crmtracker}
DB_USERNAME=${DB_USERNAME:-crmtracker}
DB_PASSWORD=${DB_PASSWORD}

BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=redis
REDIS_PASSWORD=${REDIS_PASSWORD}
REDIS_PORT=6379
REDIS_DB=0

# Multi-tenant configuration
MULTI_TENANT_ENABLED=true
TENANT_ID_HEADER=X-Tenant-ID

# Security
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict

MAIL_MAILER=smtp
MAIL_HOST=${MAIL_HOST:-smtp.mailtrap.io}
MAIL_PORT=${MAIL_PORT:-2525}
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-noreply@${DOMAIN_NAME}}
MAIL_FROM_NAME="${MAIL_FROM_NAME:-Laravel}"
EOF

echo "✅ .env.production file created"

# Generate DH parameters for SSL
echo "🔐 Generating DH parameters for SSL..."
if [ ! -f .docker/nginx/dhparam/dhparam.pem ]; then
    mkdir -p .docker/nginx/dhparam
    docker run --rm -v "$(pwd)/.docker/nginx/dhparam:/dhparam" alpine/openssl dhparam -out /dhparam/dhparam.pem 2048
    echo "✅ DH parameters generated"
else
    echo "✅ DH parameters already exist"
fi

# Start Docker containers (without SSL first)
echo "🐳 Starting Docker containers..."
docker compose -f docker-compose-prod.yml up -d postgres redis

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 15

# Copy .env.production to .env
echo "📋 Copying production environment configuration..."
cp .env.production .env

# Start app container for running commands
echo "🚀 Starting app container..."
docker compose -f docker-compose-prod.yml up -d app

# Wait for app container to be ready
echo "⏳ Waiting for app container to be ready..."
sleep 10

# Install dependencies
echo "📦 Installing dependencies..."
docker compose -f docker-compose-prod.yml exec -T app composer install --no-dev --optimize-autoloader

# Generate application key if needed
echo "🔑 Generating application key..."
docker compose -f docker-compose-prod.yml exec -T app php artisan key:generate --force

# Run migrations
echo "🗄️ Running migrations..."
docker compose -f docker-compose-prod.yml exec -T app php artisan migrate --force

# Publish Spatie Permission migrations
echo "🔐 Publishing Spatie Permission migrations..."
docker compose -f docker-compose-prod.yml exec -T app php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations" --force

# Run migrations again
echo "🗄️ Running migrations with permission tables..."
docker compose -f docker-compose-prod.yml exec -T app php artisan migrate --force

# Optimize for production
echo "⚡ Optimizing for production..."
docker compose -f docker-compose-prod.yml exec -T app php artisan config:cache
docker compose -f docker-compose-prod.yml exec -T app php artisan route:cache
docker compose -f docker-compose-prod.yml exec -T app php artisan view:cache

# Set proper permissions
echo "🔧 Setting proper permissions..."
docker compose -f docker-compose-prod.yml exec -T app chmod -R 775 storage bootstrap/cache
docker compose -f docker-compose-prod.yml exec -T app chown -R www-data:www-data storage bootstrap/cache

# Start Nginx and App
echo "🌐 Starting Nginx and App containers..."
docker compose -f docker-compose-prod.yml up -d nginx app

# Request SSL certificate
echo "🔒 Requesting SSL certificate from Let's Encrypt..."
docker compose -f docker-compose-prod.yml run --rm certbot certonly \
    --webroot \
    --webroot-path=/var/www/certbot \
    --email ${SSL_EMAIL:-admin@${DOMAIN_NAME}} \
    --agree-tos \
    --no-eff-email \
    -d ${DOMAIN_NAME} \
    -d www.${DOMAIN_NAME}

# Restart Nginx with SSL
echo "🔄 Restarting Nginx with SSL configuration..."
docker compose -f docker-compose-prod.yml restart nginx

# Generate Swagger documentation
echo "📚 Generating Swagger documentation..."
docker compose -f docker-compose-prod.yml exec -T app php artisan l5-swagger:generate

echo ""
echo "🎉 Production setup completed successfully!"
echo ""
echo "📋 Available services:"
echo "   🌐 HTTPS: https://${DOMAIN_NAME}"
echo "   🗄️ Database: localhost:5432 (${DB_DATABASE:-crmtracker}/${DB_USERNAME:-crmtracker})"
echo "   📚 API Docs: https://${DOMAIN_NAME}/api/documentation"
echo "   🔴 Redis: localhost:6379"
echo ""
echo "🔧 Useful commands:"
echo "   docker compose -f docker-compose-prod.yml logs -f"
echo "   docker compose -f docker-compose-prod.yml exec app php artisan migrate"
echo "   docker compose -f docker-compose-prod.yml exec app php artisan queue:work"
echo ""
echo "🛑 To stop: docker compose -f docker-compose-prod.yml down"
echo "🔄 To restart: docker compose -f docker-compose-prod.yml restart"
echo ""
echo "📝 SSL Certificate will auto-renew via Certbot container"
