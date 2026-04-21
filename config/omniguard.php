<?php

return [

    'models' => [
        /*
         * The model that should be used for permissions.
         */
        'permission' => OmniGuard\Models\Permission::class,

        /*
         * The model that should be used for roles.
         */
        'role' => OmniGuard\Models\Role::class,

        /*
         * Dynamic Model Integrations (The Models that OmniGuard will protect via bitmasking)
         */
        'targets' => [
            'users' => [
                'class' => \App\Models\User::class,
                'bitmask_column' => 'bit_mask',
            ],
        ],
    ],

    'table_names' => [
        /*
         * Simple table mapping for OmniGuard.
         */
        'roles' => 'omni_roles',
        'permissions' => 'omni_permissions',
        'model_has_permissions' => 'omni_model_has_permissions',
        'model_has_roles' => 'omni_model_has_roles',
        'role_has_permissions' => 'omni_role_has_permissions',
        'audit_log' => 'omni_audit_log',
    ],

    'column_names' => [
        /*
         * Primary key used for model morphs.
         */
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'team_id',
    ],

    /*
     * Core Helpers & Settings
     */
    'super_admin' => [
        'email'   => env('OMNIGUARD_SUPER_ADMIN_EMAIL', null),
        'user_id' => env('OMNIGUARD_SUPER_ADMIN_ID', null),
    ],

    'panic_mode' => env('OMNIGUARD_PANIC_MODE', false),

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'omniguard.cache',
        'store' => 'default',
        'column_names_except' => ['created_at', 'updated_at', 'deleted_at'],
    ],

    'teams' => false,

    'discovery' => [
        'paths' => [
            app_path('Models'),
            app_path('Http/Controllers'),
            app_path('Livewire'),
        ],
        'chunk_size' => 50, // For $1 hosting efficiency
    ],
];
