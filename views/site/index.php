<?php

use yii\bootstrap4\Carousel;
use yii\easyii\modules\article\api\Article;
use yii\easyii\modules\carousel\api\Carousel as ApiCarusel;
use yii\easyii\modules\gallery\api\Gallery;
use yii\easyii\modules\guestbook\api\Guestbook;
use yii\easyii\modules\news\api\News;
use yii\easyii\modules\page\api\Page;
use yii\easyii\modules\text\api\Text;
use yii\helpers\Html;

$page = Page::get('page-index');

$this->title = $page->seo('title', $page->model->title);
?>

<?php
/** @var \yii\easyii\modules\carousel\api\CarouselObject[] $items */
$items = ApiCarusel::items();
echo Carousel::widget([
    'items' => array_map(function ($item) {
        /** @var yii\easyii\modules\carousel\api\CarouselObject $item */
        return [
            'content' => $item->link ?
                '<a href="' . $item->link . '"><img src="' . $item->thumb(1140, 320) . '" class="d-block w-100" alt="' . $item->title . '"></a>' :
                '<img src="' . $item->thumb(1140, 320) . '" class="d-block w-100" alt="' . $item->title . '">'
            ,
            'caption' => '
            <h5>' . $item->title . '</h5>
            <p>' . $item->text . '</p>
            ',
        ];
    }, $items)
])
?>

<div class="container text-center my-3">
    <p><?= $page->text ?></p>
</div>

<div class="row bg-light m-0 my-3">
    <?php
    foreach(Gallery::last(6) as $photo) {
        $img = Html::img($photo->thumb(320, 200), [
            'class' => 'img-fluid'
        ]);
        $a = Html::a($img, $photo->image, [
            'class' => 'easyii-box img-fluid',
            'rel' => 'photo-'.$photo->model->item_id,
            'title' => $photo->description
        ]);
        ?>
        <div class="col-md-2 m-0 p-0">
            <?= $a ?>
        </div>
        <?php
    }
    Gallery::plugin();
    ?>
</div>

<div class="container my-3">
    <h2>Last news</h2>
    <blockquote class="text-left">
        <?= Html::a(News::last()->title, ['news/view', 'slug' => News::last()->slug]) ?>
        <br/>
        <?= News::last()->short ?>
    </blockquote>
</div>

<div class="bg-light py-3 mb-3">
    <div class="container">
        <div class="row text-left">
            <?php $article = Article::last(1, ['category_id' => 1]); ?>
            <div class="col-md-2">
                <?= Html::img($article->thumb(160, 120)) ?>
            </div>
            <div class="col-md-10 text-left">
                <?= Html::a($article->title, ['articles/view', 'slug' => $article->slug]) ?>
                <br/>
                <?= $article->short ?>
            </div>
        </div>
    </div>
</div>

<div class="container my-3">
    <h2>Last reviews</h2>
    <br/>
    <div class="row text-left">
        <?php foreach(Guestbook::last(2) as $post) : ?>
            <div class="col-md-6">
                <b><?= $post->name ?></b>
                <p class="text-muted"><?= $post->text ?></p>
            </div>
        <?php endforeach;?>
    </div>
</div>
