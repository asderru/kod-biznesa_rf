<?php
    
    namespace frontend\controllers;
    
    use core\edit\editors\Content\NoteEditor;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Content\Note;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Content\Review;
    use core\edit\entities\Library\Author;
    use core\edit\entities\Utils\Gallery;
    use core\edit\entities\Utils\Photo;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\InformationReader;
    use core\read\readers\Content\NoteReader;
    use core\read\readers\Content\PageReader;
    use core\read\readers\Content\ReviewReader;
    use core\read\readers\Library\AuthorReader;
    use core\read\readers\Utils\GalleryReader;
    use core\read\readers\Utils\PhotoReader;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use Exception;
    use Throwable;
    use Yii;
    use yii\helpers\ArrayHelper;
    use yii\web\Controller;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class SiteController extends Controller
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
        )
        {
            parent::__construct(
                $id, $module,
            );
            $this->sites   = $sites;
            $this->pages   = $pages;
            $this->notes   = $notes;
            $this->gallery = $gallery;
            $this->photos  = $photos;
            $this->authors = $authors;
            $this->reviews = $reviews;
        }
        
        public function actions(): array
        {
            return [
                'error' => [
                    'class'  => ErrorAction::class,
                    'layout' => '@app/views/layouts/blank',
                ],
            ];
        }
        
        public function actionIndex(): string|Response
        {
            $package      = $this->sites->getFullPackedSite(Information::FULL_PACK_FIELDS);
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
            $notes = NoteEditor::getArray(
                Constant::SITE_TYPE, Parametr::siteId(), Parametr::siteId(), Note::FULL_PACK_FIELDS,
            );
            ArrayHelper::multisort($notes, 'id');
            $model = current($package);
            
         // PrintHelper::print($team);
            
            return $this->render(
                '@app/views/site/index',
                [
                    'model'        => $model,
                    'rootPage'     => $rootPage,
                    'notes'        => $notes,
                    'firstPage'    => $pages[2],
                    'pageNotes1'   => $pageNotes[2],
                    'secondPage'   => $pages[3],
                    'pageNotes2'   => $pageNotes[3],
                    'thirdPage'    => $pages[4],
                    'gallery'      => $gallery,
                    'photos'       => $photos,
                    'team'         => $team,
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
