<?php

use yii\db\Migration;

/**
 * Class m200108_152956_add_new_field_to_country
 */
class m200108_152956_add_new_field_to_country extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('country', 'file_name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('country', 'file_name');
    }
}
