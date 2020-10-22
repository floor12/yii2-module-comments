<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 04.08.2018
 * Time: 14:18
 *
 * @var $this View
 * @var $model CommentFilter
 * @var $adminTitle string|NULL
 * @var $module Module
 */

use floor12\comments\assets\CommentsAdminAsset;
use floor12\comments\models\CommentFilter;
use floor12\comments\models\CommentOrder;
use floor12\comments\models\CommentStatus;
use floor12\comments\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

CommentsAdminAsset::register($this);

$this->title = $adminTitle ?: Yii::t('app.f12.comments', 'Comments');
$f12CommentApproveUrl = Url::toRoute(['/comments/admin/approve']);
$this->registerJs("f12CommentApproveUrl = '{$f12CommentApproveUrl}'");

$form = ActiveForm::begin([
    'id' => 'comments-admin-filter',
    'method' => 'GET',
    'options' => ['class' => 'f12-form-autosubmit', 'data-container' => '#items']
]);

echo Html::tag('h1', $this->title);

?>

    <div class="f12-filter-block">
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'filter')->label(false)->textInput(['placeholder' => Yii::t('app.f12.comments', 'search in comments...')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')->label(false)->dropDownList(CommentStatus::listData(), ['prompt' => Yii::t('app.f12.comments', 'any status')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'class')->label(false)->dropDownList($model->getCommentObjectClasses(), ['prompt' => Yii::t('app.f12.comments', 'all types of objects')]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'order')->label(false)->dropDownList(CommentOrder::listData()) ?>
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
