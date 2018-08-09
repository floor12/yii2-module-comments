<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 05.08.2018
 * Time: 16:04
 */

namespace floor12\comments\logic;

use floor12\comments\models\Comment;
use floor12\comments\models\CommentStatus;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\IdentityInterface;

class CommentApprove
{
    private $_comment;
    private $_identity;

    public function __construct(Comment $commet, IdentityInterface $identity)
    {
        $this->_identity = $identity;
        $this->_comment = $commet;
        if ($this->_comment->status != CommentStatus::PENDING)
            throw new BadRequestHttpException(Yii::t('app.f12.comments', 'This comments is not pending.'));
    }

    public function execute()
    {
        $this->_comment->status = CommentStatus::PUBLISHED;
        $this->_comment->update_user_id = $this->_identity->getId();
        $this->_comment->updated = time();

        $this->_comment->on(Comment::EVENT_AFTER_UPDATE, function ($event) {
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => "@vendor/floor12/yii2-module-comments/src/mail/comment-approved-html.php"],
                    ['comment' => $event->sender]
                )
                ->setFrom([Yii::$app->getModule('comments')->emailFromAddress => Yii::t('app.f12.comments', 'Сomment module email robot')])
                ->setSubject(Yii::t('app.f12.comments', 'Your comment was approved.'))
                ->setTo($this->_comment->email)
                ->send();
        });


        // уведомляем подписчиков
        $this->_comment->on(Comment::EVENT_AFTER_UPDATE, function ($event) {
            Yii::createObject(CommentInform::class, [$event->sender])->execute();
        });


        return $this->_comment->save(true, ['status', 'update_user_id', 'updated']);
    }
}