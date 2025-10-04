<?php
    
    namespace backend\helpers;
    
    use core\edit\search\Seo\AnonsSearch;
    use core\edit\search\Admin\LinkSearch;
    use core\edit\search\Blog\CategorySearch;
    use core\edit\search\Blog\PostSearch;
    use core\edit\search\Content\PageSearch;
    use core\edit\search\Forum\GroupSearch;
    use core\edit\search\Forum\ThreadSearch;
    use core\edit\search\Library\AuthorSearch;
    use core\edit\search\Library\BookSearch;
    use core\edit\search\Library\ChapterSearch;
    use core\edit\search\Link\InternalSearch;
    use core\edit\search\Link\MasterSearch;
    use core\edit\search\Magazin\ArticleSearch;
    use core\edit\search\Magazin\SectionSearch;
    use core\edit\search\Seo\FaqSearch;
    use core\edit\search\Seo\FootnoteSearch;
    use core\edit\search\Seo\MaterialSearch;
    use core\edit\search\Seo\NewsSearch;
    use core\edit\search\Shop\BrandSearch;
    use core\edit\search\Shop\CharacteristicSearch;
    use core\edit\search\Shop\ProductSearch;
    use core\edit\search\Shop\RazdelSearch;
    use core\edit\search\Tech\TroubleSearch;
    use core\edit\search\Tools\DraftSearch;
    use core\edit\search\Tools\SeoLinkSearch;
    use core\edit\search\User\PersonSearch;
    use core\edit\search\User\UserSearch;
    use core\tools\Constant;
    use Yii;
    use yii\data\ActiveDataProvider;
    
    class SearchHelper
    {
        
        public static function getRazdels(
            RazdelSearch $searchModel,
            ?int         $siteId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
        }
        
        public static function getBrands(
            BrandSearch $searchModel,
            ?int        $siteId = null,
        ): ActiveDataProvider
        {
            
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
            $dataProvider->prepare();
            return $dataProvider;
        }
        
        
        public static function getProducts(
            ProductSearch $searchModel,
            ?int          $siteId = null,
            ?int          $textType = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
                $textType,
            );
        }
        
        
        public static function getProductsByRazdel(
            ProductSearch $searchModel,
            ?int          $razdelId = null,
            ?int          $textType = null,
        ): ActiveDataProvider
        {
            return $searchModel->searchByRazdel(
                Yii::$app->request->queryParams,
                $razdelId,
                null,
                $textType,
                Constant::STATUS_ROOT,
                [],
            );
        }
        
        
        public static function getCategories(
            CategorySearch $searchModel,
            ?int           $siteId = null,
        ): ActiveDataProvider
        {
            
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
            $dataProvider->prepare();
            return $dataProvider;
        }
        
        
        public static function getPosts(
            PostSearch $searchModel,
            ?int       $categoryId = null,
        ): ActiveDataProvider
        {
            return $searchModel->searchByCategory(
                Yii::$app->request->queryParams,
                $categoryId,
            );
        }
        
        
        public static function getBooks(
            BookSearch $searchModel,
            ?int       $siteId = null,
        ): ActiveDataProvider
        {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
            $dataProvider->prepare();
            return $dataProvider;
        }
        
        
        public static function getChapters(
            ChapterSearch $searchModel,
            ?int          $bookId = null,
            bool|null     $order = false,
        ): ActiveDataProvider
        {
            return $searchModel->searchByBook(
                Yii::$app->request->queryParams,
                $bookId,
                $order,
            );
        }
        
        
        public static function getSections(
            SectionSearch $searchModel,
            ?int          $siteId = null,
        ): ActiveDataProvider
        {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
            $dataProvider->prepare();
            return $dataProvider;
        }
        
        
        public static function getArticles(
            ArticleSearch $searchModel,
            ?int          $sectionId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $sectionId,
            );
        }
        
        
        public static function getGroups(
            GroupSearch $searchModel,
            ?int        $siteId = null,
        ): ActiveDataProvider
        {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
            $dataProvider->prepare();
            return $dataProvider;
        }
        
        public static function getThreads(
            ThreadSearch $searchModel,
            ?int         $sectionId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $sectionId,
            );
        }
        
        
        public static function getPages(
            PageSearch $searchModel,
            ?int       $siteId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
        }
        
        public static function getDrafts(
            DraftSearch $searchModel,
            ?int        $siteId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
        }
        
        
        public static function getMaterials(
            MaterialSearch $searchModel,
            ?int           $siteId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
        }
        
        
        public static function getNews(
            NewsSearch $searchModel,
            ?int       $siteId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
        }
        
        
        public static function getFaqs(
            FaqSearch $searchModel,
            ?int      $siteId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
        }
        
        
        public static function getFootnotes(
            FootnoteSearch $searchModel,
            ?int           $siteId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
        }
        
        
        public static function getAnonses(
            AnonsSearch $searchModel,
            ?int        $siteId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
        }
        
        
        public static function getAuthors(
            AuthorSearch $searchModel,
            ?int         $siteId = null,
        ): ActiveDataProvider
        {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
            $dataProvider->prepare();
            return $dataProvider;
        }
        
        
        public static function getUsers(
            UserSearch $searchModel,
        ): ActiveDataProvider
        {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            $dataProvider->prepare();
            return $dataProvider;
        }
        
        
        public static function getPersons(
            PersonSearch $searchModel,
            ?int         $siteId = null,
        ): ActiveDataProvider
        {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
            $dataProvider->prepare();
            return $dataProvider;
        }
        
        
        public static function getCharacteristics(
            CharacteristicSearch $searchModel,
        ): ActiveDataProvider
        {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            $dataProvider->prepare();
            return $dataProvider;
        }
        
        
        public static function getMasters(
            MasterSearch $searchModel,
            ?int         $code = null,
            ?int         $siteId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $code,
                $siteId,
            );
            
        }
        
        
        public static function getInternals(
            InternalSearch $searchModel,
            ?int           $siteId = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
            
        }
        
        
        public static function getSeoLinks(
            SeoLinkSearch $searchModel,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
        }
        
        
        public static function getLinksByModel(
            MasterSearch $searchModel,
            int          $typeId,
            int          $parentId,
            bool         $code200,
        ): ActiveDataProvider
        {
            return $searchModel->searchByModel(
                Yii::$app->request->queryParams,
                $typeId,
                $parentId,
                $code200,
            );
            
        }
        
        
        public static function getTroubles(
            TroubleSearch $searchModel,
            ?int          $textType = null,
        ): ActiveDataProvider
        {
            return $searchModel->search(
                Yii::$app->request->queryParams,
                $textType,
            );
        }
        
        
        public static function getEdits(
            LinkSearch $searchModel,
        ): ActiveDataProvider
        {
                
                $dataProvider = $searchModel->search(
                    Yii::$app->request->queryParams,
                );
                $dataProvider->prepare();
                return $dataProvider;
        }
        
    }
