<?php

/**
 * Created by PhpStorm.
 * User: dkhodakovskiy
 * Date: 14.11.16
 * Time: 13:20
 */
class EveCRESTCharacter
{

    public function __construct(User $user)
    {
        if ((new DateTime($user->expiresOn))->getTimestamp() < time()) {
            $this->_tokenUpdateNeeded();
        }
    }

    private function _tokenUpdateNeeded()
    {
        echo 'Update Needed';
    }

}