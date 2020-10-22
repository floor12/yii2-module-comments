<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 04.08.2018
 * Time: 14:18
 *
 * @var $this \yii\web\View
 * @var $model \floor12\comments\models\CommentFilter
 * @var $adminTitle string|NULL
 * @var $module \floor12\comments\Module
 */

use floor12\comments\assets\CommentsAdminAsset;
use floor12\comments\models\CommentStatus;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

CommentsAdminAsset::register($this);

$f12CommentApproveUrl = Url::toRoute(['/comments/admin/approve']);
$this->registerJs("f12CommentApproveUrl = '{$f12CommentApproveUrl}'");

$form = ActiveForm::begin([
    'id' => 'comments-admin-filter',
    'method' => 'GET',
    'options' => ['class' => 'f12-form-autosubmit', 'data-container' => '#items']
]);

echo Html::tag('h1', $adminTitle ?: Yii::t('app.f12.comments', 'Comments'));

?>

    <div class="f12-filter-block">
        <div class="row">
            <div class="col-md-5">
                <?= $form->field($model, 'filter')->label(false)->textInput(['placeholder' => Yii::t('app.f12.comments', 'search in comments...')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')->label(false)->dropDownList(CommentStatus::listData(), ['prompt' => Yii::t('app.f12.comments', 'any status')]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'class')->label(false)->dropDownList($model->getCommentObjectClasses(), ['prompt' => Yii::t('app.f12.comments', 'all types of objects')]) ?>
            </div>

        </div>
    </div>
<?php

ActiveForm::end();

Pjax::begin(['id' => 'items']);

echo ListView::widget([
    'id' => 'comments-list-view',
    'dataProvider' => $model->dataProvider(),
    'itemView' => $module->viewAdminIndexItem,
    'layout' => '{items}{pager}{summary}',
    'viewParams' => [
        'module' => $module
    ]
]);

Pjax::end();
