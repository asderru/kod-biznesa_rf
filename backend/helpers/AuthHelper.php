<?php
    
    namespace backend\helpers;
    
    use JetBrains\PhpStorm\ArrayShape;
    
    class AuthHelper
    {
        #[ArrayShape([
            'actions' => "string[]",
            'allow'   => "bool",
            'roles'   => "string[]",
        ])]
        public static function ruleGuest(): array
        {
            return [
                'actions' => [
                    'login', 'error', 'logout',
                ],
                'allow'   => true,
                'roles'   => [
                    '?',
                ],
            ];
        }
        
        #[ArrayShape([
            'actions' => "string[]",
            'allow'   => "bool",
            'roles'   => "string[]",
        ])]
        public static function ruleWriter(): array
        {
            return [
                'actions' => [
                    'add',
                    'add-photo',
                    'chapter',
                    'choose',
                    'create',
                    'copy',
                    'create-razdel',
                    'error',
                    'feature',
                    'full',
                    'index',
                    'move-photo-down',
                    'move-photo-up',
                    'product',
                    'razdel',
                    'reference',
                    'stock',
                    'tags',
                    'tinymce',
                    'view',
                    'update',
                ],
                'allow'   => true,
                'roles'   => [
                    'writer',
                ],
            ];
        }
        
        #[ArrayShape([
            'actions' => 'string[]',
            'allow'   => 'bool',
            'roles'   => 'string[]',
        ])]
        public static function ruleEditor(): array
        {
            return [
                'actions' => [
                    'activate',
                    'advert',
                    'archive',
                    'check-spaces',
                    'check-url',
                    'clear-content',
                    'clear-sort',
                    'create-one',
                    'content',
                    'copy-content',
                    'draft',
                    'delete',
                    'delete-block',
                    'delete-photo',
                    'delete-panel',
                    'lists',
                    'main',
                    'main-menu',
                    'menu',
                    'nodeMove',
                    'noroots',
                    'panel',
                    'port',
                    'property',
                    'reference',
                    'refresh-url',
                    'related',
                    'resort',
                    'slider',
                    'set-price',
                    'sort',
                    'view-ajax',
                    'update-all',
                    'update-one',
                    'update-url',
                ],
                'allow'   => true,
                'roles'   => [
                    'editor',
                ],
            ];
        }
        
        #[ArrayShape([
            'actions' => 'string[]',
            'allow'   => 'bool',
            'roles'   => 'string[]',
        ])]
        public static function ruleAdmin(): array
        {
            return [
                'actions' => [
                    'activate',
                    'add-photo',
                    'add',
                    'advert',
                    'archive',
                    'cache',
                    'check-spaces',
                    'check-url',
                    'choose',
                    'clear-content',
                    'clear-sort',
                    'contact-create',
                    'contact',
                    'content',
                    'copy-content',
                    'copy',
                    'create-one',
                    'create-razdel',
                    'create',
                    'delete-block',
                    'delete-panel',
                    'delete-photo',
                    'delete',
                    'draft',
                    'error',
                    'feature',
                    'full',
                    'index',
                    'lists',
                    'location',
                    'main-menu',
                    'main',
                    'menu',
                    'move-photo-down',
                    'move-photo-up',
                    'nodeMove',
                    'noroots',
                    'panel',
                    'port',
                    'product',
                    'property',
                    'razdel',
                    'reference',
                    'reference',
                    'refresh-url',
                    'related',
                    'resort',
                    'set-price',
                    'slider',
                    'sort',
                    'stock',
                    'tags',
                    'tinymce',
                    'update-all',
                    'update-one',
                    'update-url',
                    'update',
                    'view-ajax',
                    'view',
                ],
                'allow'   => true,
                'roles'   => [
                    'admin',
                ],
            ];
        }
        
        #[ArrayShape([
            'actions' => 'string[]',
            'allow'   => 'bool',
            'roles'   => 'string[]',
        ])]
        public static function ruleSuperadmin(): array
        {
            return [
                'actions' => [
                    'activate',
                    'add-photo',
                    'add',
                    'advert',
                    'archive',
                    'cache',
                    'check-spaces',
                    'check-url',
                    'choose',
                    'clear-content',
                    'clear-sort',
                    'contact',
                    'contact-create',
                    'content',
                    'copy-content',
                    'copy',
                    'create-one',
                    'create-razdel',
                    'create',
                    'delete-block',
                    'delete-panel',
                    'delete-photo',
                    'delete',
                    'draft',
                    'error',
                    'feature',
                    'full',
                    'index',
                    'lists',
                    'main-menu',
                    'main',
                    'menu',
                    'mistakes',
                    'move-photo-down',
                    'move-photo-up',
                    'nodeMove',
                    'noroots',
                    'password',
                    'panel',
                    'path',
                    'port',
                    'product',
                    'person',
                    'property',
                    'razdel',
                    'reference',
                    'reference',
                    'refresh-url',
                    'related',
                    'register',
                    'resort',
                    'scale',
                    'set-price',
                    'slider',
                    'sort',
                    'stock',
                    'tags',
                    'tinymce',
                    'update-all',
                    'update-one',
                    'update-url',
                    'update',
                    'view-ajax',
                    'view',
                ],
                'allow'   => true,
                'roles'   => [
                    'superadmin',
                ],
            ];
        }
        
        #[ArrayShape([
            'actions' => 'string[]',
            'allow'   => 'bool',
            'roles'   => 'string[]',
        ])]
        public static function ruleSite(): array
        {
            return [
                'actions' => [
                    'add',
                    'content',
                    'data-base',
                    '/express',
                    'index',
                    'logout',
                    '/shop',
                ],
                'allow'   => true,
                'roles'   => [
                    'writer',
                ],
            ];
        }
        
        #[ArrayShape([
            'actions' => 'string[]',
            'allow'   => 'bool',
            'roles'   => 'string[]',
        ])]
        public static function action(): array
        {
            return [
                'actions' => [
                    'product',
                    'razdel',
                ],
                'allow'   => true,
                'roles'   => [
                    'admin',
                ],
            ];
        }
        
        #[ArrayShape([
            'actions' => 'string[]',
            'allow'   => 'bool',
            'roles'   => 'string[]',
        ])]
        public static function cache(): array
        {
            return [
                'actions' => [
                    'index',
                    'info',
                    'razdels',
                    'products',
                    'prices',
                    'brands',
                    'anonses',
                    'panels',
                    'banners',
                    'selfads',
                    'blog',
                    'posts',
                    'pages',
                    'news',
                    'materials',
                    'property-all',
                    'update-content',
                ],
                'allow'   => true,
                'roles'   => [
                    'writer',
                ],
            ];
        }
        
        #[ArrayShape([
            'actions' => 'string[]',
            'allow'   => 'bool',
            'roles'   => 'string[]',
        ])]
        public static function discount(): array
        {
            return [
                'actions' => [
                    'apply',
                    'disapply',
                ],
                'allow'   => true,
                'roles'   => [
                    'admin',
                ],
            ];
        }
        
    }
