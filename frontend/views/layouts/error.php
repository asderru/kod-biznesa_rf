<?php
    
    use frontend\extensions\forms\ContactForm;

    use yii\web\View;
    
    /* @var $this View */
    /* @var $model core\edit\entities\Admin\Information */
    /* @var $contactForm ContactForm */
    /* @var $rootPage array */
    /* @var $firstPage array */
    /* @var $pageNotes1 array */
    /* @var $secondPage array */
    /* @var $thirdPage array */
    /* @var $pageNotes2 array */
    /* @var $photos array */
    /* @var $team array */
    /* @var $reviewsArray array */
    /* @var $textType int */
    
    $layoutId = '#frontend_views_site_index';
    
    $contactAddress = json_decode($model['contact_address'], true);
    $contactPhones  = json_decode($model['contact_phones'], true);
    
    // Получаем отдельные значения из адреса
    $postalCode      = $contactAddress['postalCode'];
    $streetAddress   = $contactAddress['streetAddress'];
    $addressCountry  = $contactAddress['addressCountry'];
    $addressLocality = $contactAddress['addressLocality'];
    $mainPage        = $rootPage['model'];
    
    
    // PrintHelper::print($team);

?>
