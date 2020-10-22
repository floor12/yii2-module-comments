<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 19.06.2018
 * Time: 13:13
 */

namespace floor12\comments\controllers;

use floor12\comments\logic\CommentApprove;
use floor12\comments\models\Comment;
use floor12\comments\models\CommentFilter;
use floor12\comments\Module;
use floor12\editmodal\DeleteAction;
use floor12\editmodal\EditModalAction;
use floor12\editmodal\IndexAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/** Comment admin controllers
 * Class AdminController
 * @package floor12\comments\controllers
 */
class AdminController extends Controller
{
    /** @var string */
    public $defaultAction = 'index';
    /** @var Module */
    public $commentModule;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [$this->commentModule->editRole],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'form' => ['get', 'post'],
                    'delete' => ['delete'],
                    'approve' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->commentModule = Yii::$app->getModule('comments');
        $this->layout = $this->commentModule->layout;
    }


    /** Comment approve action
     * @param $id
     * @return string
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
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


    /** Edit actions
     * @return array
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'model' => CommentFilter::class,
                'view' => $this->commentModule->viewAdminIndex,
                'viewParams' => [
                    'adminTitle' => $this->commentModule->adminTitle,
                    'module' => $this->commentModule
                ]
            ],
            'form' => [
                'class' => EditModalAction::class,
                'model' => Comment::class,
                'view' => Yii::$app->getModule('comments')->viewAdminForm,
                'message' => Yii::t('app.f12.comments', 'The comment is saved.')
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'model' => Comment::class,
                'message' => Yii::t('app.f12.comments', 'The comment is deleted.')
            ],
        ];
    }
}
