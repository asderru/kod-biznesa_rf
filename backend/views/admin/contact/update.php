<?php
    
    use core\edit\entities\Admin\Information;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Admin\ContactForm */
    /* @var $contact core\edit\entities\Admin\Contact */
    /* @var $site Information */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_contact_update';
    
    $this->title                   = 'Правка контакта';
    $this->params['breadcrumbs'][] = ['label' => 'Контакты', 'url' => ['index']];
    
    $this->params['breadcrumbs'][] = [
        'label' => $site->id,
        'url'   => [
            'view',
            'id' => $contact->id,
        ],
    ];
    $this->params['breadcrumbs'][] = 'Правка';
    
    echo $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => LAYOUT_ID,
        ],
    );
    
    echo $this->render(
        '@app/views/admin/contact/_form',
        [
            'model' => $model,
        ],
    );
