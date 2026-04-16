#!/bin/sh
set -e

DOMAIN="${DOMAIN:-hovi-content.annamloi.com}"
CERT_NAME="${CERT_NAME:-$DOMAIN}"
CERT_DIR="/etc/letsencrypt/live/${CERT_NAME}"

mkdir -p "$CERT_DIR"

if [ ! -f "$CERT_DIR/fullchain.pem" ] || [ ! -f "$CERT_DIR/privkey.pem" ]; then
  echo "[nginx] No certificate found for ${CERT_NAME}. Generating temporary self-signed cert..."
  openssl req -x509 -nodes -newkey rsa:2048 \
    -days 1 \
    -keyout "$CERT_DIR/privkey.pem" \
    -out "$CERT_DIR/fullchain.pem" \
    -subj "/CN=${DOMAIN}" >/dev/null 2>&1
fi

echo "[nginx] Starting Nginx..."
nginx

while :; do
  sleep 6h
  echo "[nginx] Reloading Nginx to pick up certificate updates..."
  nginx -s reload || true
done
