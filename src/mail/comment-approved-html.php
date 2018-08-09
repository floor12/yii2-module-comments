<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 08.08.2018
 * Time: 22:21
 *
 * @var $this View
 * @var $comment Comment
 * @var $moderateLink string
 */

use floor12\comments\models\Comment;
use yii\helpers\Html;
use yii\web\View;

?>

<p>
    <b><?= Yii::t('app.f12.comments', 'Your comment was approved.') ?></b>
</p>

<p>
    <?= Yii::t('app.f12.comments', 'The comment is available on') ?>

    <?= Html::a(Yii::t('app.f12.comments', 'this page'), $comment->url) ?>.
</p>