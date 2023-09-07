<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вход';
?>
<div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Пожалуйста введите пароль для входа:</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

<!--            <?php //= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>-->

            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label(false) ?>

            <?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить') ?>

            <div class="form-group">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
