<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 09.08.2018
 * Time: 12:53
 */

namespace floor12\comments\logic;

use floor12\comments\models\Comment;
use floor12\comments\models\CommentStatus;
use Yii;

class CommentInform
{

    private $_comment;
    private $_commentsInThread;
    private $_emails = [];

    public function __construct(Comment $comment)
    {
        $this->_comment = $comment;

        $this->_commentsInThread = Comment::find()->where([
            'status' => CommentStatus::PUBLISHED,
            'subscribe' => Comment::SUBSCRIBED,
            'object_id' => $this->_comment->object_id,
            'class' => $this->_comment->class,
        ])->all();

    }

    public function execute()
    {
        if ($this->_comment->status != CommentStatus::PUBLISHED || !$this->_commentsInThread)
            return;

        // собираем в кучу все адреса
        foreach ($this->_commentsInThread as $comment) {
            $this->_emails[] = $comment->email;
        }

        // убираем повторы
        $this->_emails = array_unique($this->_emails);

        // таким образом убираем почту автора коммента из рассылки, если она там есть.
        $this->_emails = array_diff($this->_emails, [$this->_comment->email]);

        // рассылаем
        if ($this->_emails)
            foreach ($this->_emails as $email)
                Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => "@vendor/floor12/yii2-module-comments/src/mail/comment-inform-html.php"],
                        ['comment' => $this->_comment, 'unsubscribeLink' => $this->makeUnsubscibeLink($email)]
                    )
                    ->setFrom([Yii::$app->getModule('comments')->emailFromAddress => Yii::t('app.f12.comments', 'Сomment module email robot')])
                    ->setSubject(Yii::t('app.f12.comments', 'New comment in thread'))
                    ->setTo($email)
                    ->send();

    }

    /** Return unsubscribe link for cumment comment
     * @return string
     */
    private function makeUnsubscibeLink(string $email): string
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            '/comments/frontend/unsubscibe',
            'comment_id' => $this->_comment->id,
            'email' => $email,
            'hash' => CommentUnsubscribe::hash($email)
        ]);
    }
}