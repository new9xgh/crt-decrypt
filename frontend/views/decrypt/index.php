<?php

/* @var $this yii\web\View */

$this->title = 'SecureCRT密码找回';

use common\widgets\Alert;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\widgets\ActiveForm; ?>
<?= Alert::widget(); ?>

<div class="site-index">

    <h3 class="text-center">SecureCRT配置密码找回</h3>

    <div class="body-content">

        <div class="row">
            <h4>方式一：上传配置文件</h4>

            <?php $form = ActiveForm::begin([
                'action' => Url::toRoute('decrypt/decrypt-by-config'),
                'method' => 'post',
                'options' => ['enctype' => 'multipart/form-data', 'class' => 'col-lg-6'],
            ]);
            ?>
            <?= $form->field($model, 'configFile')
                ->label(false)
                ->widget(FileInput::class, [
                'pluginOptions' => [
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false
                ],
            ]);
            ?>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">解密</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <br>
        <div class="row">
            <h4>方式二：上传Hash密文</h4>

            <?php $form = ActiveForm::begin([
                'action' => Url::toRoute('decrypt/decrypt-by-hash'),
                'method' => 'post',
                'options' => ['class' => 'col-lg-6']
            ]);
            ?>
            <?= $form->field($model, 'version')->dropDownList($model->hashVersions, ['placeholder'=>'加密版本'])->label(false); ?>
            <?= $form->field($model, 'hash')->textInput(['placeholder'=>'Hash密文'])->label(false); ?>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">解密</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
