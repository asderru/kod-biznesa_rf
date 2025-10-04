<?php
    
    namespace backend\helpers;
    
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use Yii;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\helpers\BaseHtml;
    
    class UrlHelper extends BaseHtml
    {
        public static function checkUrl(int $status, ?string $modelUrl): string
        {
            if (!$modelUrl) {
                return '<span class="badge text-bg-danger">Ссылка отсутствует</span>';
            }
            $sock = @fsockopen('www.google.com', 80);
            if (!$sock) {
                return '<span class="badge text-bg-warning">Интернет отсутствует</span>';
            }
            fclose($sock);
            
            if ($status === Constant::STATUS_ROOT) {
                return '<span class="badge text-bg-danger">Корневая модель</span>';
            }
            
            if ($status === Constant::STATUS_DRAFT) {
                return '<span class="badge text-bg-secondary">Mодель не активирована!</span>';
            }
            if ($status === Constant::STATUS_ARCHIVE) {
                return '<span class="badge text-bg-secondary">Mодель отправлена в архив!</span>';
            }
            
            $hds         = @get_headers($modelUrl);
            $firstAnswer = !(!$hds || (str_contains($hds[0], ' 404 ')));
            
            return (!$firstAnswer) ? '<span class="badge text-bg-danger">Битая ссылка</span>'
                : '<span class="badge text-bg-success">!</span>';
        }
        
        public static function local(Model $model): string
        {
            $url = TypeHelper::getView($model::TEXT_TYPE, $model->id);
            $localUrl = Yii::$app->params['frontendHostInfo'] . $url;
            return Html::a($localUrl, $localUrl, [
                'target' => '_blank',
            ]);
        }
        
        public static function page_title(string $url): array
        {
            $res     = [];
            $options = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_CONNECTTIMEOUT => 10,
            ];
            $ch      = curl_init($url);
            curl_setopt_array($ch, $options);
            $content = curl_exec($ch);
            curl_close($ch);
            $res['content'] = $content;
            return $res;
        }
        
    }
