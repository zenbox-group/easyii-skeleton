<?php
use yii\helpers\Url;

$this->title = 'EasyiiCMS installation step 1';
?>

<?= $this->render('_steps', ['currentStep' => 1])?>

<?php if(!$this->context->dbConnected) : ?>
    <h2 class="text-danger">Warning</h2>
    <div class="alert alert-danger">Cannot connect to database. Please configure <code>.env</code> file</div>
<?php else : ?>
    <div class="text-center">
        <h2 class="text-muted">If all the requirements are satisfied</h2>
        <p>
            <a href="<?= Url::to(['/install/step2']) ?>" class="btn btn-primary btn-lg">Continue <i class="fa fa-forward"></i></a>
        </p>
    </div>
    <hr/>
    <?= $this->renderFile(Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR .'requirements.php') ?>
<?php endif; ?>
