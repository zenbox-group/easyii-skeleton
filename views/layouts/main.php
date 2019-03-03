<?php

use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\easyii\models\Setting;
use yii\easyii\modules\shopcart\api\Shopcart;
use yii\helpers\Url;

$goodsCount = count(Shopcart::goods());
?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>
<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white border-bottom box-shadow">
    <div class="container position-relative">
        <button class="navbar-toggler btn btn-light border-secondary" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/">
            <b>ZenBox</b> Easyii
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?= Nav::widget([
                'options' => ['class' => 'navbar-nav mr-auto'],
                'items' => [
                    ['label' => 'Shop', 'url' => ['shop/index']],
                    ['label' => 'News', 'url' => ['news/index']],
                    ['label' => 'Articles', 'url' => ['articles/index']],
                    ['label' => 'Gallery', 'url' => ['gallery/index']],
                    ['label' => 'Guestbook', 'url' => ['guestbook/index']],
                    ['label' => 'FAQ', 'url' => ['faq/index']],
                    ['label' => 'Contact', 'url' => ['contact/index']],
                ],
            ]); ?>
            <div class="navbar-text text-dark mr-3">
                <b><?= Setting::get('admin_phone') ?></b>
            </div>
        </div>
        <a href="<?= Url::to('shopcart') ?>" class="btn btn-light border-secondary navbar-cart">
            <span class="fa fa-shopping-cart"></span>
            <span class="navbar-cart-badge badge badge-danger" id="navbar-cart-count"><?= $goodsCount ?></span>
        </a>
    </div>
</nav>
<main>
    <?php if($this->context->id != 'site') { ?>
        <div class="container"><?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => ['class' => 'breadcrumb bg-white mb-0 px-0']
        ])?></div>
    <?php } ?>
    <?= $content ?>
</main>
<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h5>ZenBox</h5>
                <small class="d-block mb-3 text-muted">&copy; <?= date('Y') ?></small>
            </div>
            <div class="col-md-3">
                <h5>Who We Are</h5>
                <ul class="list-unstyled text-small">
                    <li><?= Html::a('News', ['news/index'], ['class' => 'text-muted']) ?></li>
                    <li><?= Html::a('Articles', ['articles/index'], ['class' => 'text-muted']) ?></li>
                    <li><?= Html::a('FAQ', ['faq/index'], ['class' => 'text-muted']) ?></li>
                    <li><?= Html::a('Contact', ['contact/index'], ['class' => 'text-muted']) ?></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Need Help?</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="text-muted" href="tel:">Call Us</a></li>
                    <li><a class="text-muted" href="mailto:">Email Us</a></li>
                    <li><a class="text-muted" href="viber://chat?number=">Viber</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Community</h5>
                <p class="h3">
                    <a href="#" class="text-white" target="_blank"><span class="fa fa-vk"></span></a>
                    <a href="#" class="text-white" target="_blank"><span class="fa fa-youtube-play"></span></a>
                </p>
            </div>
        </div>
    </div>
</footer>
<?php $this->endContent(); ?>
