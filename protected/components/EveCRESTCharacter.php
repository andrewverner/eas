<?php

/**
 * Created by PhpStorm.
 * User: dkhodakovskiy
 * Date: 14.11.16
 * Time: 13:20
 */
class EveCRESTCharacter
{

    private $_userModel;

    public function __construct(User $user)
    {
        $this->_userModel = $user;
        if ((new DateTime($user->expiresOn))->getTimestamp() < time()) {
            echo "{$this->_userModel->accessToken}<br />";
            $this->_tokenUpdateNeeded();
            echo "{$this->_userModel->accessToken}";
        }
    }

    private function _tokenUpdateNeeded()
    {
        $result = (new EveCRESTRequest('https://login.eveonline.com/oauth/token'))
            ->post()
            ->postData(json_encode([
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->_userModel->refreshToken
            ]))
            ->authBasic()
            ->contentType('application/json')
            ->send();

        if ($result) {
            $json = json_decode($result);
            if ($json && isset($json->access_token) && isset($json->refresh_token)) {
                $this->_userModel->saveAttributes([
                    'accessToken'   => $json->access_token,
                    'refreshToken'  => $json->refresh_token
                ]);
            }
            else
                EveLogger::log(
                    __CLASS__,
                    EveLogger::LEVEL_NOTICE,
                    "Can't update user token (JSON) {$this->_userModel->characterID}: "
                    . print_r($result, true)
                    . print_r($json, true)
                );
        }
        else
            EveLogger::log(
                __CLASS__,
                EveLogger::LEVEL_NOTICE,
                "Can't update user token (RESULT) {$this->_userModel->characterID}: "
                . print_r($result, true)
            );
    }

}