<?php

namespace app\controllers;

use Yii;
use app\models\PaiUser;
use app\models\AdminSearch;
use app\models\IctWebService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Curl;

/**
 * AdminController implements the CRUD actions for PaiUser model.
 */
class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all PaiUser models.
     * @return mixed
     */
    public function actionIndex()
    {

        /**
         * ************************ 临时获取token测试用 begin**********************************
         */
    	Yii::$app->session['user.apiKey']='36116967d1ab95321b89df8223929b14207b72b1';

    	$WS = new IctWebService();
        	$params = [
        			'name' => '18900913303',
        			'password' => '123456'
        	];
        	$result = $WS->getAuth_Token ( $params );

//         	var_dump ( $result );
        	// echo $result->result->auth_token;
        	// 			return;

        	$uid = $result->result->uid;
        	$auth_token = $result->result->auth_token;
        	$eguid = $result->result->eguid;


        /**
         * ************************ 临时获取token测试用 end**********************************
         */


        $uid=\Yii::$app->request->get('uid',$uid);
        list($tmp, $eid) = explode("@", $uid);
        $token=\Yii::$app->request->get('auth_token',$auth_token);
        Yii::$app->session['user.token']=$token;

//         $WS = new IctWebService();
        if($WS->authToken($uid, $token) == 0){
            echo "auth failed!";
            exit;
        }
        if($eid){
            \Yii::$app->session['eid']=$eid;
        }else{
            $eid = \Yii::$app->session['eid'];
        }
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PaiUser model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PaiUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaiUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PaiUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PaiUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PaiUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PaiUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaiUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
