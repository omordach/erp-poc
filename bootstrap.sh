#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACK="$ROOT/backend"
FRONT="$ROOT/frontend"

echo "==> Checking prerequisites"
command -v php >/dev/null || { echo "PHP not found"; exit 1; }
command -v composer >/dev/null || { echo "Composer not found"; exit 1; }
command -v node >/dev/null || { echo "Node.js not found"; exit 1; }
command -v npm >/dev/null || { echo "npm not found"; exit 1; }
command -v mysql >/dev/null || { echo "mysql client not found"; exit 1; }

echo "==> Backend: composer install"
cd "$BACK"
composer install --no-interaction --prefer-dist

if [ ! -f "$BACK/.env" ]; then
  echo "==> Creating backend .env from example"
  cp "$BACK/.env.example" "$BACK/.env"
fi

php artisan key:generate --force

# Load database credentials
ENV_FILE="$BACK/.env"
if [ ! -f "$ENV_FILE" ]; then
  ENV_FILE="$BACK/.env.example"
fi
set -a
# shellcheck disable=SC1090
source "$ENV_FILE"
set +a

MYSQL_OPTS=(--host="$LANDLORD_DB_HOST" --port="$LANDLORD_DB_PORT" --user="$LANDLORD_DB_USERNAME" --password="$LANDLORD_DB_PASSWORD")
if ! mysql "${MYSQL_OPTS[@]}" -e "USE \`$LANDLORD_DB_DATABASE\`" >/dev/null 2>&1; then
  echo "==> Creating landlord database"
  mysql "${MYSQL_OPTS[@]}" -e "CREATE DATABASE IF NOT EXISTS \`$LANDLORD_DB_DATABASE\`"
fi

echo "==> Landlord migrate"
php artisan migrate --database=landlord --path=database/migrations_landlord --force

echo "==> Landlord seed (creates tenants + their DBs)"
php artisan db:seed --class="Database\\Seeders\\LandlordDatabaseSeeder" --force

echo "==> Tenants migrate (all tenants)"
php artisan tenants:migrate:poc --force

echo "==> Tenants seed (users, demo data, demo tokens)"
php artisan tenants:seed:poc --force

echo "==> Starting Laravel (http://127.0.0.1:8000) in background"
php artisan serve --host=127.0.0.1 --port=8000 > storage/logs/dev-server.log 2>&1 &
LARAVEL_PID=$!
echo "Laravel PID: $LARAVEL_PID"

cleanup() {
  echo "==> Stopping Laravel (PID $LARAVEL_PID)"
  kill $LARAVEL_PID 2>/dev/null || true
}
trap cleanup EXIT

echo "==> Frontend: npm install & dev (http://127.0.0.1:5173)"
cd "$FRONT"
npm install
npm run dev
