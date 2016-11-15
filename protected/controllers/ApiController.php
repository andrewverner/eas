<?php

/**
 * Created by PhpStorm.
 * User: dkhodakovskiy
 * Date: 15.11.16
 * Time: 13:00
 */
class ApiController extends Controller
{

    public function actionIndex()
    {
        $this->render('index');
    }

     public function verify()
     {
         $error = false;
         $user = false;

         if (isset($_GET['code'])) {

             $result = (new EveCRESTRequest('https://login.eveonline.com/oauth/token'))
                 ->post()
                 ->postData(json_encode([
                     'grant_type' => 'authorization_code',
                     'code' => $_GET['code']
                 ]))
                 ->authBasic()
                 ->contentType('application/json')
                 ->send();

             if ($result) {
                 $json = json_decode($result);
                 if ($json) {
                     $result = (new EveCRESTRequest('https://login.eveonline.com/oauth/verify', $json->access_token))
                         ->contentType('application/json')
                         ->send();

                     $character = json_decode($result);

                     print_r($character);

                     $user = User::model()->findByAttributes([
                         'characterID'   => $character->CharacterID,
                         //'scopeHash'     => md5($character->Scopes)
                     ]);
                     if (!$user) $user = new User();

                     $user->setAttributes([
                         'characterID'   => $character->CharacterID,
                         'characterName' => $character->CharacterName,
                         'accessToken'   => $json->access_token,
                         'expiresOn'     => $character->ExpiresOn,
                         'scopes'        => $character->Scopes,
                         'refreshToken'  => $json->refresh_token,
                         'scopeHash'     => md5($character->Scopes)
                     ]);
                     $user->save();
                 }
                 else
                     $error = new ErrorHandler(ErrorHandler::ERROR_INVALID_JSON, 'Invalid JSON format');
             }
             else
                 $error = new ErrorHandler(ErrorHandler::ERROR_REQUEST_FAILED, 'CREST request failed');
         }
         else
             $error = new ErrorHandler(ErrorHandler::ERROR_NOT_ENOUGH_DATA, 'Verify code not found');

         $this->render('verify', [
             'error'    => $error,
             'user'     => $user
         ]);
     }

}