<?php

use app\models\Settings;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var yii\web\View $this */
/* @var ActiveDataProvider $dataProvider */

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-index">


    <p>
        <?= Html::a('Добавить настройку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'title',
            'label',
            'value',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>


</div>
