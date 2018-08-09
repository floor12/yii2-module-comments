<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 08.08.2018
 * Time: 22:21
 *
 * @var $this View
 * @var $comment Comment
 */

use floor12\comments\models\Comment;
use yii\helpers\Html;
use yii\web\View;

?>


<h1><?= Yii::t('app.f12.comments', 'New comment is waiting for moderation.') ?></h1>

<div style="padding: 20px; background-color: #f6f6f6; border: 1px #eee solid;">

    <b><?= $comment->name ?> (<?= $comment->email ?>)</b>

    <?= Yii::$app->getModule('comments')->useWYSIWYG ? $comment->content : Html::tag('p', nl2br($comment->content)); ?>

</div>


<?= Yii::t('app.f12.comments', 'To moderate it click') ?>

<?= Html::a(Yii::t('app.f12.comments', 'this link'), $moderateLink) ?>.
