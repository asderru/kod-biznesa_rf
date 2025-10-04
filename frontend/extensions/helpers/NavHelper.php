<?php
    
    namespace frontend\extensions\helpers;
    
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Shop\Razdel;
    use core\tools\Constant;
    use Yii;
    use yii\bootstrap5\BaseHtml;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    class NavHelper extends BaseHtml
    {
        public static function navigate(
            string      $label,
            string      $url,
            null|string $context,
            null|string $class = 'nav-link',
        ): string
        {
            return Html::a(
                '<span itemprop="name">' . $label . '</span>',
                $url,
                [
                    'class'    => [
                        Yii::$app->controller->id === $context
                            ?
                            $class . ' active'
                            : $class,
                    ],
                    'itemprop' => 'url',
                ],
            );
        }
        
        /*######## Navs ###########################################*/
        
        public static function home(string $class): string
        {
            return self::navigate(
                'Главная',
                '/',
                'site',
                $class,
            );
            
        }
        
        public static function page(Page $model, string $class): string
        {
            return self::navigate(
                $model->name,
                Url::to($model['link'], true),
                'page/view',
                $class,
            );
            
        }
        
        public static function indexPage(Page $model, string $class): string
        {
            return self::navigate(
                $model->name,
                Url::home(true) . Constant::PAGE_PREFIX,
                null,
                $class,
            );
            
        }
        
        public static function contacts(string $class): string
        {
            return self::navigate(
                'Контакты',
                '/contact/index',
                'contact/index',
                $class,
            );
            
        }
        
        public static function razdel(Razdel $model, string $class): string
        {
            return self::navigate(
                $model->name,
                Url::to($model['link'], true),
                'razdel/view',
                $class,
            );
        }
        
        public static function category(Category $model, string $class): string
        {
            return self::navigate(
                'блог ' . $model->name,
                Url::to($model['link'], true),
                'category/view',
                $class,
            );
            
        }
        
        public static function book(Book $model, string $class): string
        {
            return self::navigate(
                $model->name,
                Url::to($model['link'], true),
                'book/view',
                $class,
            );
            
        }
        
        public static function bookIndex(string $class): string
        {
            return self::navigate(
                'Библиотека СЕО',
                Url::home(true) . Constant::BOOK_PREFIX,
                Constant::BOOK_PREFIX,
                $class,
            );
            
        }
        
        public static function groupIndex(string $class): string
        {
            return self::navigate(
                'Форум СЕО',
                '/' . Constant::GROUP_PREFIX,
                Constant::GROUP_PREFIX,
                $class,
            );
            
        }
        
        public static function group(Group $model, string $class): string
        {
            return self::navigate(
                $model->name,
                Url::to($model['link'], true),
                'group/view',
                $class,
            );
            
        }
        
        
    }
