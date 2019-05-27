<?php

use yii\db\Migration;

/**
 * Class m190527_073809_url_length
 */
class m190527_073809_url_length extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%f12_comment}}', 'url', $this->string(1000)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
