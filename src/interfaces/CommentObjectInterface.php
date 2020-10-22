<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 16.07.2018
 * Time: 12:15
 */

namespace floor12\comments\interfaces;

/**
 * Этот интерфейс необходимо имплементировать тем моделям ActiveRecord проекта,
 *  которые будут получать комментарии
 */
interface CommentObjectInterface
{
    public static function getHumanReadbleModelName(): string;

    public function getHumanReadbleObjectName(): string;
}
