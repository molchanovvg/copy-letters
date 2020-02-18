<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Flights */

$this->title = 'Новый рейс';
$this->params['breadcrumbs'][] = ['label' => 'Рейсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="flights-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
