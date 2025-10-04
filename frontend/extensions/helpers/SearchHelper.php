<?php
    
    namespace frontend\extensions\helpers;
    
    use core\tools\Constant;
    use frontend\extensions\search\BookSearch;
    use frontend\extensions\search\ChapterSearch;
    use frontend\extensions\search\ThreadSearch;
    use Throwable;
    use Yii;
    use yii\caching\DbDependency;
    use yii\data\ActiveDataProvider;
    
    class SearchHelper
    {
        /**
         * @throws Throwable
         */
        public static function getBooks(
            BookSearch $searchModel,
            null|int   $siteId = null,
        ): ActiveDataProvider
        {
            $dependency = new DbDependency(
                [
                    'sql' => 'SELECT MAX(updated_at) FROM library_books',
                ],
            );
            
            
            return Yii::$app->db->cache(function () use ($searchModel, $siteId) {
                
                $dataProvider = $searchModel->search(
                    Yii::$app->request->queryParams,
                    $siteId,
                );
                $dataProvider->prepare();
                return $dataProvider;
            },
                Constant::DEFAULT_CACHE_DURATION,
                $dependency,
            );
        }
        
        /**
         * @throws Throwable
         */
        public static function getChapters(
            ChapterSearch $searchModel,
            null|int      $bookId = null,
            bool|null     $order = false,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $bookId,
            );
        }
        
        /**
         * @throws Throwable
         */
        public static function getThreads(
            ThreadSearch $searchModel,
            null|int     $groupId = null,
            null|bool $comments = false,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $groupId,
                $comments,
            );
        }
    }
