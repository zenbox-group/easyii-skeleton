<?php
namespace App\Assets;

use rmrevin\yii\fontawesome\AssetBundle;
use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\web\YiiAsset;

class AppAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/views/layouts/media';
    public $css = [
        'css/styles.css',
    ];
    public $js = [
        'js/scripts.js'
    ];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        AssetBundle::class,
    ];
}
