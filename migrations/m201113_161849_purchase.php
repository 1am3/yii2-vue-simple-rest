<?php

use yii\db\Migration;

/**
 * Class m201113_161849_purchase
 */
class m201113_161849_purchase extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('purchase', [
            'id' => $this->primaryKey(),
            'status' => "ENUM('Новый', 'Оплачено') DEFAULT 'Новый'",
            'total' => $this->float(),
        ]);

        $this->createIndex(
            'idx-purchase-id_purchase',
            'purchase',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201113_161849_purchase cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201113_161849_purchase cannot be reverted.\n";

        return false;
    }
    */
}
