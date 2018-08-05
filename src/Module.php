<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 31.12.2017
 * Time: 14:45
 */

namespace floor12\comments;

use Yii;

/**
 * Class Module
 * @package floor12\files
 * @property string $token_salt
 * @property string $fontAwesome
 * @property string $storage
 * @property string $controllerNamespace
 *
 */
class Module extends \yii\base\Module
{
    const FORM_POSITION_BEFORE_LIST = 'before';
    const FORM_POSITION_AFTER_LIST = 'after';

    const MODE_PRE_MODERATION = 'pre';
    const MODE_POST_MODERATION = 'post';

    const MODE_GUESTS = 'guest';
    const MODE_ONLY_USERS = 'users';

    const ORDER_NEW_FIRST = '-';
    const ORDER_OLD_FIRST = '+';


    /**  @var string Switch comment form position: before or after comment list. */
    public $formPosition = self::FORM_POSITION_BEFORE_LIST;

    /** @var string FontAwesome helper class */
    public $fontAwesome = 'rmrevin\yii\fontawesome\FontAwesome';

    /** @var string */
    public $editRole = 'admin';

    /** @var string Pre- or post-moderation switch */
    public $moderationMode = self::MODE_PRE_MODERATION;

    /** @var string Guests or only users allowed */
    public $userMode = self::MODE_GUESTS;

    /** @var string Set user class */
    public $userClass = 'app\models\User';

    /** @var boolean Show avatars on fronend */
    public $useAvatar = true;

    /** @var boolean */
    public $useWYSIWYG = true;

    /** @var array Client options for Summernote WYSIWYG Editor */
    public $summernoteClientOptions = [
        'height' => '100px',
        'disableDragAndDrop' => true,
        'toolbar' => [
            ['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear', 'color']],
            ['insert', ['link']],
            ['paragraph style', ['ol', 'ul']],
        ]
    ];

    /** @var string Default avatar path (if user hasn userpic or its a guiest user */
    public $defaultAvatar = '';

    /** @var string Order of comments in listing */
    public $commentsOrder = self::ORDER_NEW_FIRST;


    /** Comments views paths */
    public $viewCommentList = '@vendor/floor12/yii2-module-comments/src/views/frontend/index';
    public $viewCommentListItem = '@vendor/floor12/yii2-module-comments/src/views/frontend/_index';
    public $viewForm = '@vendor/floor12/yii2-module-comments/src/views/frontend/_form';


    /** @inheritdoc */
    public $controllerNamespace = 'floor12\comments\controllers';


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->fontAwesome = Yii::createObject($this->fontAwesome);
        $this->registerTranslations();
    }


    public function registerTranslations()
    {
        Yii::$app->i18n->translations['app.f12.comments'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@vendor/floor12/yii2-module-comments/src/messages',
            'sourceLanguage' => 'en-US',
            'fileMap' => [
                'app.f12.comments' => 'comments.php',
            ],
        ];
    }

}