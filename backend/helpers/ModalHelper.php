<?php
    
    namespace backend\helpers;
    
    use backend\widgets\FeedbackWidget;
    use core\edit\forms\UploadPhotoForm;
    use Exception;
    use JetBrains\PhpStorm\Pure;
    use Throwable;
    use Yii;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    class ModalHelper
    {
        public static function callDescription(): string
        {
            return '<button type="button"
					class="btn btn-sm btn-primary"
					 data-bs-toggle="modal"
					 data-bs-target="#descriptionModal">
                    <i class="bi bi-code-slash"></i>
                </button>';
        }
        
        #[Pure]
        public static function description(mixed $model): string
        {
            return '<div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <p class="text-center">Просмотр HTML-кода описания</p>
                        <button type="button" 
                            class="btn-close" 
                            data-bs-dismiss="modal" 
                            aria-label="Закрыть">                            
                        </button>
                  </div>
                <div class="modal-body">
                    ' . Html::encode($model->description) . '
                </div>
                  <div class="modal-footer">
                    <a href="update?id=' . $model->id . '" class="btn btn-sm btn-primary">Редактировать</a>
                  </div>
            </div>
        </div>
    </div>';
        }
        
        public static function uploadImage(
            int     $id,
            ?string $title = null,
        ): string
        {
            return Html::a(
                $title ?? 'Загрузить картинку',
                [
                    'delete-photo',
                    'id' => $id,
                ]
                ,
                [
                    'type'           => 'button',
                    'data-bs-toggle' => 'modal',
                    'data-bs-target' => '#uploadModal',
                    'class'          => 'btn btn-sm btn-primary',
                ],
            );
        }
        
        public static function changeImage(): string
        {
            return Html::a(
                'Сменить картинку',
                '',
                [
                    'type'           => 'button',
                    'data-bs-toggle' => 'modal',
                    'data-bs-target' => '#uploadModal',
                    'class'          => 'btn btn-sm btn-primary',
                ],
            );
        }
        
        public static function callText(): string
        {
            return '<button type="button"
                    class="btn btn-sm btn-primary"
                     data-bs-toggle="modal" 
                     data-bs-target="#textModal">
                    <i class="bi bi-code-slash"></i>
                </button>';
        }
        
        #[Pure]
        public static function text(mixed $model): string
        {
            return '<div class="modal fade" id="textModal" tabindex="-1" aria-labelledby="textModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <p class="text-center">Просмотр HTML-кода текста</p>
                        <button type="button" 
                            class="btn-close" 
                            data-bs-dismiss="modal" 
                            aria-label="Закрыть">
                        </button>
                  </div>
                <div class="modal-body">
                    ' . Html::encode($model->text) . '
                </div>
                      <div class="modal-footer">
                        <a href="update?id=' . $model->id . '" class="btn btn-sm btn-primary">Редактировать</a>
                      </div>
            </div>
        </div>
    </div>';
        }
        
        public static function setFirst(): string
        {
            return '<button type="button"
                    class="btn btn-sm btn-outline-secondary"
                     data-bs-toggle="modal"
                     data-bs-price="1"
                     data-bs-target="#percentModalSet">
                    Изменить цены 1
                </button>';
        }
        
        public static function setSecond(): string
        {
            return '<button type="button"
                    class="btn btn-sm btn-outline-secondary"
                     data-bs-toggle="modal"
                     data-bs-price="2"
                     data-bs-target="#percentModalSet">
                    Изменить цены 2
                </button>';
        }
        
        public static function setThird(): string
        {
            return '<button type="button"
                    class="btn btn-sm btn-outline-secondary"
                     data-bs-toggle="modal"
                     data-bs-price="3"
                     data-bs-target="#percentModalSet">
                    Изменить цены 3
                </button>';
        }
        
        public static function setSort(mixed $model): string
        {
            return Yii::$app->getView()->renderFile(
                '@app/tools/modals/_sortModal.php',
                [
                    'model' => $model,
                ],
            );
        }
        
        public static function setAction(mixed $model): string
        {
            return Yii::$app->getView()->renderFile(
                '@app/tools/modals/_actionModal.php',
                [
                    'model' => $model,
                ],
            );
        }
        
        public static function setDiscount(mixed $model): string
        {
            return Yii::$app->getView()->renderFile(
                '@app/tools/modals/_discountModal.php',
                [
                    'model' => $model,
                ],
            );
        }
        
        public static function setImage(
            Model           $model,
            UploadPhotoForm $uploadForm,
        ): string
        {
            return Yii::$app->getView()->renderFile(
                '@app/tools/_uploadImage.php',
                [
                    'model'      => $model,
                    'uploadForm' => $uploadForm,
                ],
            );
        }
        
        /**
         * @throws Exception|Throwable
         */
        public static function setFeedback(): string
        {
            return FeedbackWidget::widget();
        }
        
    }
