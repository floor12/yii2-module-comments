<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.07.2018
 * Time: 21:16
 */

namespace floor12\comments\assets;


use yii\web\AssetBundle;

class CommentsAsset extends AssetBundle
{

    public $sourcePath = '@vendor/floor12/yii2-module-comments/assets/';

    public $css = [
        'floor12-comments.css',
    ];
    public $js = [
        'floor12-comments.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'floor12\notification\NotificationAsset'
    ];
    
}