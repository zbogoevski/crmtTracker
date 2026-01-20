#!/bin/sh
# Initialize SSL certificates for production

DOMAIN_NAME=${DOMAIN_NAME:-localhost}
SSL_EMAIL=${SSL_EMAIL:-admin@${DOMAIN_NAME}}

# Wait for Nginx to be ready
until nc -z nginx 80; do
  echo "Waiting for Nginx..."
  sleep 2
done

# Request certificate if it doesn't exist
if [ ! -f "/etc/letsencrypt/live/${DOMAIN_NAME}/fullchain.pem" ]; then
  echo "Requesting SSL certificate for ${DOMAIN_NAME}..."
  certbot certonly \
    --webroot \
    --webroot-path=/var/www/certbot \
    --email ${SSL_EMAIL} \
    --agree-tos \
    --no-eff-email \
    -d ${DOMAIN_NAME} \
    -d www.${DOMAIN_NAME} || echo "Certificate request failed - will retry"
fi

echo "SSL initialization complete"
