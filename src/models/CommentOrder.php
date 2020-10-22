<?php

namespace floor12\comments\models;

use yii2mod\enum\helpers\BaseEnum;

class CommentOrder extends BaseEnum
{
    const FIRST_NEWEST = 0;
    const FIRST_OLDEST = 1;

    public static $messageCategory = 'app.f12.comments';

    public static function getOrderSign(int $value): ?string
    {
        return self::$orderSign[$value] ?? null;
    }

    public static $list = [
        self::FIRST_OLDEST => 'Oldest first',
        self::FIRST_NEWEST => 'Newest first',
    ];

    public static $orderSign = [
        self::FIRST_OLDEST => '+',
        self::FIRST_NEWEST => '-',
    ];
}
