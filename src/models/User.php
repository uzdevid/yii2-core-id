<?php

namespace uzdevid\CoreID\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $create_time
 */
class User extends ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array {
        return [
            [['id', 'create_time'], 'required'],
            [['create_time'], 'default', 'value' => null],
            [['create_time'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array {
        return [
            'id' => 'ID',
            'create_time' => 'Create Time',
        ];
    }
}
