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
        return $this->_comment->save(true, ['status', 'update_user_id', 'updated']);
    }
}