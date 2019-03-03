<?php

use yii\easyii\modules\gallery\api\Gallery;

$this->title = $album->seo('title', $album->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Gallery', 'url' => ['gallery/index']];
$this->params['breadcrumbs'][] = $album->model->title;
/**
 * @var \yii\easyii\modules\gallery\api\PhotoObject $photo
 */
?>
<div class="container">
<h1><?= $album->seo('h1', $album->title) ?></h1>

<?php if(count($photos)) { ?>
    <div class="row">
        <?php foreach($photos as $photo) { ?>
            <div class="col-md-3 mb-4">
                <a href="<?= $photo->image ?>" class=" card easyii-box" rel="album-<?= $photo->model->item_id ?>" title="<?= $photo->description ?>">
                    <img class="card-img-top" src="<?= $photo->thumb(320, 180, true) ?>" alt="<?= $photo->description ?>">
                </a>
            </div>
        <?php } ?>
        <?php Gallery::plugin() ?>
    </div>
<?php } else { ?>
    <p>Album is empty.</p>
<?php } ?>
<?= $album->pages() ?>
</div>
