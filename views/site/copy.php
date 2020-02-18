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
    <?= HTml::a('test', ['site/test'])?>
  <div class="row">
      <?php $form = ActiveForm::begin([
          'id' => 'login-form',
          //  'layout' => 'horizontal',
//        'fieldConfig' => [
//            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
//            'labelOptions' => ['class' => 'col-lg-11 control-label'],
//        ],
      'options' => [
              'class'=> 'form-horizontal'
      ],
      ]); ?>
      <div class="col-lg-6">
          <?= $form->field($copyForm, 'flightDate')->widget(DatePicker::class, [
              'type' => DatePicker::TYPE_INLINE,
              //'value' => '23-Feb-1982',
              'pluginOptions' => [
                  'format' => 'dd-mm-yyyy',
                  //     'multidate' => true,
                  'autoclose'=>true
              ]
//         'pluginOptions' => [
//        'autoclose'=>true

          ]) ?>
          <?= $form->field($copyForm, 'flight')->widget(Select2::class, [
              'data' => ArrayHelper::map(Flights::find()->andWhere(['is_active' => 1])->all(), 'id', 'title'),
              'hideSearch' => true,
          ]) ?>
      </div>
      <div class="col-lg-6">

          <?= $form->field($copyForm, 'clientList')->widget(Select2::class, [
              'data' => ArrayHelper::map(Client::find()->andWhere(['is_active' => 1])->all(), 'id', 'title'),
              'hideSearch' => true,
          ]) ?>
          <div class="form-group">
              <div class="col-lg-offset-1 col-lg-11">
                  <?= Html::submitButton('Скопировать', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
              </div>
          </div>
          <?php ActiveForm::end(); ?>

      </div>
  </div>


</div>
