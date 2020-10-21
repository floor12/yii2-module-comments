<?php

use yii\db\Migration;

/**
 * Class m190527_073809_url_length
 */
class m201021_120500_add_rating_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%f12_comment}}', 'rating', $this->integer(1)->null());
        $this->createIndex('f12_comment-rating', '{{%f12_comment}}', 'rating');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%f12_comment}}', 'rating');
    }
}
