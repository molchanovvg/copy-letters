<?php

$this->title = 'Копирование писем';

use app\models\Client;
use app\models\Flights;
use app\models\forms\CopyForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var CopyForm $copyForm */
?>
<div class="site-index">

    <div class="row">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => [
                'class' => 'form-horizontal col-lg-11',
                //  'data-pjax' => true,
            ],
            'fieldConfig' => [
                'template' => "<div class='col-lg-4'>{label}</div>\n<div class='col-lg-7'>{input}</div>\n<div class='col-lg-12 col-lg-offset-3'>{error}</div>",
                'labelOptions' => ['class' => ''],
            ],
        ]); ?>
        <div class="col-lg-6">
            <?= $form->field($copyForm, 'flightDate')->widget(DatePicker::class, [
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'value' => $copyForm->flightDate,
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true,
                ],
            ]) ?>
            <?= $form->field($copyForm, 'flightId')->widget(Select2::class, [
                'data' => ArrayHelper::map(Flights::find()->andWhere(['is_active' => 1])->all(), 'id', 'title'),
                'hideSearch' => true,
            ]) ?>
            <?= $form->field($copyForm, 'clientId')->widget(Select2::class, [
                'data' => ArrayHelper::map(Client::find()->andWhere(['is_active' => 1])->all(), 'id', 'title'),
                'hideSearch' => true,
            ]) ?>
        </div>
        <div class="col-lg-6">

            <div class="form-group">
                <?= Html::submitButton('Скопировать', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
    <hr>


</div>
