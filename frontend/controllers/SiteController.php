<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Content\Review;
    use core\edit\entities\Library\Author;
    use core\edit\entities\Utils\Gallery;
    use core\edit\entities\Utils\Photo;
    use core\edit\useCases\ContactService;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\InformationReader;
    use core\read\readers\Content\NoteReader;
    use core\read\readers\Content\PageReader;
    use core\read\readers\Content\ReviewReader;
    use core\read\readers\Library\AuthorReader;
    use core\read\readers\Utils\GalleryReader;
    use core\read\readers\Utils\PhotoReader;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use Exception;
    use frontend\controllers\admin\MainController;
    use frontend\extensions\forms\ContactForm;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class SiteController extends MainController
    {
        public array $pageNotes = [];
        
        protected const int        TEXT_TYPE      = Information::TEXT_TYPE;
        protected const array      DEFAULT_FIELDS = Information::DEFAULT_FIELDS;
        
        
        private InformationReader $sites;
        private PageReader        $pages;
        private NoteReader        $notes;
        private GalleryReader     $gallery;
        private PhotoReader       $photos;
        private AuthorReader      $authors;
        private ReviewReader      $reviews;
        private ContactService    $service;
        
        public function __construct(
            $id,
            $module,
            InformationReader $sites,
            PageReader $pages,
            NoteReader $notes,
            GalleryReader $gallery,
            PhotoReader $photos,
            AuthorReader $authors,
            ReviewReader $reviews,
            ContactService $service,
            BreadcrumbsService $breadcrumbsService,
            MetaService $metaService,
            SchemaService $schemaService,
            $config = [],
        )
        {
            parent::__construct(
                $id, $module,
                $breadcrumbsService,
                $metaService,
                $schemaService,
                $config,
            );
            $this->sites              = $sites;
            $this->pages              = $pages;
            $this->notes              = $notes;
            $this->gallery            = $gallery;
            $this->photos             = $photos;
            $this->authors            = $authors;
            $this->reviews            = $reviews;
            $this->service            = $service;
            $this->breadcrumbsService = $breadcrumbsService;
            $this->metaService        = $metaService;
            $this->schemaService      = $schemaService;
        }
        
        #[ArrayShape([
            'error' => 'string[]',
        ])]
        public function actions(): array
        {
            return [
                'error' => [
                    'class'  => ErrorAction::class,
                    'layout' => '@app/views/layouts/blank',
                ],
            ];
        }
        
        public function behaviors(): array
        {
            return
                [
                    //  CacheHelper::index(),
                ];
        }
        
        public function actionIndex(): string|Response
        {
            $package      = $this->sites->getFullPackedSite();
            $rootPage     = $this->pages->getFullPackedRoot();
            $pages        = $this->pages->getMainArray(Page::FULL_PACK_FIELDS);
            $gallery      = $this->gallery->getModelById(2, Gallery::FULL_PACK_FIELDS);
            $photos       = $this->photos->getMediaByGallery($gallery, Photo::FULL_PACK_FIELDS);
            $team         = $this->authors->getArray(Author::TEXT_TYPE, Author::FULL_PACK_FIELDS);
            $reviews      = $this->reviews->getArray(Review::TEXT_TYPE, ['id']);
            $reviewsArray = [];
            foreach ($reviews as $review) {
                $reviewsArray[] = $this->reviews->getFullPackedReviewById($review['id']);
            }
            
            $pageNotes = [];
            foreach ($pages as $page) {
                $pageNotes[$page['id']] = $this->notes->getNotesArray($page['array_type'], $page['id']);
            }
            $form = new ContactForm();
            
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $this->service->send($form);
                    Yii::$app->session->setFlash('success', 'Спасибо за сообщение. Мы свяжемся с Вами в бижайшее время!');
                    return $this->goHome();
                }
                catch (\Exception $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('danger', 'Случилась ошибка при отправке сообщения. Попробуйте повторить позже.');
                }
                return $this->refresh();
            }
            $siteModel                       = current($package);
            $this->view->params['siteModel'] = $siteModel;
            
            return $this->render(
                '@app/views/site/index',
                [
                    'model'        => $siteModel,
                    'rootPage'     => $rootPage,
                    'firstPage'    => $pages[2],
                    'pageNotes1'   => $pageNotes[2],
                    'secondPage'   => $pages[3],
                    'pageNotes2'   => $pageNotes[3],
                    'thirdPage'    => $pages[4],
                    'gallery'      => $gallery,
                    'photos'       => $photos,
                    'team'         => $team,
                    'contactForm'  => $form,
                    'reviewsArray' => $reviewsArray,
                    'textType'     => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * @throws Exception
         * @throws Throwable
         */
        
        public function actionError(): string|Response
        {
            try {
                $randomNumber = random_int(1, 15);
            }
            catch (Exception $e) {
                // Логируем исключение, устанавливаем значение по умолчанию
                PrintHelper::exception('randomNumber', $e);
                $randomNumber = 1; // значение по умолчанию, если генерация случайного числа не удалась
            }
            
            $exception = Yii::$app->errorHandler->exception;
            
            if ($exception !== null) {
                // Обработка ошибки и отображение соответствующего представления
                $message = $exception->getMessage();
                
                return $this->render(
                    'error',
                    [
                        'exception' => $exception,
                        'message'   => $message,
                        'textType'  => self::TEXT_TYPE,
                    ],
                );
            }
            
            return $this->redirect(
                [
                    'index',
                ],
            );
        }
    }
