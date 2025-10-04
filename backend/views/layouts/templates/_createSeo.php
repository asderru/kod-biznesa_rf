<?php
    
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\base\Model;
    
    /* @var $model Model|Razdel|Section|Book|Category|Page|Information|Group */
    
    const TEMPLATES_CREATE_SEO_LAYOUT = '#layouts_templates_createSeo';
    echo PrintHelper::layout(TEMPLATES_CREATE_SEO_LAYOUT);
    
    try {
        $modelUrl = $model->getFullUrl();
    }
    catch (Exception $e) {
    
    }
    
    $parentTitle = match ($model::TEXT_TYPE) {
        Constant::RAZDEL_TYPE   => 'Родительский раздел',
        Constant::SECTION_TYPE  => 'Родительская рубрика',
        Constant::BOOK_TYPE     => 'Родительский том',
        Constant::CATEGORY_TYPE => 'Родительский блог',
        Constant::PAGE_TYPE     => 'Родительская страница',
        Constant::SITE_TYPE     => 'Родительский сайт',
        Constant::GROUP_TYPE    => 'Родительский форум',
        
    };
    
    $childrenTitle = match ($model::TEXT_TYPE) {
        Constant::RAZDEL_TYPE   => 'Вложенные разделы',
        Constant::SECTION_TYPE  => 'Вложенные рубрики',
        Constant::BOOK_TYPE     => 'Вложенные тома',
        Constant::CATEGORY_TYPE => 'Вложенные блоги',
        Constant::PAGE_TYPE     => 'Вложенные страницы',
        Constant::SITE_TYPE     => 'Вложенные сайты',
        Constant::GROUP_TYPE    => 'Вложенные форумы',
    };
