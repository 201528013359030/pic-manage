<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\AnnounceForm;
use app\models\Enterpris;
use app\modules\admin\models\IctWebService;
use app\modules\admin\models\AuthToken;
use app\models\Curl;

class MainController extends \yii\web\Controller
{
	public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'main';
 //   public $layout  = 'announce';

    public function actionCreate()
    {	 
        $auth = new AuthToken();
        $auth->authTokenSession();
        return $this->render('send');
    }

}
