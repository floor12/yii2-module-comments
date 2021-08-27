<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 30.07.2018
 * Time: 9:16
 */

namespace floor12\comments\controllers;

use floor12\comments\logic\CommentApprove;
use floor12\comments\logic\CommentCreate;
use floor12\comments\logic\CommentUnsubscribe;
use floor12\comments\models\Comment;
use floor12\comments\models\CommentStatus;
use floor12\comments\Module;
use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class FrontendController extends Controller
{

    private $_commentsRendered;
    private $_comments;


    /**
     * @param $object_id
     * @param $classname
     * @return string
     */
    public function actionIndex($object_id, $classname, $additional_ids = null): string
    {
        if ($additional_ids)
            $additional_ids = explode(',', $additional_ids);
        $commetsQuery = Comment::find()
            ->byObject($classname, $additional_ids ? array_merge($additional_ids, [$object_id]) : $object_id)
            ->tree($this->module->commentsOrder);

        $ratingMaxValue = $this->module->ratingMaxValue;

        if (!Yii::$app->user->can($this->module->editRole))
            $commetsQuery->active();

        $this->_comments = $commetsQuery->all();

        $commentsTotal = sizeof($this->_comments);

        if ($this->_comments) {
            foreach ($this->_comments as $comment)
                $this->_commentsRendered .= $this->renderPartial(Yii::$app->getModule('comments')->viewCommentListItem, [
                    'model' => $comment,
                    'useAvatar' => Yii::$app->getModule('comments')->useAvatar,
                    'defaultAvatar' => Yii::$app->getModule('comments')->defaultAvatar,
                    'allowPublish' => $comment->status == CommentStatus::PENDING && Yii::$app->user->can(Yii::$app->getModule('comments')->editRole),
//                    'allowAnswer' => $comment->status == CommentStatus::PUBLISHED && !(Yii::$app->user->isGuest && Yii::$app->getModule('comments')->userMode == Module::MODE_ONLY_USERS),
                    'allowEdit' => (Yii::$app->user->id == $comment->create_user_id || Yii::$app->user->can(Yii::$app->getModule('comments')->editRole)),
                    'ratingMaxValue' => $ratingMaxValue,
                    'object_id' => $object_id,
                    'classname' => $classname,
                ]);
        }

        return $this->renderAjax(Yii::$app->getModule('comments')->viewCommentList, [
            'comments' => $this->_commentsRendered,
            'commentsTotal' => $commentsTotal,
            'object_id' => $object_id,
            'classname' => $classname,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionApprove($id)
    {
        $model = Comment::findOne((int)$id);
        if (!$model)
            throw new NotFoundHttpException(Yii::t('app.f12.comments', 'The comment is not found.'));

        if (!Yii::createObject(CommentApprove::class, [$model, Yii::$app->user->identity])->execute())
            throw new BadRequestHttpException(Yii::t('app.f12.comments', 'Comment savign error.'));

        return Yii::t('app.f12.comments', 'The comment is approved.');
    }

    /**
     * @param null $classname
     * @param int $object_id
     * @param int $parent_id
     * @return string
     * @throws InvalidConfigException
     */
    public function actionForm($classname = NULL, $object_id = 0, $parent_id = 0)
    {

        if (Yii::$app->user->isGuest && Yii::$app->getModule('comments')->userMode == Module::MODE_ONLY_USERS)
            return Yii::t('app.f12.comments', "Only authorized user can create a comment.");

        $model = new Comment();

        if (Yii::$app->user->isGuest)
            $model->scenario = Comment::SCENARIO_GUEST;

        if (Yii::$app->request->isPost) {

            $result = Yii::createObject(CommentCreate::class, [
                $model,
                Yii::$app->request->post(),
                Yii::$app->user->isGuest ? null : Yii::$app->user->identity,
                $classname,
                $object_id,
                $parent_id
            ])->execute();

            if ($result)
                return $result;
        }

        return $this->renderAjax(Yii::$app->getModule('comments')->viewForm, [
            'model' => $model,
            'allowAttachments' => Yii::$app->getModule('comments')->isAttachmentsAllowed()
        ]);
    }

    /**
     * @param string $comment_id
     * @param string $email
     * @param string $hash
     * @return string
     * @throws InvalidConfigException
     */
    public function actionUnsubscibe(string $comment_id, string $email, string $hash)
    {
        Yii::createObject(CommentUnsubscribe::class, [
            (int)$comment_id,
            $email,
            $hash
        ])->execute();

        return $this->renderContent(Html::tag('h1', Yii::t('app.f12.comments', 'Your email is unsubscribed.')));
    }

    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        return [
            'delete' => [
                'class' => DeleteAction::class,
                'model' => Comment::class,
                'access' => $this->allowEdit(Yii::$app->request->getBodyParam('id')),
                'message' => Yii::t('app.f12.comments', 'The comment is delited.')
            ],
            'edit' => [
                'class' => EditModalAction::class,
                'model' => Comment::class,
                'access' => $this->allowEdit(Yii::$app->request->get('id')),
                'message' => Yii::t('app.f12.comments', 'The comment is updated.')
            ]
        ];
    }

    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    protected function allowEdit($id)
    {
        if (Yii::$app->user->can(Yii::$app->getModule('comments')->editRole))
            return true;

        $model = Comment::findOne($id);
        if (!$model)
            return false;

        if ($model->create_user_id = Yii::$app->user->id)
            return true;

        return false;
    }
}
