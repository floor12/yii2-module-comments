<?php

use yii\db\Migration;

class m160730_102518_comment extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable('{{%comment2}}', [
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
            'author_name' => $this->string()->notNull()->comment('Имя автора комментария'),
            'author_email' => $this->string()->notNull()->comment('Email автора комментария'),
        ],
            $tableOptions
        );

        $this->createIndex('idx-comment2-updated', '{{%comment2}}', 'updated');
        $this->createIndex('idx-comment2-update_user_id', '{{%comment2}}', 'update_user_id');
        $this->createIndex('idx-comment2-created', '{{%comment2}}', 'created');
        $this->createIndex('idx-comment2-create_user_id', '{{%comment2}}', 'create_user_id');
        $this->createIndex('idx-comment2-status', '{{%comment2}}', 'status');

        $this->createIndex('idx-comment2-parent_id', '{{%comment2}}', 'parent_id');
        $this->createIndex('idx-comment2-object_id', '{{%comment2}}', 'object_id');
        $this->createIndex('idx-comment2-class', '{{%comment2}}', 'class');
        $this->createIndex('idx-comment2-class-object-id', '{{%comment2}}', ['class', 'object_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%comment2}}');
    }
}
