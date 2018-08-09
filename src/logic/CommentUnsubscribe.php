<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 09.08.2018
 * Time: 20:07
 */

namespace floor12\comments\logic;


use floor12\comments\models\Comment;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class CommentUnsubscribe
{
    const SALT = "NEDSl31asdqD{2";

    private $_model;
    private $_email;
    private $_hash;

    public function __construct(int $comment_id, string $email, string $hash)
    {
        $this->_model = Comment::findOne($comment_id);
        if (!$this->_model)
            throw new NotFoundHttpException('Comment not found');

        $this->_hash = $hash;
        $this->_email = $email;
    }

    public function execute()
    {
        if (!$this->_hash)
            throw new BadRequestHttpException('Empty hash');

        if ($this->_hash != self::hash($this->_email))
            throw new BadRequestHttpException('Invalid hash');

        Yii::$app->db->createCommand()->update("{{comment2}}", [
            'subscribe' => Comment::UNSUBSCRIBED
        ], [
            'class' => $this->_model->class,
            'author_email' => $this->_email,
            'object_id' => $this->_model->object_id
        ])->execute();
    }


    public static function hash(string $email)
    {
        return md5(self::SALT . $email . self::SALT);
    }
}