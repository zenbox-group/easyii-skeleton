<?php
$this->title = 'EasyiiCMS installation step 2';
?>

<?= $this->render('_steps', ['currentStep' => 2])?>

<div class="col-md-6 mx-auto">
    <div class="well">
        <?= $this->render('@easyii/views/install/_form', ['model' => $model])?>
    </div>
</div>