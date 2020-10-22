<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 30.07.2018
 * Time: 11:17
 */

use floor12\comments\models\Comment;

class CommentUpdate
{

    private $_comment;
    private $_data;
    private $_identity;
    private $_object;

    public function __construct(Comment $comment, array $data, IdentityInterface $identity)
    {
        $this->_comment = $comment;
        $this->_data = $data;
        $this->_identity = $identity;
    }

    public function execute()
    {
        $this->_comment->load($this->_data, '');

        if ($this->_data['content'])
            $this->_comment->content = Yii::createObject(PostContentPrepare::class, [$this->_data['content']])->execute();


        if ($this->_comment->parent_id) {
            if ($this->_comment->parent) {
                $this->_comment->class = $this->_comment->parent->class;
                $this->_comment->object_id = $this->_comment->parent->object_id;

                if ($this->_comment->parent->parent_id)
                    $this->_comment->parent_id = $this->_comment->parent->parent_id;

            } else
                throw new BadRequestHttpException('Parent comment not found.');

        }


        if ($this->_comment->getIsNewRecord()) {
            $classname = $this->_comment->class;
            $this->_object = $classname::findOne($this->_comment->object_id);
            if (!$this->_object)
                return false;
            $this->_comment->create_user_id = $this->_identity->id;


            if ($this->_comment->parent_id) {
                $this->_comment->on(Comment::EVENT_AFTER_INSERT, function () {
                    Notify::create("Пользователь <b>{$this->_identity->fullname}</b> ответил на ваш комментарий.", $this->_comment->parent->create_user_id, $this->_object->url, $this->_identity->avatar);
                });
            } else {
                $this->_comment->on(Comment::EVENT_AFTER_INSERT, function () {
                    if ($this->_comment->class == 'common\models\Post')
                        Notify::create("Пользователь <b>{$this->_identity->fullname}</b> оставил комментарий к вашей публикации.", $this->_object->create_user_id, $this->_object->url, $this->_identity->avatar);
                });


            }


        }

        $this->_comment->update_user_id = $this->_identity->id;


        return $this->_comment->save();


    }
}
