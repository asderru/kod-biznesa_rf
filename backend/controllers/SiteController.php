<?php
    
    namespace backend\controllers;
    
    use core\edit\editors\Admin\InformationEditor;
    use core\edit\editors\Content\ContentEditor;
    use core\edit\editors\Content\ReviewEditor;
    use core\edit\editors\Library\AuthorEditor;
    use core\edit\entities\Admin\Information;
    use core\edit\traits\RedirectControllerTrait;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use core\tools\params\Label;
    use core\tools\params\Parametr;
    use core\tools\params\Prefix;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\helpers\FileHelper;
    use yii\web\Controller;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    /**
     *
     * @property-read array $allCacheItems
     */
    class SiteController extends Controller
    {
        use RedirectControllerTrait;
        
        public $layout = '@app/views/layouts/main';
        
        private const int    TEXT_TYPE             = Information::TEXT_TYPE;
        private const string ACTION_INDEX          = 'SiteController_';
        private const string CACHE_KEY_PREFIX      = 'site_controller_cache_';
        private const string CACHE_KEY_LAST_UPDATE = 'site_controller_last_update';
        private ContentEditor            $editor;
        
        public function __construct(
            $id,
            $module,
            ContentEditor $editor,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->editor = $editor;
        }
        
        public function behaviors(): array
        {
            return [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'logout' => ['POST'],
                    ],
                ],
            ];
        }
        
        public function actions(): array|string
        {
            return [
                'error' => [
                    'class'  => ErrorAction::class,
                    'layout' => 'blank',
                ],
            ];
        }
        
        public function actionIndex(): string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $sites      = InformationEditor::getArray(['id', 'name']);
            
            // Группировка результатов по типу для отображения
            $team = AuthorEditor::getArray();
            $reviews = ReviewEditor::getArray(Constant::SITE_TYPE, Parametr::siteId());
            
            return $this->render('index', [
                'sites'          => $sites,
                'team'           => $team,
                'reviews'           => $reviews,
                'actionId'       => $actionId,
                'textType'       => static::TEXT_TYPE,
                'prefix'         => Prefix::site(),
                'label'          => Label::site(),
            ]);
        }
        
        /**
         * @action SiteController_actionUpdate
         */
        public function actionUpdate(): Response
        {
            // Устанавливаем максимальное время выполнения скрипта в 600 секунд
            set_time_limit(600);
            
            try {
                // Создаем временное соединение с удаленной базой данных
                $remoteDb = Yii::$app->remoteDb;
                $remoteDb->open();
                
                $dsn = Yii::$app->remoteDb->dsn;
                preg_match('/dbname=([^;]*)/', $dsn, $matches);
                $databaseName = $matches[1] ?? 'Неизвестно';
                
                // Получаем список таблиц
                $allTables = $remoteDb->schema->getTableNames();
                
                $tables = array_filter($allTables, function ($table) {
                    return !str_starts_with($table, 'auth_')
                           && !str_starts_with($table, 'user_')
                           && !str_starts_with($table, 'oauth_')
                           && !str_starts_with($table, 'q_')
                           && $table !== 'users';
                });
                
                $tempDir = Yii::getAlias('@runtime/temp_db_dump');
                
                if (!FileHelper::createDirectory($tempDir)) {
                    throw new Exception('Cannot create directory');
                }
                
                if (!is_writable($tempDir)) {
                    throw new Exception('Directory is not writable');
                }
                
                // Экспортируем данные из удаленной базы
                foreach ($tables as $table) {
                    $command = $remoteDb->createCommand("SELECT * FROM `$table`");
                    $data    = $command->queryAll();
                    file_put_contents("$tempDir/$table.json", json_encode($data));
                }
                
                // Сохраняем структуру таблиц
                foreach ($tables as $table) {
                    $createTableSql = $remoteDb->createCommand("SHOW CREATE TABLE `$table`")->queryOne()['Create Table'];
                    file_put_contents("$tempDir/$table.sql", $createTableSql);
                }
                
                $remoteDb->close();
                
                // Подключаемся к локальной базе данных
                $localDb = Yii::$app->db;
                $localDb->open();
                // Удаляем таблицы в локальной базе данных, кроме исключенных
                $localDb->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
                foreach ($tables as $table) {
                    $localDb->createCommand("DROP TABLE IF EXISTS `$table`")->execute();
                }
                $localDb->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
                // Создаем структуру таблиц
                foreach ($tables as $table) {
                    $createTableSql = file_get_contents("$tempDir/$table.sql");
                    $localDb->createCommand($createTableSql)->execute();
                }
                
                // Импортируем данные в локальную базу
                foreach ($tables as $table) {
                    $data = json_decode(file_get_contents("$tempDir/$table.json"), true);
                    foreach ($data as $row) {
                        $localDb->createCommand()->insert($table, $row)->execute();
                    }
                }
                
                // Закрываем соединение
                $localDb->close();
                
                // Удаляем временные файлы
                FileHelper::removeDirectory($tempDir);
                
                Yii::$app->session->setFlash('success', 'База данных "' . $databaseName . '" успешно обновлена');
                return $this->redirect(['index']);
            }
            catch (Exception $e) {
                Yii::error('Ошибка при обновлении базы данных ": ' . $e->getMessage());
                Yii::$app->session->setFlash(
                    'danger', 'Произошла ошибка при обновлении базы данных"'
                              . '": ' . $e->getMessage(),
                );
                return $this->redirect(['index']);
            }
        }
        
        /**
         * @throws Exception
         */
        public function actionExpress(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionExpress';
            
            $isAlone = ParametrHelper::isAlone();
            
            return $this->render(
                '@app/views/site/express',
                [
                    'isAlone'  => $isAlone,
                    'actionId' => $actionId,
                ],
            );
        }
        
        public function actionConvert(): void
        {
            $sourceFolder = Yii::$app->params['frontendPath'] . '/img';
            
            $this->convertImagesRecursively($sourceFolder);

// Display success message
            Yii::$app->session->setFlash('success', 'Все картинки сконвертированы в WebP!');
            $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }
        
        public function actionActivate(): Response
        {
            if ($this->service::setProduction()) {
                Yii::$app->getSession()->setFlash(
                    'success',
                    'Установлена рабочая конфигурация сайта!',
                );
            }

// Возвращаем пользователя на предыдущий URL или на главную страницу, если referrer не задан
            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
        }
        
        private function convertImagesRecursively(
            string $folder,
        ): void
        {
            if (!is_dir($folder)) {
// Если директория не существует, создаем ее
                mkdir($folder, 0777, true);
            }
            
            $files = scandir($folder);
            
            foreach ($files as $file) {
                $filePath = $folder . DIRECTORY_SEPARATOR . $file;
                
                if ($file !== '.' && $file !== '..') {
                    if (is_dir($filePath)) {
// Recursively call the function for the subfolder
                        $this->convertImagesRecursively($filePath);
                    }
                    elseif (is_file($filePath) && in_array(pathinfo($filePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $image = null;
                        
                        switch (pathinfo($filePath, PATHINFO_EXTENSION)) {
                            case 'jpg':
                            case 'jpeg':
                                $image = imagecreatefromjpeg($filePath);
                                break;
                            case 'png':
                                $image = imagecreatefrompng($filePath);
                                break;
                            case 'gif':
                                $image = imagecreatefromgif($filePath);
                                break;
                        }
                        
                        if ($image !== false) {
// Convert the image to true color if it's palette-based
                            $trueColorImage = imagecreatetruecolor(imagesx($image), imagesy($image));
                            imagecopy($trueColorImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                            
                            $webpFilePath = str_replace('.' . pathinfo($filePath, PATHINFO_EXTENSION), '.webp', $filePath);
                            
                            if (!file_exists($webpFilePath) || filemtime($webpFilePath) < filemtime($filePath)) {
                                imagewebp($trueColorImage, $webpFilePath);
                            }
                            
                            imagedestroy($image);
                            imagedestroy($trueColorImage);
                        }
                    }
                }
            }
        }
        
    }
