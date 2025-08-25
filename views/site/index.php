<?php

/**
 * @var yii\web\View $this
 * @var $form ShortLinkForm
 * */

use app\forms\ShortLinkForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Congratulations!</h1>

        <p class="lead">You can create qr code and short link</p>

    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php $activeForm = ActiveForm::begin(['action' => ['site/create'], 'options' => ['id' => 'short-link-form']]); ?>
            <?= $activeForm->field($form, 'name')->textInput(['value' => 'Google']) ?>
            <?= $activeForm->field($form, 'url')->textInput(['value' => 'https://www.google.com/']) ?>
            <?= Html::button('Create', ['class' => 'btn btn-primary', 'id' => 'create-link']) ?>
            <?php ActiveForm::end() ?>
            <div id="result"></div>


        </div>
    </div>

</div>

<?php
$this->registerJs(<<<JS
    $("#short-link-form").on("keydown", "input, textarea", function (e) {
    if (e.key === "Enter") {
        e.preventDefault(); 
        $("#create-link").trigger("click"); 
    }
});
    $("#create-link").on('click', function () {
        var form = $("#short-link-form");
        var data = form.serialize();
        var result = $("#result");
        
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: data,
            success: function (response) {
               if (response.success) {
                    $("#result").html(`
                        <p style='color:green;'> \${JSON.stringify(response.errors ?? response.message)} </p>
                        <p><a href="\${response.url}" target="_blank">\${response.url}</a></p>
                        <img src="\${response.qr_file}" style="max-width: 200px;">
                    `);
                } else {
                    $("#result").html("<p style='color:red;'>Xatolik: " + JSON.stringify(response.errors ?? response.message) + "</p>");
                }
                console.log(response);
            },
            error: function (response) {
                console.log(response);
            }
        })
    })
JS
)
?>
