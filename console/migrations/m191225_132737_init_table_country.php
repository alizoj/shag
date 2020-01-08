<?php

use yii\db\Migration;

/**
 * Class m191225_132737_init_table_country
 */
class m191225_132737_init_table_country extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $this->createTable('country', [
            'code'       => $this->string(2),
            'name'       => $this->string(52)->notNull(),
            'population' => $this->integer(11)->notNull()->defaultValue(0),
            'PRIMARY KEY (code)',
        ]);
        $this->batchInsert('country', ['code', 'name', 'population'], [
            ['AU', 'Australia', 24016400],
            ['BR', 'Brazil', 205722000],
            ['CA', 'Canada', 35985751],
            ['CN', 'China', 1375210000],
            ['DE', 'Germany', 81459000],
            ['FR', 'France', 64513242],
            ['GB', 'United Kingdom', 65097000],
            ['IN', 'India', 1285400000],
            ['RU', 'Russia', 146519759],
            ['US', 'United States', 322976000],
        ]);
//
//        $this->insert('country', ['code' => 'AU', 'name' => 'Australia', 'population' => 24016400]);
//        $this->insert('country', ['code' => 'BR', 'name' => 'Brazil', 'population' => 205722000]);
//        $this->insert('country', ['code' => 'CA', 'name' => 'Canada', 'population' => 35985751]);
//        $this->insert('country', ['code' => 'CN', 'name' => 'China', 'population' => 1375210000]);
//        $this->insert('country', ['code' => 'DE', 'name' => 'Germany', 'population' => 81459000]);
//        $this->insert('country', ['code' => 'FR', 'name' => 'France', 'population' => 64513242]);
//        $this->insert('country', ['code' => 'GB', 'name' => 'United Kingdom', 'population' => 65097000]);
//        $this->insert('country', ['code' => 'IN', 'name' => 'India', 'population' => 1285400000]);
//        $this->insert('country', ['code' => 'RU', 'name' => 'Russia', 'population' => 146519759]);
//        $this->insert('country', ['code' => 'US', 'name' => 'United States', 'population' => 322976000]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('country');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191225_132737_init_table_country cannot be reverted.\n";

        return false;
    }
    */
}
