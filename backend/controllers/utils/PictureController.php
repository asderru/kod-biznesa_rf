<?php
    
    namespace backend\controllers\utils;
    
    use core\edit\entities\Utils\Photo;
    use core\edit\forms\Utils\PictureUploadPhotoForm;
    use core\edit\useCases\Photo\ImageProcessorService;
    use core\helpers\ParentHelper;
    use core\helpers\types\TypeHelper;
    use Exception;
    use InvalidArgumentException;
    use Throwable;
    use Yii;
    use yii\filters\auth\CompositeAuth;
    use yii\filters\Cors;
    use yii\filters\VerbFilter;
    use yii\web\BadRequestHttpException;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    class PictureController extends Controller
    {
        private const int    TEXT_TYPE     = Photo::TEXT_TYPE;
        private const string MODEL_LABEL   = Photo::MODEL_LABEL;
        private const string MODEL_PREFIX  = Photo::MODEL_PREFIX;
        private const string ACTION_INDEX  = 'Utils_PictureController_';
        
        private ImageProcessorService $service;
        
        public function __construct(
            $id,
            $module,
            ImageProcessorService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->service = $service;
        }
        
        public function behaviors(): array
        {
            return [
                'verbs'         => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete'    => ['POST'],
                        'save-crop' => ['POST'],
                    ],
                ],
                'corsFilter'    => [
                    'class' => Cors::className(),
                ],
                'authenticator' => [
                    'class' => CompositeAuth::className(),
                ],
            ];
        }
        
        /**
         * Displays a single model pictures.
         * @param int $textType
         * @param int $parentId
         * @return Response|string
         * @throws Throwable
         * @action Utils_PictureController_actionView
         */
        public function actionView(int $textType, int $parentId): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $model = ParentHelper::getModel($textType, $parentId);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $parentId . ' не найдена!',
                );
                return $this->redirect([
                    Yii::$app->request->referrer
                    ??
                    Yii::getAlias('@backHost'),
                ]);
            }
            
            $pictures = [];
            $sizes    = [];
            
            for ($i = 1; $i <= 9; $i++) {
                $pictures[$i] = $model->getImageUrl($i);
                $sizes[$i]    = self::getImageDimensions($pictures[$i]);
            }
            
            $pictures[12] = $model->getImageUrl(12);
            $sizes[12]    = self::getImageDimensions($pictures[12]);
            
            return $this->render(
                '@app/views/utils/picture/view',
                [
                    'model'    => $model,
                    'pictures' => $pictures,
                    'sizes'    => $sizes,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Throwable
         * @action Utils_PictureController_actionCreate_
         */
        public function actionCreate(string $url, int $textType, int $id, int $column): string|Response
        {
            $actionId = static::ACTION_INDEX . '22_actionCreate';
            
            $parent = ParentHelper::getModel($textType, $id);
            
            $sizes = $this->extractSizesFromUrl($url, $parent->site_id, $textType);
            
            $width             = $sizes['width'];
            $height            = $sizes['height'];
            $modelImagePath    = TypeHelper::getRootSlug($textType, $parent->site_id);
            $aliasHost         = Yii::getAlias('@static');
            $aliasRoot         = Yii::getAlias('@staticRoot');
            $originalPhotoPath = $aliasRoot . '/origin/' . $modelImagePath . DIRECTORY_SEPARATOR . $parent->id . '.jpg';
            $originalPhotoUrl  = $aliasHost . '/origin/' . $modelImagePath . DIRECTORY_SEPARATOR . $parent->id . '.jpg';
            $targetPhotoPath   = $aliasRoot . '/cache/' . $modelImagePath . DIRECTORY_SEPARATOR . $parent->id . '-' . $parent->slug . '_col-' . $column . '.webp';
            
            return $this->render('@app/views/utils/picture/create', [
                'parent'     => $parent, // модель к которой приписана картинка, которую хотим обрезать
                'photoPath'  => $originalPhotoPath, //путь к файлу на сервере
                'photoUrl'   => $originalPhotoUrl,  //адрес картинки в интернете
                'targetPath' => $targetPhotoPath,  //адрес картинки в интернете
                'targetUrl'  => $url,  //адрес картинки в интернете
                'width'      => $width, // новая ширина картинки
                'height'     => $height, // новая высота картинки
                'actionId'   => $actionId,
                'textType'   => static::TEXT_TYPE,
                'prefix'     => static::MODEL_PREFIX,
                'label'      => static::MODEL_LABEL,
            ]);
        }
        
        private function extractSizesFromUrl(string $url, int $siteId, int $textType): array
        {
            // Извлекаем размер колонки из URL с помощью регулярного выражения
            if (!preg_match('/col-(\d+)\.webp$/', $url, $matches)) {
                throw new InvalidArgumentException('Invalid URL format: column size not found');
            }
            
            $colSize = '_col-' . $matches[1];
            
            // Получаем все возможные размеры для данного типа
            $sizes = $this->service->getPhotoRatioSizes(
                $textType,
                $this->service->getBaseWidth($siteId, $textType),  // Предполагаем, что baseWidth доступен через сервис
                [
                    'width'  => Yii::$app->params['photoWidth'] / 12,
                    'height' => Yii::$app->params['photoHeight'] / 12,
                ],                                                 // photoSizes можно передать пустым, так как нам нужны только пропорции
                $this->service->getRatios($siteId, $textType),     // Предполагаем, что ratios доступны через сервис
            );
            
            // Проверяем, существует ли указанный размер колонки
            if (!isset($sizes[$colSize])) {
                throw new InvalidArgumentException("Invalid column size: $colSize");
            }
            
            return [
                'width'  => $sizes[$colSize]['width'],
                'height' => $sizes[$colSize]['height'],
            ];
        }
        
        public function actionSaveCrop(): array
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            if (!Yii::$app->request->isPost) {
                return [
                    'success' => false,
                    'message' => 'Invalid request method',
                ];
            }
            
            // Get raw POST data to debug
            $rawPost = Yii::$app->request->getRawBody();
            Yii::info('Raw POST data: ' . $rawPost, 'crop-debug');
            
            // Get and validate input data
            $sourcePath = Yii::$app->request->post('sourcePath');
            $targetPath = Yii::$app->request->post('targetPath');
            $coords     = Yii::$app->request->post('coords');
            
            // Log received data
            Yii::info(
                'Received data: ' . print_r([
                    'sourcePath' => $sourcePath,
                    'targetPath' => $targetPath,
                    'coords'     => $coords,
                ], true), 'crop-debug',
            );
            
            // Get and validate input data
            $sourcePath = Yii::$app->request->post('sourcePath');
            $targetPath = Yii::$app->request->post('targetPath');
            $coords     = Yii::$app->request->post('coords');
            
            // Validate required parameters
            if (!$sourcePath || !$targetPath || !$coords) {
                return [
                    'success'  => false,
                    'message'  => 'Missing required parameters',
                    'received' => [
                        'sourcePath' => $sourcePath,
                        'targetPath' => $targetPath,
                        'coords'     => $coords,
                    ],
                ];
            }
            
            // Validate coordinates structure
            if (
                !isset($coords['x'], $coords['y'], $coords['w'], $coords['h'])
                || !is_numeric($coords['x'])
                || !is_numeric($coords['y'])
                || !is_numeric($coords['w'])
                || !is_numeric($coords['h'])
                || $coords['w'] <= 0
                || $coords['h'] <= 0
            ) {
                return [
                    'success'  => false,
                    'message'  => 'Invalid coordinates format',
                    'received' => $coords,
                ];
            }
            
            try {
                // Validate source file
                if (!file_exists($sourcePath)) {
                    throw new Exception("Source image not found: $sourcePath");
                }
                
                // Ensure target directory exists
                $targetDir = dirname($targetPath);
                if (!is_dir($targetDir)) {
                    if (!mkdir($targetDir, 0755, true)) {
                        throw new Exception('Failed to create target directory');
                    }
                }
                
                // Check write permissions
                if (!is_writable($targetDir)) {
                    throw new Exception("Target directory is not writable: $targetDir");
                }
                
                // Load source image
                $sourceImage = imagecreatefromjpeg($sourcePath);
                if (!$sourceImage) {
                    throw new Exception('Failed to load source image');
                }
                
                // Create new image
                $croppedImage = imagecreatetruecolor(
                    (int)$coords['w'],
                    (int)$coords['h'],
                );
                
                if (!$croppedImage) {
                    throw new Exception('Failed to create new image');
                }
                
                // Perform crop
                if (
                    !imagecopy(
                        $croppedImage,
                        $sourceImage,
                        0,
                        0,
                        (int)$coords['x'],
                        (int)$coords['y'],
                        (int)$coords['w'],
                        (int)$coords['h'],
                    )
                ) {
                    throw new Exception('Failed to crop image');
                }
                
                // Save result
// Save result in WebP format
                if (!imagewebp($croppedImage, $targetPath, 90)) {
                    throw new Exception('Failed to save cropped image in WebP format');
                }
                
                // Clean up
                imagedestroy($sourceImage);
                imagedestroy($croppedImage);
                
                return [
                    'success' => true,
                    'message' => 'Image cropped successfully',
                    'path'    => $targetPath,
                ];
                
            }
            catch (Exception $e) {
                Yii::error($e->getMessage(), 'crop-error');
                return [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }
        
        
        /**
         * @action Utils_PictureController_actionUpdate
         */
        public function actionUpdate(string $url, int $textType, int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . '22_actionUpdate';
            
            $staticHost   = Yii::getAlias('@staticHost');
            $relativePath = str_replace($staticHost, '', $url);
            
            // Get the full file path
            $baseDir  = Yii::getAlias('@staticRoot');
            $fullPath = realpath($baseDir . DIRECTORY_SEPARATOR . ltrim($relativePath, '/'));
            // Determine the original file's extension
            $originalExtension = pathinfo($fullPath, PATHINFO_EXTENSION);
            
            list($width, $height) = getimagesize($fullPath);
            
            // Form model
            $model = new PictureUploadPhotoForm();
            
            if (Yii::$app->request->isPost) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->upload($fullPath, $originalExtension)) {
                    Yii::$app->session->setFlash('success', 'Image successfully updated.');
                    return $this->redirect(
                        [
                            'view',
                            'textType' => $textType,
                            'id'       => $id,
                        ],
                    ); // Redirect to your desired page
                }
                else {
                    Yii::$app->session->setFlash('danger', 'Failed to update the image.');
                }
            }
            
            return $this->render(
                '@app/views/utils/picture/update', [
                'model'    => $model,
                'photo'    => $url,
                'width'    => $width,
                'height'   => $height,
                'actionId' => $actionId,
                'textType' => static::TEXT_TYPE,
                'prefix'   => static::MODEL_PREFIX,
                'label'    => static::MODEL_LABEL,
            ],
            );
        }
        
        /**
         * Deletes an existing model picture.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param string $url
         * @param int    $textType
         * @param int    $id
         * @return Response
         * @action Utils_PictureController_actionDelete
         */
        public function actionDelete(string $url, int $textType, int $id): Response
        {
            $staticHost   = Yii::getAlias('@staticHost');
            $relativePath = str_replace($staticHost, '', $url);
            
            // Get the full file path
            $baseDir  = Yii::getAlias('@staticRoot');
            $fullPath = realpath($baseDir . DIRECTORY_SEPARATOR . ltrim($relativePath, '/'));
            
            if ($fullPath && str_starts_with($fullPath, realpath($baseDir))) {
                // Validate that the full path is within the base directory
                if (str_starts_with($fullPath, realpath($baseDir))) {
                    if (file_exists($fullPath)) {
                        if (unlink($fullPath)) {
                            Yii::$app->session->setFlash('success', 'Image successfully deleted.');
                        }
                        else {
                            Yii::$app->session->setFlash('danger', 'Failed to delete the image.');
                        }
                    }
                    else {
                        Yii::$app->session->setFlash('danger', 'Image not found.');
                    }
                }
                else {
                    Yii::$app->session->setFlash('danger', 'Invalid path specified.');
                }
                
                return $this->redirect(
                    [
                        'view',
                        'textType' => $textType,
                        'id'       => $id,
                    ],
                );
            }
            return $this->redirect(
                TypeHelper::getView($textType, $id),
            );
            
        }
        
        public function getImageDimensions(string|null $url): array|null
        {
            if ($url) {
                list($width, $height) = @getimagesize($url);
                if ($width && $height) {
                    return ['width' => $width, 'height' => $height];
                }
            }
            return ['width' => null, 'height' => null];
        }
        
        // В контроллере
        public function actionImageSize(string $url): Response
        {
            $size = @filesize($url);
            return $this->asJson([
                'size' => $size !== false ? $size : null,
            ]);
        }
        
        /**
         * Действие для получения изображения по локальному пути
         * @throws NotFoundHttpException
         */
        public function actionGetImage($path): false|string
        {
            $filePath = base64_decode($path);
            
            if (!file_exists($filePath)) {
                throw new NotFoundHttpException('Изображение не найдено.');
            }
            // Получаем MIME-тип файла
            $mimeType = mime_content_type($filePath);
            
            // Отправляем файл
            Yii::$app->response->format = Response::FORMAT_RAW;
            Yii::$app->response->headers->set('Content_Type', $mimeType);
            Yii::$app->response->headers->set('Content_Length', filesize($filePath));
            
            return file_get_contents($filePath);
        }
        
        /**
         * @throws BadRequestHttpException
         */
        public function beforeAction($action): bool
        {
            if ($action->id === 'save-crop') {
                $this->enableCsrfValidation = false;
            }
            
            return parent::beforeAction($action);
        }
    }
