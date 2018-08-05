<?php

namespace floor12\comments\models;

use Yii;

/**
 * This is the model class for table "comment2".
 *
 * @property int $id
 * @property int $status Статус
 * @property int $created Создан
 * @property int $updated Обновлен
 * @property int $create_user_id Создал
 * @property int $update_user_id Обновил
 * @property string $class Класс объёкта
 * @property int $object_id ID объёкта
 * @property int $parent_id Родительский комментарий
 * @property string $content Текст комментария
 * @property string $author_name Имя автора комментария
 * @property string $author_email Email автора комментария
 *
 * @property string $avatar Имя автора комментария
 * @property string $name  Аватар автора комментария
 */
class Comment extends \yii\db\ActiveRecord
{
    const SCENARIO_GUEST = 'guest';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created', 'updated', 'class', 'object_id', 'content'], 'required'],
            [['status', 'created', 'updated', 'create_user_id', 'update_user_id', 'object_id', 'parent_id', 'subscribe'], 'integer'],
            [['content'], 'string'],
            [['author_email'], 'email'],
            [['class', 'author_name', 'author_email'], 'string', 'max' => 255],
            [['author_name', 'author_email'], 'required', 'on' => self::SCENARIO_GUEST],
            [['create_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->getModule('comments')->userClass, 'targetAttribute' => ['create_user_id' => 'id']],
            [['update_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->getModule('comments')->userClass, 'targetAttribute' => ['update_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => Yii::t('app.f12.comments', 'Status'),
            'created' => Yii::t('app.f12.comments', 'Created'),
            'updated' => Yii::t('app.f12.comments', 'Updated'),
            'create_user_id' => Yii::t('app.f12.comments', 'Creator'),
            'update_user_id' => Yii::t('app.f12.comments', 'Updator'),
            'class' => Yii::t('app.f12.comments', 'Classname of object'),
            'object_id' => Yii::t('app.f12.comments', 'ID of object'),
            'parent_id' => Yii::t('app.f12.comments', 'Parent comment ID'),
            'content' => Yii::t('app.f12.comments', 'Comment text'),
            'author_name' => Yii::t('app.f12.comments', 'Author name'),
            'author_email' => Yii::t('app.f12.comments', 'Author email'),
            'subscribe' => Yii::t('app.f12.comments', 'Send me new comments in this thread to email.'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommentQuery(get_called_class());
    }

    public function getName()
    {
        if ($this->create_user_id) {
            $classname = Yii::$app->getModule('comments')->userClass;
            $model = $classname::findOne($this->create_user_id);
            if (!$model)
                return Yii::t('app.f12.comments', 'Deleted user');
            return $model->commentatorName;
        }
        return $this->author_name;
    }

    public function getAvatar()
    {
        if ($this->create_user_id) {
            $classname = Yii::$app->getModule('comments')->userClass;
            $model = $classname::findOne($this->create_user_id);
            return $model->commentatorAvatar;
        }
        return Yii::$app->getModule('comments')->defaultAvatar;

    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        $this->status = CommentStatus::DELETED;
        return $this->save(true, ['status']);
    }
}
