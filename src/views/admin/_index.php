<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 04.08.2018
 * Time: 14:37
 *
 * @var $this View
 * @var $model Comment
 * @var $module Module
 */

use floor12\comments\assets\IconHelper;
use floor12\comments\models\Comment;
use floor12\comments\models\CommentStatus;
use floor12\comments\Module;
use floor12\editmodal\EditModalHelper;
use yii\helpers\Html;
use yii\web\View;

?>

<div class="f12-comment-admin-object <?php if ($model->parent_id) echo "f12-comment-admin-sub"; ?>">
    <div class="f12-comment-admin-object-header">
        <div class="f12-comment-admin-object-control">
            <?= $model->status == CommentStatus::PENDING ? Html::a(IconHelper::CHECK, null, [
                'onclick' => "commentApprove({$model->id})", 
                'class' => 'btn btn-default btn-xs',
                'title' => Yii::t('app.f12.comments', 'Approve this comment')
            ]) : null ?>

            <?= Html::a(\floor12\editmodal\IconHelper::PENCIL, null, [
                'onclick' => EditModalHelper::showForm(['/comments/admin/form'], $model->id),
                'title' => Yii::t('app.f12.comments', 'Edit this comment'),
                'class' => 'btn btn-default btn-xs',
            ]) ?>

            <?= Html::a(IconHelper::LINK, $model->url, [
                'title' => Yii::t('app.f12.comments', 'Go to page'),
                'data-pjax' => '0',
                'target' => '_blank',
                'class' => 'btn btn-default btn-xs',
            ]) ?>

            <?= $model->status != CommentStatus::DELETED ? Html::a(\floor12\editmodal\IconHelper::TRASH, null, [
                'onclick' => EditModalHelper::deleteItem(['/comments/admin/delete'], $model->id),
                'title' => Yii::t('app.f12.comments', 'Delete this comment'),
                'class' => 'btn btn-default btn-xs',
            ]) : null ?>

        </div>
        <div class="f12-comment-admin-object-status f12-comment-admin-object-status<?= $model->status ?>"></div>
        <div class="f12-comment-name"><?= $model->name ?></div>
        <?php if ($module->ratingMaxValue && $model->rating): ?>
            <div class="f12-comment-rating"><?= $model->rating ?>/<?= $module->ratingMaxValue ?></div>
        <?php endif; ?>
        <div class="f12-comment-date"><?= Yii::$app->formatter->asDatetime($model->created) ?></div>
        <?php if ($model->getCommentObject()): ?>
            <div class="small">
                <?= Html::a($model->getCommentObject()->getHumanReadbleObjectName(), $model->url, [
                    'target' => '_blank',
                    'data-pjax' => '0',
                ]) ?>
            </div>
        <?php endif; ?>
    </div>
    <?= $module->useWYSIWYG ? $model->content : Html::tag('p', nl2br($model->content)); ?>
</div>
