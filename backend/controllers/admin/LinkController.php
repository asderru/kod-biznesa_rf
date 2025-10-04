<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Forum\Thread;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Seo\Faq;
    use core\edit\entities\Seo\Footnote;
    use core\edit\entities\Shop\Brand;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\tools\Constant;
    use frontend\urls\ArticleRule;
    use frontend\urls\BookRule;
    use frontend\urls\BrandRule;
    use frontend\urls\CategoryRule;
    use frontend\urls\FaqRule;
    use frontend\urls\GroupRule;
    use frontend\urls\PageRule;
    use frontend\urls\PostRule;
    use frontend\urls\ProductRule;
    use frontend\urls\RazdelRule;
    use frontend\urls\SectionRule;
    use frontend\urls\ThreadRule;
    use Yii;
    use yii\db\Exception;
    use yii\web\Controller;
    use yii\web\Response;
    
    class LinkController extends Controller
    {
        /**
         * @throws Exception
         */
        public function actionAll(): Response
        {
            self::actionRazdel();
            self::actionProduct();
            self::actionBrand();
            self::actionBook();
            self::actionChapter();
            self::actionFaq();
            self::actionFootnote();
            self::actionGroup();
            self::actionThread();
            self::actionCategory();
            self::actionPost();
            self::actionSection();
            self::actionArticle();
            self::actionPage();
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно.");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionRazdel(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Razdel::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (RazdelRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей: $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionProduct(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Product::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (ProductRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей: $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionBrand(): Response
        {
            // Получаем бренды с итерацией по 50 записей за раз
            $query = Brand::find();
            
            $updatedCount = 0; // Считаем количество обновленных брендов
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (BrandRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных брендов
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все бренды обновлены успешно. Обновлено записей: $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionBook(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Book::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (BookRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей: $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionChapter(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Chapter::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                // Получаем всех родителей и сортируем их по возрастанию по полю lft
                if ($model->status === Constant::STATUS_ROOT) {
                    $model->link = '/' . $model->slug . '/';
                }
                else {
                    
                    $root = Chapter::find()->where([
                        'status'  => Chapter::STATUS_ROOT,
                        'site_id' => $model->site_id,
                    ])
                                   ->limit(1)
                                   ->one()
                    ;
                    
                    $book = $model->book;
                    
                    if ($book) // Формируем новый URL для модели
                    {
                        $model->link = '/' . $root->slug . DIRECTORY_SEPARATOR . $book->slug . DIRECTORY_SEPARATOR . $model->slug . '/';
                    }
                    else {
                        $model->link = null;
                    }
                }
                
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей: $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionFaq(): Response
        {
            // Получаем бренды с итерацией по 50 записей за раз
            $query = Faq::find();
            
            $updatedCount = 0; // Считаем количество обновленных брендов
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (FaqRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных брендов
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все бренды обновлены успешно. Обновлено записей: $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionFootnote(): Response
        {
            // Получаем бренды с итерацией по 50 записей за раз
            $query = Footnote::find();
            
            $updatedCount = 0; // Считаем количество обновленных брендов
            
            foreach ($query->each(50) as $model) {
                
                $root = Footnote::find()->where([
                    'status'  => Footnote::STATUS_ROOT,
                    'site_id' => $model->site_id,
                ])
                                ->limit(1)
                                ->one()
                ;
                
                // Генерируем новый URL
                $model->link = '/' . $root->slug . DIRECTORY_SEPARATOR . $model->slug . '/';
                
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных брендов
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все бренды обновлены успешно. Обновлено записей:  $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionGroup(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Group::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (GroupRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей:  $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionThread(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Thread::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (ThreadRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей:  $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionCategory(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Category::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (CategoryRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей:  $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionPost(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Post::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (PostRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей:  $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionSection(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Section::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (SectionRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей:  $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionArticle(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Article::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (ArticleRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей:  $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
        /**
         * @throws Exception
         */
        public function actionPage(): Response
        {
            // Получаем записи с итерацией по 50 записей за раз
            $query = Page::find();
            
            $updatedCount = 0; // Считаем количество обновленных записей
            
            foreach ($query->each(50) as $model) {
                $baseUrl = (PageRule::generateBaseUrl($model));
                // Формируем новый URL для модели
                $model->link = $baseUrl . $model->slug . '/';
                // Сохраняем обновление
                if ($model->save(false, ['link'])) {
                    $updatedCount++; // Увеличиваем счетчик обновленных записей
                }
                else {
                    // Логируем ошибку, если сохранение не удалось
                    Yii::error('Failed to update model ID: ' . $model->id, __METHOD__);
                }
            }
            // Устанавливаем flash сообщение об успехе
            Yii::$app->session->setFlash('success', "Все записи обновлены успешно. Обновлено записей:  $updatedCount");
            
            // Перенаправляем на нужную страницу (например, список брендов или разделов)
            return $this->redirect(['site/index']);
        }
        
    }
