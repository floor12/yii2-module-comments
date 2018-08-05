<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.07.2018
 * Time: 21:09
 *
 * @var $this \yii\web\View
 * @var $model \floor12\comments\models\Comment
 *
 */

use floor12\comments\Module;
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


<?= Yii::$app->getModule('comments')->useWYSIWYG ?
    $form->field($model, 'content')->label(false)->widget(Summernote::class, [
        'clientOptions' => Yii::$app->getModule('comments')->summernoteClientOptions
    ]) :
    $form->field($model, 'content')->textarea([
        'rows' => 5,
        'placeholder' => Yii::t('app.f12.comments', 'text of your comment...'),
    ])->label(false);
?>

    <div class="pull-left">
        <?= $form->field($model, 'subscribe')->checkbox() ?>
    </div>

    <div class="text-right">
        <?= Html::submitButton(Yii::t('app.f12.comments', 'Send'), ['class' => 'btn btn-primary']); ?>
    </div>

<?php ActiveForm::end(); ?>