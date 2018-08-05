<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 05.12.2016
 * Time: 16:20
 *
 * @var $this \yii\web\View
 * @var $comments \common\models\Comment[]
 * @var $commentsTotal integer
 */

?>

<div class="f12-comments-header">
    <?= Yii::t('app.f12.comments', "Comments") ?> <?= $commentsTotal ? "($commentsTotal)" : NULL ?>
</div>
<div class="object-comments">
    <?=$commentsTotal? $comments : Yii::t('app.f12.comments',"There are no comments yet. Be the first who will post a comment!")?>
</div>

