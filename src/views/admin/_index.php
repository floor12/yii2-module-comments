<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 04.08.2018
 * Time: 14:37
 *
 * @var $this \yii\web\View
 * @var $model \floor12\comments\models\Comment
 */

use floor12\comments\models\CommentStatus;
use floor12\editmodal\EditModalHelper;
use yii\helpers\Html;

$fontAwesome = Yii::$app->getModule('comments')->fontAwesome;
?>

<div class="f12-comment-admin-object <?= "f12-comment-admin-object-status{$model->status}" ?> <?php if ($model->parent_id) echo "f12-comment-admin-sub"; ?>">
    <div class="f12-comment-admin-object-header">
        <?= $model->name ?>
        <span class="f12-comment-admin-object-controllr">
            <?= $model->status == CommentStatus::PENDING ? Html::a($fontAwesome->icon('check'), null, [
                'onclick' => "commentApprove({$model->id})",
                'class' => 'btn btn-default btn-xs',
                'title' => Yii::t('app.f12.comments', 'Approve this comment')
            ]) : null ?>

            <?= Html::a($fontAwesome::icon('pencil'), null, [
                'onclick' => EditModalHelper::showForm(['/comments/admin/form'], $model->id),
                'title' => Yii::t('app.f12.comments', 'Edit this comment'),
                'class' => 'btn btn-default btn-xs',
            ]) ?>

            <?= $model->status != CommentStatus::DELETED ? Html::a($fontAwesome::icon('trash'), null, [
                'onclick' => EditModalHelper::deleteItem(['/comments/admin/delete'], $model->id),
                'title' => Yii::t('app.f12.comments', 'Delete this comment'),
                'class' => 'btn btn-default btn-xs',
            ]) : null ?>

        </span>


        <div class="f12-comment-admin-object-date">
            <?= Yii::$app->formatter->asDatetime($model->created) ?>
        </div>
    </div>
    <?= Yii::$app->getModule('comments')->useWYSIWYG ? $model->content : Html::tag('p', nl2br($model->content)); ?>
</div>
