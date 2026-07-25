<?php

declare(strict_types=1);

use App\Models\Central\Domain;
use App\Models\Central\Tenant;

return [
    'tenant_model' => Tenant::class,

    /*
     * Single-database tenancy: tenant_id is resolved from the URL path
     * via InitializeTenancyByPath middleware. No separate databases are created.
     * tenant()->tenant gives the active Tenant model instance.
     */

    'domain_model' => Domain::class,

    /**
     * The list of domains hosting your central app.
     */
    'central_domains' => [
        '127.0.0.1',
        'localhost',
        env('APP_DOMAIN', 'app_marilms_laravel12.test'),
    ],

    /**
     * Bootstrappers: DatabaseTenancyBootstrapper dihapus karena kita menggunakan
     * single-database tenancy. Tidak ada lagi switch database per tenant.
     */
    'bootstrappers' => [
        // Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class, // DISABLED: single-db tenancy
        // Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        // Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        // Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
    ],

    /**
     * Cache tenancy config (tidak digunakan saat ini).
     */
    'cache' => [
        'tag_base' => 'tenant',
    ],

    /**
     * Filesystem tenancy config (tidak digunakan saat ini).
     */
    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => ['local', 'public'],
        'root_override' => [
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],
        'suffix_storage_path' => true,
        'asset_helper_tenancy' => false,
    ],

    /**
     * Redis tenancy config (tidak digunakan saat ini).
     */
    'redis' => [
        'prefix_base' => 'tenant',
        'prefixed_connections' => [],
    ],

    /**
     * Features yang aktif.
     */
    'features' => [
        Stancl\Tenancy\Features\UniversalRoutes::class,
    ],

    /**
     * Should tenancy routes be registered.
     */
    'routes' => true,

    /**
     * Migration parameters (folder tenant/ sudah tidak digunakan,
     * semua migrasi kini ada di database/migrations/ utama).
     */
    'migration_parameters' => [
        '--force' => true,
        '--path' => [database_path('migrations/tenant')],
        '--realpath' => true,
    ],

    /**
     * Seeder parameters (tidak digunakan lagi).
     */
    'seeder_parameters' => [
        '--class' => 'Database\\Seeders\\TenantDatabaseSeeder',
    ],
];
