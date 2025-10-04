<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\Html;
    use yii\web\JqueryAsset;
    
    /* @var $this yii\web\View */
    /* @var $model array */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_contact_base_layout';
    
    $this->title                   = 'База данных. Таблица Информация';
    $this->params['breadcrumbs'][] = ['label' => 'Контакты', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
        ButtonHelper::update($model['id'], 'Редактировать'),
        ButtonHelper::create(),
    ];
    
    $target         = ($model['site_id'] === Parametr::siteId()) ? '_self' : '_blank';
    
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
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_viewHeader-start',
        [
            'id'      => $model['id'],
            'title'   => $this->title,
            'status'  => Constant::STATUS_ACTIVE,
            'buttons' => $buttons,
        ],
    )
?>

    <div class='card-body mb-2 p-2'>
<pre><code><?= htmlspecialchars(print_r($model, true)) ?></code></pre>
    </div>

<?php
    echo '</div>';
