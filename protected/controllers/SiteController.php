<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionCallback()
    {
        echo '<pre>';
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
                } else {
                    echo 'JSON is invalid';
                }
            } else {
                echo 'cURL error';
            }
        }
        echo '</pre>';
    }

    public function actionTest()
    {
        echo '<pre>';
        $ch = curl_init('https://login.eveonline.com/oauth/verify');
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer GzVtW_C2dZsXV_sjrK2Fg3HKhQ96tD3tDEPWl6FtaMIzdJ6IGTp8f_2IgqmDE3L-1-RpER6wgWOkYopM5buUiQ2",
            'Host: login.eveonline.com'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        $character = json_decode($result);

        print_r($character);
        echo '</pre>';
    }

    public function actionUser($id)
    {
        echo '<pre>';
        $user = User::model()->findByAttributes([
            'characterID' => $id
        ]);
        if ($user) {

            $character = (new EveCRESTCharacter($user))->details();



            /*$result = (new EveCRESTRequest("https://crest-tq.eveonline.com/characters/{$id}/", $user->accessToken))
                ->send();

            //print_r($result);

            $json = json_decode($result);
            if ($json) {
                print_r($json);

                foreach ($json as $key => $data) {
                    if (isset($data->href)) {

                        echo $key;

                        $res = (new EveCRESTRequest($data->href, $user->accessToken))
                            ->send();

                        var_dump($res);
                    }
                }
            }*/

            /*$authCode = base64_encode('862b3aa0e295461f8f2fdaaf3055c3f4:JSt85YT7p8w3B36sTvQfzCX37TIi4JDabNNOd6iQ');
            $refreshCh = curl_init('https://login.eveonline.com/oauth/token');
            curl_setopt($refreshCh, CURLOPT_POST, true);
            curl_setopt($refreshCh, CURLOPT_HTTPHEADER, [
                "Authorization: Basic $authCode",
                'Content-Type: application/json',
                'Host: login.eveonline.com'
            ]);
            curl_setopt($refreshCh, CURLOPT_POSTFIELDS, json_encode([
                'grant_type' => 'refresh_token',
                'refresh_token' => $user->refreshToken
            ]));
            curl_setopt($refreshCh, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($refreshCh);
            print_r($result);

            $json = json_decode($result);
            print_r($json);

            if (isset($json->access_token)) {
                $user->accessToken = $json->access_token;
                $user->refreshToken = $json->refresh_token;
                $user->save();
            }*/
        }
        echo '</pre>';
    }

}
