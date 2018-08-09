<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 08.08.2018
 * Time: 22:21
 *
 * @var $this View
 * @var $comment Comment
 * @var $unsubscribeLink string
 */

use floor12\comments\models\Comment;
use yii\helpers\Html;
use yii\web\View;

?>

<p>
    <b><?= Yii::t('app.f12.comments', 'New comment in thread') ?>.</b>
</p>

<p>
    <?= Yii::t('app.f12.comments', 'The comment is available on') ?>

    <?= Html::a(Yii::t('app.f12.comments', 'this page'), $comment->url) ?>.
</p>

<p>
    <?= Yii::t('app.f12.comments', 'To unsubscribe from comments of current thread press') ?>

    <?= Html::a(Yii::t('app.f12.comments', 'this link'), $unsubscribeLink) ?>.
</p>