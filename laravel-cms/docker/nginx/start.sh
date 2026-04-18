#!/bin/sh
set -e

echo "[nginx] Validating config..."
nginx -t

echo "[nginx] Starting Nginx behind external reverse proxy..."
nginx

while :; do
  sleep 6h
  echo "[nginx] Reloading Nginx..."
  nginx -s reload || true
done
