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
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'floor12\comments\controllers';

    public $fontAwesome = 'rmrevin\yii\fontawesome\FontAwesome';

    /** Вьюхи для комментариев
     * @var string 
     */
    public $viewView = '@vendor/floor12/yii2-module-news/src/views/news/view';
    public $viewForm = '@vendor/floor12/yii2-module-news/src/views/news/_form';


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->fontAwesome = Yii::createObject($this->fontAwesome);
    }


}