<?php

use yii\db\Migration;

class m180722_102518_comment extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable('{{%f12_comment}}', [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->notNull()->comment('Статус'),
            'created' => $this->integer()->notNull()->comment('Создан'),
            'updated' => $this->integer()->notNull()->comment('Обновлен'),
            'create_user_id' => $this->integer()->null()->comment('Создал'),
            'update_user_id' => $this->integer()->null()->comment('Обновил'),
            'class' => $this->string()->notNull()->comment('Класс объёкта'),
            'object_id' => $this->integer()->notNull()->comment('ID объёкта'),
            'parent_id' => $this->integer()->notNull()->comment('Родительский комментарий'),
            'content' => $this->text()->notNull()->comment('Текст комментария'),
            'author_name' => $this->string()->null()->comment('Имя автора комментария'),
            'author_email' => $this->string()->null()->comment('Email автора комментария'),
            'subscribe' => $this->boolean()->null()->comment('получать комментарии из этой ветки на почту'),
            'url' => $this->string()->null()->comment('адрес страницы комментария'),
        ],
            $tableOptions
        );

        $this->createIndex('idx-f12_comment-updated', '{{%f12_comment}}', 'updated');
        $this->createIndex('idx-f12_comment-update_user_id', '{{%f12_comment}}', 'update_user_id');
        $this->createIndex('idx-f12_comment-created', '{{%f12_comment}}', 'created');
        $this->createIndex('idx-f12_comment-create_user_id', '{{%f12_comment}}', 'create_user_id');
        $this->createIndex('idx-f12_comment-status', '{{%f12_comment}}', 'status');
        $this->createIndex('idx-f12_comment-subscribe', '{{%f12_comment}}', 'subscribe');

        $this->createIndex('idx-f12_comment-parent_id', '{{%f12_comment}}', 'parent_id');
        $this->createIndex('idx-f12_comment-object_id', '{{%f12_comment}}', 'object_id');
        $this->createIndex('idx-f12_comment-class', '{{%f12_comment}}', 'class');
        $this->createIndex('idx-f12_comment-class-object-id', '{{%f12_comment}}', ['class', 'object_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%f12_comment}}');
    }
}
