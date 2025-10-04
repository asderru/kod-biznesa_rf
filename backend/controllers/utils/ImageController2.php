<?php

namespace backend\controllers\utils;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ImageController2 extends Controller
{
 public function behaviors(): array
 {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'upload' => ['POST'],
                    'replace' => ['POST'],
                ],
            ],
        ];
    }
    public function actionIndex(): string
    {
        $imgPath = Yii::$app->params['imgPath'];
        $imgHostInfo = Yii::$app->params['imgHostInfo'];
        
        // Получаем список всех файлов рекурсивно
        $files = FileHelper::findFiles($imgPath, [
            'only' => ['*.jpg', '*.jpeg', '*.png', '*.gif'],
            'recursive' => true
        ]);
        
        // Формируем массив с информацией о файлах
        $images = [];
        foreach ($files as $file) {
            $relativePath = str_replace($imgPath, '', $file);
            $webPath = $imgHostInfo . $relativePath;
            
            $images[] = [
                'name' => basename($file),
                'path' => $relativePath,
                'webPath' => $webPath,
                'size' => filesize($file),
                'modified' => date('Y-m-d H:i:s', filemtime($file))
            ];
        }
        
        return $this->render('index', [
            'images' => $images,
        ]);
    }
    
    /**
     * @throws Exception
     */
    public function actionUpload(): Response
    {
        $imgPath = Yii::$app->params['imgPath'];
        $file = UploadedFile::getInstanceByName('imageFile');
        
        if ($file) {
            $subDir = isset($_POST['subDir']) ? trim($_POST['subDir'], '/') : '';
            $uploadPath = $imgPath . '/' . $subDir;
            
            if (!is_dir($uploadPath)) {
                FileHelper::createDirectory($uploadPath);
            }
            
            $fileName = $file->baseName . '.' . $file->extension;
            $filePath = $uploadPath . '/' . $fileName;
            
            if ($file->saveAs($filePath)) {
                return $this->asJson([
                    'success' => true,
                    'message' => 'File uploaded successfully'
                ]);
            }
        }
        
        return $this->asJson([
            'success' => false,
            'message' => 'Upload failed'
        ]);
    }
    
    /**
     * @throws NotFoundHttpException
     */
   public function actionDelete(): array
   {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $path = Yii::$app->request->post('path');
        if (!$path) {
            return [
                'success' => false,
                'message' => 'No file path provided'
            ];
        }

        $imgPath = Yii::$app->params['imgPath'];
        $filePath = $imgPath . '/' . trim($path, '/');
        
        // Проверяем, что путь к файлу находится внутри разрешенной директории
        if (strpos(realpath($filePath), realpath($imgPath)) !== 0) {
            return [
                'success' => false,
                'message' => 'Invalid file path'
            ];
        }

        if (!is_file($filePath)) {
            return [
                'success' => false,
                'message' => 'File not found'
            ];
        }

        try {
            if (unlink($filePath)) {
                // Проверяем, осталась ли директория пустой
                $dir = dirname($filePath);
                if ($dir !== $imgPath && count(scandir($dir)) <= 2) {
                    // Удаляем пустую директорию
                    rmdir($dir);
                }

                return [
                    'success' => true,
                    'message' => 'File deleted successfully'
                ];
            }
        } catch (\Exception $e) {
            Yii::error('Error deleting file: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error deleting file'
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to delete file'
        ];
    }
    
    public function actionReplace(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            $imgPath = Yii::$app->params['imgPath'];
            $oldPath = Yii::$app->request->post('oldPath');
            
            // Проверяем наличие oldPath
            if (!$oldPath) {
                Yii::error('Replace failed: oldPath is empty');
                return $this->asJson([
                    'success' => false,
                    'message' => 'Old path is not provided'
                ]);
            }
            
            // Получаем новый файл
            $file = UploadedFile::getInstanceByName('newFile');
            if (!$file) {
                Yii::error('Replace failed: no new file uploaded');
                return $this->asJson([
                    'success' => false,
                    'message' => 'No file uploaded'
                ]);
            }
            
            // Формируем полный путь к старому файлу
            $oldFilePath = $imgPath . '/' . trim($oldPath, '/');
            
            // Проверяем существование старого файла
            if (!is_file($oldFilePath)) {
                Yii::error("Replace failed: old file not found at {$oldFilePath}");
                return $this->asJson([
                    'success' => false,
                    'message' => 'Original file not found'
                ]);
            }
            
            // Проверяем, что это изображение
            if (!in_array($file->type, ['image/jpeg', 'image/png', 'image/gif'])) {
                Yii::error("Replace failed: invalid file type {$file->type}");
                return $this->asJson([
                    'success' => false,
                    'message' => 'Invalid file type. Only images are allowed.'
                ]);
            }
            
            // Удаляем старый файл
            if (!unlink($oldFilePath)) {
                Yii::error("Replace failed: could not delete old file at {$oldFilePath}");
                return $this->asJson([
                    'success' => false,
                    'message' => 'Could not delete original file'
                ]);
            }
            
            // Сохраняем новый файл
            if (!$file->saveAs($oldFilePath)) {
                Yii::error("Replace failed: could not save new file to {$oldFilePath}");
                return $this->asJson([
                    'success' => false,
                    'message' => 'Could not save new file'
                ]);
            }
            
            Yii::info("File successfully replaced at {$oldFilePath}");
            return $this->asJson([
                'success' => true,
                'message' => 'File replaced successfully'
            ]);
            
        }
        catch (\Exception $e) {
            Yii::error('Replace failed with exception: ' . $e->getMessage());
            return $this->asJson([
                'success' => false,
                'message' => 'An error occurred while replacing the file'
            ]);
        }
    }
}
