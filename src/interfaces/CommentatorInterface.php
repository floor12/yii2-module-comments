<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 16.07.2018
 * Time: 12:15
 */

namespace floor12\comments\interfaces;

/** Этот интерфейс необходимо имплементировать тем моделям ActiveRecord проекта,
 *  которые могут оставлять комментарии как пользователи.
 * Interface MailingRecipientInterface
 * @package floor12\mailing\interfaces
 * @property string $commentatorName
 */
interface CommentatorInterface
{

    /** Возращаем строку содержащую имя пользователя для отображения в комментариях.
     *  Пример: eсли в модели есть поле name, то реализация такая:
     *
     *      public function getCommentatorName(): string
     *      {
     *          return $this->name;
     *      }
     *
     * @return string
     */
    public function getCommentatorName(): string;

    /** Возращаем строку содержащую путь к аватару пользователя.
     *  Пример реализации
     *
     *      public function getCommentatorAvatar(): string
     *      {
     *          return $this->avatar->fullpath;
     *      }
     *
     * @return string
     */
    public function getCommentatorAvatar(): string;

    /** Возращаем строку содержащую email автора.
     *  Пример реализации
     *
     *      public function getCommentatorAvatar(): string
     *      {
     *          return $this->email;
     *      }
     *
     * @return string
     */
    public function getCommentatorEmail(): string;



}