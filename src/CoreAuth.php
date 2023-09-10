<?php

namespace uzdevid\CoreID;

use yii\filters\auth\AuthMethod;
use yii\web\BadRequestHttpException;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;

class CoreAuth extends AuthMethod {
    public string $header = 'Authorization';

    /**
     * @throws UnauthorizedHttpException
     * @throws BadRequestHttpException
     */
    public function authenticate($user, $request, $response): IdentityInterface|null {
        $bearer = $request->headers->get($this->header);

        if (is_null($bearer)) {
            throw new BadRequestHttpException('Missing authorization header');
        }

        $identity = $user->loginByAccessToken($bearer, get_class($this));

        if ($identity === null) {
            $this->challenge($response);
            $this->handleFailure($response);
        }

        return $identity;
    }
}