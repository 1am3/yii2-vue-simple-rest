<?php

use yii\db\Migration;

/**
 * Class m201113_161934_purchase_product
 */
class m201113_161934_purchase_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('purchase_product', [
            'id' => $this->primaryKey(),
            'id_product' => $this->integer(),
            'id_purchase' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-purchase_product-id_product',
            'purchase_product',
            'id_product'
        );
        $this->createIndex(
            'idx-purchase_product-id_purchase',
            'purchase_product',
            'id_purchase'
        );

        $this->addForeignKey(
            'fk-purchase_product-id_product',
            'purchase_product',
            'id_product',
            'product',
            'id'
        );

        $this->addForeignKey(
            'fk-purchase_product-id_purchase',
            'purchase_product',
            'id_purchase',
            'purchase',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201113_161934_purchase_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201113_161934_purchase_product cannot be reverted.\n";

        return false;
    }
    */
}
