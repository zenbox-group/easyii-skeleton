<?php

use yii\helpers\Url;

$this->registerCss('body {padding-bottom: 50px;}');
?>
<nav id="easyii-navbar" class="navbar bg-light fixed-bottom">
    <div class="container">
        <ul class="nav navbar-nav navbar-left">
            <li><a class="text-muted" href="<?= Url::to(['/admin']) ?>"><span class="fa fa-cogs"></span> <?= Yii::t('easyii', 'Control Panel') ?></a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li><a class="text-muted" href="<?= Url::to(['/admin/sign/out']) ?>"><span class="fa fa-sign-out"></span> <?= Yii::t('easyii', 'Logout') ?></a></li>
        </ul>
    </div>
</nav>