<?php

namespace uzdevid\CoreID\models;

use uzdevid\CoreID\CoreID;
use uzdevid\CoreID\CoreIdentityInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\IdentityInterface;

class Identity extends User implements IdentityInterface, CoreIdentityInterface {

    public static function coreId(): CoreID {
        return Yii::$app->coreID;
    }

    public static function findIdentity($id): Identity|IdentityInterface|null {
        return static::findOne($id);
    }

    /**
     * @throws InvalidConfigException
     */
    public static function findIdentityByAccessToken($token, $type = null): Identity|IdentityInterface|null {
        $profile = self::coreId()->getProfileByToken($token);

        if (is_null($profile)) {
            return null;
        }

        $id = $profile['Identity']['id'];

        $identity = static::findIdentity($id);

        if (is_null($identity)) {
            $identity = new Identity();
            $identity->id = $id;
            $identity->save();
        }

        return $identity;
    }

    public function getId(): int|string {
        return $this->id;
    }

    public function getAuthKey() {
        return null;
    }

    public function validateAuthKey($authKey): bool {
        return false;
    }
}