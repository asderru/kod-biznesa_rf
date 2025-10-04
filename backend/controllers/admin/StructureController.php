<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\arrays\Admin\InformationEditor;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Content\Note;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Content\Review;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Seo\Anons;
    use core\edit\entities\Seo\Faq;
    use core\edit\entities\Seo\Footnote;
    use core\edit\entities\Seo\Material;
    use core\edit\entities\Seo\News;
    use core\edit\entities\Shop\Brand;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\edit\entities\Utils\Gallery;
    use core\edit\repositories\Admin\StructureRepository;
    use core\helpers\ParametrHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use Throwable;
    use Yii;
    use yii\caching\TagDependency;
    use yii\web\Controller;
    use yii\web\Response;
    
    /**
     *
     * @property-read array $razdels
     * @property-read array $products
     */
    class StructureController extends Controller
    {
        
        public $layout = '@app/views/layouts/content';
        private const string MODEL_LABEL  = 'Структура сервера';
        private const string MODEL_PREFIX = 'SiteStructure';
        private const string ACTION_INDEX = 'Admin_StructureController_';
        
        private StructureRepository $repository;
        
        public function __construct(
            $id,
            $module,
            StructureRepository $repository,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
        }
        
        /**
         * @action Admin_StructureController_actionIndex
         */
        public function actionIndex(): string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            $sites = InformationEditor::getArray(['id', 'name']);
            $types = TypeHelper::getTypesArray();
            
            return $this->render(
                '@app/views/admin/structure/index',
                [
                    'sites'    => $sites,
                    'types' => $types,
                    'actionId' => $actionId,
                    'textType' => null,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @action Admin_StructureController_actionView
         */
        public function actionView(int $textType): string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $models = $this->repository::getContentModelsArray($textType);
            
            return $this->render(
                '@app/views/admin/structure/view',
                [
                    'models'   => $models,
                    'actionId' => $actionId,
                    'textType' => $textType,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * @action Admin_StructureController_actionSite
         */
        public function actionSite(int $siteId): string
        {
            $site    = ParametrHelper::getSite($siteId);
            $actionId = static::ACTION_INDEX . 'actionSite';
            
            $models     = $this->repository->getSiteArray($siteId);
            $razdels    = $models[Razdel::TEXT_TYPE];
            $products   = $models[Product::TEXT_TYPE];
            $sections   = $models[Section::TEXT_TYPE];
            $articles   = $models[Article::TEXT_TYPE];
            $books      = $models[Book::TEXT_TYPE];
            $chapters   = $models[Chapter::TEXT_TYPE];
            $categories = $models[Category::TEXT_TYPE];
            $posts      = $models[Post::TEXT_TYPE];
            $groups     = $models[Group::TEXT_TYPE];
            $pages      = $models[Page::TEXT_TYPE];
            $brands     = $models[Brand::TEXT_TYPE];
            $galleries  = $models[Gallery::TEXT_TYPE];
            $anonses = $models[Anons::TEXT_TYPE];
            $faqs       = $models[Faq::TEXT_TYPE];
            $footnotes  = $models[Footnote::TEXT_TYPE];
            $news       = $models[News::TEXT_TYPE];
            $materials  = $models[Material::TEXT_TYPE];
            $notes      = $models[Note::TEXT_TYPE];
            $reviews    = $models[Review::TEXT_TYPE];
            
            return $this->render(
                '@app/views/admin/structure/site',
                [
                    'site'    => $site,
                    'razdels'    => $razdels,
                    'products'   => $products,
                    'sections'   => $sections,
                    'articles'   => $articles,
                    'books'      => $books,
                    'chapters'   => $chapters,
                    'categories' => $categories,
                    'posts'      => $posts,
                    'groups'     => $groups,
                    'pages'      => $pages,
                    'brands'     => $brands,
                    'galleries'  => $galleries,
                    'anonses' => $anonses,
                    'faqs'       => $faqs,
                    'footnotes'  => $footnotes,
                    'news'       => $news,
                    'materials'  => $materials,
                    'notes'      => $notes,
                    'reviews'    => $reviews,
                    'actionId'   => $actionId,
                    'textType'   => Constant::SITE_TYPE,
                    'prefix'     => static::MODEL_PREFIX,
                    'label'      => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @action Admin_StructureController_actionGoto
         */
        public function actionGoto(int $textType, int $parentId): Response
        {
            $url = TypeHelper::getLongEditUrl($textType);
            return $this->redirect(
                [
                    $url . 'view',
                    'id' => $parentId,
                ],
            );
        }
        
        public function actionGotoEdit(int $textType, int $parentId): Response
        {
            $url = TypeHelper::getLongEditUrl($textType);
            return $this->redirect(
                [
                    $url . 'update',
                    'id' => $parentId,
                ],
            );
        }
        
        /**
         * @action Admin_StructureController_actionDescendants
         */
        public function actionDescendants(int $textType, int $parentId): Response
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            if ($textType === Constant::RAZDEL_TYPE) {
                $groups[] = $this->repository->getRazdelChildrenArray($parentId);
                $groups[] = $this->repository->getProductsArray($parentId);
            }
            if ($textType === Constant::BOOK_TYPE) {
                $groups[] = $this->repository->getBookChildrenArray($parentId);
                $groups[] = $this->repository->getChaptersArray($parentId);
            }
            if ($textType === Constant::CATEGORY_TYPE) {
                $groups[] = $this->repository->getCategoryChildrenArray($parentId);
                $groups[] = $this->repository->getPostsArray($parentId);
            }
            if ($textType === Constant::GROUP_TYPE) {
                $groups[] = $this->repository->getGroupChildrenArray($parentId);
                $groups[] = $this->repository->getThreadsArray($parentId);
            }
            if ($textType === Constant::SECTION_TYPE) {
                $groups[] = $this->repository->getSectionChildrenArray($parentId);
                $groups[] = $this->repository->getArticlesArray($parentId);
            }
            $groups[] = $this->repository->getAnonsesArray($textType, $parentId);
            $groups[] = $this->repository->getFaqsArray($textType, $parentId);
            $groups[] = $this->repository->getFootnotesArray($textType, $parentId);
            $groups[] = $this->repository->getNewsArray($textType, $parentId);
            $groups[] = $this->repository->getMaterialsArray($textType, $parentId);
            $groups[] = $this->repository->getNotesArray($textType, $parentId);
            $groups[] = $this->repository->getReviewsArray($textType, $parentId);
            $groups[] = $this->repository->getGalleriesArray($textType, $parentId);
            
            // Добавляем имя типа для каждой группы
            foreach ($groups as $group) {
                if (!empty($group)) {
                    foreach ($group as &$item) {
                        // Добавляем имя типа к каждому элементу
                        $item['type_name'] = TypeHelper::getName($item['array_type']);
                        $item['photo'] = TypeHelper::getImage(
                            $item['site_id'],
                            $item['id'],
                            $item['array_type'],
                            $item['slug'],
                            6,
                        );
                    }
                }
                $descendants[] = $group;
            }
            
            
            return $this->asJson($descendants);
            
        }
        
        /**
         * @action Admin_StructureController_actionClearCache
         */
        public function actionClearCache(int $siteId, ?int $textType = null, ?int $id = null): Response
        {
            try {
                $cache = Yii::$app->cache;
                
                if ($textType === null) {
                    // Generate cache tags for text types 1 to 100
                    $cacheTags = [];
                    for ($type = 1; $type <= 100; $type++) {
                        $cacheTag    = 'cache_tag_' . $siteId . '_' . $type . '_' . $id;
                        $cacheTagAll = 'cache_tag_' . $siteId . '_' . $type . '_all';
                        $cacheTags[] = $cacheTag;
                        $cacheTags[] = $cacheTagAll;
                    }
                    
                    TagDependency::invalidate($cache, $cacheTags);
                }
                else {
                    // Original logic for specific text type
                    $cacheTag    = 'cache_tag_' . $siteId . '_' . $textType . '_' . $id;
                    $cacheTagAll = 'cache_tag_' . $siteId . '_' . $textType . '_all';
                    
                    TagDependency::invalidate($cache, [$cacheTag, $cacheTagAll]);
                }
                Yii::$app->session->
                setFlash(
                    'success',
                    'Кэш успешно почищен!',
                );
                
                return $this->redirect(Yii::$app->request->referrer ?: ['index']);
            }
            catch (Throwable $e) {
                Yii::$app->errorHandler->logException($e);
                return $this->redirect(Yii::$app->request->referrer ?: ['index']);
            }
        }
    }
