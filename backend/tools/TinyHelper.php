<?php
    
    namespace backend\tools;
    
    use Yii;
    
    class TinyHelper
    {
        public static function getDescription(): string
        {
            return Yii::$app->getView()->renderFile(
                '@app/tools/tinymce/_description.php',
            );
        }
        
        public static function getText(?int $height = null): string
        {
            return Yii::$app->getView()->renderFile(
                '@app/tools/tinymce/_text.php',
                [
                    'height' => $height ?? 800,
                ],
            );
        }
        
        public static function getUnstickyText(?int $height = null): string
        {
            return Yii::$app->getView()->renderFile(
                '@app/tools/tinymce/_unstickyText.php',
                [
                    'height' => $height ?? 800,
                ],
            );
        }
        
        public static function getExpress(?int $height = null): string
        {
            
            return Yii::$app->getView()->renderFile(
                '@app/tools/tinymce/_expressText.php',
                [
                    'height' => $height,
                ],
            );
        }
        
        public static function getAdvert(?int $height = null): string
        {
            
            return Yii::$app->getView()->renderFile(
                '@app/tools/tinymce/_advert.php',
                [
                    'height' => $height,
                ],
            );
        }
        
    }
