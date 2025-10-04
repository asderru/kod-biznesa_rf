<?php
    
    use core\edit\forms\Admin\ContactForm;
    
    /* @var $this yii\web\View */
    /* @var $model ContactForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_contact_create';
    
    $this->title = 'Создание контакта';
    
    $this->params['breadcrumbs'][] = ['label' => 'Контакты', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
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
