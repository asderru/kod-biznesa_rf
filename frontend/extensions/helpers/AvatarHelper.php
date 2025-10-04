<?php
    
    namespace frontend\extensions\helpers;
    
    use core\edit\entities\User\Person;
    use core\tools\Constant;
    use Random\RandomException;
    use Throwable;
    use yii\bootstrap5\BaseHtml;
    use yii\bootstrap5\Html;
    
    class AvatarHelper extends BaseHtml
    {
        
        /**
         * @throws Throwable
         */
        public static function getAvatar(Person $model, ?int $size = 3): string
        {
            $column = $size === 3 ? 3 : 12;
            $body   = match ($size) {
                6       => ' medium',
                12      => ' large',
                default => ' small',
            };
            
            if ($model->photo !== null) {
                $img = $model->getImageUrl($column);
                return self::wrapAvatar($img, $body);
            }
            
            $avatarNumber = self::getAvatarNumber($model);
            $avatarPath   = "/img/avatar/incognito_col-{$column}_{$avatarNumber}.webp";
            
            return self::wrapAvatar($avatarPath, $body);
        }
        
        private static function getAvatarNumber(Person $model): int
        {
            $baseNumber = ($model->id + $model->user_id) % 100 + 1;
            return $model->gender === Constant::GENDER_FEMALE ? $baseNumber + 100 : $baseNumber;
        }
        
        private static function wrapAvatar(string $imgSrc, string $bodyClass): string
        {
            return Html::tag(
                'div',
                Html::img($imgSrc, ['class' => 'avatar-image', 'alt' => 'Avatar']),
                ['class' => 'avatar-image__body' . $bodyClass],
            );
        }
        
        /**
         * @throws RandomException
         */
        public static function getRandomAvatar(int|null $size = 3): string
        {
            $column = ($size === 3) ? 3 : 12;
            $body   = match ($size) {
                6       => ' medium',
                12      => ' large',
                default => ' small',
            };
            
            $random = random_int(1, 20);
            
            return Html::tag(
                'div', Html::img(
                '/img/avatar/incognito_col-' . $column . '_' . $random . '.webp',
                ['class' => 'avatar-image', 'alt' => 'Avatar'],
            ),
                ['class' => 'avatar-image__body' . $body],
            );
        }
        
    }
