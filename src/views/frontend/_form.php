<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.07.2018
 * Time: 21:09
 *
 * @var $this \yii\web\View
 * @var $this \yii\web\View
 * @var $allowAttachments boolean
 *
 */

use floor12\comments\Module;
use floor12\files\components\FileInputWidget;
use kartik\form\ActiveForm;
use marqu3s\summernote\Summernote;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'id' => md5(rand(999, 99999)),
    'options' => ['class' => 'f12-comments-form']
]);

echo Html::tag('div', Yii::t('app.f12.comments', 'New comment'), ['class' => 'f12-comments-header']);

if (Yii::$app->getModule('comments')->userMode == Module::MODE_GUESTS && Yii::$app->user->isGuest) { ?>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'author_name')->label(false)->textInput([
                'placeholder' => Yii::t('app.f12.comments', 'name...'),
            ]); ?>
        </div>
        <div class="col-xs-6">
            <?= $form->field($model, 'author_email')->label(false)->textInput([
                'placeholder' => Yii::t('app.f12.comments', 'email...'),
            ]); ?>
        </div>
    </div>

<?php } ?>


<?php
if (Yii::$app->getModule('comments')->useWYSIWYG)
    echo $form->field($model, 'content')
        ->label(false)
        ->widget(Summernote::class, [
            'clientOptions' => Yii::$app->getModule('comments')->summernoteClientOptions
        ]);
else
    echo $form->field($model, 'content')
        ->label(false)
        ->textarea([
            'rows' => 5,
            'placeholder' => Yii::t('app.f12.comments', 'enter the comment here...'),
        ]);
?>

<?php if (Yii::$app->getModule('comments')->isAttachmentsAllowed()): ?>
    <?= $form->field($model, 'attachments')->widget(FileInputWidget::class, []) ?>
<?php endif; ?>


<?php if (Yii::$app->getModule('comments')->allowSubscribe): ?>
    <div class="pull-left">
        <?= $form->field($model, 'subscribe')->checkbox() ?>
    </div>
<?php endif; ?>

    <div class="text-right">
        <?= Html::submitButton(Yii::t('app.f12.comments', 'Send'), ['class' => 'btn btn-primary']); ?>
    </div>

<?php ActiveForm::end(); ?>