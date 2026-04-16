#!/bin/sh
set -e

cd /var/www/html

echo "[entrypoint] Starting Laravel container..."

if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
fi

if [ ! -f vendor/autoload.php ]; then
  if [ "${APP_ENV:-production}" = "production" ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
  else
    composer install --no-interaction --prefer-dist --optimize-autoloader
  fi
fi

if [ -f .env ] && grep -Eq '^APP_KEY=$|^APP_KEY=""$' .env; then
  echo "[entrypoint] Generating APP_KEY..."
  php artisan key:generate --force
fi

chown -R www-data:www-data storage bootstrap/cache >/dev/null 2>&1 || true
chmod -R 775 storage bootstrap/cache >/dev/null 2>&1 || chmod -R 777 storage bootstrap/cache || true

php artisan config:clear >/dev/null 2>&1 || true
php artisan route:clear >/dev/null 2>&1 || true
php artisan view:clear >/dev/null 2>&1 || true

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  DB_WAIT_TIMEOUT="${DB_WAIT_TIMEOUT:-120}"
  echo "[entrypoint] Waiting for MySQL at ${DB_HOST:-mysql}:${DB_PORT:-3306} (timeout ${DB_WAIT_TIMEOUT}s)..."

  waited=0
  until php -r '
    try {
      $host = getenv("DB_HOST") ?: "mysql";
      $port = getenv("DB_PORT") ?: "3306";
      $db = getenv("DB_DATABASE") ?: "";
      $user = getenv("DB_USERNAME") ?: "";
      $pass = getenv("DB_PASSWORD") ?: "";
      $dsn = "mysql:host={$host};port={$port};dbname={$db}";
      new PDO($dsn, $user, $pass);
      exit(0);
    } catch (Throwable $e) {
      fwrite(STDERR, $e->getMessage() . PHP_EOL);
      exit(1);
    }
  '; do
    waited=$((waited + 2))
    if [ "$waited" -ge "$DB_WAIT_TIMEOUT" ]; then
      echo "[entrypoint] ERROR: Could not connect to MySQL after ${DB_WAIT_TIMEOUT}s"
      exit 1
    fi
    sleep 2
  done

  echo "[entrypoint] Running migrations..."
  php artisan migrate --force

  if [ "${RUN_SEEDER:-false}" = "true" ]; then
    echo "[entrypoint] Running database seeder..."
    php artisan db:seed --force
  fi
fi

exec "$@"