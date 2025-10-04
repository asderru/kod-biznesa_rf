<?php
    
    namespace backend\helpers;
    
    use core\edit\entities\Admin\Information;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use Exception;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\helpers\BaseHtml;
    
    class LinkHelper extends BaseHtml
    {
        public static function checkSiteContent(
            Information $model,
            ?string     $label = null,
        ): string
        {
            return Html::a(
                $label ?? $model->name,
                [
                    '/link/master/check-links',
                    'linkType' => Constant::CONTENT_LINK,
                    'siteId'   => $model->id,
                
                ],
                [
                    'class' => 'btn btn-sm btn-outline-primary',
                ],
            );
        }
        
        public static function checkMediaContent(
            Information $model,
            ?string     $label = null,
        ): string
        {
            return Html::a(
                $label ?? $model->name,
                [
                    '/link/master/check-links',
                    'linkType' => Constant::MEDIA_LINK_IN_CONTENT,
                    'siteId'   => $model->id,
                
                ],
                [
                    'class' => 'btn btn-sm btn-outline-primary',
                ],
            );
        }
        
        public static function checkSiteCode(
            Information $model,
            ?string     $label = null,
        ): string
        {
            return Html::a(
                $label ?? $model->name,
                [
                    '/link/master/check-links',
                    'linkType' => Constant::CODE_LINK,
                    'siteId'   => $model->id,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-success',
                ],
            );
        }
        
        public static function checkMediaCode(
            Information $model,
            ?string     $label = null,
        ): string
        {
            return Html::a(
                $label ?? $model->name,
                [
                    '/link/master/check-links',
                    'linkType' => Constant::MEDIA_LINK_IN_CODE,
                    'siteId'   => $model->id,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-secondary',
                ],
            );
        }
        
        public static function checkStyleCode(
            Information $model,
            ?string     $label = null,
        ): string
        {
            return Html::a(
                $label ?? $model->name,
                [
                    '/link/master/check-links',
                    'linkType' => Constant::STYLE_LINK_IN_CODE,
                    'siteId'   => $model->id,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-dark',
                ],
            );
        }
        
        public static function checkScriptCode(
            Information $model,
            ?string     $label = null,
        ): string
        {
            return Html::a(
                $label ?? $model->name,
                [
                    '/link/master/check-links',
                    'linkType' => Constant::SCRIPT_LINK_IN_CODE,
                    'siteId'   => $model->id,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-dark',
                ],
            );
        }
        
        public static function toLinks(): string
        {
            return '<a href="#links" class="btn btn-sm btn-outline-dark "> к ссылкам </a>';
        }
        
        
        /**
         * @throws Exception
         */
        public static function checkLinks(
            int     $textType,
            int     $siteId,
            ?string $label = null,
            ?int    $parentId = null,
        ): string
        {
            return Html::a(
                $label ?? 'Проверить ссылки на странице',
                [
                    '/link/check/view',
                    'siteId'   => $siteId,
                    'textType' => $textType,
                    'parentId' => $parentId,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-primary',
                ],
            );
        }
        
        /**
         * @throws Exception
         */
        public static function checkType(
            int     $textType,
            int     $siteId,
            int     $linkType,
            ?string $label = null,
            ?int    $parentId = null,
        ): string
        {
            return Html::a(
                $label ?? TypeHelper::getName($textType, null, false, true),
                [
                    '/link/check/check-type',
                    'id'       => $siteId,
                    'textType' => $textType,
                    'linkType' => $linkType,
                    'parentId' => $parentId,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-primary',
                ],
            );
        }
        
        /**
         * @throws Exception
         */
        public static function checkInternalType(
            int     $textType,
            int     $siteId,
            ?string $label = null,
        ): string
        {
            return Html::a(
                $label ?? TypeHelper::getName($textType, null, false, true),
                [
                    '/link/gazer/check-type',
                    'siteId'   => $siteId,
                    'textType' => $textType,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-success',
                ],
            );
        }
        
        /**
         * @throws Exception
         */
        public static function checkInternalLinks(
            int     $textType,
            int     $siteId,
            int     $linkType,
            ?string $label = null,
            ?int    $parentId = null,
        ): string
        {
            
            return Html::a(
                $label ?? TypeHelper::getName($textType, null, false, true),
                [
                    '/link/gazer/check-type',
                    'id'       => $siteId,
                    'textType' => $textType,
                    'linkType' => $linkType,
                    'parentId' => $parentId,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-success',
                ],
            );
        }
        
        public static function checkBrokenContentLinks(?Model $model = null): ?string
        {
            if ($model !== null) {
                return Html::a(
                    $model->name,
                    [
                        '/link/check/check-broken',
                        'id'       => $model->id,
                        'linkType' => Constant::CONTENT_LINK,
                    ],
                    [
                        'class' => 'btn btn-sm btn-outline-danger',
                    ],
                );
            }
            return null;
        }
        
        public static function checkBrokenCodeLinks(?Model $model = null): ?string
        {
            if ($model !== null) {
                return Html::a(
                    $model->name,
                    [
                        '/link/check/check-broken',
                        'id'       => $model->id,
                        'linkType' => Constant::CODE_LINK,
                    ],
                    [
                        'class' => 'btn btn-sm btn-outline-danger',
                    ],
                );
            }
            return null;
        }
        
        public static function checkCodeLinks(): string
        {
            return Html::a(
                'Ссылки в коде',
                [
                    '/link/check/check-links',
                    'linkType' => Constant::LINK_IN_CODE,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-info',
                ],
            );
        }
        
        public static function checkContentLinks(): string
        {
            return Html::a(
                'Ссылки в контенте',
                [
                    '/link/check/check-links',
                    'linkType' => Constant::LINK_IN_CONTENT,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-info',
                ],
            );
        }
        
        public static function checkContentMediaLinks(): string
        {
            return Html::a(
                'Картинки в текстах',
                [
                    '/link/check/check-links',
                    'linkType' => Constant::MEDIA_LINK_IN_CONTENT,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-info',
                ],
            );
        }
        
        public static function checkMediaLinks(): string
        {
            return Html::a(
                'Картинки',
                [
                    '/link/check/check-links',
                    'linkType' => Constant::MEDIA_LINK_IN_CODE,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-info',
                ],
            );
        }
        
        public static function checkStyleLinks(): string
        {
            return Html::a(
                'Стили',
                [
                    '/link/check/check-links',
                    'linkType' => Constant::STYLE_LINK_IN_CODE,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-info',
                ],
            );
        }
        
        public static function checkScriptLinks(): string
        {
            return Html::a(
                'Скрипты',
                [
                    '/link/check/check-links',
                    'linkType' => Constant::SCRIPT_LINK_IN_CODE,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-info',
                ],
            );
        }
        
        public static function checkUrlLink(Model $model, ?int $linkType = null): string
        {
            $label = match ($linkType) {
                Constant::LINK_IN_CODE          => 'Проверить ссылки на странице',
                Constant::LINK_IN_CONTENT       => 'Проверить ссылки в контенте',
                Constant::MEDIA_LINK_IN_CODE    => 'Проверить картинки на странице',
                Constant::MEDIA_LINK_IN_CONTENT => 'Проверить картинки в контенте',
                Constant::STYLE_LINK_IN_CODE    => 'Проверить стили на странице',
                Constant::SCRIPT_LINK_IN_CODE   => 'Проверить скрипты на странице',
                default                         => 'Проверить все ссылки',
            };
            
            return Html::a(
                $label,
                [
                    '/link/master/view',
                    'textType' => $model::TEXT_TYPE,
                    'parentId' => $model->id,
                    'linkType' => $linkType,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-info',
                ],
            );
        }
        
        public static function checkModelLink(Model $model): string
        {
            return Html::a(
                'Проверить ссылки в контенте',
                [
                    '/link/check/check-status',
                    'textType' => $model::TEXT_TYPE,
                    'parentId' => $model->id,
                    'linkType' => true,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-primary',
                ],
            );
        }
        
        public static function checkStatus(Model $model): string
        {
            return Html::a(
                'Проверить битые ссылки на странице',
                [
                    '/link/check/check-status',
                    'textType' => $model::TEXT_TYPE,
                    'parentId' => $model->id,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-primary',
                ],
            );
        }
        
        public static function checkManual(int $id): string
        {
            return Html::a(
                '<i class="fa-regular fa-circle-check"></i>',
                [
                    '/link/check/check-manual',
                    'id' => $id,
                ],
                [
                    'class' => 'text-success',
                ],
            );
        }
        
        public static function deleteStatus(int $status): string
        {
            return Html::a(
                'Cтатус ' . $status,
                [
                    '/link/check/delete-status',
                    'status' => $status,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-danger',
                ],
            );
        }
        
        public static function deleteAllLinks(): string
        {
            return Html::a(
                'Очистить БД',
                [
                    '/link/check/delete-alllinks',
                ],
                [
                    'class' => 'btn btn-sm btn-outline-danger',
                ],
            );
        }
        
        
    }
