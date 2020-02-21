<?php

use app\models\domains\SearchItem;
use yii\grid\ActionColumn;

$this->title = 'Копирование писем';

use app\models\Client;
use app\models\Flights;
use app\models\forms\CopyForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var CopyForm $copyForm */
/* @var ArrayDataProvider $dataProvider */
/* @var View $this */

$gridColumns = [
    'client.title',
    [
        'class' => ActionColumn::class,
        'template' => '{last} {prev}',
        'buttons' => [
                'last' => static function ($url, SearchItem $data) {
                    return Html::a($data->last->date, Url::to(['open-file', 'path' => $data->last->path]), ['class' => 'btn btn-default']);
                },
//                'prev' => static function (SearchItem $data) {
//                    return Html::a($data->prev->date, Url::to(['open-file', 'path' => $data->prev->path]));
//                }
        ],
    ],
];
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
                'value' => $copyForm->flightDate ?? date('yyyy-mm-dd'),
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
                <?= Html::submitButton('Скопировать', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
    <hr>
    <?= GridView::widget([
        'summary' => false,
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'showHeader'=> false,
    ]) ?>

</div>
