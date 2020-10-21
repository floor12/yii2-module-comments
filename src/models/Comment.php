<?php

namespace floor12\comments\models;

use floor12\comments\interfaces\CommentatorInterface;
use floor12\files\components\FileBehaviour;
use floor12\files\models\File;
use floor12\phone\PhoneValidator;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "f12_comment".
 *
 * @property int $id
 * @property int $status Статус
 * @property int $created Создан
 * @property int $updated Обновлен
 * @property int $create_user_id Создал
 * @property int $update_user_id Обновил
 * @property string $class Класс объёкта
 * @property string $url Адресс страницы комментария
 * @property int $subscribe  подписка на ветку
 * @property int $object_id ID объёкта
 * @property int $parent_id Родительский комментарий
 * @property string $content Текст комментария
 * @property string $author_name Имя автора комментария
 * @property string $author_email Email автора комментария
 * @property string $author_phone Телефон автора комментария
 * @property int $rating Оценка в комментарии
 *
 * @property string $avatar Имя автора комментария
 * @property string $name  Аватар автора комментария
 * @property string $email  Email автора комментария
 * @property ActiveRecord $userObject()  Объект автора
 * @property File[] $attachments  Приложенные файлы
 */
class Comment extends \yii\db\ActiveRecord
{
    const SCENARIO_GUEST = 'guest';
    const SUBSCRIBED = '1';
    const UNSUBSCRIBED = '0';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'f12_comment';
    }

    /**
     * {@inheritdoc}
     * @return CommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommentQuery(get_called_class());
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->subscribe = Yii::$app->getModule('comments')->allowSubscribe ? self::SUBSCRIBED : self::UNSUBSCRIBED;
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created', 'updated', 'class', 'object_id', 'content'], 'required', 'message' => Yii::t('app.f12.comments', 'This field is required.')],
            [['status', 'created', 'updated', 'create_user_id', 'update_user_id', 'object_id', 'parent_id', 'subscribe'], 'integer'],
            [['content'], 'string'],
            [['author_email'], 'email'],
            ['author_phone', PhoneValidator::class],
            [['class', 'author_name', 'author_email'], 'string', 'max' => 255],
            [['author_name'], 'required', 'on' => self::SCENARIO_GUEST, 'message' => Yii::t('app.f12.comments', 'This field is required.')],
            [['author_email'], 'required', 'on' => self::SCENARIO_GUEST, 'message' => Yii::t('app.f12.comments', 'This field is required.')],
            [['author_phone'], 'required', 'on' => self::SCENARIO_GUEST, 'message' => Yii::t('app.f12.comments', 'This field is required.')],
            [['create_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->getModule('comments')->userClass, 'targetAttribute' => ['create_user_id' => 'id']],
            [['update_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii::$app->getModule('comments')->userClass, 'targetAttribute' => ['update_user_id' => 'id']],
            [['attachments'], 'file', 'maxFiles' => 100],
            ['attachments_ids', 'safe']
        ];
    }

    /**
     * @param $attribute
     */
    public function requiredany($attribute)
    {
        die($attribute);
        if (!$this->author_email && !$this->author_phone)
            $this->addError($attribute, Yii::t('app.f12.comments', 'Enter email or phone number'));

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
            'Author Phone' => Yii::t('app.f12.comments', 'Author phone'),
            'subscribe' => Yii::t('app.f12.comments', 'Send me new comments in this thread to email.'),
            'attachments' => Yii::t('app.f12.comments', 'Attachments'),
        ];
    }

    /** Return name of author (from comment attribute or from User class)
     * @return string
     */
    public function getName()
    {
        if ($this->create_user_id) {
            $model = $this->userObject;
            if (!$model)
                return Yii::t('app.f12.comments', 'Deleted user');
            return $model->commentatorName;
        }
        return $this->author_name;
    }

    /** Return avatar of author (from comment attribute or from User class)
     * @return string
     */
    public function getAvatar()
    {
        if ($this->create_user_id) {
            $model = $this->userObject;
            if (!$model)
                return Yii::$app->getModule('comments')->defaultAvatar;
            return $model->commentatorAvatar;
        }
        return Yii::$app->getModule('comments')->defaultAvatar;

    }

    /** Return email of author (from comment attribute or from User class)
     * @return string
     */
    public function getEmail()
    {
        if ($this->create_user_id) {
            $model = $this->userObject;
            if (!$model)
                return Yii::t('app.f12.comments', 'Deleted user');
            return $model->commentatorEmail;
        }
        return $this->author_email;
    }

    /**
     * @return CommentatorInterface
     */
    public function getUserObject()
    {
        $classname = Yii::$app->getModule('comments')->userClass;
        return $classname::findOne($this->create_user_id);
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        $this->status = CommentStatus::DELETED;
        return $this->save(true, ['status']);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'files' => [
                'class' => FileBehaviour::class,
                'attributes' => [
                    'attachments' => [
                        'title' => 'Файлы',
                        'preview' => false,
                        'multiply' => true,
                        'showName' => true,
                        'bricked' => true,
                        'showControl' => true,
                        'label' => false,
                        'successFunction' => 'info("Файл загружен",1);',
                        'errorFunction' => 'info(message,2);',
                    ],
                ]

            ]
        ];
    }
}
