<?php

namespace app\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\View;
use yii\web\YiiAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class NotifyAsset extends AssetBundle
{
    public $sourcePath = '@sources';

    public $js = [
        'js/bootstrap-notify/bootstrap-notify.min.js',
    ];

    public $depends = [
        BootstrapAsset::class,
    ];
    public $jsOptions = ['position' => View::POS_END];
}
