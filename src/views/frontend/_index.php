<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 30.07.2018
 * Time: 16:14
 * @var $this \yii\web\View
 * @var $model \floor12\comments\models\Comment
 * @var $useAvatar boolean
 * @var $allowAnswer boolean
 */

use yii\helpers\Html;

$ansId = "commentAnswer{$model->id}";
$params = json_encode(['block_id' => "#{$ansId}", 'parent_id' => $model->parent_id ?: $model->id])

?>
<div data-key="<?= $model->id ?>" class="f12-comment <?php if ($model->parent_id) echo "f12-subcomment"; ?>">

    <?= ($useAvatar) ? Html::img($model->avatar, ['alt' => $model->name, 'class' => 'f12-comment-avatar']) : NULL ?>

    <div class="f12-comment-date"><?= \Yii::$app->formatter->asDatetime($model->created) ?></div>
    <div class="f12-comment-name"><?= $model->name ?></div>
    <div class="f12-comment-content"><?= $model->content; ?></div>

    <div class="f12-comment-control">
        <?= $allowAnswer ? Html::a(Yii::t('app.f12.comments', 'answer'), null, [
            'class' => 'f12-comment-button f12-comment-button-answer',
            'onclick' => "f12CommentsLoadForm({$params})"
        ]) : NULL ?>
    </div>

    <div class="" id="<?= $ansId ?>"></div>
</div>
