<?php

use floor12\comments\models\Comment;
use yii\db\Migration;

class m180722_102518_comment extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(Comment::tableName(), [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->notNull()->comment('Статус'),
            'created' => $this->integer()->notNull()->comment('Создан'),
            'updated' => $this->integer()->notNull()->comment('Обновлен'),
            'create_user_id' => $this->integer()->null()->comment('Создал'),
            'update_user_id' => $this->integer()->null()->comment('Обновил'),
            'class' => $this->string()->notNull()->comment('Класс объёкта'),
            'object_id' => $this->integer()->notNull()->comment('ID объёкта'),
            'parent_id' => $this->integer()->notNull()->defaultValue(0)->comment('Родительский комментарий'),
            'content' => $this->text()->notNull()->comment('Текст комментария'),
            'author_name' => $this->string()->null()->comment('Имя автора комментария'),
            'author_email' => $this->string()->null()->comment('Email автора комментария'),
            'subscribe' => $this->boolean()->null()->comment('получать комментарии из этой ветки на почту'),
            'url' => $this->string()->null()->comment('адрес страницы комментария'),
        ],
            $tableOptions
        );

        $this->createIndex('idx-f12_comment-updated', Comment::tableName(), 'updated');
        $this->createIndex('idx-f12_comment-update_user_id', Comment::tableName(), 'update_user_id');
        $this->createIndex('idx-f12_comment-created', Comment::tableName(), 'created');
        $this->createIndex('idx-f12_comment-create_user_id', Comment::tableName(), 'create_user_id');
        $this->createIndex('idx-f12_comment-status', Comment::tableName(), 'status');
        $this->createIndex('idx-f12_comment-subscribe', Comment::tableName(), 'subscribe');

        $this->createIndex('idx-f12_comment-parent_id', Comment::tableName(), 'parent_id');
        $this->createIndex('idx-f12_comment-object_id', Comment::tableName(), 'object_id');
        $this->createIndex('idx-f12_comment-class', Comment::tableName(), 'class');
        $this->createIndex('idx-f12_comment-class-object-id', Comment::tableName(), ['class', 'object_id']);
    }

    public function safeDown()
    {
        $this->dropTable(Comment::tableName());
    }
}
