<?php
    
    namespace backend\helpers;
    
    use core\helpers\ButtonHelper;
    use core\tools\Constant;
    use JetBrains\PhpStorm\ArrayShape;
    use yii\bootstrap5\Html;
    use yii\helpers\BaseHtml;
    
    class StatusHelper extends BaseHtml
    {
        public static function getStatusName(?int $status = null): ?string
        {
            if ($status === null) {
                return null;
            }
            return self::statusList()[$status] ?? '';
        }
        
        #[ArrayShape([
            Constant::STATUS_ROOT          => "string",
            Constant::STATUS_DRAFT         => "string",
            Constant::STATUS_ARCHIVE       => "string",
            Constant::STATUS_ACTIVE        => "string",
            Constant::STATUS_MENU          => "string",
            Constant::STATUS_MAIN          => "string",
            Constant::STATUS_SLIDER        => "string",
            Constant::STATUS_APPLY         => "string",
            Constant::STATUS_USER_ACTIVE   => "string",
            Constant::STATUS_NEW           => "string",
            Constant::STATUS_NEW_MAIN      => "string",
            Constant::STATUS_SALE          => "string",
            Constant::STATUS_SALE_MAIN     => "string",
            Constant::STATUS_DISCOUNT      => "string",
            Constant::STATUS_DISCOUNT_MAIN => "string",
            Constant::STATUS_MAIN_MENU     => "string",
        ])]
        public static function statusList(): array
        {
            return [
                Constant::STATUS_ROOT          => 'Корневая модель',
                Constant::STATUS_DRAFT         => 'Черновик',
                Constant::STATUS_ARCHIVE       => 'Архив',
                Constant::STATUS_ACTIVE        => 'Активирован',
                Constant::STATUS_MENU          => 'Пункт меню',
                Constant::STATUS_MAIN          => 'Главная',
                Constant::STATUS_MAIN_MENU     => 'Пункт меню + на главной',
                Constant::STATUS_SLIDER        => 'Слайд',
                Constant::STATUS_APPLY         => 'Применен',
                Constant::STATUS_USER_ACTIVE   => 'Активный пользователь',
                Constant::STATUS_NEW           => 'Новинка',
                Constant::STATUS_NEW_MAIN      => 'Новинка + на главной',
                Constant::STATUS_SALE          => 'Распродажа',
                Constant::STATUS_SALE_MAIN     => 'Распродажа + на главной',
                Constant::STATUS_DISCOUNT      => 'Скидка',
                Constant::STATUS_DISCOUNT_MAIN => 'Скидка + на главной',
            ];
        }
        
        public static function linkType(?int $linkType = null): ?string
        {
            if ($linkType === null) {
                return null;
            }
            return self::linkTypesList()[$linkType] ?? null;
            
        }
        
        #[ArrayShape([
            Constant::LINK_IN_CODE          => 'string',
            Constant::LINK_IN_CONTENT       => 'string',
            Constant::MEDIA_LINK_IN_CODE    => 'string',
            Constant::MEDIA_LINK_IN_CONTENT => 'string',
            Constant::STYLE_LINK_IN_CODE    => 'string',
            Constant::SCRIPT_LINK_IN_CODE   => 'string',
        ])]
        public static function linkTypesList(): array
        {
            return [
                Constant::LINK_IN_CODE          => 'ссылка в коде страницы',
                Constant::LINK_IN_CONTENT       => 'ссылка в тексте',
                Constant::MEDIA_LINK_IN_CODE    => 'ссылка в коде на медиа и пр.',
                Constant::MEDIA_LINK_IN_CONTENT => '<картинка в тексте и пр.',
                Constant::STYLE_LINK_IN_CODE    => 'ссылка в коде страницы на стили',
                Constant::SCRIPT_LINK_IN_CODE   => 'ссылка в коде страницы на скрипты',
            ];
        }
        
        public static function linkStatus(?int $linkStatus = null): ?string
        {
            if ($linkStatus === null) {
                return null;
            }
            return self::linkStatusesList()[$linkStatus] ?? null;
        }
        
        #[ArrayShape([
            Constant::INTERNAL_LINK_CONTENT => 'string',
            Constant::INTERNAL_LINK_IMAGE   => 'string',
            Constant::INTERNAL_LINK_BUTTON  => 'string',
            Constant::OUTBOUND_LINK_CONTENT => 'string',
            Constant::OUTBOUND_LINK_IMAGE   => 'string',
            Constant::OUTBOUND_BUTTON       => 'string',
        ])]
        public static function linkStatusesList(): array
        {
            return [
                Constant::INTERNAL_LINK_CONTENT => 'внутренняя ссылка в тексте',
                Constant::INTERNAL_LINK_IMAGE   => 'внутренняя ссылка в картинке текста',
                Constant::INTERNAL_LINK_BUTTON  => 'внутренняя ссылка в кнопке текста',
                Constant::OUTBOUND_LINK_CONTENT => 'внешняя ссылка в тексте',
                Constant::OUTBOUND_LINK_IMAGE   => 'внешняя ссылка в картинке текста',
                Constant::OUTBOUND_BUTTON       => 'внешняя ссылка в кнопке текста',
            ];
        }
        
        #[ArrayShape([
            Constant::STATUS_DRAFT   => "string",
            Constant::STATUS_ARCHIVE => "string",
            Constant::STATUS_ACTIVE  => "string",
        ])]
        public static function simpleList(): array
        {
            return [
                Constant::STATUS_DRAFT   => 'Черновик',
                Constant::STATUS_ARCHIVE => 'Архив',
                Constant::STATUS_ACTIVE  => 'Активен',
            ];
        }
        
        #[ArrayShape([
            Constant::STATUS_ACTIVE    => "string",
            Constant::STATUS_MENU      => "string",
            Constant::STATUS_MAIN      => "string",
            Constant::STATUS_MAIN_MENU => "string",
        ])]
        public static function activeList(): array
        {
            return [
                Constant::STATUS_ACTIVE    => 'Только активные',
                Constant::STATUS_MENU      => 'Пункты меню',
                Constant::STATUS_MAIN      => 'Главная стр',
                Constant::STATUS_MAIN_MENU => 'Пункты меню и Главная стр.',
            ];
        }
        
        #[ArrayShape([
            Constant::STATUS_DRAFT     => "string",
            Constant::STATUS_ARCHIVE   => "string",
            Constant::STATUS_ACTIVE    => "string",
            Constant::STATUS_MENU      => "string",
            Constant::STATUS_MAIN      => "string",
            Constant::STATUS_MAIN_MENU => "string",
            Constant::STATUS_SLIDER    => 'string',
            Constant::STATUS_APPLY     => 'string',
        ])]
        public static function fullStatusList(): array
        {
            return [
                Constant::STATUS_DRAFT     => 'Статус: Черновик',
                Constant::STATUS_ARCHIVE   => 'Статус: Архив',
                Constant::STATUS_ACTIVE    => 'Статус: Активен',
                Constant::STATUS_MENU      => 'Статус: Пункт меню',
                Constant::STATUS_MAIN      => 'Статус: Главная',
                Constant::STATUS_MAIN_MENU => 'Статус: Пункт меню + Главная',
                Constant::STATUS_SLIDER    => 'Статус: Слайд',
                Constant::STATUS_APPLY     => 'Статус: Скидка всей номенклатуры',
            ];
        }
        
        public static function requiredLabel(?int $required): string|null
        {
            return ($required === 1)
                ?
                Html::tag(
                    'span',
                    'Обязательно', [
                    'class' => 'btn btn-sm  btn-primary',
                ],
                )
                : Html::tag(
                    'span',
                    'Опция', [
                    'class' => 'btn btn-sm  btn-secondary',
                ],
                );
        }
        
        public static function anonsesStatusLabel(?int $status): string|null
        {
            if ($status) {
                $class = match ($status) {
                    Constant::STATUS_ARCHIVE                              => 'btn btn-sm  btn-dark',
                    Constant::STATUS_ACTIVE, Constant::STATUS_USER_ACTIVE => 'btn btn-sm  btn-success',
                    Constant::STATUS_MENU                                 => 'btn btn-sm  btn-info text-dark',
                    Constant::STATUS_MAIN                                 => 'btn btn-sm  btn-warning text-dark',
                    Constant::STATUS_SLIDER                               => 'btn btn-sm  btn-outline-primary',
                    Constant::STATUS_APPLY,
                    Constant::STATUS_MAIN_MENU                            => 'btn btn-sm  btn-danger',
                    default                                               => 'btn btn-sm  btn-secondary',
                };
            }
            
            return ($status) ? Html::tag(
                'span',
                self::anonsesList()[$status] ?? '',
                [
                'class' => $class,
            ],
            ) : null;
        }
        
        #[ArrayShape([
            Constant::STATUS_DRAFT   => "string",
            Constant::STATUS_ARCHIVE => "string",
            Constant::STATUS_ACTIVE  => "string",
            Constant::STATUS_SLIDER  => 'string',
        ])]
        public static function anonsesList(): array
        {
            return [
                Constant::STATUS_DRAFT   => 'Черновик',
                Constant::STATUS_ARCHIVE => 'Архив',
                Constant::STATUS_ACTIVE  => 'Активен',
                Constant::STATUS_SLIDER  => 'Слайд',
            ];
        }
        
        public static function statusBadgeLabel(?int $status): string|null
        {
            if ($status) {
                $class = match ($status) {
                    Constant::STATUS_ROOT => 'badge bg-danger-outline',
                    Constant::STATUS_ARCHIVE                           => 'badge bg-dark',
                    Constant::STATUS_ACTIVE                            => 'badge bg-success',
                    Constant::STATUS_MENU                              => 'badge bg-info text-dark',
                    Constant::STATUS_MAIN                              => 'badge bg-warning text-dark',
                    Constant::STATUS_SLIDER                            => 'badge bg-primary',
                    Constant::STATUS_APPLY, Constant::STATUS_MAIN_MENU => 'badge bg-danger',
                    default                                            => 'badge bg-secondary',
                };
            }
            
            return ($status) ? Html::tag(
                'span',
                self::statusList()[$status] ?? '',
                ['class' => $class],
            ) : null;
        }
        
        public static function digitStatusLabel(int $status): string|null
        {
            $class = match ($status) {
                Constant::STATUS_ARCHIVE                           => 'btn btn-sm btn-outline-dark text-dark',
                Constant::STATUS_ACTIVE                            => 'btn btn-sm  btn-outline-success text-dark',
                Constant::STATUS_MENU                              => 'btn btn-sm  btn-outline-info text text-dark-dark',
                Constant::STATUS_MAIN                              => 'btn btn-sm  btn-outline-warning text-dark',
                Constant::STATUS_SLIDER                            => 'btn btn-sm  btn-outline-primary text-dark',
                Constant::STATUS_APPLY, Constant::STATUS_MAIN_MENU => 'btn btn-sm  btn-outline-danger text-dark',
                default                                            => 'btn btn-sm  btn-outline-secondary text-dark',
            };
            
            return ($status) ? Html::tag(
                'span',
                self::digitStatusList()[$status] ?? '',
                ['class' => $class],
            ) : null;
        }
        
        #[ArrayShape([
            Constant::STATUS_ROOT      => "string",
            Constant::STATUS_DRAFT     => "string",
            Constant::STATUS_ARCHIVE   => "string",
            Constant::STATUS_ACTIVE    => "string",
            Constant::STATUS_MENU      => "string",
            Constant::STATUS_MAIN      => "string",
            Constant::STATUS_MAIN_MENU => "string",
            Constant::STATUS_SLIDER    => "string",
            Constant::STATUS_APPLY     => "string",
        ])]
        public static function digitStatusList(): array
        {
            return [
                Constant::STATUS_ROOT      => 'Статус не определен',
                Constant::STATUS_DRAFT     => 'Статус 1',
                Constant::STATUS_ARCHIVE   => 'Статус 2',
                Constant::STATUS_ACTIVE    => 'Статус 3',
                Constant::STATUS_MENU      => 'Статус 4',
                Constant::STATUS_MAIN      => 'Статус 5',
                Constant::STATUS_MAIN_MENU => 'Статус 6',
                Constant::STATUS_SLIDER    => 'Статус 7',
                Constant::STATUS_APPLY     => 'Статус 8',
            ];
        }
        
        public static function name(?int $status): string|null
        {
            return ($status) ? Html::tag(
                'span',
                self::statusList()[$status] ?? '',
            ) : null;
        }
        
        public static function marketStatusLabel(int $status): string
        {
            $class = match ($status) {
                Constant::STATUS_NEW           => 'btn btn-sm btn-outline-info text-dark',
                Constant::STATUS_NEW_MAIN      => 'btn btn-sm  btn-outline-primary text-dark',
                Constant::STATUS_SALE          => 'btn btn-sm  btn-outline-warning text text-dark-dark',
                Constant::STATUS_SALE_MAIN     => 'btn btn-sm  btn-outline-danger text-dark',
                Constant::STATUS_DISCOUNT_MAIN => 'btn btn-sm  btn-outline-dark text-dark',
                default                        => 'btn btn-sm  btn-outline-secondary text-dark',
            };
            
            return Html::tag(
                'span',
                self::marketStatusList()[$status] ?? '',
                [
                'class' => $class,
            ],
            );
        }
        
        #[ArrayShape([
            Constant::STATUS_DRAFT         => 'string',
            Constant::STATUS_ARCHIVE       => 'string',
            Constant::STATUS_ACTIVE        => 'string',
            Constant::STATUS_MAIN          => 'string',
            Constant::STATUS_NEW           => 'string',
            Constant::STATUS_NEW_MAIN      => 'string',
            Constant::STATUS_SALE          => 'string',
            Constant::STATUS_SALE_MAIN     => 'string',
            Constant::STATUS_DISCOUNT      => 'string',
            Constant::STATUS_DISCOUNT_MAIN => 'string',
        ])]
        public static function marketStatusList(): array
        {
            return [
                Constant::STATUS_DRAFT         => 'Черновик',
                Constant::STATUS_ARCHIVE       => 'Архив',
                Constant::STATUS_ACTIVE        => 'Активирован',
                Constant::STATUS_MAIN          => 'Главная',
                Constant::STATUS_NEW           => 'Новинка',
                Constant::STATUS_NEW_MAIN      => 'Новинка на главной',
                Constant::STATUS_SALE          => 'Распродажа',
                Constant::STATUS_SALE_MAIN     => 'Распродажа на главной',
                Constant::STATUS_DISCOUNT      => 'Скидка',
                Constant::STATUS_DISCOUNT_MAIN => 'Скидка на главной',
            ];
        }
        
        public static function ordersStatusLabel(?int $status): string|null
        {
            if ($status) {
                $class = match ($status) {
                    Constant::ORDER_NEW                   => 'btn btn-sm  btn-danger',
                    Constant::ORDER_PAID                  => 'btn btn-sm  btn-warning',
                    Constant::ORDER_SENT                  => 'btn btn-sm  btn-info text-dark',
                    Constant::ORDER_COMPLETED             => 'btn btn-sm  btn-primary text-dark',
                    Constant::ORDER_CANCELLED             => 'btn btn-sm  btn-secondary',
                    Constant::ORDER_CANCELLED_BY_CUSTOMER => 'btn btn-sm  btn-dark',
                    default                               => 'btn btn-sm  btn-outline-secondary',
                };
            }
            
            return ($status) ? Html::tag(
                'span',
                self::ordersList()[$status] ?? '',
                [
                'class' => $class,
            ],
            ) : null;
        }
        
        #[ArrayShape([
            Constant::ORDER_NEW                   => 'string',
            Constant::ORDER_PAID                  => 'string',
            Constant::ORDER_SENT                  => 'string',
            Constant::ORDER_COMPLETED             => 'string',
            Constant::ORDER_CANCELLED             => 'string',
            Constant::ORDER_CANCELLED_BY_CUSTOMER => 'string',
        ])]
        public static function ordersList(): array
        {
            return [
                Constant::ORDER_NEW                   => 'Новый заказ',
                Constant::ORDER_PAID                  => 'Оплачен',
                Constant::ORDER_SENT                  => 'Отправлен',
                Constant::ORDER_COMPLETED             => 'Выполнен',
                Constant::ORDER_CANCELLED             => 'Отменен',
                Constant::ORDER_CANCELLED_BY_CUSTOMER => 'Отменен покупателем',
            ];
        }
        
        public static function faviconStatusList(?int $status): string|null
        {
            if ($status) {
                $class = match ($status) {
                    Constant::STATUS_ROOT      => 'btn btn-sm  btn-outline-dark',
                    Constant::STATUS_DRAFT     => 'btn btn-sm  btn-secondary',
                    Constant::STATUS_ARCHIVE   => 'btn btn-sm  btn-dark',
                    Constant::STATUS_ACTIVE    => 'btn btn-sm  btn-success',
                    Constant::STATUS_MENU      => 'btn btn-sm  btn-warning',
                    Constant::STATUS_MAIN      => 'btn btn-sm  btn-primary',
                    Constant::STATUS_MAIN_MENU => 'btn btn-sm  btn-danger',
                    default                    => 'btn btn-sm  btn-outline-primary',
                };
            }
            
            return ($status) ? Html::tag(
                'span',
                self::faviconsList()[$status] ?? '',
                [
                'class' => $class,
            ],
            ) : null;
        }
        
        #[ArrayShape([
            Constant::STATUS_DRAFT     => 'string',
            Constant::STATUS_ARCHIVE   => 'string',
            Constant::STATUS_ACTIVE    => 'string',
            Constant::STATUS_MENU      => 'string',
            Constant::STATUS_MAIN      => 'string',
            Constant::STATUS_MAIN_MENU => 'string',
        ])]
        public static function faviconsList(): array
        {
            return [
                Constant::STATUS_DRAFT     => '<i class="bi bi-pencil-square"></i>',
                Constant::STATUS_ARCHIVE   => '<i class="bi bi-archive"></i>',
                Constant::STATUS_ACTIVE    => '<i class="bi bi-file-text-fill"></i>',
                Constant::STATUS_MAIN      => '<i class="bi bi-pc-display-horizontal"></i>',
                Constant::STATUS_MENU      => '<i class="bi bi-menu-app"></i>',
                Constant::STATUS_MAIN_MENU => '<i class="bi bi-menu-button-wide-fill"></i>',
            ];
        }
        
        public function setStatuses(
            int $id, int $status, string $type,
        ): string
        {
            return ($type === 'main')
                ?
                self::statusLabel
                (
                    $status,
                ) .
                '<hr>'
                .
                self::fullActivation
                (
                    $id, $status,
                )
                :
                self::statusLabel
                (
                    $status,
                ) .
                '<hr>'
                .
                self::activation
                (
                    $id, $status,
                );
            
        }
        
        public static function statusLabel(int $status): string|null
        {
            $class = match ($status) {
                Constant::STATUS_ROOT                                 => 'btn btn-sm  btn-danger',
                Constant::STATUS_ARCHIVE                              => 'btn btn-sm  btn-light border border-dark',
                Constant::STATUS_ACTIVE, Constant::STATUS_USER_ACTIVE => 'btn btn-sm  btn-light border border-success',
                Constant::STATUS_MENU                                 => 'btn btn-sm  btn-light border border-info text-dark',
                Constant::STATUS_MAIN                                 => 'btn btn-sm  btn-light border border-warning text-dark',
                Constant::STATUS_SLIDER                               => 'btn btn-sm  btn-light border border-primary',
                Constant::STATUS_APPLY,
                Constant::STATUS_MAIN_MENU                            => 'btn btn-sm  btn-light border border-danger',
                default                                               => 'btn btn-sm  btn-light border border-secondary',
            };
            
            return Html::tag(
                'span',
                self::statusList()[$status] ?? '',
                [
                'class' => $class,
            ],
            );
        }
        
        public static function icon(int $status): ?string
        {
            // Определяем класс Bootstrap и цвет по статусу
            $class = match ($status) {
                Constant::STATUS_ROOT    => 'badge bg-danger rounded-circle',
                Constant::STATUS_DRAFT   => 'badge bg-dark rounded-circle',
                Constant::STATUS_ARCHIVE => 'badge bg-secondary rounded-circle',
                default                  => 'badge bg-success rounded-circle',
            };
            
            // Создаем HTML-тег для кружка
            return Html::tag(
                'span',
                '', // Пустое содержимое, так как это просто цветной кружок
                ['class' => $class, 'style' => 'width: 10px; height: 10px; display: inline-block;'],
            );
        }
        
        public static function fullActivation(
            int $id, int $status,
        ): string
        {
            if (
                $status < Constant::STATUS_ACTIVE
            ) {
                return ButtonHelper::badgeActivate($id);
            }
            
            if (
                $status === Constant::STATUS_ACTIVE
            ) {
                return
                    ButtonHelper::badgeDraft($id)
                    . ButtonHelper::badgeArchive($id)
                    . ButtonHelper::badgeMain($id)
                    . ButtonHelper::badgeMenu($id)
                    . ButtonHelper::badgeMainMenu($id);
            }
            return
                ButtonHelper::badgeDraft($id)
                . ButtonHelper::badgeArchive($id)
                . ButtonHelper::badgeActivate($id);
        }
        
        public static function activation(int $id, int $status): string
        {
            if (
                $status === Constant::STATUS_DRAFT
                || $status === Constant::STATUS_ARCHIVE
            ) {
                return ButtonHelper::badgeActivate($id);
            }
            
            if (
                $status > Constant::STATUS_ACTIVE
            ) {
                return ButtonHelper::badgeActivate($id, 'Убрать из активности')
                       . ButtonHelper::badgeArchive($id);
            }
            return
                ButtonHelper::badgeDraft($id)
                . ButtonHelper::badgeArchive($id);
        }
        
        public static function activationType(int $textType, int $id, int $status): string
        {
            if (
                $status === Constant::STATUS_DRAFT
                || $status === Constant::STATUS_ARCHIVE
            ) {
                return ButtonHelper::badgeTypeActivate($textType, $id);
            }
            
            if (
                $status > Constant::STATUS_ACTIVE
            ) {
                return ButtonHelper::badgeTypeActivate($textType, $id, 'Убрать из активности')
                       . ButtonHelper::badgeTypeArchive($textType, $id);
            }
            
            return
                ButtonHelper::badgeTypeDraft($textType, $id)
                . ButtonHelper::badgeTypeArchive($textType, $id);
        }
        
    }
