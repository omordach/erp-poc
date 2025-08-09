@servers(['web' => 'deploy@example.com'])

@setup
$repo = 'git@github.com:yourorg/unionimpact-erp-poc.git';
$release_dir = '/var/www/erp-poc';
$backend = $release_dir . '/backend';
@endsetup

@story('deploy')
    clone
    composer_install
    migrate_landlord
    seed_landlord
    tenants_migrate
    tenants_seed
    cache_optimize
@endstory

@task('clone', ['on' => 'web'])
    if [ ! -d {{ $release_dir }} ]; then
        git clone {{ $repo }} {{ $release_dir }}
    else
        cd {{ $release_dir }} && git fetch && git reset --hard origin/main
    fi
@endtask

@task('composer_install', ['on' => 'web'])
    cd {{ $backend }}
    composer install --no-dev --prefer-dist -o
    if [ ! -f .env ]; then cp .env.example .env; fi
    php artisan key:generate --force
@endtask

@task('migrate_landlord', ['on' => 'web'])
    cd {{ $backend }}
    php artisan migrate --database=landlord --path=database/migrations_landlord --force
@endtask

@task('seed_landlord', ['on' => 'web'])
    cd {{ $backend }}
    php artisan db:seed --class="Database\\Seeders\\LandlordDatabaseSeeder" --force
@endtask

@task('tenants_migrate', ['on' => 'web'])
    cd {{ $backend }}
    php artisan tenants:migrate:poc
@endtask

@task('tenants_seed', ['on' => 'web'])
    cd {{ $backend }}
    php artisan tenants:seed:poc
@endtask

@task('cache_optimize', ['on' => 'web'])
    cd {{ $backend }}
    php artisan config:cache
    php artisan route:cache
@endtask
