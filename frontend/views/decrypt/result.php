<?php

/* @var $this yii\web\View */

use common\widgets\Alert;
use yii\helpers\Url;


$this->title = 'SecureCRT密码找回';
?>
<?= Alert::widget(); ?>

<div class="row">
    <div class="col-md-12">
        <p class="h2">解密结果</p>
    </div>
    <div class="col-md-12">
        <p class="h3">server: <?= $model->host ?></p>
        <p class="h3">password: <?= $model->password ?></p>
        <div class="form-group">
            <a class="btn btn-primary" href="<?= Url::toRoute('/decrypt')?>">重新上传配置</a>
        </div>
    </div>
</div>
