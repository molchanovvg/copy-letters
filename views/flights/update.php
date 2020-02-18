<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Flights */

$this->title = 'Редактировать рейсы: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Рейсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="flights-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
