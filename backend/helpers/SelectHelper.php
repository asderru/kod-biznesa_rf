<?php
    
    namespace backend\helpers;
    
    use core\read\readers\Admin\InformationReader;
    use core\read\readers\User\PersonReader;
    use core\edit\entities\User\User;
    use core\edit\entities\Utils\Gallery;
    use core\helpers\ParametrHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use Yii;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    class SelectHelper extends Html
    {
        public static function status(mixed $form, mixed $model): string
        {
            if (
                Yii::$app->user->can(
                    'admin',
                    ['class' => static::class],
                )
            ):
                return $form
                    ->field($model, 'status')
                    ->radioList(
                        [
                            Constant::STATUS_DRAFT  => 'Черновик ',
                            Constant::STATUS_ACTIVE => 'Активный ',
                        ],
                        [
                            'value' => Constant::STATUS_DRAFT,
                        ],
                    )
                    ->label(
                        false,
                    )
                ;
            else:
                return Html::activeHiddenInput(
                    $model, 'status',
                    [
                        'value' =>
                            Constant::STATUS_DRAFT,
                    ],
                );
            endif;
            
        }
        
        /*###### Some ########################################################*/
        
        public static function getAction(): string
        {
            $referrerUrl = trim(Yii::$app->request->referrer, '/');
            $urlParts    = parse_url($referrerUrl);
            $url         = $urlParts['path'];
            return substr(strrchr($url, '/'), 1);
        }
        
        public static function getInformations(
            ActiveForm $form,
            Model      $model,
            bool       $true,
        ): string
        {
            
            if (ParametrHelper::isServer()) {
                return $form->field(
                    $model, 'site_id',
                )
                            ->dropDownList(
                                InformationReader::getDropDownFilter(0),
                                [
                                    'prompt' => ' выбрать сайт (обязательно) ',
                                    'value'  => Parametr::siteId(),
                                ],
                            )
                            ->label($true ? 'Cайт' : false)
                ;
            }
            return Html::activeHiddenInput(
                $model, 'site_id',
                [
                    'value' => Parametr::siteId(),
                ],
            );
        }
        
        /**
         * @throws \Exception
         */
        public static function getPersons(
            ActiveForm  $form,
            Model       $model,
            string|null $label = null,
        ): string|null
        {
            $user = User::findOne([Yii::$app->user->id]);
            
            if (!$user) {
                return null;
            }
            
            $personId = ($model->person_id) ?? $user->person->id;
            
            return $form->field(
                $model, 'person_id',
            )
                        ->dropDownList(
                            PersonReader::getDropDownFilter(0),
                            [
                                'prompt' => ' выбрать профиль (обязательно) ',
                                'value'  => $personId,
                            ],
                        )
                        ->label($label ?? 'Выбор профиля')
            ;
        }
        
        public static function selectGallery(
            ActiveForm $form, Model $model, null|Gallery $parent,
        ): string
        {
            
            return ($parent)
                ? $form->field
                (
                    $model->galleries,
                    'galleryId',
                )
                       ->dropDownList
                       (
                           $model->galleries->galleriesList($model->site_id),
                           ['prompt' => ' -- -- '],
                       )
                       ->label(
                           $parent->hasModels($parent->id)
                               ?
                               'Сменить галерею "<strong>' .
                               $parent?->gallery?->name . '</strong>" на:'
                               :
                               'Выбрать галерею:',
                       )
                : $form->field
                (
                    $model->galleries,
                    'galleryId',
                )
                       ->dropDownList
                       (
                           $model->galleries->galleriesList($model->site_id),
                           ['prompt' => ' -- -- '],
                       )
                       ->label('Выбрать галерею.')
            ;
        }
        
        public static function selectTree(
            Model $model,
        ): string
        {
            return Html::activeHiddenInput(
                $model, 'tree',
                [
                    'value' => Parametr::siteId(),
                ],
            );
        }
    }
