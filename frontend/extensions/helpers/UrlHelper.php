<?php
    
    namespace frontend\extensions\helpers;
    
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use Exception;
    use Yii;
    use yii\base\Model;
    use yii\bootstrap5\BaseHtml;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    class UrlHelper extends BaseHtml
    {
        /**
         * @throws Exception
         */
        public static function getUrlIndex(
            null|Model  $model,
            null|string $class = null,
        ): string
        {
            if ($model) {
                $name = TypeHelper::typesLabel($model->parent_id);
                $url  = self::getUrl($model->textType);
                
                return Html::a(
                    $name,
                    ($url) ? $url . 'index' : Yii::getAlias('@backHost'),
                    [
                        'class' => $class,
                    ],
                );
            }
            
            return Html::a(
                'На главную',
                Yii::getAlias('@backHost'),
                [
                    'class' => $class,
                ],
            );
            
        }
        
        public static function getUrl(int $textType): null|string
        {
            return match ($textType) {
                Constant::FAQ_TYPE      => 'faq/',
                Constant::FOOTNOTE_TYPE => 'footnote/',
                Constant::OFFER_TYPE         => 'offer/',
                Constant::BRAND_TYPE         => 'brand/',
                Constant::SECTION_TYPE       => 'section/',
                Constant::ARTICLE_TYPE       => 'article/',
                Constant::BOOK_TYPE          => 'book/',
                Constant::CHAPTER_TYPE       => 'chapter/',
                Constant::CATEGORY_TYPE      => 'category/',
                Constant::POST_TYPE          => 'post/',
                Constant::PAGE_TYPE          => 'page/',
                Constant::TAG_TYPE           => 'tag/',
                Constant::NEWS_TYPE          => 'news/',
                Constant::MATERIAL_TYPE      => 'material/',
                Constant::AUTHOR_TYPE        => 'author/',
                Constant::GROUP_TYPE         => 'group/',
                Constant::THREAD_TYPE   => 'thread/',
                Constant::PERSON_TYPE   => 'profile/',
                Constant::GALLERY_TYPE       => 'gallery/',
                Constant::PHOTO_GALLERY_TYPE => 'photo/',
                default                      => null,
            };
        }
        
        public static function getUrlView(
            null|Model  $model,
            null|string $class = null,
        ): string
        {
            if ($model) {
                $name = Html::encode($model->name);
                $id   = $model->id;
                $url  = self::getUrl($model->textType);
                
                $finalUrl = Url::toRoute(
                    [
                        $url . 'view',
                        'id' => $id,
                    ],
                );
                
                return Html::a(
                    $name,
                    ($finalUrl) ?? Url::home(true),
                    [
                        'class' => $class,
                    ],
                );
            }
            
            return Html::a(
                'На главную',
                Yii::getAlias('@backHost'),
                [
                    'class' => $class,
                ],
            );
            
        }
        
        public static function getUrlUpdate(
            null|Model  $model,
            null|string $class = null,
        ): string
        {
            if ($model) {
                $name = Html::encode($model->name);
                $id   = $model->id;
                $url  = self::getUrl($model->textType);
                
                return Html::a(
                    $name,
                    ($url) ? $url . 'update?id=' . $id : Yii::getAlias('@backHost'),
                    [
                        'class' => $class,
                    ],
                );
            }
            return Html::a(
                'На главную',
                Yii::getAlias('@backHost'),
                [
                    'class' => $class,
                ],
            );
            
        }
        
        public static function checkUrl(Model $model): string
        {
            $sock = @fsockopen('www.google.com', 80);
            if (!$sock) {
                return '<span class="badge text-bg-warning">Интернет отсутствует</span>';
            }
            fclose($sock);
            
            $url = $model->url;
            if ($model->status < Constant::STATUS_ACTIVE) {
                return '<span class="badge text-bg-secondary">Mодель не активирована!</span>';
            }
            
            $hds         = @get_headers($url);
            $firstAnswer = !(!$hds || (str_contains($hds[0], ' 404 ')));
            
            return (!$firstAnswer) ? '<span class="badge text-bg-danger">Битая ссылка</span>'
                : '<span class="badge text-bg-success">!</span>';
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
