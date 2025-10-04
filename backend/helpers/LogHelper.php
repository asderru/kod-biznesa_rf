<?php
    
    namespace backend\helpers;
    
    
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\helpers\BaseHtml;
    
    class LogHelper extends BaseHtml
    {
        public static function logView(
            int $logType,
            int $countLogs,
        ): string
        {
            $class = self::getClass($logType);
            
            return Html::a(
                '<i class="bi bi-check-square"></i>',
                [
                    'admin/log/view',
                    'logType'   => $logType,
                    'countLogs' => $countLogs,
                ],
                [
                    'class' => $class,
                ],
            );
        }
        
        public static function getLabel(
            int $logType,
        ): string
        {
            return match ($logType) {
                Constant::LOG_NGINX_WEB_ACCESS     => 'Сайт Nginx',
                Constant::LOG_NGINX_WEB_ERROR      => 'Ошибка сайта Nginx',
                Constant::LOG_NGINX_PANEL_ACCESS   => 'Панель Nginx',
                Constant::LOG_NGINX_PANEL_ERROR    => 'Ошибка панели Nginx',
                Constant::LOG_NGINX_STATIC_ACCESS  => 'Статика Nginx',
                Constant::LOG_NGINX_STATIC_ERROR   => 'Ошибка статики Nginx',
                Constant::LOG_APACHE_WEB_ACCESS    => 'Сайт Apache',
                Constant::LOG_APACHE_WEB_ERROR     => 'Ошибка сайта Apache',
                Constant::LOG_APACHE_PANEL_ACCESS  => 'Панель Apache',
                Constant::LOG_APACHE_PANEL_ERROR   => 'Ошибка панели Apache',
                Constant::LOG_APACHE_STATIC_ACCESS => 'Статика Apache',
                Constant::LOG_APACHE_STATIC_ERROR  => 'Ошибка статики Apache',
            };
        }
        
        private static function getClass(
            int $logType,
        ): string
        {
            
            return match ($logType) {
                Constant::LOG_NGINX_WEB_ACCESS,
                Constant::LOG_NGINX_WEB_ERROR,
                Constant::LOG_NGINX_PANEL_ACCESS,
                Constant::LOG_NGINX_PANEL_ERROR,
                Constant::LOG_NGINX_STATIC_ACCESS,
                Constant::LOG_NGINX_STATIC_ERROR  => 'btn btn-sm btn-success',
                Constant::LOG_APACHE_WEB_ACCESS,
                Constant::LOG_APACHE_WEB_ERROR,
                Constant::LOG_APACHE_PANEL_ACCESS,
                Constant::LOG_APACHE_PANEL_ERROR,
                Constant::LOG_APACHE_STATIC_ACCESS,
                Constant::LOG_APACHE_STATIC_ERROR => 'btn btn-sm btn-danger',
            };
        }
        
    }
