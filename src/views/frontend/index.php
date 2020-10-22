<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 05.12.2016
 * Time: 16:20
 *
 * @var $this View
 * @var $comments Comment[]
 * @var $commentsTotal integer
 */

use common\models\Comment;
use yii\web\View;

?>


<button onclick="f12Comments.newComment(event); $(this).hide(); return false;"
        class="f12-comments-btn-add-new">
    <?= Yii::t('app.f12.comments', "Add new comment") ?>
</button>

<div class="f12-comments-header">
    <?= Yii::t('app.f12.comments', "Comments") ?> <?= $commentsTotal ? "($commentsTotal)" : NULL ?>
</div>
<div class="object-comments">
    <?= $commentsTotal ? $comments : Yii::t('app.f12.comments', "There are no comments yet. Be the first who will post a comment!") ?>
</div>
