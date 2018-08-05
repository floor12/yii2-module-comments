<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 05.08.2018
 * Time: 16:36
 *
 * @var $this \yii\web\View
 * @var $model \floor12\comments\models\CommentStatus
 */

use floor12\comments\models\CommentStatus;
use marqu3s\summernote\Summernote;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'f12-comment-form',
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
]);

?>


<div class="modal-header">
    <h2><?= Yii::t('app.f12.comments', 'Comment update') ?></h2>
</div>
<div class="modal-body">

    <?= $form->errorSummary($model); ?>

    <div class="row">
        <?php if (!$model->create_user_id): ?>
            <div class="col-md-4">
                <?= $form->field($model, 'author_name') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'author_email') ?>
            </div>
        <?php endif; ?>
        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList(CommentStatus::listData()) ?>
        </div>
    </div>

    <?= Yii::$app->getModule('comments')->useWYSIWYG ?
        $form->field($model, 'content')->widget(Summernote::class, [
            'clientOptions' => Yii::$app->getModule('comments')->summernoteClientOptions
        ]) :
        $form->field($model, 'content')->textarea(['rows' => '6']) ?>

    <?= $form->field($model, 'subscribe')->checkbox() ?>

</div>

<div class="modal-footer">
    <?= Html::a(Yii::t('app.f12.comments', 'Cancel'), '', ['class' => 'btn btn-default modaledit-disable']) ?>
    <?= Html::submitButton(Yii::t('app.f12.comments', 'Save'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
