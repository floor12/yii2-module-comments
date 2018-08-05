<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 30.07.2018
 * Time: 9:16
 */

namespace floor12\comments\controllers;

use floor12\comments\logic\CommentCreate;
use floor12\comments\models\Comment;
use floor12\comments\Module;
use floor12\editmodal\DeleteAction;
use Yii;
use yii\web\Controller;

class FrontendController extends Controller
{

    private $_commentsRendered;
    private $_comments;


    public function actionIndex($object_id, $classname): string
    {
        $this->_comments = Comment::find()
            ->active()
            ->byObject($classname, $object_id)
            ->tree(Yii::$app->getModule('comments')->commentsOrder)
            ->all();

        $commentsTotal = sizeof($this->_comments);

        if ($this->_comments) {
            foreach ($this->_comments as $comment)
                $this->_commentsRendered .= $this->renderPartial(Yii::$app->getModule('comments')->viewCommentListItem, [
                    'model' => $comment,
                    'useAvatar' => Yii::$app->getModule('comments')->useAvatar,
                    'defaultAvatar' => Yii::$app->getModule('comments')->defaultAvatar,
                    'allowAnswer' => !(Yii::$app->user->isGuest && Yii::$app->getModule('comments')->userMode == Module::MODE_ONLY_USERS)
                ]);
        }

        return $this->renderAjax(Yii::$app->getModule('comments')->viewCommentList, [
            'comments' => $this->_commentsRendered,
            'commentsTotal' => $commentsTotal,
        ]);
    }


    public function actionForm($classname = NULL, $object_id = 0, $parent_id = 0)
    {

        if (Yii::$app->user->isGuest && Yii::$app->getModule('comments')->userMode == Module::MODE_ONLY_USERS)
            return Yii::t('app.f12.comments', "Only authorized user can create a comment.");

        $model = new Comment();

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

        return $this->renderAjax(Yii::$app->getModule('comments')->viewForm, ['model' => $model]);
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
                'message' => Yii::t('app.f12.comments', 'The comment is delited.')
            ]
        ];
    }
}