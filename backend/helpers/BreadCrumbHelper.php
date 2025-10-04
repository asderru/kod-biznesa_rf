<?php
    
    namespace backend\helpers;
    
    use core\helpers\ParametrHelper;
    use core\helpers\types\TypeHelper;
    use JetBrains\PhpStorm\ArrayShape;
    
    class BreadCrumbHelper
    {
        #[ArrayShape([
            'label' => 'string',
            'url'   => 'string[]',
            'class' => 'string',
        ])]
        public static function index(string $mainTitle): array
        {
            return [
                'label' => $mainTitle,
                'url'   => [
                    'index',
                ],
            ];
        }
        
        #[ArrayShape([
            'label' => 'string',
            'url'   => 'string[]',
            'class' => 'string',
        ])]
        public static function typeIndex(int $textType, string|null $label = null, int|null $id = null): array
        {
            $urlPath   = TypeHelper::getLongEditUrl($textType);
            $indexName = TypeHelper::getName($textType, null, true, true);
            
            return [
                'label' => $label ?? $indexName,
                'url'   => [
                    $urlPath . 'index',
                    'id' => $id ?? null,
                ],
            ];
        }
        
        #[ArrayShape([
            'label' => 'string',
            'url'   => 'string[]',
            'class' => 'string',
        ])]
        public static function typeView(int $textType, int $id, ?string $label = null): array
        {
            $viewName = TypeHelper::getName($textType, null, true, true);
            $urlPath = TypeHelper::getLongEditUrl($textType);
            
            return [
                'label' => $label ?? $viewName,
                'url'   => [
                    $urlPath . 'view',
                    'id' => $id,
                ],
            ];
        }
        
        #[ArrayShape([
            'label' => 'string',
            'url'   => 'string[]',
            'class' => 'string',
        ])]
        public static function structure(?int $textType = null): ?array
        {
            if (!ParametrHelper::isSuperadmin()) {
                return null;
            }
            return [
                'label' => 'Структура ' . TypeHelper::getName($textType, 1, true),
                'url'   => [
                    '/admin/structure/view',
                    'textType' => $textType,
                ],
            ];
        }
    }
