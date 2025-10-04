<?php
    
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $model core\edit\forms\SortForm */

?>

<!-- Modal -->
<div class='modal fade' id='sortModal' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1'
     aria-labelledby='staticBackdropLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <?php
                $form = ActiveForm::begin()
            ?>
            <div class='modal-header'>
                <h5 class='modal-title' id='sortModalLabel'>Укажите новый порядковый номер</h5>
                <button type='button' class='btn-sm btn-close' data-bs-dismiss='modal' aria-label='Закрыть'></button>
            </div>
            <div class='modal-body'>
                
                <?= Html::activeHiddenInput(
                    $model, 'id',
                    [
                        'value' => '',
                    ],
                )
                ?>
                
                <?= $form
                    ->field($model, 'sort')
                    ->textInput(
                        [
                            'type'     => 'number',
                            'min'      => 1,
                            'required' => true,
                        ],
                    )
                    ->label(
                        false,
                    )
                ?>

            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Закрыть</button>
                <?= ButtonHelper::submitSort()
                ?>
            </div>
            
            <?php
                ActiveForm::end();
            ?>
        </div>
    </div>
</div>

<script>
    var sortModal = document.getElementById('sortModal')
    sortModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget
        // Extract info from data-bs-* attributes
        var recipient = button.getAttribute('data-bs-whatever')
        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        var modalTitle = sortModal.querySelector('.modal-title')
        var modalBodyInput = sortModal.querySelector('.modal-body input')

        modalTitle.textContent = 'Сменить порядковый номер на:'
        modalBodyInput.value = recipient
    })
</script>
