#!/bin/sh
set -e

DOMAIN="${DOMAIN:-hovi-content.annamloi.com}"
CERT_NAME="${CERT_NAME:-$DOMAIN}"
EMAIL="${LE_EMAIL:-admin@example.com}"

request_certificate() {
  echo "[certbot] Requesting certificate for ${DOMAIN}..."
  certbot_args="--webroot -w /var/www/certbot --cert-name \"$CERT_NAME\" --email \"$EMAIL\" --agree-tos --non-interactive --rsa-key-size 4096 --keep-until-expiring"
  for d in $DOMAIN; do
    certbot_args="$certbot_args -d \"$d\""
  done
  eval "certbot certonly $certbot_args"
}

if [ ! -f "/etc/letsencrypt/live/${CERT_NAME}/fullchain.pem" ]; then
  if [ "$EMAIL" = "admin@example.com" ]; then
    echo "[certbot] LE_EMAIL is still default. Skipping initial certificate request."
    echo "[certbot] Please set LE_EMAIL in .env then restart certbot service."
  else
    request_certificate || true
  fi
fi

while :; do
  certbot renew --webroot -w /var/www/certbot --non-interactive --quiet || true
  sleep 12h
done
