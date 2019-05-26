<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 05.08.2018
 * Time: 21:19
 */

namespace floor12\comments\logic;

use floor12\comments\models\Comment;
use floor12\comments\models\CommentStatus;
use floor12\comments\Module;
use Yii;
use yii\web\IdentityInterface;

class CommentCreate
{

    private $_model;
    private $_identity;
    private $_classname;
    private $_object_id;
    private $_parent;
    private $_message;
    private $_data = [];

    /**
     * CommentCreate constructor.
     * @param Comment $model Comment AR model
     * @param array $data Post data
     * @param IdentityInterface|null $identity
     * @param string|null $classname
     * @param int|null $object_id
     * @param int|null $parent_id
     */
    public function __construct(Comment $model, array $data, $identity = null, $classname = NULL, $object_id = 0, $parent_id = 0)
    {
        if (!$classname && $parent_id && $object_id)
            throw new BadRequestHttpException(Yii::t('app.f12.comments', 'Error create comment.'));

        $this->_data = $data;
        $this->_identity = $identity;
        $this->_classname = $classname;
        $this->_object_id = (int)$object_id;
        $this->_model = $model;

        $this->_model->created = time();
        $this->_model->updated = time();

        if ($parent_id) {
            $this->_parent = Comment::findOne((int)$parent_id);
            if (!$this->_parent)
                throw new BadRequestHttpException(Yii::t('app.f12.comments', 'Parent comment not found.'));
        }
    }

    /**
     * @return bool|string
     */
    public function execute()
    {
        $this->_model->load($this->_data);

        if ($this->_parent) {
            $this->_model->object_id = $this->_parent->object_id;
            $this->_model->class = $this->_parent->class;
            $this->_model->parent_id = $this->_parent->id;
        } else {
            $this->_model->class = $this->_classname;
            $this->_model->object_id = $this->_object_id;
        }

        $this->_model->url = Yii::$app->request->referrer;

        $this->_message = Yii::t('app.f12.comments', "Thank you! Your comment is published!");

        $this->_model->status = CommentStatus::PUBLISHED;


        // Если активная премодерация и пишет не админ
        if (
            Yii::$app->getModule('comments')->moderationMode == Module::MODE_PRE_MODERATION &&
            !Yii::$app->user->can(Yii::$app->getModule('comments')->editRole)
        ) {
            $this->_model->status = CommentStatus::PENDING;
            $this->_message = Yii::t('app.f12.comments', "Thank you! Your comment will be published after moderation.");

            if (Yii::$app->getModule('comments')->adminEmailAddress) {
                $this->_model->on(Comment::EVENT_AFTER_INSERT, function ($event) {
                    Yii::$app
                        ->mailer
                        ->compose(
                            ['html' => "@vendor/floor12/yii2-module-comments/src/mail/new-comment-html.php"],
                            ['comment' => $event->sender, 'moderateLink' => Yii::$app->urlManager->createAbsoluteUrl('/comments/admin')]
                        )
                        ->setFrom([Yii::$app->getModule('comments')->emailFromAddress => Yii::t('app.f12.comments', 'Сomment module email robot')])
                        ->setSubject(Yii::t('app.f12.comments', 'New comment is waiting for moderation.'))
                        ->setTo(Yii::$app->getModule('comments')->adminEmailAddress)
                        ->send();
                });
            }

        }

        if (Yii::$app->getModule('comments')->commentPostProcessor) {

            $this->_model->on(Comment::EVENT_AFTER_INSERT, function ($event) {
                Yii::createObject(Yii::$app->getModule('comments')->commentPostProcessor, [$this->_model])->execute();
            });
        }

        // уведомляем подписчиков
        if (Yii::$app->getModule('comments')->enableNotificator)
            $this->_model->on(Comment::EVENT_AFTER_INSERT, function ($event) {
                Yii::createObject(CommentInform::class, [$event->sender])->execute();
            });

        if (!$this->_identity && Yii::$app->getModule('comments')->userMode == Module::MODE_GUESTS)
            $this->_model->scenario = Comment::SCENARIO_GUEST;

        if ($this->_identity) {
            $this->_model->create_user_id = $this->_model->update_user_id = $this->_identity->getId();
            $this->_model->author_email = $this->_identity->getCommentatorEmail();
        }


        if (!$this->_model->save())
            return false;

        return $this->_message;
    }
}