<?php
    
    namespace backend\controllers\utils;
    
    use Yii;
    use yii\web\Controller;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    /**
     *
     * @property-read array $allCacheItems
     */
    class UploadController extends Controller
    {
        
        public function actionImages(): ?Response
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            // Логирование полученных данных
            Yii::debug('Request data: ' . json_encode(Yii::$app->request->post()));
            Yii::debug('FILES: ' . json_encode($_FILES));
            
            $uploadedFile = UploadedFile::getInstanceByName('file');
            if (!$uploadedFile) {
                return $this->asJson(['error' => 'No file uploaded'])->setStatusCode(400);
            }
            
            /***************************************************
             * Only these origins are allowed to upload images *
             ***************************************************/
            $accepted_origins = [
                'http://localhost',
                Yii::$app->params['backendHostInfo'],
                Yii::$app->params['staticHostInfo'],
            ];
            
            /*********************************************
             * Change this line to set the upload folder *
             *********************************************/
            $imageFolder = Yii::getAlias('@webroot/uploads/');
            
            $origin = Yii::$app->request->getOrigin();
            if ($origin && !in_array($origin, $accepted_origins, true)) {
                return $this->asJson(['error' => 'Origin Denied'])->setStatusCode(403);
            }
            
            // OPTIONS requests are handled for CORS
            if (Yii::$app->request->isOptions) {
                Yii::$app->response->headers->add('Access-Control-Allow-Methods', 'POST, OPTIONS');
                return null;
            }
            
            $uploadedFile = UploadedFile::getInstanceByName('file');
            if (!$uploadedFile) {
                return $this->asJson(['error' => 'No file uploaded'])->setStatusCode(400);
            }
            
            // Sanitize input
            if (preg_match('/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/', $uploadedFile->name)) {
                return $this->asJson(['error' => 'Invalid file name'])->setStatusCode(400);
            }
            
            // Verify extension
            $validExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array(strtolower($uploadedFile->extension), $validExtensions, true)) {
                return $this->asJson(['error' => 'Invalid file extension'])->setStatusCode(400);
            }
            
            // Generate a unique filename to avoid overwrites
            $fileName = uniqid('img_', true) . '.' . $uploadedFile->extension;
            $filePath = $imageFolder . $fileName;
            
            // Ensure the directory exists
            if (!is_dir($imageFolder) && !mkdir($imageFolder, 0777, true)) {
                return $this->asJson(['error' => 'Failed to create upload directory'])->setStatusCode(500);
            }
            
            // Save the file
            if (!$uploadedFile->saveAs($filePath)) {
                return $this->asJson(['error' => 'Failed to save file'])->setStatusCode(500);
            }
            
            // Determine the base URL
            $baseUrl = Yii::getAlias('@static/uploads/');
            $fileUrl = $baseUrl . $fileName;
            
            return $this->asJson(['location' => $fileUrl]);
        }
    }
