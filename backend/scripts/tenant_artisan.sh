#!/usr/bin/env bash
set -euo pipefail
TENANT_KEY="${1:-}"
shift || true
if [ -z "$TENANT_KEY" ]; then
  echo "Usage: $0 <tenant-key> artisan-args..."
  exit 1
fi

php -r '
use Spatie\Multitenancy\Models\Tenant;
require __DIR__."/../vendor/autoload.php";
$app = require __DIR__."/../bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$tenant = Tenant::query()->where("key","'"$TENANT_KEY"'")->first();
if(!$tenant){ fwrite(STDERR,"No tenant\n"); exit(1); }
$tenant->makeCurrent();
$code = $kernel->handle(Illuminate\Http\Request::capture());'
