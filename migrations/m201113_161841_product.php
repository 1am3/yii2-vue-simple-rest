<?php

use yii\db\Migration;

/**
 * Class m201113_161841_product
 */
class m201113_161841_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'price' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-product-id_product',
            'product',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201113_161841_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201113_161841_product cannot be reverted.\n";

        return false;
    }
    */
}
