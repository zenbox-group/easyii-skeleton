<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
/** @var \yii\easyii\modules\catalog\api\CategoryObject $cat */
$this->title = $cat->seo('title', $cat->model->title);
$this->params['breadcrumbs'][] = ['label' => 'Shop', 'url' => ['shop/index']];
$this->params['breadcrumbs'][] = $cat->model->title;
?>

<div class="container">
    <h1><?= $cat->seo('h1', $cat->title) ?></h1>
    <div class="row my-3">
        <div class="col-md-3">
            <h4>Filters</h4>
            <div class="well well-sm">
                <?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/shop/cat', 'slug' => $cat->slug])]); ?>
                <?= $form->field($filterForm, 'brand')->dropDownList($cat->fieldOptions('brand', 'Select brand')) ?>
                <?= $form->field($filterForm, 'priceFrom') ?>
                <?= $form->field($filterForm, 'priceTo') ?>
                <?= $form->field($filterForm, 'touchscreen')->checkbox() ?>
                <?= $form->field($filterForm, 'storageFrom') ?>
                <?= $form->field($filterForm, 'storageTo') ?>
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-md-9">
            <?php if(count($items)) : ?>
                <br/>
                <?php foreach($items as $item) : ?>
                    <?= $this->render('_item', ['item' => $item]) ?>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Category is empty</p>
            <?php endif; ?>

            <?= $cat->pages() ?>
        </div>

    </div>
</div>
