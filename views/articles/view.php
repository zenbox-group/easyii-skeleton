<?php
use yii\easyii\modules\article\api\Article;
use yii\helpers\Url;

$this->title = $article->seo('title', $article->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['articles/index']];
$this->params['breadcrumbs'][] = ['label' => $article->cat->title, 'url' => ['articles/cat', 'slug' => $article->cat->slug]];
$this->params['breadcrumbs'][] = $article->model->title;
?>
<div style="background-image: url('<?= $article->thumb(1280, 320) ?>'); background-size: cover">
<section class="jumbotron text-center header-jumbotron landing-header text-white" style="background: rgba(0,0,0,0.3)">
    <div class="container">
        <h1 class="jumbotron-heading"><?= $article->seo('h1', $article->title) ?></h1>
        <p class="lead"><?= $article->short ?></p>
    </div>
</section>
</div>
<div class="container">
    <?= $article->text ?>

    <?php if(count($article->photos)) : ?>
        <div>
            <h4>Photos</h4>
            <?php foreach($article->photos as $photo) : ?>
                <?= $photo->box(100, 100) ?>
            <?php endforeach;?>
            <?php Article::plugin() ?>
        </div>
        <br/>
    <?php endif; ?>
    <p>
        <?php foreach($article->tags as $tag) : ?>
            <a href="<?= Url::to(['/articles/cat', 'slug' => $article->cat->slug, 'tag' => $tag]) ?>" class="label label-info"><?= $tag ?></a>
        <?php endforeach; ?>
    </p>

    <small class="text-muted">Views: <?= $article->views?></small>
</div>