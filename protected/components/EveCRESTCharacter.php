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
            $this->_tokenUpdateNeeded();
        }
    }

    public function details()
    {
        if (!$this->_userModel->accessToken) return false;

        $characterRequest = (new EveCRESTRequest(
            "https://crest-tq.eveonline.com/characters/{$this->_userModel->characterID}/",
            $this->_userModel->accessToken)
        )->send();

        if ($characterRequest) {
            $characterJson = json_decode($characterRequest);
            print_r($characterJson);
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
                    'refreshToken'  => $json->refresh_token,
                    'expiresOn'     => (new DateTime())->modify('+1200 second')->format('Y-m-d H:i:s')
                ]);
            } else {
                EveLogger::log(
                    __CLASS__,
                    EveLogger::LEVEL_NOTICE,
                    "Can't update user token (JSON) {$this->_userModel->characterID}: "
                    . print_r($result, true)
                    . print_r($json, true)
                );
                $this->_userModel->saveAttributes(['accessToken' => null]);
            }
        } else {
            EveLogger::log(
                __CLASS__,
                EveLogger::LEVEL_NOTICE,
                "Can't update user token (RESULT) {$this->_userModel->characterID}: "
                . print_r($result, true)
            );
            $this->_userModel->saveAttributes(['accessToken' => null]);
        }
    }

}