<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 30.07.2018
 * Time: 16:14
 * @var $this \yii\web\View
 * @var $model \floor12\comments\models\Comment
 * @var $useAvatar boolean
 * @var $allowPublish boolean
 * @var $allowEdit boolean
 */

use floor12\comments\models\CommentStatus;
use floor12\files\components\FilesBlock;
use yii\helpers\Html;

$ansId = "commentAnswer{$model->id}";
$params = json_encode(['block_id' => "#{$ansId}", 'parent_id' => $model->parent_id ?: $model->id])

?>
<div data-key="<?= $model->id ?>"
     class="f12-comment
     <?php if ($model->status == CommentStatus::PENDING) echo "f12-pending"; ?>
     <?php if ($model->status == CommentStatus::DELETED) echo "f12-deleted"; ?>
     <?php if ($model->parent_id) echo "f12-subcomment"; ?>
">

    <?= ($useAvatar) ? Html::img($model->avatar, ['alt' => $model->name, 'class' => 'f12-comment-avatar']) : NULL ?>

    <div class="f12-comment-date"><?= \Yii::$app->formatter->asDatetime($model->created) ?></div>
    <div class="f12-comment-name"><?= $model->name ?></div>
    <div class="f12-comment-content">
        <?= Yii::$app->getModule('comments')->useWYSIWYG ? $model->content : Html::tag('p', nl2br($model->content)); ?>
    </div>

    <?php
    if (Yii::$app->getModule('comments')->allowAttachments && $model->attachments)
        echo FilesBlock::widget(['files' => $model->attachments, 'downloadAll' => Yii::$app->getModule('comments')->attachmentsDownloadAll])
    ?>

    <div class="f12-comment-control">

        <?php

        if ($allowPublish)
            echo Html::button(Yii::t('app.f12.comments', 'approve'), [
                'class' => 'f12-comment-button f12-comment-button-approve',
                'onclick' => "f12Comments.approve({$model->id})"
            ]);

        echo Html::button(Yii::t('app.f12.comments', 'answer'), [
            'class' => 'f12-comment-button f12-comment-button-answer',
            'onclick' => "f12Comments.loadForm({$params})"
        ]);

        if ($allowEdit) {
            echo Html::button(Yii::t('app.f12.comments', 'edit'), [
                'class' => 'f12-comment-button f12-comment-button-edit',
                'onclick' => "f12Comments.edit({$model->id})"
            ]);

            echo Html::button(Yii::t('app.f12.comments', 'delete'), [
                'class' => 'f12-comment-button f12-comment-button-delete',
                'onclick' => "f12Comments.delete({$model->id})"
            ]);
        }
        ?>

    </div>

    <div class="" id="<?= $ansId ?>"></div>
</div>
