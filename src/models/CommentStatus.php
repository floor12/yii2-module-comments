<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 29.07.2018
 * Time: 20:27
 */

namespace floor12\comments\models;

use yii2mod\enum\helpers\BaseEnum;

class CommentStatus extends BaseEnum
{
    const PUBLISHED = 0;
    const PENDING = 1;
    const DELETED = 2;

    public static  $messageCategory = 'app.f12.comments';

    public static $list = [
        self::PUBLISHED => 'Published',
        self::PENDING => 'Pending',
        self::DELETED => 'Deleted',
    ];
}