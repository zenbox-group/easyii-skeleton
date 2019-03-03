<?php

use yii\easyii\helpers\Image;
use yii\easyii\modules\gallery\api\Gallery;
use yii\easyii\modules\page\api\Page;
use yii\helpers\Url;

$page = Page::get('page-gallery');

$this->title = $page->seo('title', $page->model->title);
$this->params['breadcrumbs'][] = $page->model->title;
?>
<div class="container">
    <h1><?= $page->seo('h1', $page->title) ?></h1>
    <div class="card-deck">
        <?php foreach (Gallery::cats() as $album) { ?>
            <a href="<?= Url::to(['gallery/view', 'slug' => $album->slug]) ?>">
                <div class="card">
                    <img class="card-img-top" src="<?= Image::thumb($album->image, 320, 180) ?>" alt="<?= $album->title ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $album->title ?></h5>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>
