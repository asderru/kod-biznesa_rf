<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Content\Page;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Seo\Faq;
    use core\edit\entities\Seo\Footnote;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\read\readModels\Library\ChapterReadRepository;
    use core\read\readModels\Seo\FaqReadRepository;
    use core\read\readModels\Seo\FootnoteReadRepository;
    use core\read\readModels\Shop\ProductReadRepository;
    use core\read\readModels\Shop\RazdelReadRepository;
    use core\services\sitemap\IndexItem;
    use core\services\sitemap\MapItem;
    use core\services\sitemap\Sitemap;
    use core\tools\Constant;
    use Yii;
    use yii\caching\Dependency;
    use yii\caching\TagDependency;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\web\RangeNotSatisfiableHttpException;
    use yii\web\Response;
    
    class SitemapController extends MainController
    {
        protected const int     ITEMS_PER_PAGE = 100;
        protected const string  CACHE_TAG      = 'cache_tag_sitemap_' . Parametr::siteId() . '_';
        
        private RazdelReadRepository   $razdels;
        private ProductReadRepository  $products;
        private Sitemap                $sitemap;
        private FaqReadRepository      $faqs;
        private FootnoteReadRepository $footnotes;
        private ChapterReadRepository $chapters;
        
        public function __construct(
            $id,
            $module,
            Sitemap $sitemap,
            RazdelReadRepository   $razdels,
            ProductReadRepository  $products,
            FaqReadRepository      $faqs,
            FootnoteReadRepository $footnotes,
            ChapterReadRepository $chapters,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->sitemap   = $sitemap;
            $this->razdels   = $razdels;
            $this->products  = $products;
            $this->faqs      = $faqs;
            $this->footnotes = $footnotes;
            $this->chapters = $chapters;
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionIndex(): Response
        {
            return $this->renderSitemap(
                'sitemap-index', function () {
                return $this->sitemap->generateIndex(
                    [
                        new IndexItem(
                            Url::to(
                                [
                                    'books',
                                ], true,
                            ),
                        ),
                        new IndexItem(
                            Url::to(
                                [
                                    'book-chapters-index',
                                ], true,
                            ),
                        ),
                        new IndexItem(
                            Url::to(
                                [
                                    'pages',
                                ], true,
                            ),
                        ),
                        new IndexItem(
                            Url::to(
                                [
                                    'faq',
                                ], true,
                            ),
                        ),
                        new IndexItem(
                            Url::to(
                                [
                                    'faq-notes-index',
                                ], true,
                            ),
                        ),
                    ],
                );
            },
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionBooks(): Response
        {
            return $this->renderSitemap(
                'sitemap-books', function () {
                return $this->sitemap->generateMap(
                    array_map(static function (Book $book) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $book->link,
                                ], true,
                            ),
                            $book->updated_at,
                            ($book->rating < 50) ? MapItem::MONTHLY : MapItem::WEEKLY,
                            ($book->rating < 10) ? .1 : $book->rating / 100,
                        );
                    },
                        (array)Yii::$app->readModels->findBooks(Constant::STATUS_ACTIVE, false, false),
                    ),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            self::CACHE_TAG . Constant::BOOK_TYPE . '_' . 0,
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionBookChaptersIndex(): Response
        {
            return $this->renderSitemap(
                'sitemap-book-chapters-index', function () {
                return $this->sitemap->generateIndex(
                    array_map(static function ($start) {
                        return new IndexItem(
                            Url::toRoute(
                                [
                                    'book-chapters',
                                    'start' => $start * self::ITEMS_PER_PAGE,
                                ], true,
                            ),
                        );
                    }, range(0, (int)($this->chapters::count() / self::ITEMS_PER_PAGE))),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            self::CACHE_TAG . Constant::BOOK_TYPE . '_' . 0,
                            self::CACHE_TAG . Constant::CHAPTER_TYPE . '_' . 0,
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionBookChapters(int $start = 0): Response
        {
            return $this->renderSitemap(
                'sitemap-book-chapters' . $start,
                function () use ($start) {
                    return $this->sitemap->generateMap(
                        array_map(static function (
                            Chapter $chapter,
                        ) {
                            return new MapItem(
                                Url::toRoute(
                                    [
                                        $chapter->link,
                                    ], true,
                                ),
                                $chapter->updated_at,
                                MapItem::DAILY,
                            );
                        }, $this->chapters::findByRange(
                            $start,
                            self::ITEMS_PER_PAGE,
                        )),
                    );
                }, new TagDependency(
                [
                    'tags' => [
                        self::CACHE_TAG . Constant::BOOK_TYPE . '_' . 0,
                        self::CACHE_TAG . Constant::CHAPTER_TYPE . '_' . 0,
                    ],
                ],
            ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionRazdels(): Response
        {
            return $this->renderSitemap(
                'sitemap-razdels', function () {
                return $this->sitemap->generateMap(
                    array_map(static function (Razdel $razdel) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $razdel->link,
                                ], true,
                            ),
                            $razdel->updated_at,
                            ($razdel->rating < 50) ? MapItem::MONTHLY : MapItem::WEEKLY,
                            ($razdel->rating < 10) ? .1 : $razdel->rating / 100,
                        );
                    },
                        (array)$this->razdels->findModels(),
                    ),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            self::CACHE_TAG . Constant::RAZDEL_LABEL . '_' . 0,
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionRazdelProductsIndex(): Response
        {
            return $this->renderSitemap(
                'sitemap-razdel-products-index', function () {
                return $this->sitemap->generateIndex(
                    array_map(static function ($start) {
                        return new IndexItem(
                            Url::toRoute(
                                [
                                    'razdel-products',
                                    'start' => $start * self::ITEMS_PER_PAGE,
                                ], true,
                            ),
                        );
                    }, range(0, (int)($this->products::count() / self::ITEMS_PER_PAGE))),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            self::CACHE_TAG . Constant::RAZDEL_TYPE . '_' . 0,
                            self::CACHE_TAG . Constant::PRODUCT_TYPE . '_' . 0,
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionRazdelProducts(int $start = 0): Response
        {
            return $this->renderSitemap(
                'sitemap-razdel-products' . $start,
                function () use ($start) {
                    return $this->sitemap->generateMap(
                        array_map(static function (
                            Product $product,
                        ) {
                            return new MapItem(
                                Url::toRoute(
                                    [
                                        $product->link,
                                    ], true,
                                ),
                                $product->updated_at,
                                MapItem::DAILY,
                            );
                        }, $this->products::findByRange(
                            $start,
                            self::ITEMS_PER_PAGE,
                        )),
                    );
                }, new TagDependency(
                [
                    'tags' => [
                        self::CACHE_TAG . Constant::RAZDEL_TYPE . '_' . 0,
                        self::CACHE_TAG . Constant::PRODUCT_TYPE . '_' . 0,
                    ],
                ],
            ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionPages(): Response
        {
            return $this->renderSitemap('sitemap-pages', function () {
                return $this->sitemap->generateMap(
                    array_map(static function (Page $page) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $page->link,
                                ], true,
                            ),
                            $page->updated_at,
                            MapItem::WEEKLY,
                            .5,
                        );
                    },
                        (array)Yii::$app->readModels->findPages(Constant::STATUS_ACTIVE, false, false),
                    ),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            self::CACHE_TAG . Constant::PAGE_TYPE . '_' . 0,
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionFaqs(): Response
        {
            return $this->renderSitemap('sitemap-faqs', function () {
                return $this->sitemap->generateMap(
                    array_map(static function (Faq $faq) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $faq->link,
                                ], true,
                            ),
                            $faq->updated_at,
                            MapItem::WEEKLY,
                            .5,
                        );
                    },
                        (array)$this->faqs->findModels(),
                    ),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            self::CACHE_TAG . Constant::FAQ_TYPE . '_' . 0,
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionFootnotes(): Response
        {
            return $this->renderSitemap('sitemap-footnotes', function () {
                return $this->sitemap->generateMap(
                    array_map(static function (Footnote $footnote) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $footnote->link,
                                ], true,
                            ),
                            $footnote->updated_at,
                            MapItem::WEEKLY,
                            .5,
                        );
                    },
                        (array)$this->footnotes->findModels(),
                    ),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            self::CACHE_TAG . Constant::FOOTNOTE_TYPE . '_' . 0,
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        private function renderSitemap(
            string     $key,
            callable   $callback,
            Dependency $dependency = null,
        ): Response
        {
            return Yii::$app->response->sendContentAsFile(
                Yii::$app->cache->getOrSet(
                    $key, $callback, null, $dependency,
                ), Url::canonical(), [
                'mimeType' => 'application/xml',
                'inline'   => true,
            ],
            );
        }
        
    }
