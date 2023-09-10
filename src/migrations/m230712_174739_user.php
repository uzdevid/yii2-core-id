<?php

use yii\db\Migration;

/**
 * Class m230712_174739_user
 */
class m230712_174739_user extends Migration {
    public static string $tableName = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void {
        $this->createTable(self::$tableName, [
            'id' => $this->primaryKey(),
            'create_time' => $this->bigInteger()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): bool {
        $this->dropTable(self::$tableName);

        return true;
    }
}
