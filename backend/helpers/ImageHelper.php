<?php
    
    namespace backend\helpers;
    
    use core\tools\Constant;
    use Yii;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    class ImageHelper extends Html
    {
        
        public static function getClearPhoto(
            View  $view,
            Model $model,
        ): string
        {
            return $view->render(
                '@app/views/layouts/images/clearPhoto',
                [
                    'model' => $model,
                ],
            );
            
        }
        
        public static function checkUrl(string $url): bool
        {
            $handle = @fopen($url, 'rb');
            
            if (!$handle) {
                return false;
            }
            return true;
            
        }
        
        public static function createWatermark(?int $siteId = null): void
        {
            if (Yii::$app->params['siteMode'] !== 0) {
                return;
            }
            
            // Путь к оригинальному файлу (например, '5.png')
            $originalFilePath = Yii::getAlias('@frontRoot/watermarks/' . $siteId . '.png');
            
            // Путь для копии (например, 'watermark.png')
            $copyFilePath = Yii::getAlias('@frontRoot/img/watermark.png');
            
            // Проверка, что файлы originalFilePath и copyFilePath не одинаковы
            if ($originalFilePath === $copyFilePath) {
                // Логика для обработки случая, когда пути одинаковы
                // Может быть бросано исключение или выводится сообщение об ошибке
                return;
            }
            
            // Проверка, что файлы существуют и их содержимое совпадает
            if (file_exists($originalFilePath) && file_exists($copyFilePath)) {
                $hashOriginal = md5_file($originalFilePath);
                $hashCopy     = md5_file($copyFilePath);
                
                if ($hashOriginal === $hashCopy) {
                    // Файлы совпадают, дополнительное копирование не требуется
                    return;
                }
            }
            
            // Копирование файла
            if (file_exists($originalFilePath)) {
                copy($originalFilePath, $copyFilePath);
            }
        }
        
        
    }
