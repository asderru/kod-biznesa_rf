<?php
    
    namespace frontend\extensions\traits;
    
    
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Forum\Thread;
    use core\edit\entities\Library\Author;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Seo\Faq;
    use core\edit\entities\Seo\Footnote;
    use core\edit\entities\Shop\Brand;
    use core\edit\entities\User\Person;
    use core\helpers\FormatHelper;
    use core\tools\Constant;
    use core\tools\services\SeoClean;
    use Exception;
    use nirvana\jsonld\JsonLDHelper;
    use Throwable;
    use Yii;
    use yii\data\DataProviderInterface;
    use yii\helpers\Url;
    
    trait FrontendSchemaTrait
    {
        /**
         * @throws Exception
         */
        public static function generateSiteIndexSchema(
            Information $model,
        ): void
        {
            $seoClean = new SeoClean();
            $keywords = $seoClean->getKeywords($model);
            
            // Создаем основной объект JSON-LD
            $jsonLdData = [
                '@context'      => 'https://schema.org',
                '@type'         => 'WebSite',
                'url'           => Url::home(true),
                'name'          => '110-kod-biznesa-rf_col-12.webp.ru',
                'description'   => strip_tags($model->description),
                'inLanguage'    => 'ru',
                'keywords'      => $keywords,
                'datePublished' => FormatHelper::asDateTime($model->created_at, 'php:Y-m-d\TH:i:s'),
                'dateModified'  => FormatHelper::asDateTime($model->updated_at, 'php:Y-m-d\TH:i:s'),
                'author'        => [
                    '@type'    => 'Person',
                    'name'     => 'Асдер эС',
                    'url'      => Url::home(true),
                    'jobTitle' => 'Писатель',
                ],
            ];
            
            // Добавляем JSON-LD разметку в документ
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Exception
         */
        public static function generateArticlesSchema(
            Author|Brand|Category|Faq|Page $root, array $models,
        ): void
        {
            $keywords = (new SeoClean())->getKeywords($root);
            
            $articles = [];
            
            $i = 0;
            foreach ($models as $model) {
                $i++;
                // Создаем разметку для каждого продукта
                $articleSchema = (object)[
                    '@type'    => 'ListItem',
                    'position' => $i,
                    'item'     => (object)[
                        '@type'       => 'Article',
                        'name'        => $model->name,
                        'description' => strip_tags($model->description),
                        'url'         => $model->getFullUrl(),
                    ],
                    // Добавьте дополнительные свойства продукта, если есть
                ];
                
                // Добавляем разметку продукта в массив $articles
                $articles[] = $articleSchema;
            }
            
            // Создаем основной объект JSON-LD
            $jsonLdData = (object)[
                '@context'         => 'https://schema.org/',
                'mainEntityOfPage' => (object)[
                    '@type'           => 'ItemList',
                    'name'            => $root->name,
                    'description'     => $root->description,
                    'itemListElement' => $articles, // Добавляем массив продуктов в разметку
                    'keywords'        => $keywords,
                ],
            ];
            
            // Добавляем JSON-LD разметку в документ
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Throwable
         */
        public static function generateArticleSchema(
            Brand|Faq|Page|Category|Footnote $model,
        ): void
        {
            $author = $model->author ?? $model->site;
            
            $type = (method_exists($model, 'author') && $model->author) ? 'Person' : 'Organisation';
            
            $keywords = (new SeoClean())->getKeywords($model);
            
            $jsonLdData = (object)[
                '@context'         => (object)[
                    '@vocab' => 'https://schema.org/',
                ],
                'mainEntityOfPage' => (object)[
                    '@type'         => 'Article',
                    'headline'      => $model->title ?? $model->name,
                    'description'   => strip_tags($model->description),
                    'datePublished' => FormatHelper::asDateTime($model->created_at, 'php:Y-m-d\TH:i:s'),
                    'dateModified'  => FormatHelper::asDateTime($model->updated_at, 'php:Y-m-d\TH:i:s'),
                    'image' => $model->getImageUrl(6),
                    'keywords'      => $keywords,
                ],
                'author'           => (object)[
                    '@type' => $type,
                    'name'  => $author->name,
                    'url'   => $author->fullUrl,
                ],
            ];
            
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Exception
         */
        public static function generateBooksSchema(
            Book $root, array $models,
        ): void
        {
            $keywords = (new SeoClean())->getKeywords($root);
            
            $books = [];
            
            foreach ($models as $book) {
                // Создаем разметку для каждого продукта
                $bookSchema = (object)[
                    '@id'        => $book->getFullUrl(),
                    '@type'      => 'Book',
                    'name'       => $book->name,
                    'author'     => $book->author->name,
                    'inLanguage' => 'ru',
                    // Добавьте дополнительные свойства продукта, если есть
                ];
                
                // Добавляем разметку продукта в массив $books
                $books[] = $bookSchema;
            }
            
            // Создаем основной объект JSON-LD
            $jsonLdData = (object)[
                '@context' => 'https://schema.org/',
                'graph'    => $books,
                'keywords' => $keywords,
            ];
            
            // Добавляем JSON-LD разметку в документ
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Exception
         */
        
        public static function generateBookSchema(
            Book $model,
        ): void
        {
            $author = $model->getAuthor()->one() ?? $model->site;
            
            $type = (method_exists($model, 'getAuthor') && $model->author) ? 'Person' : 'Organisation';
            
            $keywords = (new SeoClean())->getKeywords($model);
            
            // Создаем основной объект JSON-LD
            $jsonLdData = (object)[
                '@context'         => 'https://schema.org/',
                'mainEntityOfPage' => (object)[
                    '@type'       => 'Book',
                    'name'        => $model->name,
                    'url' => Url::home(true) . Constant::BOOK_PREFIX . '/' . $model->slug,
                    'description' => strip_tags($model->description),
                    'genre'       => 'fictional',
                    'inLanguage'  => 'ru',
                    'author'      => (object)[
                        '@type' => $type,
                        'name'  => $author->name,
                        'url'   => $author->fullUrl,
                    ],
                    'keywords'    => $keywords,
                ],
            ];
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Exception
         */
        
        public static function generateMapSchema(
            string $label,
        ): void
        {
            // Создаем основной объект JSON-LD
            $jsonLdData = (object)[
                '@context'         => 'https://schema.org/',
                'mainEntityOfPage' => (object)[
                    '@type' => 'SiteNavigationElement',
                    'name'  => $label,
                ],
            ];
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Exception
         */
        
        public static function generateChaptersSchema(Book $model, array $chapters, int|null $totalCount = 10): void
        {
            // Создаем разметку для книги
            $bookSchema = (object)[
                '@type'         => 'Book',
                '@id'           => $model->getFullUrl(),  // URL страницы книги
                'name'          => $model->name,
                'author'        => (object)[
                    '@type' => 'Person',
                    'name'  => $model->author->name,
                ],
                'datePublished' => Yii::$app->formatter->asDatetime($model->created_at, 'php:Y-m-d'),  // Дата публикации книги
                'publisher'     => (object)[
                    '@type' => 'Organization',
                    'name'  => 'Qwetru Print',  // Замените на название издательства
                ],
                'numberOfPages' => $totalCount,  // Добавьте количество страниц
            ];
            
            // Создаем массив элементов списка (главы книги)
            $chaptersList = [];
            $i            = 0;
            foreach ($chapters as $chapter) {
                $i++;
                $chaptersList[] = (object)[
                    '@type'    => 'ListItem',
                    'position' => $i,
                    'url'  => $chapter->getFullUrl(),  // URL главы
                    'name' => $chapter->title,  // Название главы
                ];
            }
            
            // Создаем разметку для списка глав (ItemList)
            $itemListSchema = (object)[
                '@type'           => 'ItemList',
                'name'            => 'Список глав книги ' . $model->name,
                'itemListOrder'   => 'http://schema.org/ItemListOrderAscending',
                'numberOfItems'   => count($chapters),  // Количество отображаемых глав
                'itemListElement' => $chaptersList,  // Добавляем массив глав в разметку
            ];
            
            // Объединяем схемы книги и списка глав в один граф
            $jsonLdData = (object)[
                '@context' => 'https://schema.org',
                '@graph'   => [$bookSchema, $itemListSchema],
            ];
            
            // Добавляем разметку JSON-LD на страницу
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Exception
         */
        public static function generateChapterSchema(Chapter $model): void
        {
            $book          = $model->book;
            $authorName    = $book->author->name;
            $bookUrl       = $book->fullUrl;
            $chapterUrl    = $model->fullUrl;
            $datePublished = FormatHelper::asDateTime($book->created_at, 'php:Y-m-d\TH:i:s');
            
            $jsonLdData = (object)[
                '@context' => 'https://schema.org/',
                '@graph' => [
                    (object)[
                        '@type'         => 'Book',
                        '@id'           => $bookUrl,
                        'author'        => (object)[
                            '@type' => 'Person',
                            'name'  => $authorName,
                        ],
                        'name'          => $book->name,
                        'datePublished' => $datePublished,
                        'publisher'     => (object)[
                            '@type' => 'Organization',
                            'name'  => 'Qwetru Print',
                        ],
                    ],
                    (object)[
                        '@type'    => 'Chapter',
                        '@id'      => $chapterUrl,
                        'isPartOf' => (object)[
                            '@id' => $bookUrl,
                        ],
                        'name'     => $model->title,
                        'position' => $model->sort, // Укажите правильную позицию главы
                    ],
                ],
            ];
            
            JsonLDHelper::add($jsonLdData);
        }
        
        
        /**
         * @throws Exception
         */
        public static function generateFootnotesSchema(
            Footnote $model, DataProviderInterface $dataProvider,
        ): void
        {
            $site = $model->site;
            
            $keywords = (new SeoClean())->getKeywords($model);
            $footnotes = [];
            
            $i = 0;
            foreach ($dataProvider->models as $footnote) {
                $i++;
                // Создаем разметку для каждого продукта
                $footnoteSchema = (object)[
                    '@type'    => 'ListItem',
                    'position' => $i,
                    'item'     => (object)[
                        '@type'       => 'Footnote',
                        'name'        => $footnote->name,
                        'description' => $footnote->description,
                        'url'         => $footnote->getFullUrl(),
                    ],
                    // Добавьте дополнительные свойства продукта, если есть
                ];
                
                // Добавляем разметку продукта в массив $footnotes
                $footnotes[] = $footnoteSchema;
            }
            
            // Создаем основной объект JSON-LD
            $jsonLdData = (object)[
                '@context'         => 'https://schema.org/',
                'mainEntityOfPage' => (object)[
                    '@type'           => 'ItemList',
                    'name'            => $model->name,
                    'description'     => strip_tags($model->description),
                    'provider'        => (object)[
                        '@type' => 'Organization',
                        'name'  => $site->name,
                        'url'   => $site->getFullUrl(),
                        'logo'  => $site->getLogo(),
                    ],
                    'contactPoint'    => (object)[
                        '@type'       => 'ContactPoint',
                        'telephone'   => $site->getPhone(),
                        'contactType' => 'customer service',
                    ],
                    'itemListElement' => $footnotes, // Добавляем массив продуктов в разметку
                    'keywords'        => $keywords,
                ],
            ];
            
            // Добавляем JSON-LD разметку в документ
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Exception|Throwable
         */
        
        public static function generateFootnoteSchema(Footnote $model): void
        {
            $site      = $model->site;
            $priceData = [
                '@type'         => 'Offer',
                'price'         => $model->price,
                'priceCurrency' => 'RUB',
            ];
            $price     = $model->price ? ['offers' => $priceData] : null;
            
            $keywords = (new SeoClean())->getKeywords($model);
            
            $jsonLdData = (object)[
                '@context'         => (object)[
                    '@vocab' => 'https://schema.org/',
                ],
                'mainEntityOfPage' => (object)[
                    '@type'        => 'Service',
                    'name'         => $model->name,
                    'description'  => strip_tags($model->description),
                    'image' => $model->getImageUrl(6),
                    'areaServed'   => 'World',
                    $price,
                    'provider'     => (object)[
                        '@type' => 'Organization',
                        'name'  => $site->name,
                        'url'   => $site->getFullUrl(),
                        'logo'  => $site->getLogo(),
                    ],
                    'contactPoint' => (object)[
                        '@type'       => 'ContactPoint',
                        'telephone'   => $site->getPhone(),
                        'contactType' => 'customer service',
                    ],
                    'keywords'     => $keywords,
                ],
            ];
            
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Exception|Throwable
         */
        
        public static function generatePersonSchema(Author|Person $model): void
        {
            
            $keywords = (new SeoClean())->getKeywords($model);
            
            $jsonLdData = (object)[
                '@context'         => (object)[
                    '@vocab' => 'https://schema.org/',
                ],
                '@type'            => 'ProfilePage',
                'mainEntityOfPage' => (object)[
                    '@type'       => 'Person',
                    'name'        => $model->name,
                    'description' => strip_tags($model->description),
                    'image' => $model->getImageUrl(6),
                ],
                'keywords'         => $keywords,
            ];
            
            JsonLDHelper::add($jsonLdData);
        }
        
        
        /**
         * @throws Exception
         */
        public static function generateGroupsSchema(Group $root, array $forumItems): void
        {
            $keywords = (new SeoClean())->getKeywords($root);
            
            // Создаем основной объект JSON-LD
            $jsonLdData = [
                '@context'         => 'https://schema.org/',
                '@type'            => 'CollectionPage',
                'mainEntityOfPage' => [
                    '@type'       => 'WebPage',
                    'name'        => $root->name,
                    'description' => $root->description,
                ],
                'headline'         => 'Список форумов',
                'description'      => $root->description,
                'keywords'         => $keywords,
                'itemListElement'  => [
                    '@type'           => 'ItemList',
                    'itemListElement' => $forumItems,
                ],
            ];
            
            // Добавляем JSON-LD разметку в документ
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Exception
         */
        public function generateGroupSchema(Group $model): void
        {
            $keywords = (new SeoClean())->getKeywords($model);
            
            // Преобразуем даты в формат ISO 8601
            $dateModified  = date(DATE_ISO8601, strtotime($model->updated_at));
            $datePublished = date(DATE_ISO8601, strtotime($model->created_at));
            
            // Создаем основной объект JSON-LD
            $jsonLdData = [
                '@context'             => 'https://schema.org/',
                '@type'                => 'DiscussionForumPosting',
                'mainEntityOfPage'     => [
                    '@type'       => 'WebPage',
                    'name'        => $model->name,
                    'description' => $model->description,
                    'url' => Url::to([$model->link], true),
                ],
                'headline'             => $model->name,
                'description'          => $model->description,
                'keywords'             => $keywords,
                'articleSection'       => 'Forum',
                'dateModified'         => $dateModified,
                'datePublished'        => $datePublished,
                'url'                  => Url::to([$model->link], true),
                'author'               => [
                    '@type' => 'Organization',
                    'name' => Yii::$app->name,
                ],
                'interactionStatistic' => [
                    [
                        '@type'                => 'InteractionCounter',
                        'interactionType'      => [
                            '@type' => 'https://schema.org/WriteAction',
                        ],
                        'userInteractionCount' => $model->getThreadsCount(),
                    ],
                ],
            ];
            
            // Добавляем JSON-LD разметку в документ
            JsonLDHelper::add($jsonLdData);
        }
        
        
        /**
         * @throws Exception
         */
        public static function generateThreadsSchema(Group $root, array $forumItems): void
        {
            $keywords = (new SeoClean())->getKeywords($root);
            
            
            // Создаем основной объект JSON-LD
            $jsonLdData = [
                '@context'         => 'https://schema.org/',
                '@type'            => 'CollectionPage',
                'mainEntityOfPage' => [
                    '@type'       => 'WebPage',
                    'name'        => $root->name,
                    'description' => $root->description,
                ],
                'headline'         => 'Сообщения',
                'description'      => $root->description,
                'keywords'         => $keywords,
                'itemListElement'  => [
                    '@type'           => 'ItemList',
                    'itemListElement' => $forumItems,
                ],
            ];
            
            // Добавляем JSON-LD разметку в документ
            JsonLDHelper::add($jsonLdData);
        }
        
        /**
         * @throws Exception
         */
        public function generateThreadSchema(Thread $model): void
        {
            $keywords = (new SeoClean())->getKeywords($model);
            
            // Создаем основной объект JSON-LD
            $jsonLdData = [
                '@context'             => 'https://schema.org/',
                '@type'                => 'DiscussionForumPosting',
                'mainEntityOfPage'     => [
                    '@type'       => 'WebPage',
                    'name'        => $model->title,
                    'description' => $model->content,
                ],
                'headline'             => $model->title,
                'articleBody'          => $model->content,
                'keywords'             => $keywords,
                'articleSection'       => $model->forum->name, // Предполагаем, что у треда есть связь с форумом
                'dateModified'         => $model->updated_at,
                'datePublished'        => $model->created_at,
                'url'                  => Url::to(['thread/view', 'id' => $model->id], true),
                'author'               => [
                    '@type' => 'Person',
                    'name'  => $model->author->username, // Предполагаем, что у треда есть автор
                ],
                'interactionStatistic' => [
                    [
                        '@type'                => 'InteractionCounter',
                        'interactionType'      => 'https://schema.org/CommentAction',
                        'userInteractionCount' => $model->getCommentsCount(),
                    ],
                    [
                        '@type'                => 'InteractionCounter',
                        'interactionType'      => 'https://schema.org/ViewAction',
                        'userInteractionCount' => $model->views, // Предполагаем, что у треда есть счетчик просмотров
                    ],
                ],
            ];
            
            // Если есть родительский форум, добавляем информацию о нем
            if ($model->forum) {
                $jsonLdData['isPartOf'] = [
                    '@type' => 'DiscussionForumPosting',
                    'name'  => $model->forum->name,
                    'url'   => Url::to(['forum/view', 'id' => $model->forum->id], true),
                ];
            }
            
            // Добавляем JSON-LD разметку в документ
            JsonLDHelper::add($jsonLdData);
        }
        
        public static function generateGridViewSchema(
            Faq|Footnote|Page $root,
            DataProviderInterface $dataProvider,
            int                   $maxItems = 100,
        ): void
        {
            $keywords = (new SeoClean())->getKeywords($root);
            $articles = [];
            $models   = $dataProvider->getModels();
            
            $i = 0;
            foreach ($models as $model) {
                $i++;
                if ($i > $maxItems) break; // Ограничиваем количество элементов
                
                $articleSchema = (object)[
                    '@type'    => 'ListItem',
                    'position' => $i,
                    'item'     => (object)[
                        '@type'       => 'Article',
                        'name'        => $model->name,
                        'description' => strip_tags($model->description),
                        'url' => $model->link,
                    ],
                ];
                $articles[]    = $articleSchema;
            }
            
            $jsonLdData = (object)[
                '@context'         => 'https://schema.org/',
                'mainEntityOfPage' => (object)[
                    '@type'           => 'ItemList',
                    'name'            => $root->name,
                    'description'     => $root->description,
                    'itemListElement' => $articles,
                    'keywords'        => $keywords,
                    'numberOfItems'   => count($articles),
                ],
            ];
            
            JsonLDHelper::add($jsonLdData);
        }
        
    }
